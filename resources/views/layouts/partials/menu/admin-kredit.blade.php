<li class="menu-item has-submenu {{ Request::segment(2) == 'admin-kredit' ? 'active' : '' }} ">
    <a class="menu-link " href="page-form-product-1.html">
        <i class="icon material-icons md-ballot"></i>
        <span class="text ">Transaksi Pinjaman</span>
    </a>
    <div class="submenu">
        <a href="{{ route('pencairan.fasilitas.pinjaman') }}" class="{{ Request::segment(3) == 'pencairan-fasilitas-pinjaman' ? 'active' : '' }}">Pencairan Fasilitas Pinjaman</a>
        <a href="{{ route('penutupan.fasilitas.pinjaman') }}" class="{{ Request::segment(3) == 'penutupan-fasilitas-pinjaman' ? 'active' : '' }}">Penutupan Fasilitas Pinjaman</a>
    </div>
</li>

<li class="menu-item has-submenu {{ Request::segment(2) == 'admin-kredit' ? 'active' : '' }} ">
    <a class="menu-link " href="page-form-product-1.html">
        <i class="icon material-icons md-ballot"></i>
        <span class="text ">Informasi Pinjaman</span>
    </a>
    <div class="submenu">
        <a href="{{ route('informasi.nasabah.admin-kredit') }}" class="{{ Request::segment(4) == 'informasi-data-nasabah' ? 'active' : '' }}">Informasi Data Nasabah</a>
        <a href="{{ route('informasi.rekening.admin-kredit') }}" class="{{ Request::segment(4) == 'informasi-data-rekening' ? 'active' : '' }}">Informasi Rekening</a>
    </div>
</li>
