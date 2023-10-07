<x-app-layout>
    @push('css')
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
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

            $('#example').DataTable();
        })

    </script>
    <script>
        $(function() {
            $('input[name="sampai"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                timePicker: false,
                locale: {
                    format: 'Y-MM-DD'
                }
            });
            $('input[name="dari"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: true,
                timePicker: false,
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
            <div class="col-md-12">
                <div class="card mb-4">
                    <header class="card-header">
                        <h4>List Pembukaan Rekening</h4>
                    </header>
                    <header class="card-header">
                        <form action="{{ route('laporan.pembukaan-rekening') }}" method="GET">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Dari Tanggal </label>
                                        <input type="text" data-provide="dari" name="dari" value="{{ request('dari') }}" class="form-control dari @error('dari') is-invalid @enderror" id="exampleInputUsername1" placeholder="Masukkan tanggal dari">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">Sampai Tanggal</label>
                                        <input type="text"  name="sampai" value="{{ request('sampai') }}" class="form-control sampai @error('bulan') is-invalid @enderror" id="exampleInputUsername1" placeholder="Masukkan tanggal sampai">

                                    </div>
                                </div>
                                <div class="col-md-6 p-0 ">
                                    <label for=""></label>
                                    <div class="d-flex flex-row">
                                        <div>
                                            <button type="submit" class="btn btn-primary btn-icon-text ">
                                                <i class="ti-filter btn-icon-prepend"></i>
                                                Filter
                                            </button>
                                        </div>
                                        <div class="mx-2">
                                            <a href="{{ route('laporan.pembukaan-rekening.pdf') }}" type="button" class="btn btn-danger btn-icon-text">
                                                <i class="ti-printer btn-icon-prepend"></i>
                                                Cetak PDF
                                            </a>
                                            <a href="{{ route('laporan.pembukaan-rekening') }}" class="btn btn-outline-danger btn-icon-text mx-1">
                                                <i class="ti-shift-left btn-icon-prepend"></i>
                                                Reset
                                            </a>
                                        </div>

                                    </div>

                                </div>
                            </div>
                        </form>
                    </header>
                    <div class="card-body">
                        <div class="">
                            <table class="table table-hover" id="example">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th scope="col">Nama Anggota</th>
                                        <th scope="col">No Rekening</th>
                                        <th scope="col">Saldo</th>
                                        <th scope="col">Tanggal</th>
                                        <th scope="col">Status</th>
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
