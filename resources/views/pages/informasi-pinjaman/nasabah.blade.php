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
            <h2 class="content-title">{{ ucwords(str_replace('-',' ',Request::segment(4))) }}</h2>
        </div>
        @include('components.notification')

        <div class="card mb-4">
            <header class="card-header">
                <h4>List Anggota</h4>
            </header>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="example">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th scope="col">NIK</th>
                                <th scope="col">Nama Anggota</th>
                                <th scope="col">Jenis Kelamin</th>
                                <th scope="col">Pekerjaan</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">Status</th>
                                <th scope="col">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nik }}</td>
                                    <td>
                                        {{ $item->nama }} <br>
                                    </td>
                                    <td><b>
                                        @if ($item->jenis_kelamin == '0')
                                            Laki-Laki
                                        @else
                                            Perempuan
                                        @endif
                                        </b></td>
                                    <td>
                                        {{ $item->pekerjaan }} <br>
                                    </td>
                                    <td><b>{{ $item->alamat }}</b></td>
                                    <td>
                                        @if ($item->status == 'aktif')
                                            <span class="badge rounded-pill alert-success">Aktif</span>
                                        @else
                                            <span class="badge rounded-pill alert-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td><b>{{ \Carbon\Carbon::parse($item->tgl)->translatedFormat('d F Y') }}</b></td>

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
        <!-- card end// -->
    </section>
    @endsection
</x-app-layout>
