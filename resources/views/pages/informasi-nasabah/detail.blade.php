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
                            <h4>Detail Data Setoran</h4>
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
                                $setoran = \App\Models\Setoran::select(
                                            'setoran.id',
                                            'setoran.id_rekening_tabungan',
                                            'setoran.kode_setoran',
                                            'setoran.tgl_setor',
                                            'setoran.nominal_setor',
                                            'setoran.validasi',
                                            'setoran.saldo',
                                            'rekening_tabungan.nasabah_id',
                                            'rekening_tabungan.no_rekening',
                                            'nasabah.id as id_nasabah',
                                            'nasabah.nama',
                                            'nasabah.nik',
                                            'users.id as id_user',
                                            'users.kode_user'
                                            )
                                            ->join(
                                                'rekening_tabungan','rekening_tabungan.id','setoran.id_rekening_tabungan'
                                            )->join(
                                                'nasabah','nasabah.id','rekening_tabungan.nasabah_id'
                                            )
                                            ->join(
                                                'users', 'users.id', 'setoran.id_user'
                                            )
                                            ->where(
                                                'rekening_tabungan.nasabah_id',$data->nasabah_id
                                            )
                                            ->where(
                                                'setoran.id_user',auth()->user()->id
                                            )
                                            ->get()
                            @endphp
                            <table class="table table-bordered table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th>Kode Setoran</th>
                                        <th>Nominal</th>
                                        <th>Tanggal</th>
                                        <th>Validasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($setoran as $itemS)
                                        <tr>
                                            <td>{{ $itemS->kode_setoran }}</td>
                                            <td>Rp. {{ number_format($itemS->nominal_setor,2, ",", ".") }}</td>
                                            <td >{{ \Carbon\Carbon::parse($itemS->tgl_setor)->translatedFormat('d-F-Y') }}</td>
                                            <td>{{ $itemS->kode_user }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>Tidak ada setoran</td>
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
