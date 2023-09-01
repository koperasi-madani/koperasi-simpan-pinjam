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
        $(document).ready(function() {
            // menambahkan form otomatis
            $('#addBtn').on('click',function() {
                var formRow = `
                    <div class="row form-row my-3 item-row">
                        <div class="form-group col-md-4">
                            <label for="nominal">Nominal</label>
                            <select name="nominal[]" id="nominal" class="form-control nominal-input" required>
                                <option value="100">Rp 100</option>
                                <option value="200">Rp 200</option>
                                <option value="500">Rp 500</option>
                                <option value="1000">Rp 1.000</option>
                                <option value="2000">Rp 2.000</option>
                                <option value="5000">Rp 5.000</option>
                                <option value="10000">Rp 10.000</option>
                                <option value="20000">Rp 20.000</option>
                                <option value="50000">Rp 50.000</option>
                                <option value="75000">Rp 75.000</option>
                                <option value="100000">Rp 100.000</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="jumlah">Jumlah</label>
                            <input type="number" class="form-control jumlah-input" name="jumlah[]" required min="1" placeholder="Masukkan Jumlah">
                        </div>
                        <div class="form-group col-md-3">
                            <label for="harga">Total</label>
                            <input type="number" class="form-control total-input" disabled placeholder="Total Nominal" readonly name="total[]">
                        </div>
                        <div class="form-group col-md-2">
                            <button type="button" class="btn btn-danger remove-btn">Hapus</button>
                        </div>
                    </div>
                `
                $('#formContainer').append(formRow);
            })
            // menghapus form
            $(document).on('click','.remove-btn',function() {
                $(this).closest('.form-row').remove();
                kalkulasi();
            })

            $('#formContainer').on('input', '.nominal-input, .jumlah-input', function() {
                var row = $(this).closest('.item-row');
                var quantity = parseInt(row.find('.jumlah-input').val());
                var price = parseFloat(row.find('.nominal-input').val());
                var total = (quantity * price) || 0;
                row.find('.total-input').val(total);

                kalkulasi();
            });

            var pembayaran = $('#pembayaran').val();
            var terbilang_pembayaran = convertToTerbilang(pembayaran);
            $('#terbilang_pembayaran').text(`Terbilang : ${terbilang_pembayaran} Rupiah`)
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



        })
        function kalkulasi() {
            var grandTotal = 0;

            $('.total-input').each(function() {
                var total = parseFloat($(this).val());
                grandTotal += total;
            });
            $('#penerimaan_total').val(grandTotal);
            var terbilang_penerimaan = convertToTerbilang(grandTotal);
            $('#terbilang_penerimaan').text(`Terbilang : ${terbilang_penerimaan} Rupiah`)
            $('#grandtotal').text(`Total Penerimaan : ${new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(grandTotal)}`);
        }
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
    </script>
    <script>
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
        <div class="row">
            <div class="col-md-6">
                <div class="card card-body mb-4">
                    <article class="icontext">
                        <span class="icon icon-sm rounded-circle bg-primary-light"><i class="text-primary material-icons md-monetization_on"></i></span>
                        <div class="text">
                            <h6 class="mb-1 card-title">Saldo Teller</h6>
                            <input type="number" name="pembayaran" id="pembayaran" value="{{ isset($pembayaran) ? $pembayaran - $denominasi : 0 }}" hidden>
                            <span>Rp. {{ number_format(isset($pembayaran) ? $pembayaran - $denominasi : 0 ,2, ",", ".") }}</span>
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
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="header card-header d-flex justify-content-between">
                        <h4>DENOMINASI</h4>
                        <button type="button" class="btn btn-primary " id="addBtn">Tambah </button>
                    </div>
                    {{-- @if (count($denominasi) > 0) --}}
                        {{-- <div class="card-body">
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                <div>
                                    <strong>Perhatian!</strong> Data sudah tersedia.
                                </div>
                            </div>
                        </div> --}}
                    {{-- @else --}}
                        <div class="card-body">
                            <form action="{{ route('penerimaan.kas-teller.post') }}" method="POST">
                            @csrf
                                <div id="formContainer">
                                    <div class="row form-row my-3 item-row">
                                        <div class="form-group col-md-4">
                                            <label for="nominal">Nominal</label>
                                            <select name="nominal[]" id="nominal" class="form-control @error('nominal') is-invalid @enderror nominal-input" >
                                                <option value="100">Rp 100</option>
                                                <option value="200">Rp 200</option>
                                                <option value="500">Rp 500</option>
                                                <option value="1000">Rp 1.000</option>
                                                <option value="2000">Rp 2.000</option>
                                                <option value="5000">Rp 5.000</option>
                                                <option value="10000">Rp 10.000</option>
                                                <option value="20000">Rp 20.000</option>
                                                <option value="50000">Rp 50.000</option>
                                                <option value="75000">Rp 75.000</option>
                                                <option value="100000">Rp 100.000</option>
                                            </select>
                                            {{-- <input type="number" class="form-control @error('nominal') is-invalid @enderror nominal-input" name="nominal[]" required min="1" placeholder="Masukkan nominal"> --}}
                                            @error('nominal')
                                                <div class="invalid-feedback">
                                                    {{$message}}.
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="jumlah">Jumlah</label>
                                            <input type="number" class="form-control @error('jumlah') is-invalid @enderror jumlah-input" name="jumlah[]" required min="1" placeholder="Masukkan Jumlah">
                                            @error('jumlah')
                                                <div class="invalid-feedback">
                                                    {{$message}}.
                                                </div>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label for="harga">Total</label>
                                            <input type="number" class="form-control total-input" placeholder="Total Nominal" readonly name="total[]">
                                        </div>
                                        <div class="form-group col-md-2">
                                            <button type="button" class="btn btn-danger remove-btn">Hapus</button>
                                        </div>
                                    </div>
                                </div>
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div>
                                <div class="row">
                                    <div class="col-md-10">
                                        <input type="text" readonly hidden class="penerimaan_total" name="penerimaan_total" id="penerimaan_total">
                                        <h4 id="grandtotal">Total Penerimaan : 0</h4>
                                        <hr>
                                        <small id="terbilang_penerimaan">Terbilang : </small>
                                    </div>
                                    <div class="col-md-2 align-self-center">
                                        <button type="reset" class="btn btn-outline-danger mx-2">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    {{-- @endif --}}

                </div>
            </div>
        </div>

    </section>
    @endsection
</x-app-layout>
