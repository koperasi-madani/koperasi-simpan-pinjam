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
                            <tr>
                                <th rowspan="2" style="vertical-align:middle">Kode Akun</th>
                                <th rowspan="2" style="vertical-align:middle">Nama Akun</th>
                                <th rowspan="2" style="text-align: center;" class="text-center">Saldo Awal</th>
                                <th colspan="2" style="text-align: center;">Mutasi</th>
                                <th rowspan="2" style="text-align: center;" class="text-center">Saldo Akhir</th>
                                <tr>

                                    <th style="text-align: center;">Debet</th>
                                    <th style="text-align: center;">Kredit</th>



                                </tr>
                            </tr>
                        </thead>
                        <tbody>
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
                                        $totalMutasiDebetTotal = 0;
                                        $totalMutasiKreditTotal = 0;
                                        $totalSaldoAkhirDebetTotal = 0;
                                        $totalSaldoAkhirKreditTotal = 0;
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
                                                    $sumMutasiAwalDebetDiKode = \DB::table('jurnal')->where('kode_akun', $item_ledger->id)->where('created_at','<',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                    $sumMutasiAwalKreditDiKode = \DB::table('jurnal')->where('kode_akun', $item_ledger->id)->where('created_at','<',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                    if ($item_ledger->jenis == 'debit') {
                                                        $mutasiAwalDebet += $sumMutasiAwalDebetDiKode;
                                                        $mutasiAwalKredit += $sumMutasiAwalKreditDiKode;
                                                    }
                                                    else{
                                                        $mutasiAwalDebet += $sumMutasiAwalDebetDiKode;
                                                        $mutasiAwalKredit += $sumMutasiAwalKreditDiKode;
                                                    }

                                                    // cek apakah transaksi sebelumnya juga terdapat di field lawan
                                                    $cekTransaksiAwalDiLawan = \App\Models\Jurnal::where('created_at','<',$current_date)->where('kode_lawan', $item_ledger->id)->count();
                                                    if ($cekTransaksiAwalDiLawan > 0) {
                                                        $sumMutasiAwalDebetDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_ledger->id)->where('created_at','<',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                        $sumMutasiAwalKreditDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_ledger->id)->where('created_at','<',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                        $mutasiAwalDebet += $sumMutasiAwalDebetDiLawan;
                                                        $mutasiAwalKredit += $sumMutasiAwalKreditDiLawan;
                                                    }
                                                }
                                                else{ // cek apakah ada jurnal awal di field lawan
                                                    $cekTransaksiAwalDiLawan = \App\Models\Jurnal::where('created_at','<',$current_date)->where('kode_lawan', $item_ledger->id)->count();
                                                    if ($cekTransaksiAwalDiLawan > 0) {
                                                        $sumMutasiAwalDebetDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_ledger->id)->where('created_at','<',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                        $sumMutasiAwalKreditDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_ledger->id)->where('created_at','<',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                        if ($item_ledger->jenis == 'debit') {
                                                            $mutasiAwalDebet += $sumMutasiAwalDebetDiLawan;

                                                            $mutasiAwalKredit += $sumMutasiAwalKreditDiLawan;
                                                        }
                                                        else{
                                                            $mutasiAwalDebet += $sumMutasiAwalDebetDiLawan;
                                                            $mutasiAwalKredit += $sumMutasiAwalKreditDiLawan;
                                                        }
                                                    }
                                                    else{ //tidak ada jurnal awal di field kode maupun lawan
                                                        // if ($item_ledger->jenis == 'debit') {
                                                        //     $mutasiAwalDebet += $item_ledger->saldo_awal;
                                                        // }
                                                        // else{
                                                        //     $mutasiAwalKredit += $item_ledger->saldo_awal;
                                                        // }
                                                    }
                                                }

                                                // cek transaksi di field kode
                                                $cekTransaksiDiKode = \App\Models\Jurnal::where('created_at','>=',$current_date)->where('kode_akun', $item_ledger->id)->count();

                                                if ($cekTransaksiDiKode > 0) {
                                                    $sumMutasiDebetDiKode = \DB::table('jurnal')->where('kode_akun', $item_ledger->id)->where('created_at','>=',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                    $sumMutasiKreditDiKode = \DB::table('jurnal')->where('kode_akun', $item_ledger->id)->where('created_at','>=',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                    $mutasiDebet += $sumMutasiDebetDiKode;
                                                    $mutasiKredit += $sumMutasiKreditDiKode;

                                                    // cek transaksi di field lawan
                                                    $cekTransaksiDiLawan = \App\Models\Jurnal::where('created_at','>=',$current_date)->where('kode_lawan', $item_ledger->id)->count();

                                                    if ($cekTransaksiDiLawan > 0) {
                                                        $sumMutasiDebetDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_ledger->id)->where('created_at','>=',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                        $sumMutasiKreditDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_ledger->id)->where('created_at','>=',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                        $mutasiDebet += $sumMutasiDebetDiLawan;
                                                        $mutasiKredit += $sumMutasiKreditDiLawan;
                                                    }
                                                }
                                                else{ // cek transaksi di field lawan
                                                    // cek transaksi di field lawan
                                                    $cekTransaksiDiLawan = \App\Models\Jurnal::where('created_at','>=',$current_date)->where('kode_lawan', $item_ledger->id)->count();
                                                    if ($cekTransaksiDiLawan > 0) {
                                                        $sumMutasiDebetDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_ledger->id)->where('created_at','>=',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                        $sumMutasiKreditDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_ledger->id)->where('created_at','>=',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                        $mutasiDebet += $sumMutasiDebetDiLawan;
                                                        $mutasiKredit += $sumMutasiKreditDiLawan;
                                                    }
                                                }

                                                $saldoAwal = $mutasiAwalDebet - $mutasiAwalKredit;

                                                $saldoAkhir = ($mutasiAwalDebet + $mutasiDebet) - ($mutasiAwalKredit + $mutasiKredit);

                                                $totalMutasiDebetTotal += $mutasiDebet;
                                                $totalMutasiKreditTotal += $mutasiKredit;

                                                if ($item_ledger->jenis == 'debit') {
                                                    $totalSaldoAwalDebetTotal += $saldoAwal;
                                                    $totalSaldoAkhirDebetTotal += $saldoAkhir;
                                                }
                                                else{
                                                    $totalSaldoAwalKreditTotal += $saldoAwal;
                                                    $totalSaldoAkhirKreditTotal += $saldoAkhir;
                                                }
                                            @endphp
                                        @endforeach
                                    @endforeach
                                    <tr class="bg-secondary text-white">
                                        <td>{{ $item_induk->kode_ledger }}</td>
                                        <td>{{ $item_induk->nama_ledger }}</td>
                                        @if ($item_ledger->jenis == 'debit')
                                            <td>{{ number_format($totalSaldoAwalDebetTotal, 2, ',', '.') }}</td>
                                        @else
                                            <td>{{ number_format($totalSaldoAwalKreditTotal * -1, 2, ',', '.') }}</td>
                                        @endif
                                        <td>{{ number_format($totalMutasiDebetTotal, 2, ',', '.') }}</td>
                                        <td>{{ number_format($totalMutasiKreditTotal, 2, ',', '.') }}</td>
                                        @if ($item_ledger->jenis == 'debit')
                                            <td>{{ number_format($totalSaldoAkhirDebetTotal, 2, ',', '.') }}</td>
                                        @else
                                            <td>{{ number_format($totalSaldoAkhirKreditTotal * -1, 2, ',', '.') }}</td>
                                        @endif
                                    </tr>

                                    @php
                                        $kode_induk_dua = \App\Models\KodeInduk::select('kode_induk.id','kode_induk.id_ledger','kode_induk.kode_induk','kode_induk.nama')->where('id_ledger',$item_induk->id_ledger)->groupBy('kode_induk.kode_induk')->orderBy('id','DESC')->get();
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

                                                    // cek apakah transaksi sebelumnya juga terdapat di field lawan
                                                    $cekTransaksiAwalDiLawan = \App\Models\Jurnal::where('created_at','<',$current_date)->where('kode_lawan', $item_dua->id)->count();
                                                    if ($cekTransaksiAwalDiLawan > 0) {
                                                        $sumMutasiAwalDebetDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_dua->id)->where('created_at','<',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                        $sumMutasiAwalKreditDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_dua->id)->where('created_at','<',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                        $mutasiAwalDebet += $sumMutasiAwalDebetDiLawan;
                                                        $mutasiAwalKredit += $sumMutasiAwalKreditDiLawan;
                                                    }
                                                }
                                                else{ // cek apakah ada jurnal awal di field lawan
                                                    $cekTransaksiAwalDiLawan = \App\Models\Jurnal::where('created_at','<',$current_date)->where('kode_lawan', $item_dua->id)->count();
                                                    if ($cekTransaksiAwalDiLawan > 0) {
                                                        $sumMutasiAwalDebetDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_dua->id)->where('created_at','<',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                        $sumMutasiAwalKreditDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_dua->id)->where('created_at','<',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                        if ($item_dua->jenis == 'debit') {
                                                            $mutasiAwalDebet += $sumMutasiAwalDebetDiLawan;

                                                            $mutasiAwalKredit += $sumMutasiAwalKreditDiLawan;
                                                        }
                                                        else{
                                                            $mutasiAwalDebet += $sumMutasiAwalDebetDiLawan;
                                                            $mutasiAwalKredit += $sumMutasiAwalKreditDiLawan;
                                                        }
                                                    }
                                                    else{ //tidak ada jurnal awal di field kode maupun lawan
                                                        // if ($item_dua->jenis == 'debit') {
                                                        //     $mutasiAwalDebet += $item_dua->saldo_awal;
                                                        // }
                                                        // else{
                                                        //     $mutasiAwalKredit += $item_dua->saldo_awal;
                                                        // }
                                                    }
                                                }

                                                // cek transaksi di field kode
                                                $cekTransaksiDiKode = \App\Models\Jurnal::where('created_at','>=',$current_date)->where('kode_akun', $item_dua->id)->count();

                                                if ($cekTransaksiDiKode > 0) {
                                                    $sumMutasiDebetDiKode = \DB::table('jurnal')->where('kode_akun', $item_dua->id)->where('created_at','>=',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                    $sumMutasiKreditDiKode = \DB::table('jurnal')->where('kode_akun', $item_dua->id)->where('created_at','>=',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                    $mutasiDebet += $sumMutasiDebetDiKode;
                                                    $mutasiKredit += $sumMutasiKreditDiKode;

                                                    // cek transaksi di field lawan
                                                    $cekTransaksiDiLawan = \App\Models\Jurnal::where('created_at','>=',$current_date)->where('kode_lawan', $item_dua->id)->count();

                                                    if ($cekTransaksiDiLawan > 0) {
                                                        $sumMutasiDebetDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_dua->id)->where('created_at','>=',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                        $sumMutasiKreditDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_dua->id)->where('created_at','>=',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                        $mutasiDebet += $sumMutasiDebetDiLawan;
                                                        $mutasiKredit += $sumMutasiKreditDiLawan;
                                                    }
                                                }
                                                else{ // cek transaksi di field lawan
                                                    // cek transaksi di field lawan
                                                    $cekTransaksiDiLawan = \App\Models\Jurnal::where('created_at','>=',$current_date)->where('kode_lawan', $item_dua->id)->count();
                                                    if ($cekTransaksiDiLawan > 0) {
                                                        $sumMutasiDebetDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_dua->id)->where('created_at','>=',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                        $sumMutasiKreditDiLawan = \DB::table('jurnal')->where('kode_lawan', $item_dua->id)->where('created_at','>=',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                        $mutasiDebet += $sumMutasiDebetDiLawan;
                                                        $mutasiKredit += $sumMutasiKreditDiLawan;
                                                    }
                                                }

                                                $saldoAwal = $mutasiAwalDebet - $mutasiAwalKredit;

                                                $saldoAkhir = ($mutasiAwalDebet + $mutasiDebet) - ($mutasiAwalKredit + $mutasiKredit);

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
                                            @endphp
                                        @endforeach
                                        <tr class="bg-primary text-white">
                                                <td> {{ $tes->kode_induk }}</td>
                                                <td> {{ $tes->nama }}</td>
                                                @if ($item_ledger->jenis)
                                                    <td>{{ number_format($totalSaldoAwalDebetDua, 2, ',', '.') }}</td>
                                                @else
                                                    <td>{{ number_format($totalSaldoAwalKreditDua * -1, 2, ',', '.') }}</td>
                                                @endif
                                                <td>{{ number_format($totalMutasiDebetDua, 2, ',', '.') }}</td>
                                                <td>{{ number_format($totalMutasiKreditDua, 2, ',', '.') }}</td>
                                                @if ($item_ledger->jenis)
                                                    <td>{{ number_format($totalSaldoAkhirDebetDua, 2, ',', '.') }}</td>
                                                @else
                                                    <td>{{ number_format($totalSaldoAkhirKreditDua * -1, 2, ',', '.') }}</td>
                                                @endif
                                        </tr>
                                        @php
                                        $ledger = \App\Models\KodeAkun::select('kode_akun.*',
                                                        'kode_induk.id as induk_id',
                                                        'kode_induk.nama as nama_induk','kode_induk.jenis','kode_ledger.id as ledger_id','kode_ledger.kode_ledger','kode_ledger.nama as nama_ledger')
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

                                                    // cek apakah transaksi sebelumnya juga terdapat di field lawan
                                                    $cekTransaksiAwalDiLawan = \App\Models\Jurnal::where('created_at','<',$current_date)->where('kode_lawan', $item->id)->count();
                                                    if ($cekTransaksiAwalDiLawan > 0) {
                                                        $sumMutasiAwalDebetDiLawan = \DB::table('jurnal')->where('kode_lawan', $item->id)->where('created_at','<',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                        $sumMutasiAwalKreditDiLawan = \DB::table('jurnal')->where('kode_lawan', $item->id)->where('created_at','<',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                        $mutasiAwalDebet += $sumMutasiAwalDebetDiLawan;
                                                        $mutasiAwalKredit += $sumMutasiAwalKreditDiLawan;
                                                    }
                                                }
                                                else{ // cek apakah ada jurnal awal di field lawan
                                                    $cekTransaksiAwalDiLawan = \App\Models\Jurnal::where('created_at','<',$current_date)->where('kode_lawan', $item->id)->count();
                                                    if ($cekTransaksiAwalDiLawan > 0) {
                                                        $sumMutasiAwalDebetDiLawan = \DB::table('jurnal')->where('kode_lawan', $item->id)->where('created_at','<',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                        $sumMutasiAwalKreditDiLawan = \DB::table('jurnal')->where('kode_lawan', $item->id)->where('created_at','<',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                        if ($item->jenis == 'debit') {
                                                            $mutasiAwalDebet += $sumMutasiAwalDebetDiLawan;

                                                            $mutasiAwalKredit += $sumMutasiAwalKreditDiLawan;
                                                        }
                                                        else{
                                                            $mutasiAwalDebet += $sumMutasiAwalDebetDiLawan;
                                                            $mutasiAwalKredit += $sumMutasiAwalKreditDiLawan;
                                                        }
                                                    }
                                                    else{ //tidak ada jurnal awal di field kode maupun lawan
                                                        // if ($item->jenis == 'debit') {
                                                        //     $mutasiAwalDebet += $item->saldo_awal;
                                                        // }
                                                        // else{
                                                        //     $mutasiAwalKredit += $item->saldo_awal;
                                                        // }
                                                    }
                                                }

                                                // cek transaksi di field kode
                                                $cekTransaksiDiKode = \App\Models\Jurnal::where('created_at','>=',$current_date)->where('kode_akun', $item->id)->count();

                                                if ($cekTransaksiDiKode > 0) {
                                                    $sumMutasiDebetDiKode = \DB::table('jurnal')->where('kode_akun', $item->id)->where('created_at','>=',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                    $sumMutasiKreditDiKode = \DB::table('jurnal')->where('kode_akun', $item->id)->where('created_at','>=',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                    $mutasiDebet += $sumMutasiDebetDiKode;
                                                    $mutasiKredit += $sumMutasiKreditDiKode;

                                                    // cek transaksi di field lawan
                                                    $cekTransaksiDiLawan = \App\Models\Jurnal::where('created_at','>=',$current_date)->where('kode_lawan', $item->id)->count();

                                                    if ($cekTransaksiDiLawan > 0) {
                                                        $sumMutasiDebetDiLawan = \DB::table('jurnal')->where('kode_lawan', $item->id)->where('created_at','>=',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                        $sumMutasiKreditDiLawan = \DB::table('jurnal')->where('kode_lawan', $item->id)->where('created_at','>=',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                        $mutasiDebet += $sumMutasiDebetDiLawan;
                                                        $mutasiKredit += $sumMutasiKreditDiLawan;
                                                    }
                                                }
                                                else{ // cek transaksi di field lawan
                                                    // cek transaksi di field lawan
                                                    $cekTransaksiDiLawan = \App\Models\Jurnal::where('created_at','>=',$current_date)->where('kode_lawan', $item->id)->count();
                                                    if ($cekTransaksiDiLawan > 0) {
                                                        $sumMutasiDebetDiLawan = \DB::table('jurnal')->where('kode_lawan', $item->id)->where('created_at','>=',$current_date)->where('tipe', 'kredit')->sum('jurnal.nominal');

                                                        $sumMutasiKreditDiLawan = \DB::table('jurnal')->where('kode_lawan', $item->id)->where('created_at','>=',$current_date)->where('tipe', 'debit')->sum('jurnal.nominal');

                                                        $mutasiDebet += $sumMutasiDebetDiLawan;
                                                        $mutasiKredit += $sumMutasiKreditDiLawan;
                                                    }
                                                }

                                                $saldoAwal = $mutasiAwalDebet - $mutasiAwalKredit;

                                                $saldoAkhir = ($mutasiAwalDebet + $mutasiDebet) - ($mutasiAwalKredit + $mutasiKredit);

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
                                            @endphp
                                            <tr>
                                                <td>{{ $item->kode_akun }}</td>
                                                <td>{{ $item->nama_akun }}</td>
                                                @if ($item->jenis == 'debit')
                                                    <td>{{ number_format($saldoAwal, 2, ',', '.') }}</td>
                                                @else
                                                    <td>{{ number_format($saldoAwal * -1, 2, ',', '.') }}</td>
                                                @endif
                                                <td>{{ number_format($mutasiDebet, 2, ',', '.') }}</td>
                                                <td>{{ number_format($mutasiKredit, 2, ',', '.') }}</td>
                                                @if ($item->jenis == 'debit')
                                                    <td>{{ number_format($saldoAkhir, 2, ',', '.') }}</td>
                                                @else
                                                    <td>{{ number_format($saldoAkhir * -1, 2, ',', '.') }}</td>
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
