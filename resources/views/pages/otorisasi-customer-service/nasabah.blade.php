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
    <script>
        $(document).ready(function() {
            var id;
            $('.gantiStatus').on('click',function() {
                id = $(this).data('id');
                $('#id').val(id);
                $.ajax({
                    url:`{{ route('otorisasi.get.nasabah') }}`,
                    type: 'GET',
                    data:{
                        id:id
                    },
                    success: function(data) {
                        $.each(data, function (key, value) {
                            console.log(value);

                            $('#no_anggota').val(value.no_anggota);
                            $('#nama').val(value.nama)
                            if (value.status == 'aktif') {
                                $('#status_aktif').prop('checked',true);
                                $('#status_aktif').attr('checked', 'checked')
                            } else if(value.status == 'non-aktif'){
                                $('#status_non_aktif').prop('checked',true);
                                $('#status_non_aktif').attr('checked', 'checked')
                            }
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

        <div class="card mb-4">
            <header class="card-header">
                <h4>List Anggota</h4>
            </header>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="example">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th scope="col">NIK</th>
                                <th scope="col">Nama Anggota</th>
                                <th scope="col">Jenis Kelamin</th>
                                <th scope="col">Pekerjaan</th>
                                <th scope="col">Alamat</th>
                                <th scope="col">Status</th>
                                <th scope="col">Alasan</th>
                                <th scope="col">Tanggal</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nik }}</td>
                                    <td>
                                        {{ $item->nama }} <br>
                                    </td>
                                    <td><b>
                                        @if ($item->jenis_kelamin == '0')
                                            Laki-Laki
                                        @else
                                            Perempuan
                                        @endif
                                        </b></td>
                                    <td>
                                        {{ $item->pekerjaan }} <br>
                                    </td>
                                    <td><b>{{ $item->alamat }}</b></td>
                                    <td>
                                        @if ($item->status == 'aktif')
                                            <span class="badge rounded-pill alert-success">Aktif</span>
                                        @else
                                            <span class="badge rounded-pill alert-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                    <td>{{ $item->ket_status != null ? $item->ket_status : '-' }}</td>
                                    <td><b>{{ \Carbon\Carbon::parse($item->tgl)->translatedFormat('d F Y') }}</b></td>
                                    <td>
                                        <div>
                                            <button class="btn btn-sm font-sm rounded btn-brand gantiStatus" data-id="{{ $item->id }}" data-bs-toggle="modal" data-bs-target="#gantiStatus"> <i class="material-icons md-edit"></i> Ganti Status </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td>Tidak ada data</td>
                                </tr>
                            @endforelse

                        </tbody>
                    </table>
                </div>
                <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
            </div>
        </div>
        <!-- card end// -->

        {{-- ganti status  --}}
        <div class="modal fade" id="gantiStatus" tabindex="-1" aria-labelledby="gantiStatusLabel" aria-hidden="true">
            <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel" hidden>Ganti Status Anggota</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('otorisasi.post.nasabah') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <input type="text" name="id" id="id" hidden>
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">No Anggota</label>
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
                                    <label for="product_name" class="form-label">Alasan</label>
                                    <textarea name="ket_status" id="ket_status" cols="30" rows="10" class="form-control" placeholder="Masukkan alasan otorisasi"></textarea>
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
                                    <input class="form-check-input" id="status_aktif" name="status" value="aktif" {{ old('status') == 'aktif' ? "checked" : '' }} type="radio">
                                    <span class="form-check-label"> Aktif </span>
                                </label>
                                <label class="mb-2 form-check form-check-inline" style="width: 45%;">
                                    <input class="form-check-input" id="status_non_aktif" name="status" value="non-aktif" {{ old('status') == 'non-aktif' ? "checked" : '' }} type="radio">
                                    <span class="form-check-label"> Non-aktif </span>
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
