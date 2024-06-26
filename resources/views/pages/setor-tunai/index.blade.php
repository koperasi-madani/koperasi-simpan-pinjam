<x-app-layout>
    @push('css')
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#kode').select2();
            $('#id_rekening').select2();
            $('#example').DataTable();
        })

    </script>
    <script>
        $(document).ready(function() {

            var saldo_awal = document.getElementById("saldo_awal");
            saldo_awal.value = formatRupiah(saldo_awal.value);
            saldo_awal.addEventListener("keyup", function(e) {
                saldo_awal.value = formatRupiah(this.value);
            });

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
    @endpush
    @section('content')
    <section class="content-main">
        <div class="content-header">
            <h2 class="content-title">{{ ucwords(str_replace('-',' ',Request::segment(4))) }}</h2>

        </div>
        @include('components.notification')
        <div id="pesan_error">

        </div>
        <div class="row">
            <div class="card">
                <header class="card-header">
                    <h4>Tambah {{ ucwords(str_replace('-',' ',Request::segment(4))) }}</h4>
                </header>
                @if (Session::has('status_tutup'))
                    @if (Session::get('status_tutup') == 'buka' || Auth::user()->hasRole('manager'))
                        <div class="card-body">
                            <form id="submitForm" action="{{ route('setor-tunai.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="product_name" class="form-label">Kode Rekening</label>
                                            <select name="id_rekening" id="id_rekening" class="form-control">
                                                @foreach ($data as $item)
                                                    <option value="{{ $item->id }}" {{ old('id_rekening') == $item->id ? 'selected' : '' }}>{{ $item->no_rekening }}--{{ $item->nama }}</option>
                                                @endforeach
                                            </select>
                                            @error('id_rekening')
                                                <small class="text-danger">
                                                    {{$message}}.
                                                </small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="product_name" class="form-label">Kode Setoran</label>
                                            <input placeholder="Masukkan kode setoran" value="{{ old('kode_setoran',$noSetoran) }}" type="text" class="form-control @error('kode_setoran') is-invalid @enderror" name="kode_setoran" />
                                            @error('kode_setoran')
                                                <div class="invalid-feedback">
                                                    {{$message}}.
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-4">
                                            <label for="product_name" class="form-label">Tanggal</label>
                                            <input placeholder="Tanggal" type="text"  value="{{ old('tgl') }}" class="form-control @error('tgl') is-invalid @enderror" name="tgl"/>
                                            @error('tgl')
                                                <div class="invalid-feedback">
                                                    {{$message}}.
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-4">
                                            <label for="product_name" class="form-label">Nominal Setor</label>
                                            <input placeholder="Masukkan nominal setor" value="{{ old('nominal_setor',) }}" type="text"  class="form-control @error('nominal_setor') is-invalid @enderror" name="nominal_setor" id="saldo_awal" />
                                            @error('nominal_setor')
                                                <div class="invalid-feedback">
                                                    {{$message}}.
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Keterangan</label>
                                        <textarea name="ket" id="" cols="30" rows="10" class="form-control @error('ket') is-invalid @enderror">Setor Tunai</textarea>
                                        @error('ket')
                                            <div class="invalid-feedback">
                                                {{$message}}.
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex justify-content-end">
                                <button type="reset" class="btn btn-outline-danger">Batal</button>
                                <button type="submit" class="btn btn-primary mx-2">Setor</button>
                            </form>

                            </div>
                        </div>
                    @else
                        <div class="card-body">
                            <div class="alert alert-danger d-flex align-items-center" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                <div>
                                    <strong>Perhatian!</strong> form belum bisa diakses.
                                </div>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
            <div class="col-md-12">
                <div class="card mb-4">
                    <header class="card-header">
                        <h4>List {{ ucwords(str_replace('-',' ',Request::segment(4))) }}</h4>
                    </header>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="example">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th scope="col">Nama Nasabah</th>
                                        <th scope="col">No Rekening</th>
                                        <th scope="col">Nominal Setoran </th>
                                        <th scope="col">Tanggal</th>
                                        <th scope="col">Keterangan</th>
                                        <th scope="col">Validasi</th>
                                        <th scope="col">Aksi</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($setoran as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $item->nama }} <br>
                                                <small class="text-muted" style="font-size: 10px;">NIK : {{ $item->nik }}</small>
                                            </td>
                                            <td>{{ $item->no_rekening }}</td>
                                            <td><b>Rp. {{ number_format($item->nominal,2, ",", ".") }}</b></td>
                                            <td><b>{{ \Carbon\Carbon::parse($item->tgl)->translatedFormat('d F Y') }}</b></td>
                                            <td><b>{{ $item->ket }}</b></td>
                                            <td><b>{{ $item->kode_user }}</b></td>
                                            <td><b><a href="{{ route('setor-tunai.pdf',$item->id) }}" class="btn btn-sm font-sm btn-light rounded"> <i class="icon material-icons md-assignment mr-2" style="font-size: 14px"></i>Cetak Struk</a></b></td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">Tidak ada data</td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                    </div>
                </div>
            </div>
        </div>
        <!-- card end// -->
    </section>
    @endsection
</x-app-layout>
