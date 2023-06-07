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
            <div class="tab-content" id="myTabContent">
                <header class="card-header">
                    <h4>List Saldo Teller</h4>
                </header>
                <div class="card-body">
                    <table class="table table-hover" id="example">
                        <thead>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Nama Teller</th>
                            <th>Nominal Pembayaran</th>
                            <th>Nominal Penerimaan</th>
                        </thead>
                        <tbody>
                            @forelse ($data_pembayaran as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->kode }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td><b>Rp. {{ number_format($item->pembayaran,2, ",", ".") }}</b></td>
                                    <td><b>Rp. {{ number_format($item->penerimaan,2, ",", ".") }}</b></td>
                                </tr>
                            @empty
                                <tr>
                                    <td>Tidak ada data</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                </div>
            </div>
        </div>

    </section>
    @endsection
</x-app-layout>
