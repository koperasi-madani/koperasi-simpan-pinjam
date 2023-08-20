<x-app-layout>
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
        <style>
            .page-item.active .page-link{
                background-color: #219ebc !important;
                border-color: #8ecae6;
            }
            .table > :not(caption) > * > *{
                padding: 5px;
            }
            .table-bondered th, td.uang{
                text-align: right !important;
                /* border: 1px solid #9896966e !important; */
            }
            .table-bondered th, td, th{
                border: 0.1px solid #cccbcb59 !important;
            }
            .bg-primary{
                background-color: #E0F6FB !important;
            }
            .bg-secondary{
                background-color: #83c5be !important;
                font-weight: bold !important;
            }
            table{
                font-size: 12px !important;
            }

        </style>
    @endpush
    @push('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#example').DataTable({
                // 'paging' : false,
                info: false,
                ordering: false,
                paging: false
            });
        })
    </script>
    @endpush
    @section('content')
    <section class="content-main">

        <div class="card mb-4">
            <header class="card-header">
                <div class="d-flex justify-content-between">
                    <div> <h2 class="content-title">{{ ucwords(str_replace('-',' ',Request::segment(3))) }}</h2></div>
                    <div>
                        <h5>WILAYAH: 01 - JAWA TIMUR</h5>
                    </div>
                </div>
            </header>
            <div class="card-body">
                <div class="mb-4">
                    {{-- <form action="{{ route('neraca.index') }}" method="GET">
                        @csrf
                    <div class="row">

                        <div class="col-md-6">
                            <input type="text" name="nama_akun" id="" value="{{ request('nama_akun') }}" class="form-control" placeholder="Pencarian Nama Akun">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">Cari</button>
                            <a href="{{ route('neraca.index') }}" class="btn btn-outline-secondary">Reset</a>
                        </form>
                        </div>
                        <div class="col-md-2 d-flex justify-content-end" >
                            <a href="{{ route('neraca.cetak') }}" class="btn btn-primary text-center px-3" > CETAK</a>
                        </div>
                    </div> --}}
                    <div class="d-flex justify-content-end" >
                        <a href="{{ route('neraca.cetak') }}" class="btn btn-primary text-center px-3" > CETAK</a>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered" id="example">
                        <thead>
                            <tr>
                                <th rowspan="2" style="vertical-align:middle" width="4%">LEDGER A/C NO.</th>
                                <th rowspan="2" style="vertical-align:middle" width="28%">KETERANGAN</th>
                                <th rowspan="2" style="text-align: center;" class="text-center" width="15%">SALDO AWAL</th>
                                <th colspan="2" style="text-align: center;" align="center" width="30%" class="text-center">TRANSAKSI</th>
                                <th rowspan="2" style="text-align: center;" class="text-center" width="15%">SALDO AKHIR</th>
                                <tr>

                                    <th style="text-align: center;" class="text-center">DEBET</th>
                                    <th style="text-align: center;" class="text-center">KREDIT</th>

                                </tr>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalPendapatan = 0;
                                $totalBiaya = 0;
                            @endphp
                            @foreach ($kode_induk as $item_induk)
                                    @php
                                        $totalSaldoAwalDebet = 0;
                                        $totalSaldoAwalKredit = 0;
                                        $totalMutasiDebet = 0;
                                        $totalMutasiKredit = 0;
                                        $totalSaldoAkhirDebet = 0;
                                        $totalSaldoAkhirKredit = 0;

                                        $totalSaldoAwalDebetTotal = 0;
                                        $totalSaldoAwalKreditTotal = 0;

                                        $totalSaldoAwalDebetTotalTiga = 0;
                                        $totalSaldoAwalKreditTotalTiga = 0;

                                        $totalMutasiDebetTotal = 0;
                                        $totalMutasiKreditTotal = 0;

                                        $totalSaldoAkhirDebetTotal = 0;
                                        $totalSaldoAkhirKreditTotal = 0;

                                        $totalSaldoAkhirDebetTotalTiga = 0;
                                        $totalSaldoAkhirKreditTotalTiga = 0;

                                    @endphp

                                    @php
                                        $kode_induk_dua = \App\Models\KodeInduk::select('kode_induk.id','kode_induk.id_ledger','kode_induk.kode_induk','kode_induk.nama')->where('id_ledger',$item_induk->id_ledger)->groupBy('kode_induk.kode_induk')->orderBy('id','DESC')->get();
                                    @endphp
                                    @foreach ($kode_induk_dua as $item_ledger_akun)
                                        @php
                                            $ledger_data = \App\Models\KodeAkun::select('kode_akun.*',
                                                    'kode_induk.id as induk_id',
                                                    'kode_induk.nama as nama_induk','kode_induk.jenis','kode_ledger.id as ledger_id','kode_ledger.kode_ledger','kode_ledger.nama as nama_ledger')
                                                    ->join('kode_induk','kode_induk.id','kode_akun.id_induk')
                                                    ->join('kode_ledger','kode_ledger.id','kode_induk.id_ledger')
                                                    // ->where('kode_induk.id_ledger',$item_induk->id)
                                                    ->where('kode_akun.id_induk',$item_ledger_akun->id)
                                                    ->get()
                                        @endphp
                                        @foreach ($ledger_data as $item_ledger)
                                            @php
                                                $mutasiAwalDebet = 0;
                                                $mutasiAwalKredit = 0;

                                                $mutasiDebet = 0;
                                                $mutasiKredit = 0;
                                                $current_date = \Carbon\Carbon::now()->format('Y-m-d');

                                                // cek apakah ada jurnal awal di field kode
                                                $cekTransaksiAwalDiKode = \App\Models\Jurnal::where('created_at','<',$current_date)->where('kode_akun', $item_ledger->id)->count();

                                                if ($cekTransaksiAwalDiKode > 0) {
                                                    $sumMutasiAwalDebetDiKode = \DB::table('jurnal')->where('kode_akun', $item_ledger->id)->where('created_at','<',$current_date)->where('tipe', 'debit')->sum(\DB::raw('CAST(jurnal.nominal AS SIGNED)'));

                                                    $sumMutasiAwalKreditDiKode = \DB::table('jurnal')->where('kode_akun', $item_ledger->id)->where('created_at','<',$current_date)->where('tipe', 'kredit')->sum(\DB::raw('CAST(jurnal.nominal AS SIGNED)'));

                                                    if ($item_ledger->jenis == 'debit') {
                                                        $mutasiAwalDebet += $sumMutasiAwalDebetDiKode;
                                                        $mutasiAwalKredit += $sumMutasiAwalKreditDiKode;
                                                    }
                                                    else{
                                                        $mutasiAwalDebet += $sumMutasiAwalDebetDiKode;
                                                        $mutasiAwalKredit += $sumMutasiAwalKreditDiKode;
                                                    }

                                                }
                                                // cek transaksi di field kode
                                                $cekTransaksiDiKode = \App\Models\Jurnal::where('created_at','>=',$current_date)->where('kode_akun', $item_ledger->id)->count();

                                                if ($cekTransaksiDiKode > 0) {
                                                    $sumMutasiDebetDiKode = \DB::table('jurnal')->where('kode_akun', $item_ledger->id)->where('created_at','>=',$current_date)->where('tipe', 'debit')->sum(\DB::raw('CAST(jurnal.nominal AS SIGNED)'));

                                                    $sumMutasiKreditDiKode = \DB::table('jurnal')->where('kode_akun', $item_ledger->id)->where('created_at','>=',$current_date)->where('tipe', 'kredit')->sum(\DB::raw('CAST(jurnal.nominal AS SIGNED)'));

                                                    $mutasiDebet += $sumMutasiDebetDiKode;
                                                    $mutasiKredit += $sumMutasiKreditDiKode;

                                                }

                                                $saldoAwal = $mutasiAwalDebet - $mutasiAwalKredit;
                                                if ($item_ledger->nama_ledger == 'P E N D A P A T A N') {
                                                    $saldoAkhir = ($saldoAwal + $mutasiKredit) - $mutasiDebet;
                                                } else if ($item_ledger->nama_ledger == 'PASSIVA') {
                                                    $saldoAkhir = $saldoAwal + $mutasiKredit -  $mutasiDebet;
                                                } else {
                                                    $saldoAkhir = ($mutasiAwalDebet + $mutasiDebet) - ($mutasiAwalKredit + $mutasiKredit);
                                                }

                                                $totalMutasiDebetTotal += $mutasiDebet;
                                                $totalMutasiKreditTotal += $mutasiKredit;

                                                if ($item_ledger->jenis == 'debit') {
                                                    $totalSaldoAwalDebetTotalTiga += $saldoAwal;
                                                    $totalSaldoAkhirDebetTotalTiga += $saldoAkhir;
                                                }
                                                else{
                                                    $totalSaldoAwalKreditTotalTiga += $saldoAwal;
                                                    $totalSaldoAkhirKreditTotalTiga += $saldoAkhir;
                                                }

                                                // perhitungan laba/rugi
                                                if (count($kode_pendapatan) > 0) {
                                                    if ($item_ledger->nama_induk == 'MODAL' || $item_ledger->nama_akun == 'LABA / RUGI TAHUN BERJALAN') {
                                                        // Ngitung pendapatan;
                                                        $mutasiAwalDebetPendapatan = 0;
                                                        $mutasiAwalKreditPendapatan = 0;

                                                        $mutasiDebetPendapatan = 0;
                                                        $mutasiKreditPendapatan = 0;
                                                        foreach ($kode_pendapatan as $itemPendapatan) {
                                                            $cekTransaksiAwalDiKode = \App\Models\Jurnal::where('created_at','<',$current_date)->where('kode_akun', $itemPendapatan->id)->count();
                                                            if ($cekTransaksiAwalDiKode > 0) {
                                                                $sumMutasiAwalDebetDiKode = \DB::table('jurnal')->where('kode_akun', $itemPendapatan->id)->where('created_at','<',$current_date)->where('tipe', 'debit')->sum(\DB::raw('CAST(jurnal.nominal AS SIGNED)'));

                                                                $sumMutasiAwalKreditDiKode = \DB::table('jurnal')->where('kode_akun', $itemPendapatan->id)->where('created_at','<',$current_date)->where('tipe', 'kredit')->sum(\DB::raw('CAST(jurnal.nominal AS SIGNED)'));

                                                                if ($itemPendapatan->jenis == 'debit') {
                                                                    $mutasiAwalDebetPendapatan += $sumMutasiAwalDebetDiKode;
                                                                    $mutasiAwalKreditPendapatan += $sumMutasiAwalKreditDiKode;
                                                                }
                                                                else{
                                                                    $mutasiAwalDebetPendapatan += $sumMutasiAwalDebetDiKode;
                                                                    $mutasiAwalKreditPendapatan += $sumMutasiAwalKreditDiKode;
                                                                }
                                                            }

                                                            $cekTransaksiDiKode = \App\Models\Jurnal::where('created_at','>=',$current_date)->where('kode_akun', $itemPendapatan->id)->count();

                                                            if ($cekTransaksiDiKode > 0) {
                                                                $sumMutasiDebetDiKode = \DB::table('jurnal')->where('kode_akun', $itemPendapatan->id)->where('created_at','>=',$current_date)->where('tipe', 'debit')->sum(\DB::raw('CAST(jurnal.nominal AS SIGNED)'));

                                                                $sumMutasiKreditDiKode = \DB::table('jurnal')->where('kode_akun', $itemPendapatan->id)->where('created_at','>=',$current_date)->where('tipe', 'kredit')->sum(\DB::raw('CAST(jurnal.nominal AS SIGNED)'));

                                                                $mutasiDebetPendapatan += $sumMutasiDebetDiKode;
                                                                $mutasiKreditPendapatan += $sumMutasiKreditDiKode;

                                                            }
                                                            $saldoAwal = $mutasiAwalDebetPendapatan - $mutasiAwalKreditPendapatan;


                                                            $saldoAkhir = ($mutasiAwalDebetPendapatan + $mutasiDebetPendapatan) - ($mutasiAwalKreditPendapatan + $mutasiKreditPendapatan);

                                                            $totalPendapatan = $saldoAwal;
                                                            $totalMutasiKreditPendapatan =  $mutasiKreditPendapatan - $mutasiDebetPendapatan;
                                                        }
                                                        $mutasiAwalDebetModal = 0;
                                                        $mutasiAwalKreditModal = 0;

                                                        $mutasiDebetModal = 0;
                                                        $mutasiKreditModal = 0;
                                                        foreach ($kode_modal as $itemModal) {
                                                            $cekTransaksiAwalDiKode = \App\Models\Jurnal::where('created_at','<',$current_date)->where('kode_akun', $itemModal->id)->count();
                                                            if ($cekTransaksiAwalDiKode > 0) {
                                                                $sumMutasiAwalDebetDiKode = \DB::table('jurnal')->where('kode_akun', $itemModal->id)->where('created_at','<',$current_date)->where('tipe', 'debit')->sum(\DB::raw('CAST(jurnal.nominal AS SIGNED)'));

                                                                $sumMutasiAwalKreditDiKode = \DB::table('jurnal')->where('kode_akun', $itemModal->id)->where('created_at','<',$current_date)->where('tipe', 'kredit')->sum(\DB::raw('CAST(jurnal.nominal AS SIGNED)'));

                                                                if ($itemModal->jenis == 'debit') {
                                                                    $mutasiAwalDebetModal += $sumMutasiAwalDebetDiKode;
                                                                    $mutasiAwalKreditModal += $sumMutasiAwalKreditDiKode;
                                                                }
                                                                else{
                                                                    $mutasiAwalDebetModal += $sumMutasiAwalDebetDiKode;
                                                                    $mutasiAwalKreditModal += $sumMutasiAwalKreditDiKode;
                                                                }
                                                            }

                                                            $cekTransaksiDiKode = \App\Models\Jurnal::where('created_at','>=',$current_date)->where('kode_akun', $itemModal->id)->count();

                                                            if ($cekTransaksiDiKode > 0) {
                                                                $sumMutasiDebetDiKode = \DB::table('jurnal')->where('kode_akun', $itemModal->id)->where('created_at','>=',$current_date)->where('tipe', 'debit')->sum(\DB::raw('CAST(jurnal.nominal AS SIGNED)'));

                                                                $sumMutasiKreditDiKode = \DB::table('jurnal')->where('kode_akun', $itemModal->id)->where('created_at','>=',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                                $mutasiDebetModal += $sumMutasiDebetDiKode;
                                                                $mutasiKreditModal += $sumMutasiKreditDiKode;

                                                            }
                                                            $saldoAwal = $mutasiAwalDebetModal - $mutasiAwalKreditModal;

                                                            $saldoAkhir = ($mutasiAwalDebetModal + $mutasiDebetModal) - ($mutasiAwalKreditModal + $mutasiKreditModal);

                                                            $totalModal = $saldoAwal;
                                                            $totalMutasiDebetLaba = $mutasiDebetModal - $mutasiKreditModal;
                                                        }
                                                        $totalSaldoAwalLaba =  $totalModal - $totalPendapatan;
                                                        $totalSaldoAkhirLaba =  $totalSaldoAwalLaba + $totalMutasiKreditPendapatan - $totalMutasiDebetLaba;
                                                        if ($item_ledger->nama_induk == 'MODAL') {
                                                            $total = $totalSaldoAwalKreditTotalTiga;
                                                            $totalAkhir = $totalSaldoAkhirKreditTotalTiga;
                                                            if ($loop->first) {
                                                                $totalSaldoAwalKreditTotalTiga = $total + $totalSaldoAwalLaba;
                                                                $totalMutasiDebetTotal = $totalMutasiDebetTotal + $totalMutasiDebetLaba;
                                                                $totalMutasiKreditTotal = $totalMutasiKreditTotal + $totalMutasiKreditPendapatan;
                                                                $totalSaldoAkhirKreditTotalTiga = ($totalSaldoAwalKreditTotalTiga + $totalMutasiKreditTotal) - $totalMutasiDebetTotal;
                                                            }

                                                        }

                                                    }
                                                } else {
                                                    # code...
                                                }


                                            @endphp
                                        @endforeach
                                    @endforeach
                                    <tr class="bg-secondary">
                                        <td>{{ $item_induk->kode_ledger }}</td>
                                        <td>{{ strtoupper($item_induk->nama_ledger) }}</td>
                                        @if ($item_ledger->jenis == 'debit')
                                            <td class="uang">{{ number_format($totalSaldoAwalDebetTotalTiga / 100, 2, ',', '.') }}</td>
                                        @else
                                            <td class="uang">{{ number_format($totalSaldoAwalKreditTotalTiga / 100, 2, ',', '.') }}</td>
                                        @endif
                                        <td class="uang"> {{ number_format($totalMutasiDebetTotal / 100, 2, ',', '.') }}</td>
                                        <td class="uang">{{ number_format($totalMutasiKreditTotal / 100, 2, ',', '.') }}</td>
                                        @if ($item_ledger->jenis == 'debit')
                                            <td class="uang">{{ number_format($totalSaldoAkhirDebetTotalTiga / 100, 2, ',', '.') }}</td>
                                        @else
                                            <td class="uang">{{ number_format($totalSaldoAkhirKreditTotalTiga / 100, 2, ',', '.') }}</td>
                                        @endif
                                    </tr>
                                    @php
                                        $kode_induk_dua = \App\Models\KodeInduk::select('kode_induk.id','kode_induk.id_ledger','kode_induk.kode_induk','kode_induk.nama')
                                                    ->where('id_ledger',$item_induk->id_ledger)
                                                    ->orderBy('kode_induk.kode_induk','ASC')->get();
                                    @endphp
                                    @foreach ($kode_induk_dua as $tes)
                                        @php
                                            $totalSaldoAwalDebetDua = 0;
                                            $totalSaldoAwalKreditDua = 0;
                                            $totalMutasiDebetDua = 0;
                                            $totalMutasiKreditDua = 0;
                                            $totalSaldoAkhirDebetDua = 0;
                                            $totalSaldoAkhirKreditDua = 0;
                                        @endphp
                                        @php
                                            $ledger_dua = \App\Models\KodeAkun::select('kode_akun.*',
                                                        'kode_induk.id as induk_id',
                                                        'kode_induk.nama as nama_induk','kode_induk.jenis','kode_ledger.id as ledger_id','kode_ledger.kode_ledger','kode_ledger.nama as nama_ledger')
                                                        ->join('kode_induk','kode_induk.id','kode_akun.id_induk')
                                                        ->join('kode_ledger','kode_ledger.id','kode_induk.id_ledger')
                                                        ->where('kode_induk.id_ledger',$item_induk->id_ledger)
                                                        ->where('kode_akun.id_induk',$tes->id)
                                                        ->get()
                                        @endphp
                                        @foreach ($ledger_dua as $item_dua)
                                            @php
                                                $mutasiAwalDebet = 0;
                                                $mutasiAwalKredit = 0;

                                                $mutasiDebet = 0;
                                                $mutasiKredit = 0;
                                                $current_date = \Carbon\Carbon::now()->format('Y-m-d');

                                                // cek apakah ada jurnal awal di field kode
                                                $cekTransaksiAwalDiKode = \App\Models\Jurnal::where('created_at','<',$current_date)->where('kode_akun', $item_dua->id)->count();

                                                if ($cekTransaksiAwalDiKode > 0) {
                                                    $sumMutasiAwalDebetDiKode = \DB::table('jurnal')->where('kode_akun', $item_dua->id)->where('created_at','<',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                    $sumMutasiAwalKreditDiKode = \DB::table('jurnal')->where('kode_akun', $item_dua->id)->where('created_at','<',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                    if ($item_dua->jenis == 'debit') {
                                                        $mutasiAwalDebet += $sumMutasiAwalDebetDiKode;
                                                        $mutasiAwalKredit += $sumMutasiAwalKreditDiKode;
                                                    }
                                                    else{
                                                        $mutasiAwalDebet += $sumMutasiAwalDebetDiKode;
                                                        $mutasiAwalKredit += $sumMutasiAwalKreditDiKode;
                                                    }

                                                }

                                                // cek transaksi di field kode
                                                $cekTransaksiDiKode = \App\Models\Jurnal::where('created_at','>=',$current_date)->where('kode_akun', $item_dua->id)->count();

                                                if ($cekTransaksiDiKode > 0) {
                                                    $sumMutasiDebetDiKode = \DB::table('jurnal')->where('kode_akun', $item_dua->id)->where('created_at','>=',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                    $sumMutasiKreditDiKode = \DB::table('jurnal')->where('kode_akun', $item_dua->id)->where('created_at','>=',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                    $mutasiDebet += $sumMutasiDebetDiKode;
                                                    $mutasiKredit += $sumMutasiKreditDiKode;
                                                }
                                                $saldoAwal = $mutasiAwalDebet - $mutasiAwalKredit;

                                                if ($item_dua->nama_ledger == 'P E N D A P A T A N') {
                                                    $saldoAkhir = ($saldoAwal + $mutasiKredit) - $mutasiDebet;
                                                } else if ($item_dua->nama_ledger == 'PASSIVA') {
                                                    $saldoAkhir = $saldoAwal + $mutasiKredit -  $mutasiDebet;
                                                } else {
                                                    $saldoAkhir = ($mutasiAwalDebet + $mutasiDebet) - ($mutasiAwalKredit + $mutasiKredit);
                                                }

                                                $totalMutasiDebetDua += $mutasiDebet;
                                                $totalMutasiKreditDua += $mutasiKredit;

                                                if ($item_dua->jenis == 'debit') {
                                                    $totalSaldoAwalDebetDua += $saldoAwal;
                                                    $totalSaldoAkhirDebetDua += $saldoAkhir;
                                                }
                                                else{
                                                    $totalSaldoAwalKreditDua += $saldoAwal;
                                                    $totalSaldoAkhirKreditDua += $saldoAkhir;
                                                }
                                                if (count($kode_pendapatan) > 0) {
                                                    // perhitungan laba/rugi
                                                    if ($item_dua->nama_induk == 'MODAL' || $item_dua->nama_akun == 'LABA / RUGI TAHUN BERJALAN') {
                                                        // Ngitung pendapatan;
                                                        $mutasiAwalDebetPendapatan = 0;
                                                        $mutasiAwalKreditPendapatan = 0;

                                                        $mutasiDebetPendapatan = 0;
                                                        $mutasiKreditPendapatan = 0;
                                                        foreach ($kode_pendapatan as $itemPendapatan) {
                                                            $cekTransaksiAwalDiKode = \App\Models\Jurnal::where('created_at','<',$current_date)->where('kode_akun', $itemPendapatan->id)->count();
                                                            if ($cekTransaksiAwalDiKode > 0) {
                                                                $sumMutasiAwalDebetDiKode = \DB::table('jurnal')->where('kode_akun', $itemPendapatan->id)->where('created_at','<',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                                $sumMutasiAwalKreditDiKode = \DB::table('jurnal')->where('kode_akun', $itemPendapatan->id)->where('created_at','<',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                                if ($itemPendapatan->jenis == 'debit') {
                                                                    $mutasiAwalDebetPendapatan += $sumMutasiAwalDebetDiKode;
                                                                    $mutasiAwalKreditPendapatan += $sumMutasiAwalKreditDiKode;
                                                                }
                                                                else{
                                                                    $mutasiAwalDebetPendapatan += $sumMutasiAwalDebetDiKode;
                                                                    $mutasiAwalKreditPendapatan += $sumMutasiAwalKreditDiKode;
                                                                }
                                                            }

                                                            $cekTransaksiDiKode = \App\Models\Jurnal::where('created_at','>=',$current_date)->where('kode_akun', $itemPendapatan->id)->count();

                                                            if ($cekTransaksiDiKode > 0) {
                                                                $sumMutasiDebetDiKode = \DB::table('jurnal')->where('kode_akun', $itemPendapatan->id)->where('created_at','>=',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                                $sumMutasiKreditDiKode = \DB::table('jurnal')->where('kode_akun', $itemPendapatan->id)->where('created_at','>=',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                                $mutasiDebetPendapatan += $sumMutasiDebetDiKode;
                                                                $mutasiKreditPendapatan += $sumMutasiKreditDiKode;

                                                            }
                                                            $saldoAwal = $mutasiAwalDebetPendapatan - $mutasiAwalKreditPendapatan;


                                                            $saldoAkhir = ($mutasiAwalDebetPendapatan + $mutasiDebetPendapatan) - ($mutasiAwalKreditPendapatan + $mutasiKreditPendapatan);

                                                            $totalPendapatan = $saldoAwal;
                                                            $totalMutasiKreditPendapatan =  $mutasiKreditPendapatan - $mutasiDebetPendapatan;
                                                        }
                                                        $mutasiAwalDebetModal = 0;
                                                        $mutasiAwalKreditModal = 0;

                                                        $mutasiDebetModal = 0;
                                                        $mutasiKreditModal = 0;
                                                        foreach ($kode_modal as $itemModal) {
                                                            $cekTransaksiAwalDiKode = \App\Models\Jurnal::where('created_at','<',$current_date)->where('kode_akun', $itemModal->id)->count();
                                                            if ($cekTransaksiAwalDiKode > 0) {
                                                                $sumMutasiAwalDebetDiKode = \DB::table('jurnal')->where('kode_akun', $itemModal->id)->where('created_at','<',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                                $sumMutasiAwalKreditDiKode = \DB::table('jurnal')->where('kode_akun', $itemModal->id)->where('created_at','<',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                                if ($itemModal->jenis == 'debit') {
                                                                    $mutasiAwalDebetModal += $sumMutasiAwalDebetDiKode;
                                                                    $mutasiAwalKreditModal += $sumMutasiAwalKreditDiKode;
                                                                }
                                                                else{
                                                                    $mutasiAwalDebetModal += $sumMutasiAwalDebetDiKode;
                                                                    $mutasiAwalKreditModal += $sumMutasiAwalKreditDiKode;
                                                                }
                                                            }

                                                            $cekTransaksiDiKode = \App\Models\Jurnal::where('created_at','>=',$current_date)->where('kode_akun', $itemModal->id)->count();

                                                            if ($cekTransaksiDiKode > 0) {
                                                                $sumMutasiDebetDiKode = \DB::table('jurnal')->where('kode_akun', $itemModal->id)->where('created_at','>=',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                                $sumMutasiKreditDiKode = \DB::table('jurnal')->where('kode_akun', $itemModal->id)->where('created_at','>=',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                                $mutasiDebetModal += $sumMutasiDebetDiKode;
                                                                $mutasiKreditModal += $sumMutasiKreditDiKode;

                                                            }
                                                            $saldoAwal = $mutasiAwalDebetModal - $mutasiAwalKreditModal;

                                                            $saldoAkhir = ($mutasiAwalDebetModal + $mutasiDebetModal) - ($mutasiAwalKreditModal + $mutasiKreditModal);

                                                            $totalModal = $saldoAwal;
                                                            $totalMutasiDebetLaba = $mutasiDebetModal - $mutasiKreditModal;
                                                        }
                                                        $totalSaldoAwalLaba =  $totalModal - $totalPendapatan;
                                                        $totalSaldoAkhirLaba =  $totalSaldoAwalLaba + $totalMutasiKreditPendapatan - $totalMutasiDebetLaba;
                                                        if ($item_dua->nama_induk == 'MODAL') {
                                                            $total = $totalSaldoAwalKreditDua;
                                                            $totalAkhir = $totalSaldoAkhirKreditDua;
                                                            if ($loop->first) {
                                                                $totalSaldoAwalKreditDua = $total + $totalSaldoAwalLaba;
                                                                $totalMutasiDebetDua = $totalMutasiDebetDua + $totalMutasiDebetLaba;
                                                                $totalMutasiKreditDua = $totalMutasiKreditDua + $totalMutasiKreditPendapatan;
                                                                $totalSaldoAkhirKreditDua = ($totalSaldoAwalKreditDua + $totalMutasiKreditDua) - $totalMutasiDebetDua;
                                                            }

                                                        } else {
                                                            $totalSaldoAwalKreditDua =  $totalSaldoAwalLaba;
                                                            $totalSaldoAkhirKreditDua =  $totalSaldoAkhirLaba;
                                                            $totalMutasiDebetDua = $totalMutasiDebetLaba;
                                                            $totalMutasiKreditDua = $totalMutasiKreditPendapatan;
                                                        }

                                                    }
                                                }

                                            @endphp
                                        @endforeach
                                        <tr class="bg-primary">
                                                <td> {{ $tes->kode_induk }}</td>
                                                <td> {{ strtoupper($tes->nama) }}</td>
                                                @if ($item_ledger->jenis == 'debit')
                                                    <td class="uang">{{ number_format($totalSaldoAwalDebetDua / 100, 2, ',', '.') }}</td>
                                                @else
                                                    <td class="uang">{{ number_format($totalSaldoAwalKreditDua / 100, 2, ',', '.') }}</td>
                                                @endif
                                                <td class="uang">{{ number_format($totalMutasiDebetDua / 100, 2, ',', '.') }}</td>
                                                <td class="uang">{{ number_format($totalMutasiKreditDua / 100, 2, ',', '.') }}</td>
                                                @if ($item_ledger->jenis == 'debit')
                                                    <td class="uang">{{ number_format($totalSaldoAkhirDebetDua / 100, 2, ',', '.') }}</td>
                                                @else
                                                    <td class="uang">{{ number_format($totalSaldoAkhirKreditDua / 100, 2, ',', '.') }}</td>
                                                @endif
                                        </tr>
                                        @php
                                        $ledger = \App\Models\KodeAkun::select('kode_akun.*',
                                                        'kode_induk.id as induk_id',
                                                        'kode_induk.kode_induk',
                                                        'kode_induk.nama as nama_induk','kode_induk.jenis',
                                                        'kode_ledger.id as ledger_id',
                                                        'kode_ledger.kode_ledger','kode_ledger.nama as nama_ledger')
                                                        ->join('kode_induk','kode_induk.id','kode_akun.id_induk')
                                                        ->join('kode_ledger','kode_ledger.id','kode_induk.id_ledger')
                                                        // ->where('kode_induk.id_ledger',$item_induk->id)
                                                        ->where('kode_akun.id_induk',$tes->id)
                                                        ->get()
                                        @endphp
                                        @foreach ($ledger as $item)
                                            @php
                                                $mutasiAwalDebet = 0;
                                                $mutasiAwalKredit = 0;

                                                $mutasiDebet = 0;
                                                $mutasiKredit = 0;
                                                $current_date = \Carbon\Carbon::now()->format('Y-m-d');

                                                // cek apakah ada jurnal awal di field kode
                                                $cekTransaksiAwalDiKode = \App\Models\Jurnal::where('created_at','<',$current_date)->where('kode_akun', $item->id)->count();

                                                if ($cekTransaksiAwalDiKode > 0) {
                                                    $sumMutasiAwalDebetDiKode = \DB::table('jurnal')->where('kode_akun', $item->id)->where('created_at','<',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                    $sumMutasiAwalKreditDiKode = \DB::table('jurnal')->where('kode_akun', $item->id)->where('created_at','<',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                    if ($item->jenis == 'debit') {
                                                        $mutasiAwalDebet += $sumMutasiAwalDebetDiKode;
                                                        $mutasiAwalKredit += $sumMutasiAwalKreditDiKode;
                                                    }
                                                    else{
                                                        $mutasiAwalDebet += $sumMutasiAwalDebetDiKode;
                                                        $mutasiAwalKredit += $sumMutasiAwalKreditDiKode;
                                                    }
                                                }

                                                // cek transaksi di field kode
                                                $cekTransaksiDiKode = \App\Models\Jurnal::where('created_at','>=',$current_date)->where('kode_akun', $item->id)->count();

                                                if ($cekTransaksiDiKode > 0) {
                                                    $sumMutasiDebetDiKode = \DB::table('jurnal')->where('kode_akun', $item->id)->where('created_at','>=',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                    $sumMutasiKreditDiKode = \DB::table('jurnal')->where('kode_akun', $item->id)->where('created_at','>=',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                    $mutasiDebet += $sumMutasiDebetDiKode;
                                                    $mutasiKredit += $sumMutasiKreditDiKode;

                                                }
                                                $saldoAwal = $mutasiAwalDebet - $mutasiAwalKredit;

                                                // untuk pendapatan berbeda perhitungan
                                                if ($item->nama_ledger == 'P E N D A P A T A N') {
                                                    $saldoAkhir = ($saldoAwal + $mutasiKredit) - $mutasiDebet;
                                                } else if ($item->nama_ledger == 'PASSIVA') {
                                                    $saldoAkhir = $saldoAwal + $mutasiKredit -  $mutasiDebet;
                                                }else{
                                                    $saldoAkhir = ($mutasiAwalDebet + $mutasiDebet) - ($mutasiAwalKredit + $mutasiKredit);
                                                }


                                                $totalMutasiDebet += $mutasiDebet;
                                                $totalMutasiKredit += $mutasiKredit;

                                                if ($item->jenis == 'debit') {
                                                    $totalSaldoAwalDebet += $saldoAwal;
                                                    $totalSaldoAkhirDebet += $saldoAkhir;
                                                }
                                                else{
                                                    $totalSaldoAwalKredit += $saldoAwal;
                                                    $totalSaldoAkhirKredit += $saldoAkhir;
                                                }
                                                if (count($kode_pendapatan) > 0) {
                                                    // perhitungan laba/rugi
                                                    if ($item->nama_akun == 'LABA / RUGI TAHUN BERJALAN') {
                                                        // Ngitung pendapatan;
                                                        $mutasiAwalDebetPendapatan = 0;
                                                        $mutasiAwalKreditPendapatan = 0;

                                                        $mutasiDebetPendapatan = 0;
                                                        $mutasiKreditPendapatan = 0;
                                                        foreach ($kode_pendapatan as $itemPendapatan) {
                                                            $cekTransaksiAwalDiKode = \App\Models\Jurnal::where('created_at','<',$current_date)->where('kode_akun', $itemPendapatan->id)->count();
                                                            if ($cekTransaksiAwalDiKode > 0) {
                                                                $sumMutasiAwalDebetDiKode = \DB::table('jurnal')->where('kode_akun', $itemPendapatan->id)->where('created_at','<',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                                $sumMutasiAwalKreditDiKode = \DB::table('jurnal')->where('kode_akun', $itemPendapatan->id)->where('created_at','<',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                                if ($itemPendapatan->jenis == 'debit') {
                                                                    $mutasiAwalDebetPendapatan += $sumMutasiAwalDebetDiKode;
                                                                    $mutasiAwalKreditPendapatan += $sumMutasiAwalKreditDiKode;
                                                                }
                                                                else{
                                                                    $mutasiAwalDebetPendapatan += $sumMutasiAwalDebetDiKode;
                                                                    $mutasiAwalKreditPendapatan += $sumMutasiAwalKreditDiKode;
                                                                }
                                                            }

                                                            $cekTransaksiDiKode = \App\Models\Jurnal::where('created_at','>=',$current_date)->where('kode_akun', $itemPendapatan->id)->count();

                                                            if ($cekTransaksiDiKode > 0) {
                                                                $sumMutasiDebetDiKode = \DB::table('jurnal')->where('kode_akun', $itemPendapatan->id)->where('created_at','>=',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                                $sumMutasiKreditDiKode = \DB::table('jurnal')->where('kode_akun', $itemPendapatan->id)->where('created_at','>=',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                                $mutasiDebetPendapatan += $sumMutasiDebetDiKode;
                                                                $mutasiKreditPendapatan += $sumMutasiKreditDiKode;

                                                            }
                                                            $saldoAwal = $mutasiAwalDebetPendapatan - $mutasiAwalKreditPendapatan;


                                                            $saldoAkhir = ($mutasiAwalDebetPendapatan + $mutasiDebetPendapatan) - ($mutasiAwalKreditPendapatan + $mutasiKreditPendapatan);

                                                            $totalPendapatan = $saldoAwal;
                                                            $mutasiKredit = $mutasiKreditPendapatan - $mutasiDebetPendapatan ;
                                                        }
                                                        $mutasiAwalDebetModal = 0;
                                                        $mutasiAwalKreditModal = 0;

                                                        $mutasiDebetModal = 0;
                                                        $mutasiKreditModal = 0;
                                                        foreach ($kode_modal as $itemModal) {
                                                            $cekTransaksiAwalDiKode = \App\Models\Jurnal::where('created_at','<',$current_date)->where('kode_akun', $itemModal->id)->count();
                                                            if ($cekTransaksiAwalDiKode > 0) {
                                                                $sumMutasiAwalDebetDiKode = \DB::table('jurnal')->where('kode_akun', $itemModal->id)->where('created_at','<',$current_date)->where('tipe', 'debit')->sum(\DB::raw('CAST(jurnal.nominal AS SIGNED)'));

                                                                $sumMutasiAwalKreditDiKode = \DB::table('jurnal')->where('kode_akun', $itemModal->id)->where('created_at','<',$current_date)->where('tipe', 'kredit')->sum(\DB::raw('CAST(jurnal.nominal AS SIGNED)'));

                                                                if ($itemModal->jenis == 'debit') {
                                                                    $mutasiAwalDebetModal += $sumMutasiAwalDebetDiKode;
                                                                    $mutasiAwalKreditModal += $sumMutasiAwalKreditDiKode;
                                                                }
                                                                else{
                                                                    $mutasiAwalDebetModal += $sumMutasiAwalDebetDiKode;
                                                                    $mutasiAwalKreditModal += $sumMutasiAwalKreditDiKode;
                                                                }
                                                            }

                                                            $cekTransaksiDiKode = \App\Models\Jurnal::where('created_at','>=',$current_date)->where('kode_akun', $itemModal->id)->count();

                                                            if ($cekTransaksiDiKode > 0) {
                                                                $sumMutasiDebetDiKode = \DB::table('jurnal')->where('kode_akun', $itemModal->id)->where('created_at','>=',$current_date)->where('tipe', 'debit')->sum(\DB::raw('CAST(jurnal.nominal AS SIGNED)'));

                                                                $sumMutasiKreditDiKode = \DB::table('jurnal')->where('kode_akun', $itemModal->id)->where('created_at','>=',$current_date)->where('tipe', 'kredit')->sum(\DB::raw('CAST(jurnal.nominal AS SIGNED)'));

                                                                $mutasiDebetModal += $sumMutasiDebetDiKode;
                                                                $mutasiKreditModal += $sumMutasiKreditDiKode;

                                                            }
                                                            $saldoAwal = $mutasiAwalDebetModal - $mutasiAwalKreditModal;

                                                            $saldoAkhir = ($mutasiAwalDebetModal + $mutasiDebetModal) - ($mutasiAwalKreditModal + $mutasiKreditModal);

                                                            $totalModal = $saldoAwal;
                                                            $mutasiDebet = $mutasiDebetModal - $mutasiKreditModal;
                                                        }

                                                        $saldoAwal =  $totalModal - $totalPendapatan;
                                                        $saldoAkhir =  $saldoAwal + $mutasiKredit - $mutasiDebet ;
                                                    }
                                                }
                                                @endphp

                                            <tr>
                                                <td>{{ $item->kode_akun }}</td>
                                                <td>{{ strtoupper($item->nama_akun) }}</td>
                                                @if ($item->jenis == 'debit')
                                                    <td class="uang">{{ number_format($saldoAwal / 100, 2, ',', '.') }}</td>
                                                @else
                                                    <td class="uang">{{ number_format($saldoAwal / 100, 2, ',', '.') }}</td>
                                                @endif
                                                <td class="uang">{{ number_format($mutasiDebet / 100, 2, ',', '.') }}</td>
                                                <td class="uang">{{ number_format($mutasiKredit / 100, 2, ',', '.') }}</td>
                                                @if ($item->jenis == 'debit')
                                                    <td class="uang">{{ number_format($saldoAkhir / 100, 2, ',', '.') }}</td>
                                                @else
                                                    <td class="uang"> {{ number_format($saldoAkhir / 100, 2, ',', '.') }}</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    @endforeach
                            @endforeach
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
