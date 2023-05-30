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
            function formatRupiah(angka, prefix) {
                var number_string = angka.replace(/[^,\d]/g, "").toString(),
                    split = number_string.split(","),
                    sisa = split[0].length % 3,
                    rupiah = split[0].substr(0, sisa),
                    ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                // tambahkan titik jika yang di input sudah menjadi angka ribuan
                if (ribuan) {
                    separator = sisa ? "." : "";
                    rupiah += separator + ribuan.join(".");
                }

                rupiah = split[1] != undefined ? rupiah + "," + split[1] : rupiah;
                return prefix == undefined ? rupiah : rupiah ? rupiah : "";
            }
            $('#example').DataTable();
            $('.gantiStatus').on('click',function() {
                var id = $(this).data('id');
                $('#id').val(id);
                console.log(id);
                $.ajax({
                    url:`{{ route('otorisasi.get.rekening') }}`,
                    type: 'GET',
                    data:{
                        id:id
                    },
                    success: function(data) {
                        $.each(data, function (key, value) {
                            console.log(value);
                            $('#no_anggota').val(value.nik);
                            $('#id_nasabah').val(value.id_rekening_tabungan);
                            $('#nama').val(value.nama)
                            var total_saldo = document.getElementById("total_penarikan");
                            total_saldo.value = formatRupiah(value.nominal_setor);
                        })
                    }
                })
            })
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
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Otorisasi Rekening</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Otorisasi Pinjaman</button>
                    </li>
                </ul>
            </div>
        </div>
        <div class="card mb-4">
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <header class="card-header">
                        <h4>List Nasabah</h4>
                    </header>
                    <div class="card-body">
                        <div class="table-responsive">
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
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $item)
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
                                                    <span class="badge rounded-pill alert-warning gantiStatus" data-id="{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#gantiStatus">Menunggu Persetujuan</span>
                                                @else
                                                    <span class="badge rounded-pill alert-danger">Ditolak</span>
                                                @endif
                                            </td>
                                            <td><b>{{ $item->kode_user }}</b></td>
                                            <td><b>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->translatedFormat('d F Y') }}</b></td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">Tidak ada data</td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                        <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                    </div>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="home-tab">
                </div>
            </div>
        </div>
        <!-- card end// -->
        <div class="modal fade" id="gantiStatus" tabindex="-1" aria-labelledby="gantiStatusLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" hidden>Ganti Status Rekening</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('otorisasi.post.rekening') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" name="id" id="id" hidden>
                                <input type="text" name="id_nasabah" id="id_nasabah" hidden>
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">NIK Anggota</label>
                                    <input placeholder="No Anggota" value="" readonly type="text" value="{{ old('no_anggota') }}" class="form-control @error('no_anggota') is-invalid @enderror" name="no_anggota" id="no_anggota" />
                                    @error('no_anggota')
                                        <div class="invalid-feedback">
                                            {{$message}}.
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">Nama Anggota</label>
                                    <input placeholder="Masukkan Nama Anggota" readonly type="text" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" name="nama" id="nama" />
                                    @error('nama')
                                        <div class="invalid-feedback">
                                            {{$message}}.
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">Total Penarikan</label>
                                    <input type="text" readonly name="total_penarikan" id="total_penarikan" class="form-control">
                                    @error('nama')
                                        <div class="invalid-feedback">
                                            {{$message}}.
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <label for="product_name" class="form-label">Status</label>
                                <label class="mb-2 form-check form-check-inline" style="width: 45%;">
                                    <input class="form-check-input" id="status_aktif" name="status" checked value="setuju" {{ old('status') == 'setuju' ? "checked" : '' }} type="radio">
                                    <span class="form-check-label"> Setuju </span>
                                </label>
                                <label class="mb-2 form-check form-check-inline" style="width: 45%;">
                                    <input class="form-check-input" id="status_non_aktif" name="status" value="ditolak" {{ old('status') == 'ditolak' ? "checked" : '' }} type="radio">
                                    <span class="form-check-label"> Ditolak </span>
                                </label>
                                @error('status')
                                    <div class="invalid-feedback">
                                        {{$message}}.
                                    </div>
                                @enderror
                            </div>

                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button class="btn btn-primary" type="submit">Simpan</button>

                    </form>
                </div>
            </div>
            </div>
        </div>
    </section>
    @endsection
</x-app-layout>
