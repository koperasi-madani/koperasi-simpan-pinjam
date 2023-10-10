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
    <script>
        $('#id_akun').select2({
            placeholder: "Pilih Akun"
        });
        $('#id_rek').select2({
            placeholder: "Pilih Rekening"
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
            var nominal_peminjaman = document.getElementById("nominal_peminjaman");
            nominal_peminjaman.value = formatRupiah(nominal_peminjaman.value);
            nominal_peminjaman.addEventListener("keyup", function(e) {
                nominal_peminjaman.value = formatRupiah(this.value);
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
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <header class="card-header">
                        <div class="d-flex justify-content-between">
                            <h4>Peminjaman Kas Hari Ini</h4>
                            @if ($ledger->jenis == 'debit')
                                <h6>Total Kas : Rp.{{ number_format($saldoAkhir, 2, ',', '.') }}</h6>
                            @else
                                <h6>Total Kas : Rp.{{ number_format($saldoAkhir, 2, ',', '.') }}</h6>
                            @endif
                        </div>
                    </header>
                    <div class="card-body">
                        <form action="{{ route('peminjaman-kas.post') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="product_name" class="form-label">Kode Akun</label>
                                    <select name="id_rek" id="id_rek" class="form-control">
                                        @foreach ($kode_akun as $item)
                                            <option value="{{ $item->id }}" {{ old('id_rek') == $item->id ? 'selected' : '' }}>{{ $item->kode_akun }} -- {{ $item->nama_akun }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_rek')
                                        <small class="text-danger">
                                            {{$message}}.
                                        </small>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="product_name" class="form-label">Nominal</label>
                                    <input placeholder="Nominal" value="0" id="nominal_peminjaman" type="text" value="{{ old('nominal') }}" class="form-control @error('nominal') is-invalid @enderror" name="nominal" />
                                    @error('nominal')
                                        <div class="invalid-feedback">
                                            {{$message}}.
                                        </div>
                                    @enderror
                                </div>
                            </div>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end mb-5">
                            <button type="reset" class="btn btn-outline-danger">Batal</button>
                            <button type="submit" class="btn btn-primary mx-2">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card mb-4">
                    <header class="card-header">
                        <div class="d-flex justify-content-between">
                            <h4>Tambah Data Pembayaran</h4>
                            <h6>Total Peminjaman : Rp.{{ number_format($peminjaman,2, ",", ".") }}</h6>
                        </div>
                    </header>
                    <div class="card-body">
                            <form action="{{ route('pembayaran.kas-teller.post') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Akun Teller</label>
                                        <select name="id_akun" id="id_akun" class="form-control" {{ count($teller) == 0 ? 'disabled' : '' }}>
                                            @foreach ($teller as $item)
                                                <option value="{{ $item->id }}" {{ old('id_akun') == $item->id ? 'selected' : '' }}>{{ $item->kode_user }} -- {{ $item->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_akun')
                                            <small class="text-danger">
                                                {{$message}}.
                                            </small>
                                        @enderror

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Kode</label>
                                        <input placeholder="Kode Pembayaran" type="text" value="{{ old('kode_pembayaran',$kode ) }}" class="form-control @error('kode_pembayaran') is-invalid @enderror" name="kode_pembayaran" />
                                        @error('kode_pembayaran')
                                            <div class="invalid-feedback">
                                                {{$message}}.
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Nominal</label>
                                        <input placeholder="Nominal" value="0" id="nominal" type="text" value="{{ old('nominal') }}" class="form-control @error('nominal') is-invalid @enderror" name="nominal" />
                                        @error('nominal')
                                            <div class="invalid-feedback">
                                                {{$message}}.
                                            </div>
                                        @enderror
                                    </div>
                                    <input type="text" name="peminjaman" value="{{ $peminjaman }}" readonly hidden>
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
        <!-- card end// -->
    </section>
    @endsection
</x-app-layout>
