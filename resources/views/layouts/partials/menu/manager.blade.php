{{-- manager --}}
<li class="menu-item has-submenu {{ Request::segment(2) == 'informasi-customer-service' ? 'active' : '' }} ">
    <a class="menu-link " href="page-form-product-1.html">
        <i class="icon material-icons md-ballot"></i>
        <span class="text ">Informasi Customer Service</span>
    </a>
    <div class="submenu">
        <a href="{{ route('customer.informasi.nasabah') }}" class="{{ Request::segment(3) == 'informasi-data-nasabah' ? 'active' : '' }}">Informasi Data Nasabah</a>
        <a href="{{ route('informasi.rekening') }}" class="{{ Request::segment(3) == 'informasi-data-rekening' ? 'active' : '' }}">Informasi Rekening</a>
    </div>
</li>
{{-- manager --}}
<li class="menu-item has-submenu {{ Request::segment(2) == 'laporan-customer-service' ? 'active' : '' }}">
    <a class="menu-link " href="page-form-product-1.html">
        <i class="icon material-icons md-assignment"></i>
        <span class="text ">Laporan Customer Service</span>
    </a>
    <div class="submenu">
        <a href="{{ route('laporan.pembukaan-rekening') }}" class="{{ Request::segment(3) == 'laporan-pembukaan-rekening' ? 'active' : '' }}">Laporan Pembukaan Rekening</a>
    </div>
</li>
{{-- manager --}}
<li class="menu-item has-submenu {{ Request::segment(2) == 'otorisasi-customer-service' ? 'active' : '' }} {{ Request::segment(2) == 'kategori-biaya-variabel' ? 'active' : '' }}">
    <a class="menu-link " href="page-form-product-1.html">
        <i class="icon material-icons md-sync"></i>
        <span class="text ">Otorisasi</span>
    </a>
    <div class="submenu">
        <a href="{{ route('otorisasi.nasabah') }}" class="{{ Request::segment(3) == 'otorisasi-data-nasabah' ? 'active' : '' }}">Otorisasi Data Nasabah</a>
        <a href="{{ route('otorisasi.rekening') }}" class="{{ Request::segment(3) == 'otorisasi-data-rekening' ? 'active' : '' }}">Otorisasi Data Rekening</a>
    </div>
</li>
<li class="menu-item has-submenu {{ Request::segment(2) == 'informasi-head-teller' ? 'active' : '' }}">
    <a class="menu-link " href="page-form-product-1.html">
        <i class="icon material-icons md-ballot"></i>
        <span class="text ">Informasi Head Teller</span>
    </a>
    <div class="submenu">
        <a href="{{ route('informasi.semua-saldo') }}" class="{{ Request::segment(3) == 'informasi-semua-saldo-teller' ? 'active' : '' }}">Informasi Semua Saldo Teller</a>
        <a href="{{ route('informasi.saldo-teller') }}" class="{{ Request::segment(3) == 'saldo-teller' ? 'active' : '' }}">Saldo Teller</a>
    </div>
</li>
{{-- <li class="menu-item has-submenu {{ Request::segment(2) == 'otorisasi-head-teller' ? 'active' : '' }} {{ Request::segment(2) == 'kategori-biaya-variabel' ? 'active' : '' }}">
    <a class="menu-link " href="page-form-product-1.html">
        <i class="icon material-icons md-sync"></i>
        <span class="text ">Otorisasi Head Teller</span>
    </a>
    <div class="submenu">
        <a href="{{ route('otorisasi.transaksi-operator') }}" class="{{ Request::segment(3) == 'otorisasi-transaksi-per-operator' ? 'active' : '' }}">Otorisasi Transaksi Per Operotor</a>
    </div>
</li> --}}
<li class="menu-item has-submenu {{ Request::segment(2) == 'admin-kredit' ? 'active' : '' }} ">
    <a class="menu-link " href="page-form-product-1.html">
        <i class="icon material-icons md-ballot"></i>
        <span class="text ">Informasi Pinjaman</span>
    </a>
    <div class="submenu">
        <a href="{{ route('informasi.pinjaman') }}" class="{{ Request::segment(4) == 'data-informasi-pinjaman' ? 'active' : '' }}">Informasi Pinjaman</a>
        @role('admin-kredit')
            <a href="{{ route('informasi.nasabah.admin-kredit') }}" class="{{ Request::segment(4) == 'informasi-data-nasabah' ? 'active' : '' }}">Informasi Data Nasabah</a>
            <a href="{{ route('informasi.rekening.admin-kredit') }}" class="{{ Request::segment(4) == 'informasi-data-rekening' ? 'active' : '' }}">Informasi Rekening</a>
        @endrole
    </div>
</li>
<li class="menu-item has-submenu {{ Request::segment(2) == 'informasi-gl' ? 'active' : '' }} ">
    <a class="menu-link " href="page-form-product-1.html">
        <i class="icon material-icons md-ballot"></i>
        <span class="text ">Informasi GL</span>
    </a>
    <div class="submenu">
        <a href="{{ route('melihat.gl.master') }}" class="{{ Request::segment(3) == 'melihat-gl-master' ? 'active' : '' }}">Melihat G/L Master</a>
        <a href="{{ route('melihat.transaksi.gl') }}" class="{{ Request::segment(3) == 'melihat-transaksi-gl' ? 'active' : '' }}">Melihat Transaksi G/L</a>
        <a href="{{ route('melihat.data.rekening.tabungan') }}" class="{{ Request::segment(3) == 'melihat-data-rekening-tabungan' ? 'active' : '' }}">Melihat Data Rekening Tabungan</a>
    </div>
</li>
<li class="menu-item has-submenu {{ Request::segment(2) == 'setting' ? 'active' : '' }}">
    <a class="menu-link " href="page-form-product-1.html">
        <i class="icon material-icons md-admin_panel_settings"></i>
        <span class="text ">Setting</span>
    </a>
    <div class="submenu">
        <a href="{{ route('akun.index') }}">Data Pengguna/Akun</a>
        @role('manager')
            <a href="{{ route('tutup-cabang.index') }}">Tutup Cabang</a>
        @endrole
        <a href="{{ route('suku-bunga-koperasi.index') }}">Suku Bunga Koperasi</a>
    </div>
</li>
