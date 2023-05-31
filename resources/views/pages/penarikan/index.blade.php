<x-app-layout>
    @push('css')
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.2.0/css/datepicker.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
        <style>
            .btn-info{
                background-color: #264653;
                border: none;
            }
            .page-item.active .page-link{
                background-color: #219ebc !important;
                border-color: #8ecae6;
            }
        </style>
        <style>
            .select2-container--default .select2-selection--single {
                border-radius: 0.35rem !important;
                border: 1px solid #d1d3e2;
                height: calc(1.95rem + 5px);
                background: #fff;
            }

            .select2-container--default .select2-selection--single:hover,
            .select2-container--default .select2-selection--single:focus,
            .select2-container--default .select2-selection--single.active {
                box-shadow: none;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 32px;

            }

            .select2-container--default .select2-selection--multiple {
                border-color: #eaeaea;
                border-radius: 0;
            }

            .select2-dropdown {
                border-radius: 0;
            }

            .select2-container--default .select2-results__option--highlighted[aria-selected] {
                /* background-color: #3838eb; */
            }

            .select2-container--default.select2-container--focus .select2-selection--multiple {
                border-color: #eaeaea;
                background: #fff;

            }
        </style>
    @endpush
    @push('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#pesan").hide();
            $("#nominal_penarikan").attr("readonly", true);
            // const valueWithoutCurrency = nominal_penarikan.value.replace(/[^\d.-]/g, "");
            // console.log(valueWithoutCurrency);
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



            function onChangeSelect(url, id) {
            // send ajax request to get the cities of the selected province and append to the select tag
                $.ajax({
                        url: url,
                        type: 'GET',
                        data: {
                            id: id
                        },
                        success: function (data) {
                            console.log(data);
                            var total_saldo = document.getElementById("total_saldo");
                            total_saldo.value = formatRupiah(data);
                            total_saldo.addEventListener("keyup", function(e) {
                            total_saldo.value = formatRupiah(this.value);
                            })
                        }
                });
            }
            $('#id_nasabah').select2({
                placeholder: "Pilih Rekening"
            });
            $('#id_nasabah').on('change',function() {
                $("#nominal_penarikan").attr("readonly", false);
                $("#nominal_penarikan").attr('autofocus', 'true');
                onChangeSelect('{{ route('cek.tabungan') }}',$(this).val())
            })
            $('#example').DataTable();
        })

    </script>

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
    @endpush
    @section('content')
    <section class="content-main">
        <div class="content-header">
            <h2 class="content-title">{{ ucwords(str_replace('-',' ',Request::segment(3))) }}</h2>

        </div>
        @include('components.notification')
        <div class="row">
            <div class="card">
                <header class="card-header">
                    <h4>Tambah {{ ucwords(str_replace('-',' ',Request::segment(4))) }}</h4>
                </header>
                <div class="card-body">
                    <form action="{{ route('penarikan.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">Kode Rekening</label>
                                    <select name="id_nasabah" id="id_nasabah" class="form-control">
                                        <option value="">Pilik Rekening</option>
                                        @foreach ($data as $item)
                                            <option value="{{ $item->id }}" {{ old('id_nasabah') == $item->id ? 'selected' : '' }}>{{ $item->no_rekening }}--{{ $item->nama }}</option>
                                        @endforeach
                                    </select>
                                    @error('id_nasabah')
                                        <small class="text-danger">
                                            {{$message}}.
                                        </small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-4">
                                    <label for="product_name" class="form-label">Kode Penarikan</label>
                                    <input placeholder="Masukkan kode penarikan" value="{{ old('kode_penarikan',$noPenarikan) }}" type="text" class="form-control @error('kode_penarikan') is-invalid @enderror" name="kode_penarikan" />
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
                                    <input placeholder="Tanggal" type="text"  value="{{ old('tgl') }}" class="form-control @error('tgl') is-invalid @enderror" name="tgl"/>
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
                                    <input placeholder="Masukkan nominal penarikan" value="{{ old('nominal_penarikan') }}" type="text"  class="form-control @error('nominal_penarikan') is-invalid @enderror" name="nominal_penarikan" id="nominal_penarikan" />
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
                                    <input placeholder="Masukkan nominal penarikan" readonly value="{{ old('total_saldo',) }}" type="text"  class="form-control @error('total_saldo') is-invalid @enderror" name="total_saldo" id="total_saldo" />
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
                                <textarea name="ket" id="" cols="30" rows="10" class="form-control @error('ket') is-invalid @enderror"></textarea>
                                @error('ket')
                                    <div class="invalid-feedback">
                                        {{$message}}.
                                    </div>
                                @enderror
                            </div>
                        </div>
                </div>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        <button type="reset" class="btn btn-outline-danger">Batal</button>
                        <button type="submit" class="btn btn-primary mx-2">Simpan</button>
                    </form>

                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="card mb-4">
                    <header class="card-header">
                        <h4>List {{ ucwords(str_replace('-',' ',Request::segment(4))) }}</h4>
                    </header>
                    <div class="card-body">
                        <div class="">
                            <table class="table table-hover" id="example">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th scope="col">Nama Nasabah</th>
                                        <th scope="col">No Rekening</th>
                                        <th scope="col">Nominal Penarikan </th>
                                        <th scope="col">Tanggal</th>
                                        <th scope="col">Keterangan</th>
                                        <th scope="col">Status Otorisasi</th>
                                        <th scope="col">Validasi</th>
                                        <th scope="col" class="text-start">Action</th>
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
                                            <td><b>Rp. {{ number_format($item->nominal,2, ",", ".") }}</b></td>
                                            <td><b>{{ \Carbon\Carbon::parse($item->tgl_transaksi)->translatedFormat('d F Y') }}</b></td>
                                            <td><b>{{ $item->kode_user }}</b></td>
                                            <td>
                                                @if ($item->status == 'setuju')
                                                    <span class="badge rounded-pill alert-success">Disetujui</span>
                                                @elseif ($item->status == 'pending')
                                                    <span class="badge rounded-pill alert-warning">Menunggu Persetujuan</span>
                                                @else
                                                    <span class="badge rounded-pill alert-danger">Ditolak</span>
                                                @endif
                                            </td>
                                            <td><b>{{ $item->kode_user }}</b></td>
                                            <td class="text-start">
                                                <div class="d-flex justify-content-start">
                                                    <div class="mx-2">
                                                        <a href="{{ route('penarikan.edit',$item->id) }}" class="btn btn-sm font-sm rounded btn-brand"> <i class="material-icons md-edit"></i> Edit </a>
                                                    </div>
                                                    <form action="{{ route('penarikan.destroy',$item->id) }}" class="p-0 m-0" method="POST" onsubmit="return confirm('Move data to trash? ')">
                                                        @method('delete')
                                                        @csrf
                                                        <button  class="btn btn-sm font-sm btn-light rounded"> <i class="material-icons md-delete_forever"></i> Delete </button>
                                                    </form>

                                                </div>
                                                <!-- dropdown //end -->
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
                        <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
                    </div>
                </div>
            </div>
        </div>
        <!-- card end// -->
    </section>
    @endsection
</x-app-layout>

