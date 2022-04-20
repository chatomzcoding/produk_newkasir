<li class="nav-item">
  <a href="{{ url('/user')}}" class="nav-link small {{ menuaktif($menu,'karyawan') }}">
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