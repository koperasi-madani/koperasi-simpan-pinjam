<li class="menu-item has-submenu {{ Request::segment(2) == 'customer-service' ? 'active' : '' }}">
    <a class="menu-link " href="page-form-product-1.html">
        <i class="icon material-icons md-account_box"></i>
        <span class="text ">Transaksi Customer Service</span>
    </a>
    <div class="submenu">
        <a href="{{ route('nasabah.index') }}" class="{{ Request::segment(3) == 'nasabah' ? 'active' : '' }}">Pembukaan Anggota Baru</a>
        <a href="{{ route('pembukaan-rekening.index') }}" class="{{ Request::segment(3) == 'pembukaan-rekening' ? 'active' : '' }}">Pembukaan Rekening Baru</a>
        <a href="{{ route('perubahan-data-administrasi.index') }}" class="{{ Request::segment(3) == 'perubahan-data-administrasi' ? 'active' : '' }}">Perubahan Data Administrasi</a>
        <a href="{{ route('pemblokiran.saldo-retail') }}" class="{{ Request::segment(3) == 'pemblokiran-saldo-retail' ? 'active' : '' }}">Pemblokiran Saldo Retail</a>
        <a href="{{ route('cetak.tabungan') }}" class="{{ Request::segment(3) == 'cetak-buku-tabungan' ? 'active' : '' }}">Cetak Buku Tabungan</a>
    </div>
</li>
<li class="menu-item has-submenu {{ Request::segment(2) == 'informasi-customer-service' ? 'active' : '' }} ">
    <a class="menu-link " href="page-form-product-1.html">
        <i class="icon material-icons md-ballot"></i>
        <span class="text ">Informasi Customer Service</span>
    </a>
    <div class="submenu">
        <a href="{{ route('customer.informasi.nasabah') }}" class="{{ Request::segment(3) == 'informasi-data-anggota' ? 'active' : '' }}">Informasi Data Anggota</a>
        <a href="{{ route('informasi.rekening') }}" class="{{ Request::segment(3) == 'informasi-data-rekening' ? 'active' : '' }}">Informasi Rekening</a>
    </div>
</li>
<li class="menu-item has-submenu {{ Request::segment(2) == 'transaksi-deposito' ? 'active' : '' }}">
    <a class="menu-link " href="page-form-product-1.html">
        <i class="icon material-icons md-assignment"></i>
        <span class="text ">Transaksi Deposito</span>
    </a>
    <div class="submenu">
        <a href="{{ route('penempatan.deposito-berjangka') }}" class="{{ Request::segment(3) == 'penempatan-deposito-berjangka' ? 'active' : '' }}">Penempatan Deposito Berjangka</a>
        <a href="{{ route('pencairan-deposito-berjangka') }}" class="{{ Request::segment(3) == 'pencairan-deposito-berjangka' ? 'active' : '' }}">Pencairan Deposito Berjangka</a>
    </div>
</li>
<li class="menu-item has-submenu {{ Request::segment(2) == 'laporan-customer-service' ? 'active' : '' }}">
    <a class="menu-link " href="page-form-product-1.html">
        <i class="icon material-icons md-assignment"></i>
        <span class="text ">Laporan Customer Service</span>
    </a>
    <div class="submenu">
        <a href="{{ route('laporan.deposito') }}" class="{{ Request::segment(3) == 'laporan-deposito' ? 'active' : '' }}">Laporan Deposito</a>
        <a href="{{ route('laporan.pembukaan-rekening') }}" class="{{ Request::segment(3) == 'laporan-pembukaan-rekening' ? 'active' : '' }}">Laporan Pembukaan Rekening</a>
    </div>
</li>
