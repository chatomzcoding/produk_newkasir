<li class="nav-item">
  <a href="{{ url('/client')}}" class="nav-link small {{ menuaktif($menu,'client') }}">
    <i class="nav-icon fas fa-user"></i>
    <p class="text">Data Client</p>
  </a>
</li>

<li class="nav-item @if ($menu == 'listdata')
menu-is-opening menu-open
@endif">
    <a href="#" class="nav-link small">
      <i class="nav-icon fas fa-envelope-open"></i>
      <p>
        Data Master
        <i class="fas fa-angle-left right"></i>
        {{-- <span class="badge badge-info right">6</span> --}}
      </p>
    </a>
    <ul class="nav nav-treeview">
      <li class="nav-item">
        <a href="{{ url('/user')}}" class="nav-link small {{ menuaktif($menu,'user') }}">
          &nbsp;&nbsp;<i class="fas fa-users nav-icon"></i>
          <p>Data User</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ url('/listdata')}}" class="nav-link small {{ menuaktif($menu,'listdata') }}">
          &nbsp;&nbsp;<i class="fas fa-list nav-icon"></i>
          <p>List Data</p>
        </a>
      </li>
    </ul>
</li>

