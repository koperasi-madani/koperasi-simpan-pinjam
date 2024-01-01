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
                            <h5>{{ ucwords(auth()->user()->name) }} : {{ ucwords(Auth::user()->roles->pluck('name')[0]) }}</h5>
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
                                       <th>Nama Anggota</th>
                                       <th>Transaksi</th>
                                       <th>Nominal</th>
                                       <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $total_setoran = 0;
                                        $total_penarikan = 0;
                                    @endphp
                                    @forelse ($transaksi as $item)
                                        @php
                                            if ($item->jenis == 'masuk') {
                                                $total_setoran += $item->nominal;
                                            } else {
                                                $total_penarikan += $item->nominal;
                                            }

                                        @endphp
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y / H:i:s') }}</td>
                                            <td>{{ ucwords($item->nama) }}</td>
                                            <td>{{  $item->jenis == 'masuk' ? 'SETORAN' : 'PENARIKAN'}}</td>
                                            <td>{{ number_format($item->nominal,0,",", ".") }}</td>
                                            <td>{{ $item->ket }}</td>
                                        </tr>
                                    @empty
                                        <td>Tidak ada data</td>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold">
                                        <th colspan="4">SALDO AWAL</th>
                                        <th>{{ number_format($current_penerimaan,0,",",".") }}</th>
                                        <th>*Saldo AWAL</th>
                                    </tr>
                                    <tr class="fw-bold">
                                        <th colspan="4">SETORAN</th>
                                        <th>{{ number_format($total_setoran,0,",", ".") }}</th>
                                        <th>*STR</th>
                                    </tr>
                                    <tr class="fw-bold">
                                        <th colspan="4">PENARIKAN</th>
                                        <th>{{ number_format($total_penarikan,0,",", ".") }}</th>
                                        <th>*TRK</th>
                                    </tr>
                                    <tr class="fw-bold">
                                        <th colspan="4">SISA SALDO</th>
                                        <th>{{ number_format($pembayaran,0,",",".") }}</th>
                                        <th>*SISA SALDO</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endsection
</x-app-layout>
