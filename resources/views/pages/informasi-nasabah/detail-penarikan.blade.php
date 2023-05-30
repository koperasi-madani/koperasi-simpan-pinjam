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
                            <h4>Detail Data Penarikan</h4>
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
                                        <td width="20%">No Rekening</td>
                                        <td width="1%">:</td>
                                        <td >{{ $data->no_rekening }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Nama Nasabah</td>
                                        <td width="1%">:</td>
                                        <td >{{ $data->nasabah->nama }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Jenis Kelamin</td>
                                        <td width="1%">:</td>
                                        <td >{{ $data->nasabah->jenis_kelamin == '0' ? 'Laki-Laki' : 'Perempuan' }}</td>
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

                                </tbody>
                            </table>
                            <hr>
                            @php
                                $penarikan = \App\Models\Penarikan::select(
                                    'penarikan.id',
                                    'penarikan.id_rekening_tabungan',
                                    'penarikan.kode_penarikan',
                                    'penarikan.tgl_setor',
                                    'penarikan.nominal_setor',
                                    'penarikan.validasi',
                                    'penarikan.otorisasi_penarikan',
                                    'rekening_tabungan.nasabah_id',
                                    'rekening_tabungan.no_rekening',
                                    'nasabah.id as id_nasabah',
                                    'nasabah.nama',
                                    'nasabah.nik',
                                    'users.id as id_user',
                                    'users.kode_user'
                                )
                                ->join(
                                    'rekening_tabungan','rekening_tabungan.id','penarikan.id_rekening_tabungan'
                                )->join(
                                    'nasabah','nasabah.id','rekening_tabungan.nasabah_id'
                                )
                                ->join(
                                    'users', 'users.id', 'penarikan.id_user'
                                )
                                ->where(
                                    'rekening_tabungan.nasabah_id',$data->nasabah_id
                                )
                                ->where(
                                    'penarikan.id_user',auth()->user()->id
                                )
                                ->get()
                            @endphp
                            <table class="table table-bordered table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th>Kode Penarikan</th>
                                        <th>Nominal</th>
                                        <th>Tanggal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($penarikan as $itemS)
                                        <tr>
                                            <td>{{ $itemS->kode_penarikan }}</td>
                                            <td>Rp. {{ number_format($itemS->nominal_setor,2, ",", ".") }}</td>
                                            <td >{{ \Carbon\Carbon::parse($itemS->tgl_setor)->translatedFormat('d-F-Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>Tidak ada penarikan</td>
                                        </tr>

                                    @endforelse
                                </tbody>

                            </table>
                            <hr>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <!-- card end// -->
    </section>
    @endsection
</x-app-layout>
