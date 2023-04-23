<aside class="navbar-aside" id="offcanvas_aside">
    <div class="aside-top">
        <a href="index.html" class="brand-wrap">
            <h4>Koperasi Simpan Pinjam</h4>
        </a>
        <div>
            <button class="btn btn-icon btn-aside-minimize"><i class="text-muted material-icons md-menu_open"></i></button>
        </div>
    </div>
    <nav>
        <ul class="menu-aside">
            <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <a class="menu-link" href="{{ route('dashboard') }}">
                    <i class="icon material-icons md-home"></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li class="menu-item has-submenu {{ Request::segment(2) == 'kategori-biaya-tetap' ? 'active' : '' }} {{ Request::segment(2) == 'kategori-biaya-variabel' ? 'active' : '' }}">
                <a class="menu-link" href="page-form-product-1.html">
                    <i class="icon material-icons md-add_box"></i>
                    <span class="text">Master Akuntasi</span>
                </a>
                <div class="submenu">
                    <a href="">Kode Induk</a>
                    <a href="">Kode Akun</a>
                    <a href="">Kunci Transaksi</a>
                </div>
            </li>
            <li class="menu-item has-submenu {{ Request::segment(2) == 'kategori-biaya-tetap' ? 'active' : '' }} {{ Request::segment(2) == 'kategori-biaya-variabel' ? 'active' : '' }}">
                <a class="menu-link " href="page-form-product-1.html">
                    <i class="icon material-icons md-ballot"></i>
                    <span class="text ">Informasi Customer Service</span>
                </a>
                <div class="submenu">
                    <a href="">Informasi Data Nasabah</a>
                    <a href="">Informasi Rekening</a>
                </div>
            </li>
            <li class="menu-item has-submenu {{ Request::segment(2) == 'kategori-biaya-tetap' ? 'active' : '' }} {{ Request::segment(2) == 'kategori-biaya-variabel' ? 'active' : '' }}">
                <a class="menu-link " href="page-form-product-1.html">
                    <i class="icon material-icons md-assignment"></i>
                    <span class="text ">Laporan Customer Service</span>
                </a>
                <div class="submenu">
                    <a href="">Laporan Pembukaan Rekening</a>
                </div>
            </li>
            <li class="menu-item has-submenu {{ Request::segment(2) == 'kategori-biaya-tetap' ? 'active' : '' }} {{ Request::segment(2) == 'kategori-biaya-variabel' ? 'active' : '' }}">
                <a class="menu-link " href="page-form-product-1.html">
                    <i class="icon material-icons md-sync"></i>
                    <span class="text ">Otorisasi Customer Service</span>
                </a>
                <div class="submenu">
                    <a href="">Otorisasi Data Nasabah</a>
                    <a href="">Otorisasi Data Rekening</a>
                </div>
            </li>
            <li class="menu-item has-submenu {{ Request::segment(2) == 'kategori-biaya-tetap' ? 'active' : '' }} {{ Request::segment(2) == 'kategori-biaya-variabel' ? 'active' : '' }}">
                <a class="menu-link " href="page-form-product-1.html">
                    <i class="icon material-icons md-ballot"></i>
                    <span class="text ">Informasi Head Teller</span>
                </a>
                <div class="submenu">
                    <a href="">Informasi Semua Saldo Teller</a>
                    <a href="">Saldo Teller</a>
                </div>
            </li>
            <li class="menu-item has-submenu {{ Request::segment(2) == 'kategori-biaya-tetap' ? 'active' : '' }} {{ Request::segment(2) == 'kategori-biaya-variabel' ? 'active' : '' }}">
                <a class="menu-link " href="page-form-product-1.html">
                    <i class="icon material-icons md-sync"></i>
                    <span class="text ">Otorisasi Head Teller</span>
                </a>
                <div class="submenu">
                    <a href="">Otorisasi Transaksi Per Operotor</a>
                </div>
            </li>

        </ul>
        <hr />
        <br />
        <br />
    </nav>
</aside>
