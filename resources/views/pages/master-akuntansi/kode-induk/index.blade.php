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
        $('#id_ledger').select2({
            placeholder: "Pilih Rekening"
        });
    </script>
    <script>
        $(document).ready(function () {
            $('#example').DataTable();
        })
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
                    <h4>Tambah {{ ucwords(str_replace('-',' ',Request::segment(3))) }}</h4>
                </header>
                <div class="card-body">
                    <form action="{{ route('kode-induk.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">Kode Ledger</label>
                                    <select name="id_ledger" id="id_ledger" class="form-control">
                                        @foreach ($kode as $item)
                                            <option value="{{ $item->id }}" {{ old('id_ledger') == $item->id ? 'selected' : '' }}>{{ $item->kode_ledger }} -- {{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_ledger')
                                        <small class="text-danger">
                                            {{$message}}.
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">Kode Induk</label>
                                    <input placeholder="Masukkan kode induk" value="{{ old('kode_induk') }}" type="text" value="{{ old('kode_induk') }}" class="form-control @error('kode_induk') is-invalid @enderror" name="kode_induk" />
                                    @error('kode_induk')
                                        <div class="invalid-feedback">
                                            {{$message}}.
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">Nama</label>
                                    <input placeholder="Masukkan nama kode" value="{{ old('nama') }}" type="text" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" name="nama" />
                                    @error('nama')
                                        <div class="invalid-feedback">
                                            {{$message}}.
                                        </div>
                                    @enderror
                                </div>
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
            </div>
            <div class="col-md-12">
                <div class="card mb-4">
                    <header class="card-header">
                        <h4>List {{ ucwords(str_replace('-',' ',Request::segment(3))) }}</h4>
                    </header>
                    <div class="card-body">
                        <div class="">
                            <table class="table table-hover" id="example">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th scope="col">Kode Induk</th>
                                        <th scope="col">Nama Kode</th>
                                        <th scope="col">Jenis</th>
                                        <th scope="col">Tanggal</th>
                                        <th scope="col" class="text-start">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->kode_induk }}</td>
                                            <td>{{ ucwords($item->nama) }}</td>
                                            <td>{{ ucwords($item->jenis) }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y ')  }}</td>
                                            <td>
                                                <div class="d-flex justify-content-start">
                                                    <div class="mx-2">
                                                        <a href="{{ route('kode-induk.edit',$item->id) }}" class="btn btn-sm font-sm rounded btn-brand"> <i class="material-icons md-edit"></i> Edit </a>
                                                    </div>
                                                    <form action="{{ route('kode-induk.destroy',$item->id) }}" class="p-0 m-0" method="POST" onsubmit="return confirm('Move data to trash? ')">
                                                        @method('delete')
                                                        @csrf
                                                        <button  class="btn btn-sm font-sm btn-light rounded"> <i class="material-icons md-delete_forever"></i> Delete </button>
                                                    </form>
                                                </div>
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
