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
            <li class="menu-item has-submenu {{ Request::segment(2) == 'master-akuntansi' ? 'active' : '' }}">
                <a class="menu-link" href="page-form-product-1.html">
                    <i class="icon material-icons md-add_box"></i>
                    <span class="text">Master Akuntasi</span>
                </a>
                <div class="submenu">
                    <a href="{{ route('kode-ledger.index') }}">Kode Ledger</a>
                    <a href="{{ route('kode-induk.index') }}">Kode Induk</a>
                    <a href="{{ route('kode-akun.index') }}">Kode Akun</a>
                    <a href="">Kunci Transaksi</a>
                </div>
            </li>
            <li class="menu-item has-submenu {{ Request::segment(2) == 'informasi-customer-service' ? 'active' : '' }} ">
                <a class="menu-link " href="page-form-product-1.html">
                    <i class="icon material-icons md-ballot"></i>
                    <span class="text ">Informasi Customer Service</span>
                </a>
                <div class="submenu">
                    <a href="{{ route('informasi.nasabah') }}" class="{{ Request::segment(3) == 'informasi-data-nasabah' ? 'active' : '' }}">Informasi Data Nasabah</a>
                    <a href="{{ route('informasi.rekening') }}" class="{{ Request::segment(3) == 'informasi-data-rekening' ? 'active' : '' }}">Informasi Rekening</a>
                </div>
            </li>
            <li class="menu-item has-submenu {{ Request::segment(2) == 'admin-kredit' ? 'active' : '' }} ">
                <a class="menu-link " href="page-form-product-1.html">
                    <i class="icon material-icons md-ballot"></i>
                    <span class="text ">Informasi Pinjaman</span>
                </a>
                <div class="submenu">
                    <a href="#" class="{{ Request::segment(3) == 'informasi-data-nasabah' ? 'active' : '' }}">Informasi Pinjaman</a>
                    <a href="{{ route('informasi.nasabah.admin-kredit') }}" class="{{ Request::segment(4) == 'informasi-data-nasabah' ? 'active' : '' }}">Informasi Data Nasabah</a>
                    <a href="{{ route('informasi.rekening.admin-kredit') }}" class="{{ Request::segment(4) == 'informasi-data-rekening' ? 'active' : '' }}">Informasi Rekening</a>
                </div>
            </li>
            <li class="menu-item has-submenu {{ Request::segment(2) == 'kategori-biaya-tetap' ? 'active' : '' }}">
                <a class="menu-link " href="page-form-product-1.html">
                    <i class="icon material-icons md-assignment"></i>
                    <span class="text ">Laporan Customer Service</span>
                </a>
                <div class="submenu">
                    <a href="">Laporan Pembukaan Rekening</a>
                </div>
            </li>
            <li class="menu-item has-submenu {{ Request::segment(2) == 'otorisasi-customer-service' ? 'active' : '' }} {{ Request::segment(2) == 'kategori-biaya-variabel' ? 'active' : '' }}">
                <a class="menu-link " href="page-form-product-1.html">
                    <i class="icon material-icons md-sync"></i>
                    <span class="text ">Otorisasi Customer Service</span>
                </a>
                <div class="submenu">
                    <a href="{{ route('otorisasi.nasabah') }}" class="{{ Request::segment(3) == 'otorisasi-data-nasabah' ? 'active' : '' }}">Otorisasi Data Nasabah</a>
                    <a href="{{ route('otorisasi.rekening') }}" class="{{ Request::segment(3) == 'otorisasi-data-rekening' ? 'active' : '' }}">Otorisasi Data Rekening</a>
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
            <li class="menu-item has-submenu {{ Request::segment(2) == 'customer-service' ? 'active' : '' }}">
                <a class="menu-link " href="page-form-product-1.html">
                    <i class="icon material-icons md-account_box"></i>
                    <span class="text ">Transaksi Customer Service</span>
                </a>
                <div class="submenu">
                    <a href="{{ route('nasabah.index') }}" class="{{ Request::segment(3) == 'nasabah' ? 'active' : '' }}">Pembukaan Nasabah Baru</a>
                    <a href="{{ route('pembukaan-rekening.index') }}" class="{{ Request::segment(3) == 'pembukaan-rekening' ? 'active' : '' }}">Pembukaan Rekening Baru</a>
                    <a href="{{ route('perubahan-data-administrasi.index') }}">Perubahan Data Administrasi</a>
                    <a href="">Pemblokiran Saldo Retail</a>
                    <a href="">Cetak Buku Tabungan</a>
                </div>
            </li>
            <li class="menu-item has-submenu {{ Request::segment(2) == 'teller' ? 'active' : '' }}">
                <a class="menu-link " href="page-form-product-1.html">
                    <i class="icon material-icons md-account_box"></i>
                    <span class="text ">Transaksi Teller</span>
                </a>
                <div class="submenu">
                    <a href="{{ route('setor-tunai.index') }}">Setor Tunai</a>
                    <a href="{{ route('penarikan.index') }}">Penarikan Tunai</a>
                    <a href="">Pembayaran Kas Teller</a>
                    <a href="">Penerimaan Kas Teller</a>
                </div>
            </li>
            <li class="menu-item has-submenu {{ Request::segment(2) == 'setting' ? 'active' : '' }}">
                <a class="menu-link " href="page-form-product-1.html">
                    <i class="icon material-icons md-admin_panel_settings"></i>
                    <span class="text ">Setting</span>
                </a>
                <div class="submenu">
                    <a href="{{ route('akun.index') }}">Data Pengguna/Akun</a>
                    <a href="{{ route('suku-bunga-koperasi.index') }}">Suku Bunga Koperasi</a>
                </div>
            </li>

        </ul>
        <hr />
        <br />
        <br />
    </nav>
</aside>
