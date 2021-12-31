<li class="nav-item">
  <a href="{{ url('/transaksi')}}" class="nav-link small {{ menuaktif($menu,'transaksi') }}">
    <i class="nav-icon fas fa-shopping-cart"></i>
    <p class="text">Data Transaksi</p>
  </a>
</li>
<li class="nav-item">
  <a href="{{ url('/barang')}}" class="nav-link small {{ menuaktif($menu,'barang') }}">
    <i class="nav-icon fas fa-cube"></i>
    <p class="text">Data Barang</p>
  </a>
</li>
<li class="nav-item">
  <a href="{{ url('/userakses')}}" class="nav-link small {{ menuaktif($menu,'userakses') }}">
    <i class="nav-icon fas fa-user-lock"></i>
    <p class="text">Akses Kasir</p>
  </a>
</li>
  
  {{-- <li class="nav-item @if ($menu == 'barang')
  menu-is-opening menu-open
  @endif">
      <a href="#" class="nav-link small">
        <i class="nav-icon fas fa-envelope-open"></i>
        <p>
          Data Barang
          <i class="fas fa-angle-left right"></i>
          {{-- <span class="badge badge-info right">6</span> --}}
        {{-- </p>
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
          <span class="badge badge-info right">6</span>
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
  </li> --}}