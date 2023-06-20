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
                                        <td >{{ ucwords($data->nik) }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">No Anggota</td>
                                        <td width="1%">:</td>
                                        <td >{{ $data->no_anggota }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">No Rekening</td>
                                        <td width="1%">:</td>
                                        <td >{{ $data->no_rekening }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Nama Nasabah</td>
                                        <td width="1%">:</td>
                                        <td >{{ $data->nama_nasabah }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Jenis Kelamin</td>
                                        <td width="1%">:</td>
                                        <td >{{ $data->jenis_kelamin == '0' ? 'Laki-Laki' : 'Perempuan' }}</td>
                                    </tr>
                                    <tr>
                                        <td width="20%">Alamat</td>
                                        <td width="1%">:</td>
                                        <td >{{ $data->alamat != null ? $data->alamat : '-'  }}</td>
                                    </tr>
                                    {{-- @php
                                        $sukuBunga = $data->suku_bunga;
                                        $hitung =  $data->saldo * $sukuBunga;
                                        // 80 persen dari pajak (pph)
                                        $result = $hitung * 80 / 100 / 365;
                                    @endphp
                                    {{ ceil($result) }} --}}
                                    <tr>
                                        <td width="20%">Suku Bunga Dicadangkan</td>
                                        <td width="1%">:</td>
                                        <td >Rp.  {{ number_format($data->saldo_bunga,2, ",", ".") }}</td>
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
                                $data_tabungan = \App\Models\TransaksiTabungan::select('transaksi_tabungan.*',
                                            'rekening_tabungan.nasabah_id',
                                            'rekening_tabungan.no_rekening',
                                            'nasabah.id as id_nasabah',
                                            'nasabah.nama',
                                            'nasabah.nik',
                                            'users.id as id_user',
                                            'users.kode_user'
                                            )
                                            ->join(
                                                'rekening_tabungan','rekening_tabungan.id','transaksi_tabungan.id_nasabah'
                                            )->join(
                                                'nasabah','nasabah.id','rekening_tabungan.nasabah_id'
                                            )
                                            ->join(
                                                'users', 'users.id', 'transaksi_tabungan.id_user'
                                            )
                                            ->where(
                                                'rekening_tabungan.nasabah_id',$data->nasabah_id
                                            )
                                            ->where(
                                                'transaksi_tabungan.id_user',auth()->user()->id
                                            )
                                            ->get()
                            @endphp
                            <table class="table table-bordered table-responsive-sm">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>No Rekening</th>
                                        <th>Masuk</th>
                                        <th>Keluar</th>
                                        <th>Saldo</th>
                                        <th>Tanggal</th>
                                        <th>Validasi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data_tabungan as $itemS)
                                        <tr>
                                            <td>{{ $itemS->kode }}</td>
                                            <td>{{ $itemS->no_rekening }}</td>
                                            <td> {{ $itemS->jenis == 'masuk' ?  'Rp.'.number_format($itemS->nominal,2, ",", ".") : '-'}}</td>
                                            <td> {{ $itemS->jenis == 'keluar' ?  'Rp.'.number_format($itemS->nominal,2, ",", ".") : '-'}}</td>
                                            <td>{{ number_format($itemS->saldo,2, ",", ".") }}</td>
                                            <td >{{ \Carbon\Carbon::parse($itemS->tgl)->translatedFormat('d-F-Y') }}</td>
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
