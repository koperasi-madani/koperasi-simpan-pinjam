<li class="menu-item has-submenu {{ Request::segment(2) == 'informasi-head-teller' ? 'active' : '' }}">
    <a class="menu-link " href="page-form-product-1.html">
        <i class="icon material-icons md-ballot"></i>
        <span class="text ">Informasi Teller</span>
    </a>
    <div class="submenu">
        <a href="{{ route('informasi.semua-saldo') }}" class="{{ Request::segment(3) == 'informasi-semua-saldo-teller' ? 'active' : '' }}">Informasi Semua Saldo Teller</a>
        <a href="{{ route('informasi.saldo-teller') }}" class="{{ Request::segment(3) == 'saldo-teller' ? 'active' : '' }}">Saldo Teller</a>
        <a href="{{ route('informasi.nasabah') }}" class="{{ Request::segment(3) == 'informasi-tabungan-nasabah' ? 'active' : '' }}">Informasi Tabungan Nasabah</a>
        <a href="{{ route('informasi.denominasi') }}" class="{{ Request::segment(3) == 'informasi-denominasi' ? 'active' : '' }}">Denominasi</a>
    </div>
</li>

<li class="menu-item has-submenu {{ Request::segment(2) == 'teller' ? 'active' : '' }}">
    <a class="menu-link " href="page-form-product-1.html">
        <i class="icon material-icons md-account_box"></i>
        <span class="text ">Transaksi Teller</span>
    </a>
    <div class="submenu">
        <a href="{{ route('setor-tunai.index') }}" class="{{ Request::segment(4) == 'setor-tunai' ? 'active' : '' }}">Setor Tunai</a>
        <a href="{{ route('penarikan.index') }}" class="{{ Request::segment(4) == 'penarikan' ? 'active' : '' }}">Penarikan Tunai</a>
        <a href="{{ route('pembayaran.kas-teller') }}" class="{{ Request::segment(4) == 'pembayaran-kas-teller' ? 'active' : '' }}">Transaksi Kas Pagi</a>
        <a href="{{ route('penerimaan.kas-teller') }}" class="{{ Request::segment(4) == 'penerimaan-kas-teller' ? 'active' : '' }}">Transaksi Kas Sore</a>
        <a href="{{ route('teller.informasi.nasabah') }}" class="{{ Request::segment(4) == 'informasi-tabungan-nasabah' ? 'active' : '' }}">Informasi Tabungan Nasabah</a>
    </div>
</li>
