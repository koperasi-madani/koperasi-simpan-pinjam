<x-app-layout>
    @push('css')
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">
    @endpush
    @push('js')
        <script>

        </script>
    @endpush
    @section('content')
    <section class="content-main mb-5">
        <div class="content-header ">
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
                            <h4>Tambah {{ ucwords(str_replace('-',' ',Request::segment(3))) }}</h4>
                            <div>
                            </div>
                        </div>

                    </header>
                    <div class="card-body">
                            <form action="{{ route('kode-induk.update',$data->id) }}" method="POST">
                            @method('put')
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Kode Ledger</label>
                                        <select name="id_ledger" id="id_ledger" class="form-control">
                                            @foreach ($kode as $item)
                                                <option value="{{ $item->id }}" {{ old('id_ledger',$data->id_ledger) == $item->id ? 'selected' : '' }}>{{ $item->kode_ledger }} -- {{ $item->nama }}</option>
                                            @endforeach
                                        </select>
                                        @error('id_ledger')
                                            <small class="text-danger">
                                                {{$message}}.
                                            </small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Kode Induk</label>
                                        <input placeholder="Masukkan kode induk" value="{{ old('kode_induk',$data->kode_induk) }}" type="text" value="{{ old('kode_induk') }}" class="form-control @error('kode_induk') is-invalid @enderror" name="kode_induk" />
                                        @error('kode_induk')
                                            <div class="invalid-feedback">
                                                {{$message}}.
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Jenis</label>
                                        <select name="jenis" id="jenis" class="form-control @error('jenis') is-invalid @enderror">
                                            <option value="kredit" {{ old('jenis',$data->jenis) == 'kredit' ? 'selected' : '' }}>Kredit</option>
                                            <option value="debit" {{ old('jenis',$data->jenis) == 'debit' ? 'selected' : '' }}>Debit</option>
                                        </select>
                                        @error('jenis')
                                            <div class="invalid-feedback">
                                                {{$message}}.
                                            </div>
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
                                <hr>

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
