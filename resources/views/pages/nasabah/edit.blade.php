<x-app-layout>
    @push('css')
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">

    @endpush
    @push('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script>
        $(function() {
            $('input[name="tgl"]').daterangepicker({
                singleDatePicker: true,
                showDropdowns: false,
                timePicker: false,
                startDate: moment().startOf('hour'),
                locale: {
                        format: 'YY-MM-DD'
                    }
            });

        });

    </script>
    <script>
        $(document).ready(function() {
            var simpanan_pokok = document.getElementById("simpanan_pokok");
            simpanan_pokok.value = formatRupiah(simpanan_pokok.value);
            simpanan_pokok.addEventListener("keyup", function(e) {
                simpanan_pokok.value = formatRupiah(this.value);
            });
            var simpanan_wajib = document.getElementById("simpanan_wajib");
            simpanan_wajib.value = formatRupiah(simpanan_wajib.value);
            simpanan_wajib.addEventListener("keyup", function(e) {
                simpanan_wajib.value = formatRupiah(this.value);
            });
            var simpanan_sukarela = document.getElementById("simpanan_sukarela");
            simpanan_sukarela.value = formatRupiah(simpanan_sukarela.value);
            simpanan_sukarela.addEventListener("keyup", function(e) {
                simpanan_sukarela.value = formatRupiah(this.value);
            });
            /* Fungsi formatRupiah */
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
        })
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
            <div class="col-md-8">
                <div class="card mb-4">
                    <header class="card-header">
                        <div class="d-flex justify-content-between">
                            <h4>Edit Data Nasabah</h4>
                            <div>
                            </div>
                        </div>

                    </header>
                    <div class="card-body">
                            <form action="{{ route('nasabah.update',$data->id) }}" method="POST">
                            @method('put')
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Tanggal</label>
                                        <input placeholder="Tanggal" type="text" readonly  value="{{ old('tgl') }}" class="form-control @error('tgl') is-invalid @enderror" name="tgl" />
                                        @error('periode')
                                            <div class="invalid-feedback">
                                                {{$message}}.
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">No Anggota</label>
                                        <input placeholder="No Anggota" readonly value="{{ $data->no_anggota }}" type="text" value="{{ old('no_anggota') }}" class="form-control @error('no_anggota') is-invalid @enderror" name="no_anggota" />
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
                                        <input placeholder="Masukkan Nama Anggota" type="text" value="{{ old('nama',$data->nama) }}" class="form-control @error('nama') is-invalid @enderror" name="nama" />
                                        @error('nama')
                                            <div class="invalid-feedback">
                                                {{$message}}.
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">NIK Anggota</label>
                                        <input placeholder="Masukkan NIK Anggota" type="text" value="{{ old('nik',$data->nik) }}" class="form-control @error('nik') is-invalid @enderror" name="nik" />
                                        @error('nik')
                                            <div class="invalid-feedback">
                                                {{$message}}.
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">No HP</label>
                                        <input placeholder="Masukkan No HP" type="text" value="{{ old('no_hp',$data->no_hp) }}" class="form-control @error('no_hp') is-invalid @enderror" name="no_hp" />
                                        @error('no_hp')
                                            <div class="invalid-feedback">
                                                {{$message}}.
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="product_name" class="form-label">Alamat</label>
                                <textarea name="ket" id="" cols="30" rows="10" class="form-control @error('ket',$data->alamat) is-invalid @enderror">{{ $data->alamat }}</textarea>
                                @error('ket')
                                    <div class="invalid-feedback">
                                        {{$message}}.
                                    </div>
                                @enderror
                            </div>

                    </div>

                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <header class="card-header">
                        <div class="d-flex justify-content-between">
                            <h4>Simpanan Data Nasabah</h4>
                            <div>
                            </div>
                        </div>

                    </header>
                    <div class="card-body">
                        <div class="mb-4">
                            <label for="product_name" class="form-label">Simpanan Pokok</label>
                            <input type="text" id="simpanan_pokok" value="{{ old('simpanan_pokok',$data->sim_pokok) }}" placeholder="Masukkan simpanan pokok" class="form-control @error('simpanan_pokok') is-invalid @enderror" name="simpanan_pokok"/>
                            @error('simpanan_pokok')
                                <div class="invalid-feedback">
                                    {{$message}}.
                                </div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="product_name" class="form-label">Simpanan Wajib</label>
                            <input type="text" id="simpanan_wajib" value="{{ old('simpanan_wajib',$data->sim_wajib) }}" placeholder="Masukkan simpanan wajib" class="form-control @error('simpanan_wajib') is-invalid @enderror" name="simpanan_wajib"/>
                            @error('simpanan_wajib')
                                <div class="invalid-feedback">
                                    {{$message}}.
                                </div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="product_name" class="form-label">Simpanan Sukarela</label>
                            <input type="text" id="simpanan_sukarela"  value="{{ old('simpanan_sukarela',$data->sim_sukarela) }}" placeholder="Masukkan simpanan sukarela" class="form-control @error('simpanan_sukarela') is-invalid @enderror" name="simpanan_sukarela"/>
                            @error('simpanan_sukarela')
                                <div class="invalid-feedback">
                                    {{$message}}.
                                </div>
                            @enderror
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
