<li class="menu-item has-submenu {{ Request::segment(2) == 'master-akuntansi' ? 'active' : '' }}">
    <a class="menu-link" href="page-form-product-1.html">
        <i class="icon material-icons md-add_box"></i>
        <span class="text">Master Akuntasi</span>
    </a>
    <div class="submenu">
        <a href="{{ route('kode-ledger.index') }}">Kode Ledger</a>
        <a href="{{ route('kode-induk.index') }}">Kode Induk</a>
        <a href="{{ route('kode-akun.index') }}">Kode Akun</a>
    </div>
</li>
<li class="menu-item has-submenu {{ Request::segment(2) == 'transaksi-back-office' ? 'active' : '' }} ">
    <a class="menu-link " href="page-form-product-1.html">
        <i class="icon material-icons md-ballot"></i>
        <span class="text ">Transaksi Back Office</span>
    </a>
    <div class="submenu">
        <a href="{{ route('transaksi.pemindah.buku.rekening') }}" class="{{ Request::segment(3) == 'transaksi-pemindah-buku-antar-rekening' ? 'active' : '' }}">Transaksi Pemindah Buku Antar Rekening</a>
        <a href="{{ route('transaksi.debet.kode.gl') }}" class="{{ Request::segment(3) == 'transaksi-pendebetan-dengan-kode-gl' ? 'active' : '' }}">Transaksi Pendebetan Kode GL</a>
        <a href="{{ route('transaksi.kredit.kode.gl') }}" class="{{ Request::segment(3) == 'transaksi-pengkreditan-dengan-dengan-kode-gl' ? 'active' : '' }}">Transaksi Pengkreditan Kode GL</a>
        <a href="{{ route('transaksi.many.to.many') }}" class="{{ Request::segment(3) == 'transaksi-many-to-many' ? 'active' : '' }}">Transaksi Many To Many</a>
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
<li class="menu-item has-submenu {{ Request::segment(2) == 'laporan-back-office' ? 'active' : '' }} ">
    <a class="menu-link " href="page-form-product-1.html">
        <i class="icon material-icons md-ballot"></i>
        <span class="text ">Laporan Back Office</span>
    </a>
    <div class="submenu">
        <a href="{{ route('laporan.transaksi.sendiri') }}" class="{{ Request::segment(3) == 'laporan-transaksi-sendiri' ? 'active' : '' }}">Laporan Transaksi Sendiri</a>
    </div>
</li>

