<x-app-layout>
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
        <style>
            .page-item.active .page-link{
                background-color: #219ebc !important;
                border-color: #8ecae6;
            }
            .table-bondered th, td, th{
                padding: 8px !important;
                text-align: left !important;
                border: 1px solid #9896966e !important;
            }

        </style>
    @endpush
    @push('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#example').DataTable();
        })
    </script>
    @endpush
    @section('content')
    <section class="content-main">
        <div class="content-header">
            <h2 class="content-title">{{ ucwords(str_replace('-',' ',Request::segment(3))) }}</h2>

        </div>
        <div class="card mb-4">
            <header class="card-header">
                <h4>WILAYAH: 01 - JAWA TIMUR</h4>
            </header>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr class="table-primary">
                                <th>LEDGER A/C NO.</th>
                                <th scope="col">KETERANGAN</th>
                                <th scope="col">SALDO AWAL</th>
                                <th scope="col"> </th>
                                <th scope="col">TRANSAKSI DEBIT</th>
                                <th scope="col">TRANSAKSI KREDIT</th>
                                <th scope="col">SALDO AKHIR</th>
                                <th scope="col"> </th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalSaldoAwalDebet = 0;
                                $totalSaldoAwalKredit = 0;
                                $totalMutasiDebet = 0;
                                $totalMutasiKredit = 0;
                                $totalSaldoAkhirDebet = 0;
                                $totalSaldoAkhirKredit = 0;

                                $current_saldo_tabungan_result = 0;
                                $current_saldo_tabungan_result_saldo_akhir = 0;
                                $current_saldo_tabungan_result_saldo_akhir_total = 0;
                            @endphp
                            @forelse ($ledger as $item)
                                @php
                                    $mutasiAwalDebet = 0;
                                    $mutasiAwalKredit = 0;

                                    $mutasiDebetTabungan = 0;
                                    $mutasiKreditTabungan = 0;

                                    $mutasiDebet = 0;
                                    $mutasiKredit = 0;
                                    $current_date = \Carbon\Carbon::now();
                                    $tanggalSebelumnya = $current_date->subDay();
                                @endphp

                                @php
                                    $kode_induk = \App\Models\KodeInduk::where('id_ledger',$item->id)->orderBy('id','DESC')->get();
                                @endphp
                                <tr class="table-success">
                                    <td>{{ $item->kode_ledger }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td>{{ 'total keseluruhan' }}</td>
                                    <td>{{ 'total keseluruhan' }}</td>

                                </tr>
                                <tr style="font-weight: bold" class="table-secondary">
                                    @foreach ($kode_induk as $item_induk)
                                        @php
                                            $kode_akun = \App\Models\KodeAkun::where('id_induk',$item_induk->id)->get();
                                        @endphp
                                        <tr style="font-weight: bold" class="table-secondary">
                                            <td>{{ $item_induk->kode_induk }}</td>
                                            <td>{{ $item_induk->nama }}</td>
                                            @if ($item_induk->nama == 'Tabungan')
                                                    @php
                                                        $current_saldo_tabungan = \App\Models\TransaksiTabungan::select('transaksi_tabungan.*')
                                                                        ->whereDate('created_at','<',$current_date)
                                                                        ->get();
                                                        $current_saldo_tabungan_masuk = 0;
                                                        $current_saldo_tabungan_keluar = 0;

                                                    @endphp
                                                @foreach ($current_saldo_tabungan as $item_saldo_tabungan)
                                                    @php
                                                        if ($item_saldo_tabungan->jenis == 'keluar') {
                                                            $current_saldo_tabungan_keluar += $item_saldo_tabungan->nominal;
                                                        } else {
                                                            $current_saldo_tabungan_masuk += $item_saldo_tabungan->nominal;
                                                        }
                                                        $current_saldo_tabungan_result = $current_saldo_tabungan_masuk - $current_saldo_tabungan_keluar;
                                                    @endphp
                                                @endforeach
                                                <td class="align-top">{{ number_format($current_saldo_tabungan_result,2, ",", ".") }}</td>
                                            @else
                                                <td>Belum</td>
                                            @endif
                                            @foreach ($kode_akun as $item_akun)
                                                @php
                                                    $cekTransaksiAwalDiKode = \App\Models\Jurnal::where('kode_akun', $item_akun->id)->count();
                                                @endphp
                                                @if ($cekTransaksiAwalDiKode > 0)
                                                    @php
                                                        $sumMutasiAwalDebetDiKode = \DB::table('jurnal')->where('kode_akun', $item_akun->id)->where('tipe', 'debit')->sum('nominal');

                                                        $sumMutasiAwalKreditDiKode = \DB::table('jurnal')->where('kode_akun', $item_akun->id)->where('tipe', 'kredit')->sum('nominal');

                                                        if ($item_induk->tipe == 'debit') {
                                                            $mutasiAwalDebet += $sumMutasiAwalDebetDiKode;
                                                            $mutasiAwalKredit += $sumMutasiAwalKreditDiKode;
                                                        }
                                                        else{
                                                            $mutasiAwalDebet += $sumMutasiAwalDebetDiKode;
                                                            $mutasiAwalKredit += $sumMutasiAwalKreditDiKode;
                                                        }

                                                        // cek apakah transaksi sebelumnya juga terdapat di field lawan
                                                        $cekTransaksiAwalDiLawan = \App\Models\Jurnal::where('kode_lawan', $item_akun->id)->count();

                                                        if ($cekTransaksiAwalDiLawan > 0) {
                                                            $sumMutasiAwalDebetDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_akun->id)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                            $sumMutasiAwalKreditDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_akun->id)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                            $mutasiAwalDebet += $sumMutasiAwalDebetDiLawan;
                                                            $mutasiAwalKredit += $sumMutasiAwalKreditDiLawan;
                                                        }
                                                    @endphp
                                                @else
                                                    @php
                                                        $cekTransaksiAwalDiLawan = \App\Models\Jurnal::where('kode_lawan', $item_akun->id)->count();
                                                        if ($cekTransaksiAwalDiLawan > 0) {
                                                            $sumMutasiAwalDebetDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_akun->id)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                            $sumMutasiAwalKreditDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_akun->id)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                            if ($item_induk->tipe == 'debit') {
                                                                $mutasiAwalDebet += $sumMutasiAwalDebetDiLawan;

                                                                $mutasiAwalKredit += $sumMutasiAwalKreditDiLawan;
                                                            }
                                                            else{
                                                                $mutasiAwalDebet += $sumMutasiAwalDebetDiLawan;
                                                                $mutasiAwalKredit += $sumMutasiAwalKreditDiLawan;
                                                            }
                                                        }
                                                    @endphp
                                                @endif
                                                {{-- transaksi debet dan kredit --}}
                                                @php
                                                    $cekTransaksiDiKode = \App\Models\Jurnal::where('kode_akun', $item_akun->id)->count();
                                                    if ($cekTransaksiDiKode > 0) {
                                                        $sumMutasiDebetDiKode = \DB::table('jurnal')->where('kode_akun', $item_akun->id)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                        $sumMutasiKreditDiKode = \DB::table('jurnal')->where('kode_akun', $item_akun->id)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                        $mutasiDebet += $sumMutasiDebetDiKode;
                                                        $mutasiKredit += $sumMutasiKreditDiKode;

                                                        // cek transaksi di field lawan
                                                        $cekTransaksiDiLawan = \App\Models\Jurnal::where('kode_lawan', $item_akun->id)->count();

                                                        if ($cekTransaksiDiLawan > 0) {
                                                            $sumMutasiDebetDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_akun->id)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                            $sumMutasiKreditDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_akun->id)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                            $mutasiDebet += $sumMutasiDebetDiLawan;
                                                            $mutasiKredit += $sumMutasiKreditDiLawan;
                                                        }
                                                    }
                                                    else{ // cek transaksi di field lawan
                                                        // cek transaksi di field lawan
                                                        $cekTransaksiDiLawan = \App\Models\Jurnal::where('kode_lawan', $item_akun->id)->count();
                                                        if ($cekTransaksiDiLawan > 0) {
                                                            $sumMutasiDebetDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_akun->id)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                            $sumMutasiKreditDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_akun->id)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                            $mutasiDebet += $sumMutasiDebetDiLawan;
                                                            $mutasiKredit += $sumMutasiKreditDiLawan;
                                                        }
                                                    }

                                                    $saldoAwal = $mutasiAwalDebet - $mutasiAwalKredit;

                                                    $saldoAkhir = ($mutasiAwalDebet + $mutasiDebet) - ($mutasiAwalKredit + $mutasiKredit);

                                                    $totalMutasiDebet += $mutasiDebet;
                                                    $totalMutasiKredit += $mutasiKredit;

                                                    if ($item_induk->tipe == 'debit') {
                                                        $totalSaldoAwalDebet += $saldoAwal;
                                                        $totalSaldoAkhirDebet += $saldoAkhir;
                                                    }
                                                    else{
                                                        $totalSaldoAwalKredit += $saldoAwal;
                                                        $totalSaldoAkhirKredit += $saldoAkhir;
                                                    }
                                                @endphp

                                                <tr>
                                                    <td class="align-top">{{ $item_akun->kode_akun }}</td>
                                                    <td class="align-top">{{ $item_akun->nama_akun }}</td>
                                                    @if ($item_akun->nama_akun == 'TABUNGAN MUDHARABAH')
                                                        @php
                                                            $current_saldo_tabungan_result = 0;

                                                            $data_tabungan = \App\Models\TransaksiTabungan::select('transaksi_tabungan.*')
                                                                            ->whereDate('created_at','>',$current_date)
                                                                            ->count();
                                                            $current_saldo_tabungan = \App\Models\TransaksiTabungan::select('transaksi_tabungan.*')
                                                                            ->whereDate('created_at','<',$current_date)
                                                                            ->get();
                                                            $current_saldo_tabungan_masuk = 0;
                                                            $current_saldo_tabungan_keluar = 0;

                                                        @endphp
                                                        @foreach ($current_saldo_tabungan as $item_saldo_tabungan)
                                                            @php
                                                                if ($item_saldo_tabungan->jenis == 'keluar') {
                                                                    $current_saldo_tabungan_keluar += $item_saldo_tabungan->nominal;
                                                                } else {
                                                                    $current_saldo_tabungan_masuk += $item_saldo_tabungan->nominal;
                                                                }
                                                                $current_saldo_tabungan_result = $current_saldo_tabungan_masuk - $current_saldo_tabungan_keluar;
                                                            @endphp
                                                        @endforeach
                                                        <td class="align-top">{{ number_format($current_saldo_tabungan_result,2, ",", ".") }}</td>
                                                        <td class="align-top">{{ $item_akun->jenis == 'kredit' ? '' : 'DR' }}</td>
                                                        @if ($data_tabungan > 0)
                                                            @php
                                                                $sumMutasiKreditTabungan = \DB::table('transaksi_tabungan')->whereDate('created_at','>',$current_date)->where('jenis', 'masuk')->sum('transaksi_tabungan.nominal');

                                                                $sumMutasiDebetTabungan = \DB::table('transaksi_tabungan')->whereDate('created_at','>',$current_date)->where('jenis', 'keluar')->sum('transaksi_tabungan.nominal');

                                                                $mutasiDebetTabungan += $sumMutasiDebetTabungan;
                                                                $mutasiKreditTabungan += $sumMutasiKreditTabungan;
                                                                $current_saldo_tabungan_result_saldo_akhir = $current_saldo_tabungan_result + $sumMutasiKreditTabungan - $sumMutasiDebetTabungan;
                                                            @endphp

                                                            <td>{{ number_format($mutasiDebetTabungan, 2, ',', '.') }}</td>
                                                            <td>{{ number_format($mutasiKreditTabungan, 2, ',', '.') }}</td>
                                                            <td>{{ number_format($current_saldo_tabungan_result_saldo_akhir, 2, ',', '.') }}</td>
                                                            <td class="align-top">{{ $item_akun->jenis == 'kredit' ? '' : 'DR' }}</td>
                                                        @endif


                                                    @else
                                                        @if ($item_induk->tipe == 'debit')
                                                            <td>{{ number_format($saldoAwal, 2, ',', '.') }}</td>
                                                        @else
                                                            <td>{{ number_format($saldoAwal * -1, 2, ',', '.') }}</td>
                                                        @endif
                                                        <td class="align-top">{{ $item_akun->jenis == 'kredit' ? '' : 'DR' }}</td>
                                                        <td>{{ number_format($mutasiDebet, 2, ',', '.') }}</td>
                                                        <td>{{ number_format($mutasiKredit, 2, ',', '.') }}</td>
                                                        <td>{{ number_format($totalSaldoAkhirDebet, 2, ',', '.') }}</td>
                                                        <td class="align-top">{{ $item_akun->jenis == 'kredit' ? '' : 'DR' }}</td>

                                                        {{-- <th>{{ number_format($totalSaldoAkhirKredit * -1, 2, ',', '.') }}</th> --}}
                                                    @endif
                                                </tr>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    {{-- {{ $kode_akun }} --}}
                                </tr>
                            @empty
                                <tr>
                                    <td>Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
            </div>
        </div>
        <!-- card end// -->
    </section>
    @endsection
</x-app-layout>
