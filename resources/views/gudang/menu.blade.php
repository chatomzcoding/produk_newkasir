{{-- <li class="nav-item">
    <a href="{{ url('/user')}}" class="nav-link small {{ menuaktif($menu,'cabang') }}">
      <i class="nav-icon fas fa-user"></i>
      <p class="text">Data Karyawan</p>
    </a>
  </li> --}}
  
  <li class="nav-item @if ($menu == 'barang' || $menu == 'infobarang' || $menu == 'tambahbarang')
  menu-is-opening menu-open
  @endif">
      <a href="#" class="nav-link small">
        <i class="nav-icon fas fa-cube"></i>
        <p>
          Data Barang
          <i class="fas fa-angle-left right"></i>
          {{-- <span class="badge badge-info right">6</span> --}}
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="{{ url('/barang')}}" class="nav-link small {{ menuaktif($menu,'barang') }}">
            &nbsp;&nbsp;<i class="fas fa-list nav-icon"></i>
            <p>Daftar Barang</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/barang/create')}}" class="nav-link small {{ menuaktif($menu,'tambahbarang') }}">
            &nbsp;&nbsp;<i class="fas fa-plus nav-icon"></i>
            <p>Tambah Barang</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/barang?sesi=info')}}" class="nav-link small {{ menuaktif($menu,'infobarang') }}">
            &nbsp;&nbsp;<i class="fas fa-info-circle nav-icon"></i>
            <p>Info Barang</p>
          </a>
        </li>
      </ul>
  </li>
  <li class="nav-item @if ($menu == 'laporantransaksi' || $menu == 'laporaneod' || $menu == 'laporankeuangan')
  menu-is-opening menu-open
  @endif">
      <a href="#" class="nav-link small">
        <i class="nav-icon fas fa-file"></i>
        <p>
          Laporan
          <i class="fas fa-angle-left right"></i>
          {{-- <span class="badge badge-info right">6</span> --}}
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="{{ url('/datalaporan/transaksi?s=harian')}}" class="nav-link small {{ menuaktif($menu,'laporantransaksi') }}">
            &nbsp;&nbsp;<i class="fas fa-shopping-cart nav-icon"></i>
            <p>Transaksi</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/datalaporan/eod')}}" class="nav-link small {{ menuaktif($menu,'laporaneod') }}">
            &nbsp;&nbsp;<i class="fas fa-file-contract nav-icon"></i>
            <p>EOD</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/datalaporan/keuangan')}}" class="nav-link small {{ menuaktif($menu,'laporankeuangan') }}">
            &nbsp;&nbsp;<i class="fas fa-file-invoice-dollar nav-icon"></i>
            <p>Keuangan</p>
          </a>
        </li>
      </ul>
  </li>
  <li class="nav-item @if ($menu == 'distribusi' || $menu == 'retur')
  menu-is-opening menu-open
  @endif">
      <a href="#" class="nav-link small">
        <i class="nav-icon fas fa-cubes"></i>
        <p>
          Data Gudang
          <i class="fas fa-angle-left right"></i>
          {{-- <span class="badge badge-info right">6</span> --}}
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="{{ url('/distribusi')}}" class="nav-link small {{ menuaktif($menu,'distribusi') }}">
            &nbsp;&nbsp;<i class="fas fa-truck nav-icon"></i>
            <p>Disribusi Barang</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/retur')}}" class="nav-link small {{ menuaktif($menu,'retur') }}">
            &nbsp;&nbsp;<i class="fas fa-undo nav-icon"></i>
            <p>Retur Barang</p>
          </a>
        </li>
      </ul>
  </li>
  <li class="nav-item @if ($menu == 'kategori' || $menu == 'satuan' || $menu == 'supplier'|| $menu == 'produsen' || $menu == 'client')
  menu-is-opening menu-open
  @endif">
      <a href="#" class="nav-link small">
        <i class="nav-icon fas fa-folder-open"></i>
        <p>
          Data Master
          <i class="fas fa-angle-left right"></i>
          {{-- <span class="badge badge-info right">6</span> --}}
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="{{ url('/client')}}" class="nav-link small {{ menuaktif($menu,'client') }}">
            &nbsp;&nbsp;<i class="fas fa-store nav-icon"></i>
            <p>Data Pokok</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/kategori')}}" class="nav-link small {{ menuaktif($menu,'kategori') }}">
            &nbsp;&nbsp;<i class="fas fa-th nav-icon"></i>
            <p>Kategori Barang</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/satuan')}}" class="nav-link small {{ menuaktif($menu,'satuan') }}">
            &nbsp;&nbsp;<i class="fas fa-th nav-icon"></i>
            <p>Satuan Barang</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/supplier')}}" class="nav-link small {{ menuaktif($menu,'supplier') }}">
            &nbsp;&nbsp;<i class="fas fa-th nav-icon"></i>
            <p>Data Supplier</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/produsen')}}" class="nav-link small {{ menuaktif($menu,'produsen') }}">
            &nbsp;&nbsp;<i class="fas fa-th nav-icon"></i>
            <p>Data Produsen</p>
          </a>
        </li>
      </ul>
  </li>
  
  