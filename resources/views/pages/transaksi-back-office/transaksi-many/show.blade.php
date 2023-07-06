<x-app-layout>
    @push('css')

    @endpush
    @push('js')

    @endpush
    @section('content')
    <section class="content-main mb-5">
        <div class="content-header">
            <h2 class="content-title">{{ ucwords(str_replace('-',' ',Request::segment(3))) }}</h2>
            <div>
                <button onclick="history.back()" class="btn btn-light"><i class="text-muted material-icons md-arrow_back"></i>Kembali</button>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card mb-4">
                    <header class="card-header">
                        <div class="d-flex justify-content-between">
                            <h4>Detail Data Transaksi</h4>
                            <div>
                            </div>
                        </div>
                    </header>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-responsive-sm">
                                <tbody>
                                    <tr>
                                        <td width="20%">Kode Transaksi</td>
                                        <td width="1%">:</td>
                                        <td >{{ ucwords($data->kode_transaksi) }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Kode Akun</td>
                                        <td width="1%">:</td>
                                        <td >{{ $data->kode_akun }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Tipe</td>
                                        <td width="1%">:</td>
                                        <td >{{ $data->tipe }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Total</td>
                                        <td width="1%">:</td>
                                        <td >Rp. {{ number_format($data->total, 2, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Tanggal</td>
                                        <td width="1%">:</td>
                                        <td >{{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d-F-Y') }}</td>
                                    </tr>

                                </tbody>
                            </table>
                            <hr>
                            <table class="table table-bordered table-responsive-sm">
                                <thead>
                                    <th>No</th>
                                    <th>Kode Akun</th>
                                    <th>Nominal</th>
                                    <th>keterangan</th>
                                </thead>
                                <tbody>
                                    @foreach ($detail as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $item->kode_akun }}</td>
                                            <td>Rp. {{ number_format($item->subtotal, 2, ',', '.') }}</td>
                                            <td>{{ $item->keterangan }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                    </div>

                </div>
            </div>
        </div>
        <!-- card end// -->
    </section>
    @endsection
</x-app-layout>
