<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Document</title>
    <style>
        .btn{
            padding: 10px;
            background-color: #219ebc;
            color: #fff;
            border-radius: 10px;
            cursor: pointer;
        }
         /* Styles khusus untuk tampilan cetak */
        @media print {
            /* Ukuran kertas dan orientasi */
            @page {
                size: 80mm 1200mm; /* Ukuran kertas disesuaikan dengan kebutuhan Anda */
                margin: 0;
            }

            /* Ganti font atau atur ukuran font sesuai kebutuhan */
            body {
                font-family: Arial, sans-serif;
                margin: 0;
                padding: 0;
            }
            .header p {
                font-size: 11px;
            }
            /* Tampilan struk */
            .struk {
                width: 80mm; /* Sesuaikan dengan ukuran kertas */
                padding: 4px;
                padding-bottom: 20px;
                margin-bottom: 20px; /* Menambahkan margin bawah */
            }
            .struk

            .struk h2, .struk h3, .struk h4 {
                margin-top: 2px;
                margin-bottom: 2px;
                text-align: center;
            }

            .struk p {
                margin: 3px 0;
            }
            .no-print, .no-print *
            {
                display: none !important;
            }
        }

    </style>
</head>
<body>
    <center style="padding: 20px">
        <a href="{{ route('after-print') }}" class="btn btn-primary btn-icon-text no-print"><i class="ti-angle-left btn-icon-prepend"></i> Kembali</a>
    </center>
    <div class="struk">
        <h3 style="text-align: center">KOPERASI MADANI</h3>
        <p style="text-align: center; font-size: 10px;">Desa Ledoktempuro, Kec. Randuagung, Kab. Lumajang.</p>
        <div class="header" style="padding-top: 5px; padding-bottom: 10px">
            <div class="table-responsive">
                <table class="table table-bordered table-responsive-sm">
                    <tbody>
                        <tr>
                            <td width="30%" style="font-size: 10px;margin:0;padding:0;">NO TRANSAKSI</td>
                            <td width="1%">:</td>
                            <td style="font-size: 10px;margin:0;padding:0;" >{{ $data->kode }}</td>
                        </tr>
                        <tr>
                            <td width="30%" style="font-size: 10px;margin:0;padding:0;">ACCOUNT TYPE</td>
                            <td width="1%">:</td>
                            <td style="font-size: 10px;margin:0;padding:0;" >Tabungan</td>
                        </tr>
                        <tr>
                            <td width="30%" style="font-size: 10px;margin:0;padding:0;">PETUGAS</td>
                            <td width="1%">:</td>
                            <td style="font-size: 10px;margin:0;padding:0;" > {{ $data->user->name }}</td>
                        </tr>
                        <tr>
                            <td width="30%" style="font-size: 10px;margin:0;padding:0;">NO VALIDASI</td>
                            <td width="1%">:</td>
                            <td style="font-size: 10px;margin:0;padding:0;" >{{ $data->user->kode_user }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <h4 style="text-align: center">SETOR TUNAI</h4>
        <div style="padding-bottom: 5px;padding-top: 4px">
            <div class="table-responsive">
                <table class="table table-bordered table-responsive-sm">
                    <tbody>
                        <tr>
                            <td width="35%" style="font-size: 13px">NAMA</td>
                            <td width="1%">:</td>
                            <td style="font-size: 13px">{{ $data->nasabah->nama }}</td>
                        </tr>
                        <tr>
                            <td width="35%" style="font-size: 13px">NO REKENING</td>
                            <td width="1%">:</td>
                            <td style="font-size: 13px">{{ $tabungan->no_rekening }}</td>
                        </tr>
                        <tr>
                            <td width="35%" style="font-size: 13px">NILAI SETOR</td>
                            <td width="1%">:</td>
                            <td style="font-size: 13px"> Rp. {{ number_format($data->nominal,2, ",", ".") }}</td>
                        </tr>
                        <tr>
                            <td width="35%" style="font-size: 13px">SALDO</td>
                            <td width="1%">:</td>
                            <td style="font-size: 13px">Rp. {{ number_format($tabungan->saldo,2, ",", ".") }}</td>
                        </tr>
                        <tr>
                            <td width="35%" style="font-size: 13px">TANGGAL</td>
                            <td width="1%">:</td>
                            <td style="font-size: 13px">{{ \Carbon\Carbon::parse($data->tgl)->translatedFormat('d F Y') }}</td>
                        </tr>
                    </tbody>
                </table>
                <div style="padding-bottom: 20px; margin-bottom: 20px; margin-top: 10px">
                    <p style="text-align: center; font-size: 12px">HARAP BUKTI TRANSAKSI INI DISIMPAN <br> TERIMA KASIH</p>
                    <p style="text-align: center" style="padding: 0px">....</p>
                </div>
            </div>
        </div>
    </div>
</body>
<script>
    window.onload = function() {
        window.print();

        // Mendeteksi setelah cetak
        window.onafterprint = function() {
            // Kembali ke halaman sebelumnya
            // window.history.back();
             // Redirect to the specific Laravel route after printing
            //  window.location.href = "{{ route('after-print') }}";
        }
    }
</script>
</html>
