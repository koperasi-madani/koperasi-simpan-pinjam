<x-app-layout>
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
        <style>
            .page-item.active .page-link{
                background-color: #219ebc !important;
                border-color: #8ecae6;
            }
        </style>
    @endpush
    @push('js')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
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
            <div>
                @if (Session::has('status_tutup'))
                    @if (Session::get('status_tutup') == 'buka' || Auth::user()->hasRole('manager'))
                        <a href="{{ route('nasabah.create') }}" class="btn btn-primary"><i class="text-muted material-icons md-post_add"></i>Tambah Transaksi</a>
                    @else
                        <small><strong>Perhatian!</strong> form belum bisa diakses.</small>
                    @endif
                @endif
            </div>
        </div>
        @include('components.notification')

        <div class="card mb-4">
            <header class="card-header">
                <h4>List Transaksi Many To Many</h4>
            </header>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="example">
                        <thead>
                            <tr>
                                <th scope="col">No</th>
                                <th scope="col">Kode Kas</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Kode Akun Kas</th>
                                <th scope="col">Tipe</th>
                                <th scope="col">Total</th>
                                <th scope="col">Aksi</th>
                                {{-- <th scope="col" class="text-start">Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
            </div>
        </div>
        <!-- card end// -->
    </section>
    @endsection
</x-app-layout>
