<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        /* Styles khusus untuk tampilan cetak */
        @media print {
        /* Ukuran kertas dan orientasi */
            @page {
                size: 80mm 150mm; /* Ukuran kertas disesuaikan dengan kebutuhan Anda */
                margin: 0;
            }

            /* Ganti font atau atur ukuran font sesuai kebutuhan */
            body {
                font-family: Arial, sans-serif;
            }

            /* Tampilan struk */
            .struk {
                width: 80mm; /* Sesuaikan dengan ukuran kertas */
                padding: 10px;
                border: 1px solid #000;
            }

            .struk h2 {
                text-align: center;
            }

            .struk p {
                margin: 5px 0;
            }
        }

    </style>
</head>
<body>
    <div class="struk">
        <h2>Struk Setor Tunai</h2>
        <p>No Transaksi: {{ $transaction['kode'] }}</p>
        <p>No Validasi: {{ $transaction['validasi'] }}</p>
        <p>Nominal: Rp. {{ number_format($transaction['nominal'],2, ",", ".") }}</p>
        <p>Tanggal: {{ \Carbon\Carbon::parse($transaction['tgl'])->translatedFormat('d F Y') }}</p>
        <p>No Rekening: {{ $transaction['no_rekening'] }}</p>
    </div>
</body>
</html>
