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
                        format: 'Y-MM-DD'
                    }
            });

        });

    </script>
    <script>
        $(document).ready(function() {
            var saldo_awal = document.getElementById("saldo_awal");
            saldo_awal.value = formatRupiah(saldo_awal.value);
            saldo_awal.addEventListener("keyup", function(e) {
                saldo_awal.value = formatRupiah(this.value);
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
            <div class="col-md-12">
                <div class="card mb-4">
                    <header class="card-header">
                        <div class="d-flex justify-content-between">
                            <h4>Edit {{ ucwords(str_replace('-',' ',Request::segment(4))) }}</h4>

                        </div>

                    </header>
                    <div class="card-body">
                        <form action="{{ route('setor-tunai.update',$data->id) }}" method="POST">
                        @method('put')
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">Kode Rekening</label>
                                    <input placeholder="Masukkan kode rekening" disabled value="{{ old('id_nasabah',$data->no_rekening) }}" type="text"  class="form-control @error('id_nasabah') is-invalid @enderror" name="id_nasabah" />
                                    @error('id_nasabah')
                                        <small class="text-danger">
                                            {{$message}}.
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">Kode Setoran</label>
                                    <input placeholder="Masukkan kode setoran" disabled value="{{ old('kode_setoran',$data->kode_setoran) }}" type="text"  class="form-control @error('kode_setoran') is-invalid @enderror" name="kode_setoran" />
                                    @error('kode_setoran')
                                        <div class="invalid-feedback">
                                            {{$message}}.
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">Tanggal</label>
                                    <input placeholder="Tanggal" type="text"  value="{{ old('tgl') }}" class="form-control @error('tgl',$data->tgl_setor) is-invalid @enderror" name="tgl"/>
                                    @error('tgl')
                                        <div class="invalid-feedback">
                                            {{$message}}.
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">Nominal Setor</label>
                                    <input placeholder="Masukkan nominal setor" value="{{ old('nominal_setor') }}" type="text" class="form-control @error('nominal_setor') is-invalid @enderror" name="nominal_setor" id="saldo_awal" />
                                    @error('nominal_setor')
                                        <div class="invalid-feedback">
                                            {{$message}}.
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="mb-4">
                                <label for="product_name" class="form-label">Keterangan</label>
                                <textarea name="ket" id="" cols="30" rows="10" class="form-control @error('ket') is-invalid @enderror">{{ $data->validasi }}</textarea>
                                @error('ket')
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
