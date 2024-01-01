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
                                       <th class="text-center">No</th>
                                       <th class="text-center">TANGGAL/WAKTU</th>
                                       <th class="text-center">COA/KODE AKUN</th>
                                       <th class="text-center">TYPE</th>
                                       <th class="text-center">NOMINAL</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($transaksi as $item)
                                        <tr>
                                            <td align="right">{{ $loop->iteration }}</td>
                                            <td align="right">{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y / H:i:s') }}</td>
                                            <td align="right">{{ $item->kodeAkun->kode_akun }}</td>
                                            <td align="right">{{ $item->tipe == 'masuk' ? 'D' : 'K' }}</td>
                                            <td align="right">{{ number_format($item->total,0,",", ".") }}</td>
                                        </tr>
                                        @if ($item->detail )
                                            @foreach ($item->detail as $item_detail)
                                                @if ($item_detail->kode_transaksi == $item->kode_transaksi)
                                                    <tr>
                                                        <td align="right"></td>
                                                        <td align="right"></td>
                                                        <td align="right">{{ $item_detail->kode_akun }}</td>
                                                        <td align="right">{{ $item_detail->tipe == 'masuk' ? 'D' : 'K'}}</td>
                                                        <td align="right">{{ number_format($item_detail->subtotal,0,",", ".") }}</td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                        @endif
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
