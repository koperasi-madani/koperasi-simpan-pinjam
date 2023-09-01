<x-app-layout>
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <style>
            .page-item.active .page-link{
                background-color: #219ebc !important;
                border-color: #8ecae6;
            }
            .active-ket{
                display: none;
            }
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
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#example').DataTable();
        })
    </script>
    <script>
        $('#kodeakun').select2({
            placeholder: "Pilih Akun",
            allowClear: false,
        });

        $(document).on('change','#kodeakun',function(e) {
            // var obatId = $(this).val();
            // var hargaInput =
            $.ajax({
                url: `{{ route('get.akun') }}`,
                type: 'GET',
                data: {
                    id: $(this).val()
                },
                success: function (data) {
                    $('.nama-akun').val(data.nama_akun);

                }
            });
        });

        // ini kode nasabah
        function selectRefresh() {
            $('.formContainerTest .akun-nasabah').select2();
        }
        $(document).ready(function() {
            selectRefresh()
            $('#addBtn').click(function() {
                var formRow = `
                    <div class="row form-row my-3">
                        <div class="form-group col-md-4 mb-3">
                            <label for="obat">Kode Rekening</label>
                            <select class="form-control akun-nasabah" name="akun_nasabah[]" required>
                                <option value="0">Pilih Rekening</option>
                                @foreach($kodeRekening as $item)
                                    <option value="{{ $item->nasabah_id }}">{{ $item->no_rekening }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="harga">Nama Akun</label>
                            <input type="text" class="form-control nama-nasabah" placeholder="Nama Akun" name="nama_akun[]" readonly id="nama-nasabah" >
                        </div>
                        <div class="form-group col-md-4">
                            <label for="harga">Tipe</label>
                            <select name="tipe[]" id="" class="form-control @error('tipe') is-invalid @enderror" required>
                                <option value="Masuk" {{old('tipe') == 'Masuk' ? 'selected' : ''}}>Debet</option>
                                <option value="Keluar" {{old('tipe') == 'Keluar' ? 'selected' : ''}}>Kredit</option>
                            </select>
                            @error('tipe')
                                <div class="invalid-feedback">
                                    {{$message}}.
                                </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-5">
                            <label for="harga">Nominal</label>
                            <input type="text" class="form-control harga-input" placeholder="Masukkan Nominal" name="nominal[]" required>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="ket">Keterangan</label>
                            <input type="text" class="form-control ket-input" placeholder="Masukkan Keterangan" name="ket[]" required>
                        </div>
                        <div class="form-group col-md-2 my-3">
                            <button type="button" class="btn btn-danger remove-btn text-center px-5">Hapus</button>
                        </div>
                        <hr>
                    </div>
                `;
                $('.formContainerTest').append(formRow);
                selectRefresh()

            });

            $(document).on('change','.formContainerTest .akun-nasabah',function(e) {
                var obatId = $(this).val();
                console.log(obatId);
                var hargaInput = $(this).closest('.form-row').find('.nama-nasabah');
                $.ajax({
                    url: `{{ route('get.rekening') }}`,
                    type: 'GET',
                    data: {
                        id: $(this).val()
                    },
                    success: function (data) {
                        hargaInput.val(data.nama);

                    }
                });
            });
            $(document).on('click', '.remove-btn', function() {
                $(this).closest('.form-row').remove();
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
        <div class="card mb-4">
            <div class="card-body">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('transaksi.pemindah.store') }}" class="mt-4" method="POST">
                        @csrf
                        <div class="row form-row my-3">
                            <div class="form-group col-md-6 mb-3">
                                <label for="harga">Tanggal</label>
                                <input type="text" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal" value="{{ Carbon\Carbon::now() }}" readonly>
                                @error('tanggal')
                                    <div class="invalid-feedback">
                                        {{$message}}.
                                    </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6 mb-3">
                                <label for="obat">Kode Transaksi</label>
                                <input type="text" name="kode" id="" value="{{ $kode }}" readonly class="form-control">
                            </div>

                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <div class="my-4">
                                <h4>Transaksi</h4>
                            </div>
                        </div>
                        <hr>
                        <div class="card card-body">
                            <div class="d-flex justify-content-end">
                                <div>
                                    <div class="form-group my-4">
                                        <button type="button" class="btn btn-primary " id="addBtn">Tambah </button>
                                    </div>
                                </div>
                            </div>
                            {{-- ini kode akun  --}}
                            <div class="row form-row my-3">
                                <div class="form-group col-md-4 mb-3">
                                    <label for="obat">Kode Akun</label>
                                    <select class="form-control" name="kode_akun[]" required id="kodeakun">
                                        @foreach($KodeAkun as $item)
                                            <option value="{{ $item->id }}">{{ $item->kode_akun }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="harga">Nama Akun</label>
                                    <input type="text" class="form-control nama-akun" placeholder="Nama Akun" name="nama_akun" readonly id="nama-akun">
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="harga">Tipe</label>
                                    <select name="tipe_akun[]" id="" class="form-control @error('tipe') is-invalid @enderror" required>
                                        <option value="Masuk" {{old('tipe') == 'Masuk' ? 'selected' : ''}}>Debet</option>
                                        <option value="Keluar" {{old('tipe') == 'Keluar' ? 'selected' : ''}}>Kredit</option>
                                    </select>
                                    @error('tipe')
                                        <div class="invalid-feedback">
                                            {{$message}}.
                                        </div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="harga">Nominal</label>
                                    <input type="text" class="form-control harga-input" placeholder="Masukkan Nominal" name="nominal_akun[]" required>
                                </div>
                                <div class="form-group col-md-5">
                                    <label for="ket">Keterangan</label>
                                    <input type="text" class="form-control ket-input" placeholder="Masukkan Keterangan" name="ket_akun" required>
                                </div>
                                <div class="form-group col-md-2 my-3">
                                    <button type="button" class="btn btn-danger remove-btn text-center px-5" >Hapus</button>
                                </div>
                            </div>
                            <hr>
                            <div id="formContainer" class="formContainerTest">
                                <div class="row form-row my-3">
                                    <div class="form-group col-md-4 mb-3">
                                        <label for="obat">Kode Rekening</label>
                                        <select class="form-control akun-nasabah" name="akun_nasabah[]" required>
                                            <option value="0">Pilih Rekening</option>
                                            @foreach($kodeRekening as $item)
                                                <option value="{{ $item->nasabah_id }}">{{ $item->no_rekening }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="harga">Nama Nasabah</label>
                                        <input type="text" class="form-control nama-nasabah" placeholder="Nama Nasabah" name="nama_nasabah[]" readonly  id="nama-nasabah">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label for="harga">Tipe</label>
                                        <select name="tipe[]" id="" class="form-control @error('tipe') is-invalid @enderror" required>
                                            <option value="Masuk" {{old('tipe') == 'Masuk' ? 'selected' : ''}}>Debet</option>
                                            <option value="Keluar" {{old('tipe') == 'Keluar' ? 'selected' : ''}}>Kredit</option>
                                        </select>
                                        @error('tipe')
                                            <div class="invalid-feedback">
                                                {{$message}}.
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label for="harga">Nominal</label>
                                        <input type="text" class="form-control harga-input" placeholder="Masukkan Nominal" name="nominal[]" required>
                                    </div>
                                    <div class="form-group col-md-5">
                                        <label for="ket">Keterangan</label>
                                        <input type="text" class="form-control ket-input" placeholder="Masukkan Keterangan" name="ket[]" required>
                                    </div>
                                    <div class="form-group col-md-2 my-3">
                                        <button type="button" class="btn btn-danger remove-btn text-center px-5" >Hapus</button>
                                    </div>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <hr>
                        <div class="d-flex justify-content-end">
                            <div class="form-group">
                                <button type="submit" class="btn btn-success">Simpan</button>
                            </div>
                        </div>

                    </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- card end// -->
    </section>
    @endsection
</x-app-layout>
