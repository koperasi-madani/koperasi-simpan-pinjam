<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <title>Forecast Dashboard</title>
    <meta http-equiv="x-ua-compatible" content="ie=edge" />
    <meta name="description" content="" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta property="og:title" content="" />
    <meta property="og:type" content="" />
    <meta property="og:url" content="" />
    <meta property="og:image" content="" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <style>
        .table-info{
            background-color: #91a7d41c !important;
        }
        .card.header{
            border: 1px solid #000;
            border-radius: 0;
        }
        .card.header .card-header{
            background-color: #91A7D4;
            border-bottom: 1px solid #000;
        }
        .card#tanda_tangan{
            border: 1px solid #000;
            border-radius: 0;
        }
        .card#tanda_tangan_dua{
            border-right-color: #000;
            border-left-color: #000;
            border-bottom-color: #000;
            border-radius: 0;
        }
        hr{
            border-top: 2px dotted #000;
            background-color: transparent;
            opacity: 2;
            margin: 0;
        }

        @media print {
            * {
                -webkit-print-color-adjust: exact !important;   /* Chrome, Safari, Edge */
                color-adjust: exact !important;                 /*Firefox*/     /*Firefox*/
            }
            html, body {
                width: 210mm;
                height: 297mm;
            }
            .table-info{
                background-color: #91a7d41c !important;
            }
            .card.header{
                border: 1px solid #000;
                border-radius: 0;
            }
            .card.header .card-header{
                background-color: #91A7D4;
                border-bottom: 1px solid #000;
            }
            .card#tanda_tangan{
                border: 1px solid #000;
                border-radius: 0;
            }
            .card#tanda_tangan_dua{
                border-right-color: #000 !important;
                border-left-color: #000 ;
                border-bottom-color: #000;
                border-radius: 0;
            }
            hr{
                border-top: 2px dotted #000;
                background-color: transparent;
                opacity: 2;
                margin: 0;
            }
        }
    </style>
  </head>
  <body>
    <div class="container">
        <div class="row d-flex justify-content-center">
            <div class="col-md-8">
                <div class="card mt-5 text-uppercase header">
                    <div class="card-header text-center">
                        <h6>BUKU INI ADALAH MILIK KOPERAI MADANI <br>
                            APABILA DITEMUKAN HARAP DIKEMBALIKAN KE KANTOR KOPERAI MADANI</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="w-100" >
                                <div class="table-responsive">
                                    <table class="table table-borderless table-responsive-sm">
                                        <tbody>
                                            <tr>
                                                <td width="20%">NAMA</td>
                                                <td width="1%">:</td>
                                                <td >RIFJAN JUNDILA</td>
                                            </tr>
                                            <tr>
                                                <td width="20%">ALAMAT</td>
                                                <td width="1%">:</td>
                                                <td >dusun KRAJAN RT.02/03 LEDOKTEMPURO</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="w-50" >
                                <div class="table-responsive">
                                    <table class="table table-borderless table-responsive-sm">
                                        <tbody>
                                            <tr>
                                                <td width="100%">NOMOR SERI</td>
                                                <td width="1%">:</td>
                                                <td >21515124</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex mt-3">
                            <div class="w-50">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-responsive-sm">
                                        <tbody>
                                            <tr>
                                                <td width="40%">NOMOR REKENING <br>
                                                    <p class="fw-bold">0012345</p>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="w-100 d-flex justify-content-end">
                                <div class="table-responsive">
                                    <table class="table table-borderless table-responsive-sm">
                                        <tbody>
                                            <tr>
                                                <td width="20%">Tanggal</td>
                                                <td width="1%">:</td>
                                                <td width="">07 Februari 2023</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex mt-5">
                            <div class="w-50">
                                <p class="text-capitalize">Tanda tangan troline</p>
                                <div class="card" id="tanda_tangan">
                                    <div class="card-body p-5" style="border-bottom-color:black">

                                    </div>
                                </div>
                                <div class="card" id="tanda_tangan">
                                    <div class="card-body p-5" >

                                    </div>
                                </div>

                            </div>
                            <div class="w-50">
                                <div class="d-flex flex-column text-center mt-4">
                                    <div class="fw-bold">
                                        <img src="{{ asset('backend/assets/imgs/brands/brand-1.jpg') }}" alt="">
                                    </div>
                                    <div class="">
                                        <div class="table-responsive">
                                            <table class="table table-borderless table-responsive-sm">
                                                <tbody>
                                                    <tr>
                                                        <td width="100%"><h6>KOPERASI MADANI <br> LUMAJANG</h6></td>
                                                    </tr>
                                                    <tr>
                                                        <td></td>
                                                    </tr>
                                                    <tr>
                                                        <td>
                                                            Manager
                                                            <hr>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card text-uppercase header">
                    <div class="card-header text-center">
                        <h6>PERIKSA  SALDO TABUNGAN ANDA SEBELUM MENINGGALKAN KOPERASI</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-borderles">
                            <thead>
                                <tr>
                                    <th>NO.</th>
                                    <th class="table-info">TANGGAL</th>
                                    <th class="">SANDI</th>
                                    <th class="table-info">MUTASI</th>
                                    <th class="">SALDO</th>
                                    <th class="table-info">VALIDASI</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td class="table-info">2/7/2023</td>
                                    <td class="">10000</td>
                                    <td class="table-info"> 100.000.00</td>
                                    <td class=""> 100.000.00</td>
                                    <td class="table-info">TL00101</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-center" style="border-top-color: #000; background-color: #91A7D4;">
                        <h6>PERHATIAN ! <br>
                        PENABUNG TIDAK DIBENARKAN MENYIMPAN / MENITIPKAN <br>
                        BUKU TABUNGANN</h6>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
       window.print();
    </script>
    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
  </body>
</html>
