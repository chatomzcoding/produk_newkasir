{{-- <li class="nav-item">
    <a href="{{ url('/user')}}" class="nav-link small {{ menuaktif($menu,'cabang') }}">
      <i class="nav-icon fas fa-user"></i>
      <p class="text">Data Karyawan</p>
    </a>
  </li> --}}
  
  <li class="nav-item @if ($menu == 'barang')
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
            &nbsp;&nbsp;<i class="fas fa-list nav-icon"></i>
            <p>Disribusi Barang</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="{{ url('/retur')}}" class="nav-link small {{ menuaktif($menu,'retur') }}">
            &nbsp;&nbsp;<i class="fas fa-list nav-icon"></i>
            <p>Retur Barang</p>
          </a>
        </li>
      </ul>
  </li>
  <li class="nav-item @if ($menu == 'kategori' || $menu == 'satuan')
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
      </ul>
  </li>
  
  