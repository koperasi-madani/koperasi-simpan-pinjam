<x-app-layout>
    @push('css')
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
        <style>
            .page-item.active .page-link{
                background-color: #219ebc !important;
                border-color: #8ecae6;
            }
        </style>
    @endpush
    @push('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#example').DataTable();
        })
        $(function() {
            $('input[name="tgl"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: false,
                timePicker: false,
                startDate: moment().startOf('hour'),
                locale: {
                        format: 'Y-MM-DD'
                    }
            });

        });

    </script>
    <script>
        $(document).ready(function() {
            var penerimaan = $('#penerimaan').val();
            var pembayaran = $('#pembayaran').val();
            var terbilang_pembayaran = convertToTerbilang(pembayaran);
            $('#terbilang_pembayaran').text(`Terbilang : ${terbilang_pembayaran} Rupiah`)
            var terbilang_penerimaan = convertToTerbilang(penerimaan);
            $('#terbilang_penerimaan').text(`Terbilang : ${terbilang_penerimaan} Rupiah`)
        })
        function convertToTerbilang(number) {
            var bilangan = [
                '',
                'Satu',
                'Dua',
                'Tiga',
                'Empat',
                'Lima',
                'Enam',
                'Tujuh',
                'Delapan',
                'Sembilan',
                'Sepuluh',
                'Sebelas'
            ];

            var terbilang = '';

            if (number < 12) {
                terbilang = bilangan[number];
            } else if (number < 20) {
                terbilang = convertToTerbilang(number - 10) + ' Belas';
            } else if (number < 100) {
                terbilang = convertToTerbilang(Math.floor(number / 10)) + ' Puluh ' + convertToTerbilang(number % 10);
            } else if (number < 200) {
                terbilang = ' Seratus ' + convertToTerbilang(number - 100);
            } else if (number < 1000) {
                terbilang = convertToTerbilang(Math.floor(number / 100)) + ' Ratus ' + convertToTerbilang(number % 100);
            } else if (number < 2000) {
                terbilang = ' Seribu ' + convertToTerbilang(number - 1000);
            } else if (number < 1000000) {
                terbilang = convertToTerbilang(Math.floor(number / 1000)) + ' Ribu ' + convertToTerbilang(number % 1000);
            } else if (number < 1000000000) {
                terbilang = convertToTerbilang(Math.floor(number / 1000000)) + ' Juta ' + convertToTerbilang(number % 1000000);
            } else if (number < 1000000000000) {
                terbilang = convertToTerbilang(Math.floor(number / 1000000000)) + ' Miliar ' + convertToTerbilang(number % 1000000000);
            } else if (number < 1000000000000000) {
                terbilang = convertToTerbilang(Math.floor(number / 1000000000000)) + ' Triliun ' + convertToTerbilang(number % 1000000000000);
            }

            return terbilang;
        }
    </script>
    @endpush
    @section('content')
    <section class="content-main mb-5">
        <div class="content-header">
            <h2 class="content-title">{{ ucwords(str_replace('-',' ',Request::segment(3))) }}</h2>
            <div>
                <button onclick="history.back()" class="btn btn-light"><i class="text-muted material-icons md-arrow_back"></i>Kembali</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="card card-body mb-4">
                    <article class="icontext">
                        <span class="icon icon-sm rounded-circle bg-primary-light"><i class="text-primary material-icons md-monetization_on"></i></span>
                        <div class="text">
                            <h6 class="mb-1 card-title">Total Pembayaran</h6>
                            <input type="number" name="pembayaran" id="pembayaran" value="{{ $pembayaran }}" hidden>
                            <span>Rp. {{ number_format($pembayaran,2, ",", ".") }}</span>
                        </div>
                    </article>
                    <hr>
                    <small id="terbilang_pembayaran">Terbilang : </small>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-body mb-4">
                    <article class="icontext">
                        <span class="icon icon-sm rounded-circle bg-primary-light"><i class="text-primary material-icons md-monetization_on"></i></span>
                        <div class="text">
                            <h6 class="mb-1 card-title">Total Penerimaan</h6>
                            <input type="number" name="penerimaan" id="penerimaan" value="{{ $penerimaan }}" hidden>
                            <span>Rp. {{ number_format($penerimaan,2, ",", ".") }}</span>
                        </div>
                    </article>
                    <hr>
                    <small id="terbilang_penerimaan"> Terbilang : </small>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card">
                    <header class="card-header"><h4>List Data</h4></header>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="example">
                                <thead>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama Teller</th>
                                    <th>Nominal Pembayaran</th>
                                    <th>Nominal Penerimaan</th>
                                </thead>
                                <tbody>
                                    @forelse ($data_pembayaran as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->kode }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td><b>Rp. {{ number_format($item->pembayaran,2, ",", ".") }}</b></td>
                                            <td><b>Rp. {{ number_format($item->penerimaan,2, ",", ".") }}</b></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- card end// -->
    </section>
    @endsection
</x-app-layout>
