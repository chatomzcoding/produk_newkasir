<li class="nav-item">
  <a href="{{ url('/user')}}" class="nav-link small {{ menuaktif($menu,'cabang') }}">
    <i class="nav-icon fas fa-user"></i>
    <p class="text">Data Karyawan</p>
  </a>
</li>
<li class="nav-item">
  <a href="{{ url('/transaksi')}}" class="nav-link small {{ menuaktif($menu,'transaksi') }}">
    <i class="nav-icon fas fa-shopping-cart"></i>
    <p class="text">Data Transaksi</p>
  </a>
</li>
  
  {{-- <li class="nav-item @if ($menu == 'listdata')
  menu-is-opening menu-open
  @endif">
      <a href="#" class="nav-link small">
        <i class="nav-icon fas fa-envelope-open"></i>
        <p>
          Data Master
          <i class="fas fa-angle-left right"></i>
          <span class="badge badge-info right">6</span>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="{{ url('/listdata')}}" class="nav-link small {{ menuaktif($menu,'listdata') }}">
            &nbsp;&nbsp;<i class="far fa-circle nav-icon"></i>
            <p>List Data</p>
          </a>
        </li>
      </ul>
  </li> --}}
  
  