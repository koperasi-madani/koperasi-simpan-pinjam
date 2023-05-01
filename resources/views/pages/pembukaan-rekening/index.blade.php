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
            $('#id_nasabah').select2();
            $('#example').DataTable();
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
                        format: 'YY-MM-DD'
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
                <div class="card-body">
                    <form action="{{ route('pembukaan-rekening.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">Nama Nasabah</label>
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
                                    <label for="product_name" class="form-label">No Rekening</label>
                                    <input placeholder="No Rekening" value="{{ $noRekening }}" type="text" value="{{ old('no_rekening') }}" class="form-control @error('no_rekening') is-invalid @enderror" name="no_rekening" />
                                    @error('no_rekening')
                                        <div class="invalid-feedback">
                                            {{$message}}.
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="product_name" class="form-label">Keterangan</label>
                                <textarea name="ket" id="" cols="30" rows="10" class="form-control @error('ket') is-invalid @enderror"></textarea>
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
            </div>
            <div class="col-md-12">
                <div class="card mb-4">
                    <header class="card-header">
                        <h4>List Pembukaan Rekening</h4>
                    </header>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover" id="example">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th scope="col">Nama Nasabah</th>
                                        <th scope="col">No Rekening</th>
                                        <th scope="col">Jumlah Simpanan</th>
                                        <th scope="col">Saldo</th>
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
                                            <td><b>Rp. {{ number_format($item->jumlah_simpanan,2, ",", ".") }}</b></td>
                                            <td><b>Rp. {{ number_format($item->saldo_anggota,2, ",", ".") }}</b></td>
                                            <td><b>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->translatedFormat('d F Y') }}</b></td>
                                            <td>
                                                @if ($item->status == 'aktif')
                                                    <span class="badge rounded-pill alert-success">Aktif</span>
                                                @else
                                                    <span class="badge rounded-pill alert-danger">Tidak Aktif</span>
                                                @endif
                                            </td>
                                            <td class="text-start">
                                                <div class="d-flex justify-content-start">
                                                    <div>
                                                        <a href="{{ route('pembukaan-rekening.show',$item->id) }}" class="btn btn-sm font-sm rounded btn-info text-white"> <i class="material-icons md-info"></i> Detail </a>
                                                    </div>
                                                    <div class="mx-2">
                                                        <form action="{{ route('pembukaan-rekening.destroy',$item->id) }}" class="p-0 m-0" method="POST" onsubmit="return confirm('Move data to trash? ')">
                                                            @method('delete')
                                                            @csrf
                                                            <button  class="btn btn-sm font-sm btn-light rounded"> <i class="material-icons md-delete_forever"></i> Delete </button>
                                                        </form>
                                                    </div>
                                                </div>
                                                <!-- dropdown //end -->
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>Tidak ada data</td>
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
