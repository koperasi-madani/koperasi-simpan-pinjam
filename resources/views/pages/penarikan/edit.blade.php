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
            $("#pesan").hide();
            $("#nominal_penarikan").attr("readonly", false);
            // const valueWithoutCurrency = nominal_penarikan.value.replace(/[^\d.-]/g, "");
            // console.log(valueWithoutCurrency);
             /* Fungsi formatRupiah */
            var result = 0;
            result =  parseInt($('#nominal_penarikan').val()) - parseInt($('#total_saldo').val());
            $('#sisa_saldo').val(result);
            var sisa_saldo = document.getElementById("sisa_saldo");
            sisa_saldo.value = formatRupiah(sisa_saldo.value);
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
            function hasil_penarikan(total_nominal, total_saldo) {
                var result = 0;
                result =  parseInt(total_saldo) - parseInt(total_nominal);
                if (result < 20000) {
                    $("#pesan").show();
                    setTimeout(function() { $("#pesan").hide(); }, 5000);

                }else{
                    $("#pesan").hide();

                }
                $('#sisa_saldo').val(result);
                var sisa_saldo = document.getElementById("sisa_saldo");
                sisa_saldo.value = formatRupiah(sisa_saldo.value);
            }
             function hapus_uang(params) {
                const angka = params;
                const valueWithoutCurrency = angka.replace(/\./g, "").toString();
                return valueWithoutCurrency;
            }
            const test = document.getElementById('rupiah');
            test.value = hapus_uang(test.value);

            var nominal_penarikan = document.getElementById("nominal_penarikan");
            nominal_penarikan.value = formatRupiah(nominal_penarikan.value);
            nominal_penarikan.addEventListener("keyup", function(e) {
                nominal_penarikan.value = formatRupiah(this.value);
                hasil_penarikan(hapus_uang(this.value), hapus_uang($('#total_saldo').val()))
                $('#rupiah').val(hapus_uang(this.value))
            });


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
                        <form action="{{ route('penarikan.update',$data->id) }}" method="POST">
                            @method('put')
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Kode Rekening</label>
                                        <input placeholder="Masukkan kode rekening" readonly value="{{ old('id_nasabah',$data->no_rekening) }}" type="text" class="form-control @error('id_nasabah') is-invalid @enderror" name="id_nasabah" />
                                        @error('id_nasabah')
                                            <div class="invalid-feedback">
                                                {{$message}}.
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Kode Penarikan</label>
                                        <input placeholder="Masukkan kode penarikan" readonly value="{{ old('kode_penarikan',$data->kode_penarikan) }}" type="text" class="form-control @error('kode_penarikan') is-invalid @enderror" name="kode_penarikan" />
                                        @error('kode_penarikan')
                                            <div class="invalid-feedback">
                                                {{$message}}.
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Tanggal</label>
                                        <input placeholder="Tanggal" type="text"  value="{{ old('tgl',$data->tgl_setor) }}" class="form-control @error('tgl') is-invalid @enderror" name="tgl"/>
                                        @error('tgl')
                                            <div class="invalid-feedback">
                                                {{$message}}.
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Nominal Penarikan</label>
                                        <input type="text" name="" value="0" id="rupiah" hidden>
                                        <input placeholder="Masukkan nominal penarikan" value="{{ old('nominal_penarikan',$data->nominal_setor) }}" type="text"  class="form-control @error('nominal_penarikan') is-invalid @enderror" name="nominal_penarikan" id="nominal_penarikan" />
                                        @error('nominal_penarikan')
                                            <div class="invalid-feedback">
                                                {{$message}}.
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Total Saldo</label>
                                        @php
                                            $current_saldo  = $data->saldo + (int)$data->nominal_setor;
                                        @endphp
                                        <input placeholder="Masukkan nominal penarikan" readonly value="{{ old('total_saldo',$current_saldo) }}" type="text"  class="form-control @error('total_saldo') is-invalid @enderror" name="total_saldo" id="total_saldo" />
                                        @error('total_saldo')
                                            <div class="invalid-feedback">
                                                {{$message}}.
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-4">
                                        <label for="product_name" class="form-label">Sisa Saldo</label>
                                        <input placeholder="0" readonly value="{{ old('sisa_saldo',) }}" type="text"  class="form-control @error('sisa_saldo') is-invalid @enderror" name="sisa_saldo" id="sisa_saldo" />
                                        <div id="pesan">
                                            <small class="text-danger">Maaf saldo anda tidak mencukupi</small>
                                        </div>
                                        @error('sisa_saldo')
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
