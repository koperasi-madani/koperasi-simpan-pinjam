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
                                <th scope="col">Nama Anggota</th>
                                <th scope="col">No Rekening</th>
                                <th scope="col">Saldo Awal</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Status</th>
                                <th scope="col">Suku Bunga</th>
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
                                    <td><b>{{ $item->sukuBunga->nama }}</b>
                                        <br><small class="text-muted">{{ $item->sukuBunga->suku_bunga }}%</small>
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
        <!-- card end// -->
    </section>
    @endsection
</x-app-layout>
