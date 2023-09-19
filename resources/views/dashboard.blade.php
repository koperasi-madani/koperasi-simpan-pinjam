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
    @push('js')
    <script>
         function updateClock() {
                var currentTime = new Date();
                var hours = currentTime.getHours();
                var minutes = currentTime.getMinutes();
                var seconds = currentTime.getSeconds();
                // mengatur format menjadi "HH:MM:SSS"
                hours = (hours < 10 ? "0" : "") + hours;
                minutes = (minutes < 10 ? "0" : "") + minutes;
                seconds = (seconds < 10 ? "0" : "") + seconds;

                // menampilkan jam dalam elemen dengan id
                $('#waktu').text(`${hours} : ${minutes} : ${seconds }`)
            }
            setInterval(updateClock, 1000);
    </script>
        <script>
            var ctx = document.getElementById('myChart').getContext('2d');
            var ctxPerbulan = document.getElementById('myChartPerbulan').getContext('2d');
            var chartPerbulan = new Chart(ctxPerbulan, {
                type: 'line',
                data: {
                    labels: [
                        @foreach ($grafik_perbulan as $key => $value )
                            `{{ $key }}`,
                        @endforeach
                    ],
                    datasets: [{
                        label: 'Laba Rugi Perbulan',
                        data: [
                            @foreach ($grafik_perbulan as $key => $value )
                            {{ $value }},
                        @endforeach
                        ],
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
            var chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [
                        @foreach ($grafik_perhari as $key => $value )
                            `{{ $key }}`,
                        @endforeach
                    ],
                    datasets: [{
                        label: 'Laba Rugi Perhari',
                        data: [
                            @foreach ($grafik_perhari as $key => $value )
                            {{ $value }},
                        @endforeach
                        ],
                        fill: false,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endpush

    @section('content')
    <section class="content-main">
        <div class="content-header">
            <!-- Button trigger modal -->
            <div>
                <h2 class="content-title card-title">Dashboard</h2>
                <p>{{ auth()->user()->name }}   , Selamat datang di aplikasi Koperasi Simpan Pinjam</p>
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
                            <h3 class="mb-1 card-title">Pinjaman Kredit</h3>
                            <hr>
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-row mb-2">
                                    <strong class="fw-bold">0</strong>
                                    <small class="mx-2">: Transaksi Bulan Ini</small>
                                </div>
                                <div class="d-flex flex-row mb-2">
                                    <strong class="fw-bold">0</strong>
                                    <small class="mx-2"> : Jumlah Tagihan Tahun Ini</small>
                                </div>
                                <div class="d-flex flex-row mb-2">
                                    <strong class="fw-bold">0</strong>
                                    <small class="mx-2"> : Sisa Tagihan Tahun Ini</small>
                                </div>

                            </div>
                        </div>
                    </article>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="alert {{ $tutupBuku->status == 'buka' ? 'alert-success' : 'alert-danger'}} d-flex align-items-center" role="alert">
                    <span class="icon icon-sm rounded-circle bg-primary-light"><i class="text-primary material-icons md-admin_panel_settings"></i></span>
                    <div class="mx-3 d-flex flex-column">
                        <div>
                            <strong class="fw-bold">Status Tutup Cabang : </strong> <span class="badge {{ $tutupBuku->status == 'buka' ? 'bg-primary' : 'bg-danger'}} ">{{ $tutupBuku->status == 'buka' ? 'Buka' : 'Tutup'}}</span>
                        </div>
                        <div>
                            @php
                                    $lastUpdatedTime = \Carbon\Carbon::parse($tutupBuku->updated_at);
                                    $timeAgo = $lastUpdatedTime->diffForHumans();
                            @endphp
                            Last update : <strong class="fw-bold">{{ $timeAgo }}</strong>
                        </div>
                    </div>
                </div>
                <div class="card card-body">
                    <article class="icontext">
                        <span class="icon icon-sm rounded-circle bg-warning-light"><i class="text-warning material-icons md-timer"></i></span>
                        <div class="text">
                            <h6 class="mb-1 card-title">Waktu</h6>
                            <span id="waktu"></span>
                        </div>
                    </article>
                </div>

            </div>
            <div class="col-lg-4">
                <div class="card card-body mb-4">
                    <article class="icontext">
                        <span class="icon icon-sm rounded-circle bg-primary-light"><i class="text-primary material-icons md-monetization_on"></i></span>
                        <div class="text">
                            <h3 class="mb-1 card-title">Data Peminjam</h3>
                            <hr>
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-row mb-2">
                                    <strong class="fw-bold">0</strong>
                                    <small class="mx-2">: Peminjam</small>
                                </div>
                                <div class="d-flex flex-row mb-2">
                                    <strong class="fw-bold">0</strong>
                                    <small class="mx-2"> : Sudah Lunas</small>
                                </div>
                                <div class="d-flex flex-row mb-2">
                                    <strong class="fw-bold">0</strong>
                                    <small class="mx-2"> : Belum Lunas</small>
                                </div>

                            </div>
                        </div>
                    </article>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-body mb-4">
                    <article class="icontext">
                        <span class="icon icon-sm rounded-circle bg-secondary-light"><i class="text-secondary material-icons md-shopping_bag"></i></span>
                        <div class="text">
                            <h3 class="mb-1 card-title">Data Pengguna</h3>
                            <hr>
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-row mb-2">
                                    <strong class="fw-bold">{{ $user }}</strong>
                                    <small class="mx-2">: Pengguna Aktif</small>
                                </div>
                                <div class="d-flex flex-row mb-2">
                                    <strong class="fw-bold">{{ $hak_akses }}</strong>
                                    <small class="mx-2"> : Hak Akses</small>
                                </div>
                                <div class="d-flex flex-row mb-2">
                                    <strong class="fw-bold">{{ $user }}</strong>
                                    <small class="mx-2"> : Jumlah Pengguna</small>
                                </div>

                            </div>

                        </div>
                    </article>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card card-body mb-4">
                    <article class="icontext">
                        <span class="icon icon-sm rounded-circle bg-warning-light"><i class="text-warning material-icons md-qr_code"></i></span>
                        <div class="text">
                            <h3 class="mb-1 card-title">Data Nasabah</h3>
                            <hr>
                            <div class="d-flex flex-column">
                                <div class="d-flex flex-row mb-2">
                                    <strong class="fw-bold">{{ $nasabah_aktif }}</strong>
                                    <small class="mx-2">: Anggota Aktif</small>
                                </div>
                                <div class="d-flex flex-row mb-2">
                                    <strong class="fw-bold">{{ $nasabah_non_aktif }}</strong>
                                    <small class="mx-2"> : Anggota Tidak Aktif</small>
                                </div>
                                <div class="d-flex flex-row mb-2">
                                    <strong class="fw-bold">{{ $nasabah }}</strong>
                                    <small class="mx-2"> : Jumlah Anggota</small>
                                </div>

                            </div>

                        </div>
                    </article>
                </div>
            </div>

        </div>
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Grafik Laba Rugi Perhari</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-controls="profile" aria-selected="false">Grafik Laba Rugi Perbulan</button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                            <header class="card-header">
                                <h4>Grafik Laba Rugi Perhari</h4>
                            </header>
                            <div class="card-body">
                                <article class="card-body position-relative">
                                    <div class="mt-4">
                                        <canvas id="myChart" height="120px"></canvas>
                                    </div>
                                </article>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="home-tab">
                            <header class="card-header">
                                <h4>Grafik Laba Rugi Perbulan</h4>
                            </header>
                            <div class="card-body">
                                <article class="card-body position-relative">
                                    <div class="mt-4">
                                        <canvas id="myChartPerbulan" height="120px"></canvas>
                                    </div>
                                </article>
                            </div>
                        </div>
                    </div>
                </div>


            </div>


        </div>
    </section>
    @endsection
</x-app-layout>
