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
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#kode').select2();
            $('#suku').select2();
            $('#id_nasabah').select2();
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
            <h2 class="content-title">{{ ucwords(str_replace('-',' ',Request::segment(3))) }}</h2>

        </div>
        @include('components.notification')
        <div class="row">
            <div class="card">
                <header class="card-header">
                    <h4>Tambah Pembukaan Rekening</h4>
                </header>
                @if (Session::has('status_tutup'))
                    @if (Session::get('status_tutup') == 'buka' || Auth::user()->hasRole('manager'))
                        <div class="card-body">
                            <form action="{{ route('pembukaan-rekening.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="product_name" class="form-label">Nama Anggota</label><small class="text-danger">*</small>
                                            <select name="id_nasabah" id="id_nasabah" class="form-control">
                                                @foreach ($nasabah as $item)
                                                    <option value="{{ $item->id }}" {{ old('id_nasabah') == $item->id ? 'selected' : '' }}>{{ $item->no_anggota }}--{{ $item->nama }}</option>
                                                @endforeach
                                            </select>
                                            @error('id_nasabah')
                                                <small class="text-danger">
                                                    {{$message}}.
                                                </small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="product_name" class="form-label">Tanggal</label><small class="text-danger">*</small>
                                            <input placeholder="Tanggal" type="text"  value="{{ old('tgl') }}" class="form-control @error('tgl') is-invalid @enderror" name="tgl"/>
                                            @error('tgl')
                                                <div class="invalid-feedback">
                                                    {{$message}}.
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="product_name" class="form-label">Kode Rekening</label><small class="text-danger">*</small>
                                            <select name="kode" id="kode" class="form-control">
                                                @foreach ($kode as $item)
                                                    <option value="{{ $item->id }}" {{ old('kode') == $item->id ? 'selected' : '' }}>{{ $item->kode_akun }}--{{ $item->nama_akun }}</option>
                                                @endforeach
                                            </select>
                                            @error('kode')
                                                <small class="text-danger">
                                                    {{$message}}.
                                                </small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="product_name" class="form-label">No Rekening</label><small class="text-danger">*</small>
                                            <input placeholder="No Rekening" value="{{ $noRekening }}" type="text" value="{{ old('no_rekening') }}" class="form-control @error('no_rekening') is-invalid @enderror" name="no_rekening" />
                                            @error('no_rekening')
                                                <div class="invalid-feedback">
                                                    {{$message}}.
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="product_name" class="form-label">Kode Suku Bunga</label><small class="text-danger">*</small>
                                            <select name="suku" id="suku" class="form-control">
                                                @foreach ($sukuBunga as $item)
                                                    <option value="{{ $item->id }}" {{ old('suku') == $item->id ? 'selected' : '' }}>{{ $item->kode_suku }}--{{ $item->nama }}</option>
                                                @endforeach
                                            </select>
                                            @error('suku')
                                                <small class="text-danger">
                                                    {{$message}}.
                                                </small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label for="product_name" class="form-label">Saldo Awal</label><small class="text-danger">*</small>
                                            <input placeholder="Masukkan saldo awal" value="{{ old('saldo_awal') }}" type="text" value="{{ old('saldo_awal') }}" class="form-control @error('saldo_awal') is-invalid @enderror" name="saldo_awal" id="saldo_awal" />
                                            @error('saldo_awal')
                                                <div class="invalid-feedback">
                                                    {{$message}}.
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Keterangan</label><small style="font-size: 10px;" class="ms-1">(optional)</small>
                                        <textarea name="ket" id="" cols="30" rows="10" class="form-control @error('ket') is-invalid @enderror" placeholder="Keterangan Rekening"></textarea>
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
                                <button type="submit" class="btn btn-primary mx-2">Simpan</button>
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
                        <h4>List Pembukaan Rekening</h4>
                    </header>
                    <div class="card-body">
                        <div class="">
                            <table class="table table-hover" id="example">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th scope="col">Nama Anggota</th>
                                        <th scope="col">No Rekening</th>
                                        <th scope="col">Saldo Awal</th>
                                        <th scope="col">Tanggal</th>
                                        <th scope="col">Status</th>
                                        <th scope="col" class="text-start">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                {{ $item->nasabah->nama }} <br>
                                                <small class="text-muted" style="font-size: 10px;">NIK : {{ $item->nasabah->nik }}</small>
                                            </td>
                                            <td>{{ $item->no_rekening }}</td>
                                            <td><b>Rp. {{ number_format($item->saldo_awal,2, ",", ".") }}</b></td>
                                            <td><b>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->translatedFormat('d F Y') }}</b></td>
                                            <td>
                                                @if ($item->status == 'aktif')
                                                    <span class="badge rounded-pill alert-success">Aktif</span>
                                                @else
                                                    <span class="badge rounded-pill alert-danger">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td class="text-start">
                                                @if (Session::has('status_tutup'))
                                                        @if (Session::get('status_tutup') == 'buka' || Auth::user()->hasRole('manager'))
                                                            <div class="d-flex justify-content-start">
                                                                <form action="{{ route('pembukaan-rekening.destroy',$item->id) }}" class="p-0 m-0" method="POST" onsubmit="return confirm('Move data to trash? ')">
                                                                    @method('delete')
                                                                    @csrf
                                                                    <button  class="btn btn-sm font-sm btn-light rounded"> <i class="material-icons md-delete_forever"></i> Delete </button>
                                                                </form>
                                                                <div class="dropdown mx-1">
                                                                    <a href="#" data-bs-toggle="dropdown" class="btn btn-light rounded btn-sm font-sm"> <i class="material-icons md-more_horiz"></i> </a>
                                                                    <div class="dropdown-menu">
                                                                        <a class="dropdown-item" href="{{ route('pembukaan-rekening.show',$item->id) }}">Detail</a>
                                                                        <a class="dropdown-item" href="{{ route('cetak-rekening.pembukaan-rekening',$item->id) }}">Cetak</a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <small><strong>Perhatian!</strong> form belum bisa diakses.</small>

                                                        @endif
                                                @endif
                                                <!-- dropdown //end -->
                                            </td>
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
