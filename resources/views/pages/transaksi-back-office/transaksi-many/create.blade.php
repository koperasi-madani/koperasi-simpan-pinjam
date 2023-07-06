<x-app-layout>
    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
        <style>
            .page-item.active .page-link{
                background-color: #219ebc !important;
                border-color: #8ecae6;
            }
            .active-ket{
                display: none;
            }
        </style>
    @endpush
    @push('js')
    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#example').DataTable();
        })
    </script>
    <script>
         $(document).ready(function() {
            // Menambahkan form dinamis ketika tombol "Add Form" ditekan
            $('#addBtn').click(function() {
                var formRow = `
                    <div class="row form-row my-3">
                        <div class="form-group col-md-4">
                            <label for="obat">Lawan</label>
                            <select class="form-control akun-select" name="akun_lawan[]" required>
                                <option value="">Pilih Obat</option>
                            </select>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="harga">Nominal</label>
                            <input type="number" class="form-control harga-input" placeholder="Masukkan Nominal" name="nominal[]" required>
                        </div>
                        <div class="form-group col-md-3">
                            <label for="ket">Keterangan</label>
                            <input type="text" class="form-control ket-input" placeholder="Masukkan Keterangan" name="ket[]">
                        </div>
                        <div class="form-group col-md-2">
                            <button type="button" class="btn btn-danger remove-btn">Hapus</button>
                        </div>
                    </div>
                `;

                $('#formContainer').append(formRow);

                // Mendapatkan data obat dan mengisi dropdown di form baru
                $.ajax({
                    url: "{{ route('transaksi.kodeAkun') }}",
                    type: "GET",
                    dataType: "json",
                    success: function(response) {
                        var obatSelect = $('.akun-select:last');
                        obatSelect.empty();
                        obatSelect.append('<option value="">Pilih Akun</option>');

                        $.each(response, function(key, value) {
                            obatSelect.append('<option value="' + value.id + '">' + value.nama_akun + '</option>');
                        });
                    }
                });
            });

            // Menghapus form ketika tombol "Remove" ditekan
            $(document).on('click', '.remove-btn', function() {
                $(this).closest('.form-row').remove();
                calculateTotal();
            });

            $(document).on('keyup','.harga-input',function() {
                calculateTotal();
            })


            // Fungsi untuk menghitung total
            function calculateTotal() {
                var total = 0;
                $('.form-row').each(function() {
                    var nominal = parseInt($(this).find('.harga-input').val());
                    if (nominal) {
                        total += nominal
                    }
                });

                $('#total').text(new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(total));
                $('#total_input').val(total);
            }
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
                        <form action="{{ route('transaksi-many-to-many.store') }}" class="mt-4" method="POST">
                        @csrf
                        <div class="row form-row my-3">
                            <div class="form-group col-md-4">
                                <label for="harga">Tanggal</label>
                                <input type="date" class="form-control @error('tanggal') is-invalid @enderror" name="tanggal">
                                @error('tanggal')
                                    <div class="invalid-feedback">
                                        {{$message}}.
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="harga">Tipe</label>
                                <select name="tipe" id="" class="form-control @error('tipe') is-invalid @enderror">
                                    <option value="0"> --Pilih--</option>
                                    <option value="Masuk" {{old('tipe') == 'Masuk' ? 'selected' : ''}}>Masuk</option>
                                    <option value="Keluar" {{old('tipe') == 'Keluar' ? 'selected' : ''}}>Keluar</option>
                                </select>
                                @error('tipe')
                                    <div class="invalid-feedback">
                                        {{$message}}.
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-4">
                                <label for="obat">Lawan</label>
                                <select class="form-control @error('kode_akun') is-invalid @enderror kode_akun" name="kode_akun">
                                    <option value="">Pilih Akun</option>
                                    @foreach($KodeAkun as $item)
                                        <option value="{{ $item->id }}">{{ $item->nama_akun }}</option>
                                    @endforeach
                                </select>
                                @error('kode_akun')
                                    <div class="invalid-feedback">
                                        {{$message}}.
                                    </div>
                                @enderror
                            </div>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <div class="my-4">
                                <h4>Detail Transaksi</h4>
                            </div>
                            <div>
                                <div class="form-group my-4">
                                    <button type="button" class="btn btn-primary " id="addBtn">Tambah </button>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="card card-body">
                            <div id="formContainer">
                                <div class="row form-row my-3">
                                    <div class="form-group col-md-4">
                                        <label for="obat">Lawan</label>
                                        <select class="form-control akun-select" name="akun_lawan[]" required>
                                            <option value="">Pilih Akun</option>
                                            @foreach($KodeAkun as $item)
                                                <option value="{{ $item->id }}">{{ $item->nama_akun }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="harga">Nominal</label>
                                        <input type="number" class="form-control harga-input" placeholder="Masukkan Nominal" name="nominal[]" required>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="ket">Keterangan</label>
                                        <input type="text" class="form-control ket-input" placeholder="Masukkan Keterangan" name="ket[]">
                                    </div>
                                    <div class="form-group col-md-2">
                                        <button type="button" class="btn btn-danger remove-btn">Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row test mt-4">
                            {{-- <input type="text" name="kode_transaksi" id="" value="{{ $data->kode_transaksi }}" hidden> --}}
                            <div class="col-md-12">
                                <div class="form-group">
                                    <h4 class='text-right mt-1 pr-5' style="font-weight: bold">Total : <span id='total' class="text-info" style="font-weight: bold">0</span></h4>
                                    <input type="number" class="form-control" name="total" id="total_input" readonly hidden>
                                </div>
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
