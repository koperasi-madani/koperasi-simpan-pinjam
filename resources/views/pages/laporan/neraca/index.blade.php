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
                            @forelse ($ledger as $item)
                                @php
                                    $totalSaldoAwalDebet = 0;
                                    $totalSaldoAwalKredit = 0;
                                    $totalMutasiDebet = 0;
                                    $totalMutasiKredit = 0;
                                    $totalSaldoAkhirDebet = 0;
                                    $totalSaldoAkhirKredit = 0;
                                    $total = 0;
                                    $t = array();

                                    $totalSaldoAwalDebetDua = 0;
                                    $totalSaldoAwalKreditDua = 0;
                                    $totalMutasiDebetDua = 0;
                                    $totalMutasiKreditDua = 0;
                                    $totalSaldoAkhirDebetDua = 0;
                                    $totalSaldoAkhirKreditDua = 0;
                                    $totalDua = 0;


                                    $current_saldo_tabungan_result = 0;
                                    $current_saldo_tabungan_result_saldo_akhir = 0;
                                    $current_saldo_tabungan_result_saldo_akhir_total = 0;
                                @endphp
                                @php

                                    $mutasiDebetTabungan = 0;
                                    $mutasiKreditTabungan = 0;
                                    $current_date = \Carbon\Carbon::now()->format('Y-m-d');
                                @endphp
                                @php
                                    $kode_induk = \App\Models\KodeInduk::where('id_ledger',$item->id)->orderBy('id','DESC')->get();
                                @endphp
                                <tr class="table-success">
                                    <td  style="border: none !important;">{{ $item->kode_ledger }}</td>
                                    <td  style="border: none !important;">{{ $item->nama }}</td>
                                    <td  style="border: none !important;"></td>
                                    <td  style="border: none !important;"></td>
                                    <td  style="border: none !important;"></td>
                                    <td  style="border: none !important;"></td>
                                    <td  style="border: none !important;"></td>
                                    <td  style="border: none !important;"></td>
                                </tr>
                                {{-- kode induk --}}
                                @foreach ($kode_induk as $item_induk)
                                    {{-- kode akun --}}
                                    @php
                                        $data = \App\Models\KodeAkun::select('kode_akun.*',
                                                                'kode_induk.id as induk_id',
                                                                'kode_induk.nama as nama_induk','kode_induk.jenis')
                                                                ->join('kode_induk','kode_induk.id','kode_akun.id_induk')
                                                                ->where('kode_akun.id_induk',$item_induk->id);
                                        $kode_akun = $data->get();
                                        $kode_akun_dua = $data->get();
                                    @endphp
                                     @foreach ($kode_akun_dua as $item_akun_dua)
                                        @php
                                            $mutasiAwalDebetDua = 0;
                                            $mutasiAwalKreditDua = 0;

                                            $mutasiDebetDua = 0;
                                            $mutasiKreditDua = 0;
                                        @endphp
                                        @php
                                            $cekTransaksiAwalDiKodeDua = \App\Models\Jurnal::where('kode_akun', $item_akun_dua->id)->whereDate('created_at','<',$current_date)->count();
                                            if ($cekTransaksiAwalDiKodeDua > 0) {
                                                $sumMutasiAwalDebetDiKodeDua = \DB::table('jurnal')->whereDate('created_at','<',$current_date)->where('kode_akun', $item_akun_dua->id)->where('tipe', 'debit')->sum('nominal');

                                                $sumMutasiAwalKreditDiKodeDua = \DB::table('jurnal')->whereDate('created_at','<',$current_date)->where('kode_akun', $item_akun_dua->id)->where('tipe', 'kredit')->sum('nominal');

                                                if ($item_akun_dua->jenis == 'debit') {
                                                    $mutasiAwalDebetDua += $sumMutasiAwalDebetDiKodeDua;
                                                    $mutasiAwalKreditDua += $sumMutasiAwalKreditDiKodeDua;
                                                }
                                                else{
                                                    $mutasiAwalDebetDua += $sumMutasiAwalDebetDiKodeDua;
                                                    $mutasiAwalKreditDua += $sumMutasiAwalKreditDiKodeDua;
                                                }


                                                // cek apakah transaksi sebelumnya juga terdapat di field lawan
                                                $cekTransaksiAwalDiLawanDua = \App\Models\Jurnal::where('kode_lawan', $item_akun_dua->id)->whereDate('created_at','<',$current_date)->count();
                                                if ($cekTransaksiAwalDiLawanDua > 0) {
                                                    $sumMutasiAwalDebetDiLawanDua = \DB::table('jurnal')->whereDate('created_at','<',$current_date)->where('kode_lawan', $item_akun_dua->id)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                    $sumMutasiAwalKreditDiLawanDua = \DB::table('jurnal')->whereDate('created_at','<',$current_date)->where('kode_lawan', $item_akun_dua->id)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                    $mutasiAwalDebetDua += $sumMutasiAwalDebetDiLawanDua;
                                                    $mutasiAwalKreditDua += $sumMutasiAwalKreditDiLawanDua;
                                                }
                                            } else {
                                                $cekTransaksiAwalDiLawanDua = \App\Models\Jurnal::where('kode_lawan', $item_akun_dua->id)->whereDate('created_at','<',$current_date)->count();
                                                if ($cekTransaksiAwalDiLawanDua > 0) {
                                                    $sumMutasiAwalDebetDiLawanDua = \DB::table('jurnal')->where('kode_lawan', $item_akun_dua->id)->whereDate('created_at','<',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');
                                                    $sumMutasiAwalKreditDiLawanDua = \DB::table('jurnal')->where('kode_lawan', $item_akun_dua->id)->whereDate('created_at','<',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                    if ($item_akun_dua->jenis == 'debit') {
                                                        $mutasiAwalDebetDua += $sumMutasiAwalDebetDiLawanDua;

                                                        $mutasiAwalKreditDua += $sumMutasiAwalKreditDiLawanDua;
                                                    }
                                                    else{
                                                        $mutasiAwalDebetDua += $sumMutasiAwalDebetDiLawanDua;
                                                        $mutasiAwalKreditDua += $sumMutasiAwalKreditDiLawanDua;
                                                    }
                                                }

                                            }

                                            $cekTransaksiDiKodeDua = \App\Models\Jurnal::where('kode_akun', $item_akun_dua->id)->whereDate('created_at','>=',$current_date)->count();
                                            if ($cekTransaksiDiKodeDua > 0) {
                                                $sumMutasiDebetDiKodeDua = \DB::table('jurnal')->whereDate('created_at','>=',$current_date)->where('kode_akun', $item_akun_dua->id)->where('tipe', 'debit')->sum('jurnal.nominal');
                                                $sumMutasiKreditDiKodeDua = \DB::table('jurnal')->whereDate('created_at','>=',$current_date)->where('kode_akun', $item_akun_dua->id)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                $mutasiDebetDua += $sumMutasiDebetDiKodeDua;
                                                $mutasiKreditDua += $sumMutasiKreditDiKodeDua;

                                                // cek transaksi di field lawan
                                                $cekTransaksiDiLawanDua = \App\Models\Jurnal::where('kode_lawan', $item_akun_dua->id)->whereDate('created_at','>=',$current_date)->count();
                                                if ($cekTransaksiDiLawanDua > 0) {
                                                    $sumMutasiDebetDiLawanDua = \DB::table('jurnal')->whereDate('created_at','>=',$current_date)->where('kode_lawan', $item_akun_dua->id)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                    $sumMutasiKreditDiLawanDua = \DB::table('jurnal')->whereDate('created_at','>=',$current_date)->where('kode_lawan', $item_akun_dua->id)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                    $mutasiDebetDua += $sumMutasiDebetDiLawanDua;
                                                    $mutasiKreditDua += $sumMutasiKreditDiLawanDua;
                                                }

                                            }
                                            else{ // cek transaksi di field lawan
                                                // cek transaksi di field lawan
                                                $cekTransaksiDiLawanDua = \App\Models\Jurnal::where('kode_lawan', $item_akun_dua->id)->whereDate('created_at','>=',$current_date)->count();
                                                if ($cekTransaksiDiLawanDua > 0) {
                                                    $sumMutasiDebetDiLawanDua = \DB::table('jurnal')->whereDate('created_at','>=',$current_date)->where('kode_lawan', $item_akun_dua->id)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                    $sumMutasiKreditDiLawanDua = \DB::table('jurnal')->whereDate('created_at','>=',$current_date)->where('kode_lawan', $item_akun_dua->id)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                    $mutasiDebetDua += $sumMutasiDebetDiLawanDua;
                                                    $mutasiKreditDua += $sumMutasiKreditDiLawanDua;
                                                }

                                            }


                                            $saldoAwalDua = $mutasiAwalDebetDua - $mutasiAwalKreditDua;

                                            $saldoAkhirDua = ($mutasiAwalDebetDua + $mutasiDebetDua) - ($mutasiAwalKreditDua + $mutasiKreditDua);

                                            $totalMutasiDebetDua += $mutasiDebetDua;
                                            $totalMutasiKreditDua += $mutasiKreditDua;

                                            if ($item_akun_dua->jenis == 'debit') {
                                                $totalSaldoAwalDebetDua += $saldoAwalDua;
                                                $totalSaldoAkhirDebetDua += $saldoAkhirDua;
                                            }
                                            else{
                                                $totalSaldoAwalKreditDua += $saldoAwalDua;
                                                $totalSaldoAkhirKreditDua += $saldoAkhirDua;
                                            }

                                        @endphp
                                @endforeach
                                    {{ $totalSaldoAwalDebetDua }}
                                    @if ($item_induk->nama != 'Tabungan')
                                    @endif
                                    <tr style="font-weight: bold" class="table-secondary">
                                        <td >{{ $item_induk->kode_induk }}</td>
                                        <td >{{ $item_induk->nama }}</td>
                                        @if ($item_induk->jenis == 'debit')
                                            <td>{{ number_format($totalSaldoAwalDebetDua , 2, ',', '.') }}</td>
                                        @else
                                            <td>{{ number_format($totalSaldoAwalKreditDua * -1, 2, ',', '.') }}</td>
                                        @endif
                                        <td class="align-top">{{ $item_induk->jenis == 'kredit' ? '' : 'DR' }}</td>
                                        @if ($item_induk->id_ledger == $item->id)
                                            <td>{{ number_format($totalMutasiDebetDua, 2, ',', '.') }}</td>
                                            <td>{{ number_format($totalMutasiKreditDua , 2, ',', '.') }}</td>
                                        @endif
                                        @if ($item_induk->id_ledger == $item->id)
                                            @if ($item_induk->jenis == 'debit')
                                                <td>{{ number_format($totalSaldoAkhirDebetDua, 2, ',', '.') }}</td>
                                            @else
                                                <td>{{ number_format($totalSaldoAkhirKreditDua * -1, 2, ',', '.') }}</td>
                                            @endif
                                        @endif
                                        <td class="align-top">{{ $item_induk->jenis == 'kredit' ? '' : 'DR' }}</td>
                                    </tr>
                                    @foreach ($kode_akun as $item_akun)
                                            @php
                                                $mutasiAwalDebet = 0;
                                                $mutasiAwalKredit = 0;

                                                $mutasiDebet = 0;
                                                $mutasiKredit = 0;
                                            @endphp
                                            @php
                                                // if ($item_akun->nama_induk != 'Tabungan' || $item_akun->nama_induk != 'tabungan') {
                                                    $cekTransaksiAwalDiKode = \App\Models\Jurnal::where('kode_akun', $item_akun->id)->whereDate('created_at','<',$current_date)->count();
                                                    if ($cekTransaksiAwalDiKode > 0) {
                                                        $sumMutasiAwalDebetDiKode = \DB::table('jurnal')->whereDate('created_at','<',$current_date)->where('kode_akun', $item_akun->id)->where('tipe', 'debit')->sum('nominal');

                                                        $sumMutasiAwalKreditDiKode = \DB::table('jurnal')->whereDate('created_at','<',$current_date)->where('kode_akun', $item_akun->id)->where('tipe', 'kredit')->sum('nominal');

                                                        if ($item_akun->jenis == 'debit') {
                                                            $mutasiAwalDebet += $sumMutasiAwalDebetDiKode;
                                                            $mutasiAwalKredit += $sumMutasiAwalKreditDiKode;
                                                        }
                                                        else{
                                                            $mutasiAwalDebet += $sumMutasiAwalDebetDiKode;
                                                            $mutasiAwalKredit += $sumMutasiAwalKreditDiKode;
                                                        }


                                                        // cek apakah transaksi sebelumnya juga terdapat di field lawan
                                                        $cekTransaksiAwalDiLawan = \App\Models\Jurnal::where('kode_lawan', $item_akun->id)->whereDate('created_at','<',$current_date)->count();
                                                        if ($cekTransaksiAwalDiLawan > 0) {
                                                            $sumMutasiAwalDebetDiLawan = \DB::table('jurnal')->whereDate('created_at','<',$current_date)->where('kode_lawan', $item_akun->id)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                            $sumMutasiAwalKreditDiLawan = \DB::table('jurnal')->whereDate('created_at','<',$current_date)->where('kode_lawan', $item_akun->id)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                            $mutasiAwalDebet += $sumMutasiAwalDebetDiLawan;
                                                            $mutasiAwalKredit += $sumMutasiAwalKreditDiLawan;
                                                        }
                                                    } else {
                                                        $cekTransaksiAwalDiLawan = \App\Models\Jurnal::where('kode_lawan', $item_akun->id)->whereDate('created_at','<',$current_date)->count();
                                                        if ($cekTransaksiAwalDiLawan > 0) {
                                                            $sumMutasiAwalDebetDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_akun->id)->whereDate('created_at','<',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');
                                                            $sumMutasiAwalKreditDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_akun->id)->whereDate('created_at','<',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                            if ($item_akun->jenis == 'debit') {
                                                                $mutasiAwalDebet += $sumMutasiAwalDebetDiLawan;

                                                                $mutasiAwalKredit += $sumMutasiAwalKreditDiLawan;
                                                            }
                                                            else{
                                                                $mutasiAwalDebet += $sumMutasiAwalDebetDiLawan;
                                                                $mutasiAwalKredit += $sumMutasiAwalKreditDiLawan;
                                                            }
                                                        }

                                                    }

                                                    $cekTransaksiDiKode = \App\Models\Jurnal::where('kode_akun', $item_akun->id)->whereDate('created_at','>=',$current_date)->count();
                                                    if ($cekTransaksiDiKode > 0) {
                                                        $sumMutasiDebetDiKode = \DB::table('jurnal')->whereDate('created_at','>=',$current_date)->where('kode_akun', $item_akun->id)->where('tipe', 'debit')->sum('jurnal.nominal');
                                                        $sumMutasiKreditDiKode = \DB::table('jurnal')->whereDate('created_at','>=',$current_date)->where('kode_akun', $item_akun->id)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                        $mutasiDebet += $sumMutasiDebetDiKode;
                                                        $mutasiKredit += $sumMutasiKreditDiKode;

                                                        // cek transaksi di field lawan
                                                        $cekTransaksiDiLawan = \App\Models\Jurnal::where('kode_lawan', $item_akun->id)->whereDate('created_at','>=',$current_date)->count();
                                                        if ($cekTransaksiDiLawan > 0) {
                                                            $sumMutasiDebetDiLawan = \DB::table('jurnal')->whereDate('created_at','>=',$current_date)->where('kode_lawan', $item_akun->id)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                            $sumMutasiKreditDiLawan = \DB::table('jurnal')->whereDate('created_at','>=',$current_date)->where('kode_lawan', $item_akun->id)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                            $mutasiDebet += $sumMutasiDebetDiLawan;
                                                            $mutasiKredit += $sumMutasiKreditDiLawan;
                                                        }

                                                    }
                                                    else{ // cek transaksi di field lawan
                                                        // cek transaksi di field lawan
                                                        $cekTransaksiDiLawan = \App\Models\Jurnal::where('kode_lawan', $item_akun->id)->whereDate('created_at','>=',$current_date)->count();
                                                        if ($cekTransaksiDiLawan > 0) {
                                                            $sumMutasiDebetDiLawan = \DB::table('jurnal')->whereDate('created_at','>=',$current_date)->where('kode_lawan', $item_akun->id)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                            $sumMutasiKreditDiLawan = \DB::table('jurnal')->whereDate('created_at','>=',$current_date)->where('kode_lawan', $item_akun->id)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                            $mutasiDebet += $sumMutasiDebetDiLawan;
                                                            $mutasiKredit += $sumMutasiKreditDiLawan;
                                                        }

                                                    }


                                                    $saldoAwal = $mutasiAwalDebet - $mutasiAwalKredit;

                                                    $saldoAkhir = ($mutasiAwalDebet + $mutasiDebet) - ($mutasiAwalKredit + $mutasiKredit);

                                                    $totalMutasiDebet += $mutasiDebet;
                                                    $totalMutasiKredit += $mutasiKredit;

                                                    array_push($t,$mutasiDebet);

                                                    if ($item_akun->jenis == 'debit') {
                                                        $totalSaldoAwalDebet += $saldoAwal;
                                                        $totalSaldoAkhirDebet += $saldoAkhir;
                                                    }
                                                    else{
                                                        $totalSaldoAwalKredit += $saldoAwal;
                                                        $totalSaldoAkhirKredit += $saldoAkhir;
                                                    }
                                                    $total = $totalSaldoAwalDebet + $mutasiDebet - $mutasiKredit;
                                                // }else{
                                                // }
                                                // $current_saldo_tabungan = \App\Models\TransaksiTabungan::select('transaksi_tabungan.*')
                                                //                 ->whereDate('created_at','<',$current_date)
                                                //                 ->get();
                                                // $current_saldo_tabungan_masuk = 0;
                                                // $current_saldo_tabungan_keluar = 0;

                                                // foreach ($current_saldo_tabungan as $item_saldo_tabungan){
                                                //     if ($item_saldo_tabungan->jenis == 'keluar') {
                                                //         $current_saldo_tabungan_keluar += $item_saldo_tabungan->nominal;
                                                //     } else {
                                                //         $current_saldo_tabungan_masuk += $item_saldo_tabungan->nominal;
                                                //     }
                                                //     $current_saldo_tabungan_result = $current_saldo_tabungan_masuk - $current_saldo_tabungan_keluar;
                                                // }

                                            @endphp
                                        <tr>
                                            {{-- @if ($item_akun->nama_induk == 'Tabungan' || $item_akun->nama_induk == 'tabungan')
                                                @php
                                                    $sumMutasiKreditTabungan = \DB::table('transaksi_tabungan')->whereDate('created_at','>=',$current_date)->where('jenis', 'masuk')->sum('transaksi_tabungan.nominal');

                                                    $sumMutasiDebetTabungan = \DB::table('transaksi_tabungan')->whereDate('created_at','>=',$current_date)->where('jenis', 'keluar')->sum('transaksi_tabungan.nominal');
                                                    $mutasiDebetTabungan += $sumMutasiDebetTabungan;
                                                    $mutasiKreditTabungan += $sumMutasiKreditTabungan;
                                                    $current_saldo_tabungan_result_saldo_akhir = $current_saldo_tabungan_result + $sumMutasiKreditTabungan - $sumMutasiDebetTabungan;
                                                @endphp
                                                <td class="align-top">{{ $item_akun->kode_akun }}</td>
                                                <td class="align-top">{{ $item_akun->nama_akun }}</td>
                                                <td class="align-top">{{ number_format($current_saldo_tabungan_result,2, ",", ".") }}</td>
                                                <td class="align-top">{{ $item_akun->jenis == 'kredit' ? '' : 'DR' }}</td>
                                                <td>{{ number_format($mutasiDebetTabungan, 2, ',', '.') }}</td>
                                                <td>{{ number_format($mutasiKreditTabungan, 2, ',', '.') }}</td>
                                                <td>{{ number_format($current_saldo_tabungan_result_saldo_akhir, 2, ',', '.') }}</td>
                                                <td class="align-top">{{ $item_akun->jenis == 'kredit' ? '' : 'DR' }}</td>
                                            @else
                                            @endif --}}
                                            <td class="align-top">{{ $item_akun->kode_akun }}</td>
                                            <td class="align-top">{{ $item_akun->nama_akun }}</td>
                                            @if ($item_akun->jenis == 'debit')
                                                <td>{{ number_format($saldoAwal, 2, ',', '.') }}</td>
                                            @else
                                                <td>{{ number_format($saldoAwal * -1, 2, ',', '.') }}</td>
                                            @endif
                                            <td class="align-top">{{ $item_akun->jenis == 'kredit' ? '' : 'DR' }}</td>
                                            <td>{{ number_format($mutasiDebet, 2, ',', '.') }}</td>
                                            <td>{{ number_format($mutasiKredit, 2, ',', '.') }}</td>
                                            @if ($item_akun->jenis == 'debit')
                                                <td>{{ number_format($saldoAkhir, 2, ',', '.') }}</td>
                                            @else
                                                <td>{{ number_format($saldoAkhir * -1, 2, ',', '.') }}</td>
                                            @endif
                                            {{-- <th>{{ number_format($totalSaldoAkhirKredit * -1, 2, ',', '.') }}</th> --}}
                                            <td class="align-top">{{ $item_akun->jenis == 'kredit' ? '' : 'DR' }}</td>
                                        </tr>
                                    @endforeach
                                @endforeach

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
