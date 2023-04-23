<x-app-layout>
    @push('css')
        <style>
            .sticky-fa-card {
                position: absolute;
                left: 0;
                top: 0;
                font-size: 55px;
                opacity: 0.2;
                transform: translate(-20%, -20%);
                -webkit-transform: translate(-20%, -20%);
                -moz-transform: translate(-20%, -20%);
                -o-transform: translate(-20%, -20%);
            }
        </style>
    @endpush

    @section('content')
    <section class="content-main">
        <div class="content-header">
            <!-- Button trigger modal -->
            <div>
                <h2 class="content-title card-title">Dashboard</h2>
                <p>Selamat di aplikasi Koperasi Simpan Pinjam</p>
            </div>
            <div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="card card-body mb-4">
                    <article class="icontext">
                        <span class="icon icon-sm rounded-circle bg-primary-light"><i class="text-primary material-icons md-monetization_on"></i></span>
                        <div class="text">
                            <h6 class="mb-1 card-title">Total Pendapatan</h6>
                            <span>Rp. 210102</span>
                        </div>
                    </article>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-body mb-4">
                    <article class="icontext">
                        <span class="icon icon-sm rounded-circle bg-secondary-light"><i class="text-secondary material-icons md-shopping_bag"></i></span>
                        <div class="text">
                            <h6 class="mb-1 card-title">Total Rekening</h6>
                            <span>12</span>
                        </div>
                    </article>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-body mb-4">
                    <article class="icontext">
                        <span class="icon icon-sm rounded-circle bg-warning-light"><i class="text-warning material-icons md-qr_code"></i></span>
                        <div class="text">
                            <h6 class="mb-1 card-title">Total Nasabah</h6>
                            <span>1214</span>
                            <span class="text-sm">  </span>

                        </div>
                    </article>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-xl-8 col-lg-12">
                <div class="card mb-4">
                    <article class="card-body position-relative">
                        <span class="material-icons md-warning sticky-fa-card"></span>
                        <div class="bg-warning-light position-absolute top-0 start-0 p-2 rounded">
                        </div>
                        <div class="mt-4">
                            <canvas id="myChart" height="120px"></canvas>
                        </div>
                    </article>
                </div>

            </div>
            <div class="col-xl-4 col-lg-12">
                <div class="card mb-4">
                    <article class="card-body">
                        <h5 class="card-title">Log Aktivitas</h5>
                        <ul class="verti-timeline list-unstyled font-sm">

                            <tr>
                                <td>Tidak ada data</td>
                            </tr>

                        </ul>
                    </article>
                </div>

            </div>

        </div>
    </section>
    @endsection
</x-app-layout>
