<aside class="navbar-aside" id="offcanvas_aside">
    <div class="aside-top">
        <a href="{{ route('dashboard') }}" class="brand-wrap">
            {{-- <h4>Koperasi Simpan Pinjam</h4> --}}
            <div class="d-flex justify-content-center">
                <img src="{{ asset('backend/assets/imgs/logo.png') }}" alt="" class="img-fluid w-50 mx-auto">
            </div>
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

            @role('manager')
                @include('layouts.partials.menu.manager')
            @endrole
            @role('admin-kredit')
                @include('layouts.partials.menu.admin-kredit')
            @endrole
            @role('akuntansi')
                @include('layouts.partials.menu.akuntansi')
            @endrole
            @role('customer-service')
                @include('layouts.partials.menu.customer-service')
            @endrole
            @role('head-teller')
                @include('layouts.partials.menu.head-teller')
            @endrole
            @role('teller')
                @include('layouts.partials.menu.teller')
            @endrole
            <li class="menu-item has-submenu {{ Request::segment(2) == 'laporan' ? 'active' : '' }}">
                <a class="menu-link " href="page-form-product-1.html">
                    <i class="icon material-icons md-assignment"></i>
                    <span class="text ">Laporan</span>
                </a>
                <div class="submenu">
                    <a href="{{ route('neraca.index') }}" class="{{ Request::segment(3) == 'neraca' ? 'active' : '' }}">Neraca</a>
                    <a href="{{ route('tutup-cabang.index') }}">Tabungan</a>
                    <a href="{{ route('transaksi.harian') }}">Transaksi Harian</a>
                    <a href="{{ route('transaksi.head') }}">Transaksi Head Teller</a>
                    <a href="{{ route('transaksi.many') }}">Transaksi Many To Many</a>
                </div>
            </li>

        </ul>
        <hr />
        <br />
        <br />
    </nav>
</aside>
