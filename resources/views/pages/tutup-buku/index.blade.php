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
            .bg-secondary{
                padding: 20px;
                font-size: 14px;
                background-color: #c3c0c031 !important;
                color: #000;
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
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $('#id_akun').select2({
            placeholder: "Pilih Rekening"
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
                    <h4>{{ ucwords(str_replace('-',' ',Request::segment(3))) }}</h4>
                </header>
                <div class="card-body">
                    <form action="{{ route('tutup-cabang.post') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">

                                </div>
                                <div class="form-check form-switch" style="">
                                    <input class="form-check-input mx-2" style="height: 2rem;
                                    width: 3.5rem; " name="tutup_cabang" type="checkbox" role="switch" id="flexSwitchCheckChecked" {{ $data->status == 'buka' ? 'checked' : '' }}>
                                </div>
                                <label class="form-check-label mt-2" for="flexSwitchCheckChecked">Pindahin untuk tutup cabang</label>
                            </div>
                            <div class="col-md-6 ">
                                @php
                                    $lastUpdatedTime = \Carbon\Carbon::parse($data->updated_at);
                                    $timeAgo = $lastUpdatedTime->diffForHumans();
                                @endphp
                                <div class="d-flex justify-content-end">
                                    <span class="badge bg-secondary">Last update : {{ $timeAgo }}</span>
                                </div>
                            </div>
                        </div>

                </div>
                <hr>
                <div class="card-footer">
                    <div class="d-flex justify-content-end">
                        <button type="reset" class="btn btn-outline-danger">Batal</button>
                        <button type="submit" class="btn btn-primary mx-2">Simpan</button>
                    </form>

                    </div>
                </div>
            </div>

        </div>
        <!-- card end// -->
    </section>
    @endsection
</x-app-layout>
