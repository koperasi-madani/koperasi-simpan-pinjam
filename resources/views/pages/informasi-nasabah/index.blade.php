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
            <h2 class="content-title">{{ ucwords(str_replace('-',' ',Request::segment(3))) }}</h2>
        </div>
        @include('components.notification')
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Setor Tunai Nasabah</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Penarikan Nasabah</button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card mb-4">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <header class="card-header">
                        <h4>List Setor Nasabah</h4>
                    </header>
                    <div class="card-body">
                        <table class="table table-hover" id="example">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th scope="col">Nama Nasabah</th>
                                    <th scope="col">No Rekening</th>
                                    <th scope="col">Nominal Setoran </th>
                                    <th scope="col">Keterangan</th>
                                    <th scope="col">Validasi</th>
                                    <th scope="col">Tanggal</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($setoran as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $item->nama }} <br>
                                            <small class="text-muted" style="font-size: 10px;">NIK : {{ $item->nik }}</small>
                                        </td>
                                        <td>{{ $item->no_rekening }}</td>
                                        <td><b>Rp. {{ number_format($item->nominal_setor,2, ",", ".") }}</b></td>
                                        <td><b>{{ $item->validasi }}</b></td>
                                        <td><b>{{ $item->kode_user }}</b></td>
                                        <td><b>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->translatedFormat('d F Y') }}</b></td>
                                        <td>
                                            <a href="{{ route('teller.informasi.nasabah-detail',$item->id_nasabah) }}"  class="btn btn-sm font-sm btn-light rounded"> <i class="material-icons md-assignment"></i> Detail </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7">Tidak ada data</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table>
                        <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                    </div>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="home-tab">
                    <header class="card-header">
                        <h4>List Penarikan Nasabah</h4>
                    </header>
                    <div class="card-body">
                        <table class="table table-hover" id="example">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th scope="col">Nama Nasabah</th>
                                    <th scope="col">No Rekening</th>
                                    <th scope="col">Nominal Penarikan </th>
                                    <th scope="col">Keterangan</th>
                                    <th scope="col">Status Otorisasi</th>
                                    <th scope="col">Validasi</th>
                                    <th scope="col">Tanggal</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($penarikan as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            {{ $item->nama }} <br>
                                            <small class="text-muted" style="font-size: 10px;">NIK : {{ $item->nik }}</small>
                                        </td>
                                        <td>{{ $item->no_rekening }}</td>
                                        <td><b>Rp. {{ number_format($item->nominal_setor,2, ",", ".") }}</b></td>
                                        <td><b>{{ $item->validasi }}</b></td>
                                        <td>
                                            @if ($item->otorisasi_penarikan == 'setuju')
                                                <span class="badge rounded-pill alert-success">Disetujui</span>
                                            @elseif ($item->otorisasi_penarikan == 'pending')
                                                <span class="badge rounded-pill alert-warning">Menunggu Persetujuan</span>
                                            @else
                                                <span class="badge rounded-pill alert-danger">Ditolak</span>
                                            @endif
                                        </td>
                                        <td><b>{{ $item->kode_user }}</b></td>
                                        <td><b>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->translatedFormat('d F Y') }}</b></td>
                                        <td>
                                            <a href="{{ route('teller.informasi.nasabah-penarikan',$item->id_nasabah) }}"  class="btn btn-sm font-sm btn-light rounded"> <i class="material-icons md-assignment"></i> Detail </a>
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
                </div>
            </div>
        </div>

    </section>
    @endsection
</x-app-layout>
