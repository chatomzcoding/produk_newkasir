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
        <i class="nav-icon fas fa-envelope-open"></i>
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
  
  