<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- bootstrap css-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- fontawesome  -->
    <link rel="stylesheet" href="assets/font-awesome-4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Tinos:ital,wght@0,400;0,700;1,400&display=swap" rel="stylesheet">
    <style>
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #FAFAFA;
            font-family: 'Tinos', serif;
            font: 12pt;
        }
        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }
        p, table, ol{
            font-size: 9pt;
        }
        .table > :not(caption) > * > *{
             padding: 5px;
        }
        .table-bondered th, td.uang{
            text-align: right !important;
            /* border: 1px solid #9896966e !important; */
        }
        .table-bondered th, td, th{
            border: 1px solid #dddada6e !important;
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
        @page {
            margin: 0;
            size: landscape;
        }
        @media print {
            * {
                -webkit-print-color-adjust: exact !important;   /* Chrome, Safari, Edge */
                color-adjust: exact !important;                 /*Firefox*/     /*Firefox*/
            }
            /* html, body {
                width: 210mm;
                height: 297mm;
            } */
            .no-print, .no-print *
            {
                display: none !important;
            }
            .table > :not(caption) > * > *{
                padding: 5px;
            }
            .table-bondered th, td.uang{
                text-align: right !important;
                /* border: 1px solid #9896966e !important; */
            }
            .table-bondered th, td, th{
                border: 1px solid #dddada6e !important;
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
        /* ... the rest of the rules ... */
        }
    </style>
</head>
<body>
    <div class="p-4 my-5">
        <div class="row">
            <div class="col-md-12 mx-auto">
                <div class="card" style="border: none">
                    <div class="card-header">
                        <div class="d-flex justify-content-between">
                            <h4 class="card-title pt-2 font-weight-bold" style="font-weight: bold">Neraca</h4>
                            <div class="mx-3">
                                <button onclick="history.back()" class="btn btn-primary btn-icon-text no-print"><i class="ti-angle-left btn-icon-prepend"></i> Kembali</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
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

                    </div>
                </div>
            </div>

        </div>
    </div>

</body>
<script>
     print();
</script>
</html>
