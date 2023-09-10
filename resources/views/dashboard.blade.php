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
            var chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Pendapatan','Pengeluaran', 'Keuntungan'],
                    datasets: [{
                        label: 'Dalam Juta Rupiah',
                        data: [
                            @foreach ($grafik as $item)
                                {{ $item }},
                            @endforeach
                        ], // Nilai yang diinginkan
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.2)',  // Warna latar belakang untuk 'Pendapatan'
                            'rgba(255, 206, 86, 0.2)',  // Warna latar belakang untuk 'Pengeluaran'
                            'rgba(75, 205, 86, 0.2)'   // Warna latar belakang untuk 'Keuntungan'
                        ],
                        borderColor: [
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 205, 86, 1)'
                        ],
                        borderWidth: 1
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
                <div class="card mb-4">
                    <header class="card-header"><h4 class="card-title">Grafik Laba Rugi - {{ $tgl }}</h4></header>
                    <article class="card-body position-relative">
                        <div class="mt-4">
                            <canvas id="myChart" height="120px"></canvas>
                        </div>
                    </article>
                </div>

            </div>


        </div>
    </section>
    @endsection
</x-app-layout>
