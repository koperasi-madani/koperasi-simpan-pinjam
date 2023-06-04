<x-app-layout>
    @push('css')
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
        <style>
            .btn-info{
                background-color: #264653;
                border: none;
            }
            .page-item.active .page-link{
                background-color: #219ebc !important;
                border-color: #8ecae6;
            }
        </style>
        <style>
            .select2-container--default .select2-selection--single {
                border-radius: 0.35rem !important;
                border: 1px solid #d1d3e2;
                height: calc(1.95rem + 5px);
                background: #fff;
            }

            .select2-container--default .select2-selection--single:hover,
            .select2-container--default .select2-selection--single:focus,
            .select2-container--default .select2-selection--single.active {
                box-shadow: none;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 32px;

            }

            .select2-container--default .select2-selection--multiple {
                border-color: #eaeaea;
                border-radius: 0;
            }

            .select2-dropdown {
                border-radius: 0;
            }

            .select2-container--default .select2-results__option--highlighted[aria-selected] {
                /* background-color: #3838eb; */
            }

            .select2-container--default.select2-container--focus .select2-selection--multiple {
                border-color: #eaeaea;
                background: #fff;

            }
        </style>
    @endpush
    @push('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $('#id_akun').select2({
            placeholder: "Pilih Akun"
        });
    </script>
    <script>
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
            function updateClock() {
                var currentTime = new Date();
                var hours = currentTime.getHours();
                var minutes = currentTime.getMinutes();
                var seconds = currentTime.getSeconds();

                // mengatur format menjadi "HH:MM:SSS"
                hours = (hours < 10 ? "0" : "") + hours;
                minutes = (minutes < 10 ? "0" : "") + minutes;
                seconds = (seconds < 10 ? "0" : "") + seconds;

                // menampilkan jam dalam elemen dengan id
                $('#waktu').text(`${hours} : ${minutes} : ${seconds }`)
            }
            setInterval(updateClock, 1000);
            var pembayaran = $('#pembayaran').val();
            var terbilang_pembayaran = convertToTerbilang(pembayaran);
            $('#terbilang_pembayaran').text(`Terbilang : ${terbilang_pembayaran} Rupiah`)

            $('#nominal').on('keyup',function() {
                var nominal = $(this).val();

                var terbilang_nominal = convertToTerbilang(hapus_uang(nominal));
                $('#terbilang_nominal').text(`Terbilang : ${terbilang_nominal} Rupiah`)

            })

            function hapus_uang(params) {
                const angka = params;
                const valueWithoutCurrency = angka.replace(/\./g, "").toString();
                return parseInt(valueWithoutCurrency);
            }
            var nominal = document.getElementById("nominal");
            nominal.value = formatRupiah(nominal.value);
            nominal.addEventListener("keyup", function(e) {
                nominal.value = formatRupiah(this.value);
            });

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
            /* Fungsi formatRupiah */
            function formatRupiah(angka, prefix) {
                var number_string = angka.replace(/[^,\d]/g, "").toString(),
                    split = number_string.split(","),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                if (ribuan) {
                    separator = sisa ? "." : "";
                    rupiah += separator + ribuan.join(".");
                }

                rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
                return prefix == undefined ? rupiah : rupiah ? rupiah : "";
            }
        })
    </script>
    @endpush
    @section('content')
    <section class="content-main mb-5">
        <div class="content-header">
            <div>
                <h2 class="content-title">{{ ucwords(str_replace('-',' ',Request::segment(4))) }}</h2>
                <p>Pembayaran kas dilakukan oleh head teller ketika di pagi hari untuk semua teller</p>
            </div>
        </div>
        <div class="row">
            @include('components.notification')
        </div>
        @if ($pembayaran != null)
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-body mb-4">
                        <article class="icontext">
                            <span class="icon icon-sm rounded-circle bg-primary-light"><i class="text-primary material-icons md-monetization_on"></i></span>
                            <div class="text">
                                <h6 class="mb-1 card-title">Total Pembayaran</h6>
                                <input type="number" name="pembayaran" id="pembayaran" value="{{ $pembayaran->pembayaran }}" hidden>
                                <span>Rp. {{ number_format($pembayaran->pembayaran,2, ",", ".") }}</span>
                            </div>
                        </article>
                        <hr>
                        <small id="terbilang_pembayaran">Terbilang : </small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card card-body mb-4">
                        <article class="icontext">
                            <span class="icon icon-sm rounded-circle bg-primary-light"><i class="text-primary material-icons md-timer"></i></span>
                            <div class="text">
                                <h6 class="mb-1 card-title">Waktu</h6>
                                <span id="waktu"></span>

                            </div>
                        </article>
                    </div>
                </div>
            </div>
            @if ($penerimaan > 0)
                <div class="row">
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#info-fill"/></svg>
                        <div>
                            Data sudah diinputkan.
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-md-12">
                        <div class="card mb-4">
                            <header class="card-header">
                                <div class="d-flex justify-content-between">
                                    <h4>Tambah Data Pembayaran</h4>

                                </div>
                            </header>
                            <div class="card-body">
                                    <form action="{{ route('penerimaan.kas-teller.post') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <label for="product_name" class="form-label">Akun Teller</label>
                                                <input type="text" name="teller" id="" class="form-control" readonly value="{{ ucwords(auth()->user()->name) }}">
                                                <input type="text" name="id" id="" class="form-control" readonly hidden value="{{ auth()->user()->id }}">
                                                <input type="text" name="id_saldo" id="" class="form-control" readonly hidden value="{{ $pembayaran->id }}">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-4">
                                                <label for="product_name" class="form-label">Nominal</label>
                                                <input placeholder="Nominal" value="0" id="nominal" type="text" value="{{ old('nominal') }}" class="form-control @error('nominal') is-invalid @enderror" name="nominal" />
                                                @error('nominal')
                                                    <div class="invalid-feedback">
                                                        {{$message}}.
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                        <hr>
                                        <span id="terbilang_nominal">Terbilang : </span>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="d-flex justify-content-end mb-5">
                    <button type="reset" class="btn btn-outline-danger">Batal</button>
                    <button type="submit" class="btn btn-primary mx-2">Simpan</button>
                    </form>

                </div>
            @endif
        @else
            <div class="row">
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#info-fill"/></svg>
                    <div>
                        Data masih belum tersedia.
                    </div>
                </div>
            </div>
        @endif
        <!-- card end// -->
    </section>
    @endsection
</x-app-layout>
