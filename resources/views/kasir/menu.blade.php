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
  <a href="{{ url('/laporan')}}" class="nav-link small {{ menuaktif($menu,'eod') }}">
    <i class="nav-icon fas fa-file"></i>
    <p class="text">Laporan EOD</p>
  </a>
</li>
<li class="nav-item">
  <a href="{{ url('/userakses')}}" class="nav-link small {{ menuaktif($menu,'userakses') }}">
    <i class="nav-icon fas fa-user-lock"></i>
    <p class="text">Akses Kasir</p>
  </a>
</li>