<x-app-layout>
    @push('css')
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">

    @endpush
    @push('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $('#id_akun').select2({
            placeholder: "Pilih Rekening"
        });
    </script>
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
                            <h4>Edit {{ ucwords(str_replace('-',' ',Request::segment(4))) }}</h4>

                        </div>

                    </header>
                    <div class="card-body">
                        <form action="{{ route('suku-bunga-koperasi.update',$data->id) }}" method="POST">
                            @method('put')
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Kode Akun</label>
                                        <select name="id_akun" id="id_akun" class="form-control">
                                            @foreach ($kode as $item)
                                                <option value="{{ $item->id }}" {{ old('id_akun',$data->id_akun) == $item->id ? 'selected' : '' }}>{{ $item->kode_akun }} -- {{ $item->nama_akun }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_induk')
                                            <small class="text-danger">
                                                {{$message}}.
                                            </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Nama</label>
                                        <input placeholder="Masukkan nama kode" value="{{ old('nama',$data->nama) }}" type="text" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" name="nama" />
                                        @error('nama')
                                            <div class="invalid-feedback">
                                                {{$message}}.
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Suku Bunga</label>
                                        <input placeholder="Masukkan suku bunga" value="{{ old('suku',$data->suku_bunga) }}" type="text" class="form-control @error('suku') is-invalid @enderror" name="suku" />
                                        @error('suku')
                                            <div class="invalid-feedback">
                                                {{$message}}.
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Keterangan</label>
                                        <textarea name="keterangan" class="form-control" id="" cols="30" rows="10" name="keterangan">{{ old('keterangan',$data->keterangan) }}</textarea>
                                        @error('keterangan')
                                            <div class="invalid-feedback">
                                                {{$message}}.
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <label for="product_name" class="form-label">Jenis </label>
                                    <select name="jenis" id="" class="form-control">
                                        <option value="pinjaman" {{ $data->jenis == 'pinjaman' ? 'selected' : '' }}>Pinjaman</option>
                                        <option value="tabungan" {{ $data->jenis == 'tabungan' ? 'selected' : '' }}>Tabungan</option>
                                    </select>
                                    @error('jenis')
                                        <div class="invalid-feedback">
                                            {{$message}}.
                                        </div>
                                    @enderror
                                </div>
                            </div>
                    </div>

                </div>
            </div>
        </div>
         <div class="d-flex justify-content-end mb-5">
            <button type="reset" class="btn btn-outline-danger">Batal</button>
            <button type="submit" class="btn btn-primary mx-2">Simpan</button>
            </form>

        </div>
        <!-- card end// -->
    </section>
    @endsection
</x-app-layout>
