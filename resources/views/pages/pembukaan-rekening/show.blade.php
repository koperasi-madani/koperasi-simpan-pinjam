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
                            <h4>Detail Data Rekening</h4>
                            <div>
                            </div>
                        </div>

                    </header>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-responsive-sm">
                                <tbody>
                                    <tr>
                                        <td width="20%">NIK</td>
                                        <td width="1%">:</td>
                                        <td >{{ ucwords($data->nasabah->nik) }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Nama Nasabah</td>
                                        <td width="1%">:</td>
                                        <td >{{ $data->nasabah->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Alamat</td>
                                        <td width="1%">:</td>
                                        <td >{{ $data->nasabah->alamat != null ? $data->nasabah->alamat : '-'  }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Tanggal</td>
                                        <td width="1%">:</td>
                                        <td >{{ \Carbon\Carbon::parse($data->tgl)->translatedFormat('d-F-Y') }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Simpanan Pokok</td>
                                        <td width="1%">:</td>
                                        <td >Rp. {{ number_format($data->nasabah->sim_pokok,2, ",", ".") }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Simpanan Wajib</td>
                                        <td width="1%">:</td>
                                        <td >Rp. {{ number_format($data->nasabah->sim_wajib,2, ",", ".") }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Simpanan Sukarela</td>
                                        <td width="1%">:</td>
                                        <td >Rp. {{ number_format($data->nasabah->sim_sukarela,2, ",", ".") }}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr>
                            <table class="table table-bordered table-responsive-sm">
                                <tr>
                                    <td width="20%">No Rekening</td>
                                    <td width="1%">:</td>
                                    <td >{{ $data->no_rekening }}</td>
                                </tr>
                                <tr>
                                    <td width="20%">Jumlah Simpanan</td>
                                    <td width="1%">:</td>
                                    <td >Rp. {{ number_format($data->jumlah_simpanan,2, ",", ".") }}</td>
                                </tr>
                                <tr>
                                    <td width="20%">Saldo</td>
                                    <td width="1%">:</td>
                                    <td >Rp. {{ number_format($data->saldo,2, ",", ".") }}</td>
                                </tr>
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
