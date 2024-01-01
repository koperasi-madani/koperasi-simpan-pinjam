<x-app-layout>
    @push('css')
        <style>
            .page-item.active .page-link{
                background-color: #219ebc !important;
                border-color: #8ecae6;
            }
            .table > :not(caption) > * > *{
                /* padding: 5px; */
            }
            .table-bondered th, td.uang{
                text-align: right !important;
                /* border: 1px solid #9896966e !important; */
            }
            .table-bondered th, td, th{
                border: 0.1px solid #cccbcb59 !important;
            }
            .bg-primary{
                background-color: #E0F6FB !important;
            }
            .bg-secondary{
                background-color: #83c5be !important;
                font-weight: bold !important;
            }

        </style>
    @endpush
    @section('content')
        <section class="content-main">
            <div class="card mb-4">
                <header class="card-header">
                    <div class="d-flex justify-content-between">
                        <div class="mt-3">
                            <h2 class="content-title">{{ ucwords(str_replace('-',' ',Request::segment(3))) }}</h2>
                        </div>
                        <div>
                            <h5>WILAYAH: 01 - JAWA TIMUR</h5>
                        </div>
                    </div>
                </header>
                <div class="card-body">
                    <div class="mb-4">
                        <div class="table-responsive">
                            <table class="table table-bordered dataTable no-footer" id="example">
                                <thead>
                                    <tr>
                                       <th>No</th>
                                       <th>Tanggal/Waktu</th>
                                       <th>User</th>
                                       <th>Nominal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pembayaran as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y / H:i:s') }}</td>
                                            <td>{{ ucwords($item->user->name) }}</td>
                                            <td>{{ number_format($item->total_penerimaan,0,",", ".") }}</td>
                                        </tr>
                                    @empty
                                        <td>Tidak ada data</td>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endsection
</x-app-layout>
