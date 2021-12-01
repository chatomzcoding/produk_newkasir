@php
	$info = App\Models\Infowebsite::first(); 
	$desa = App\Models\Profil::first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<title>@yield('title')</title>
<link href="{{  asset('img/'.$info->logo_brand)}}" rel="icon">

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description" content="Unicat project">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="{{ asset('template/unicat/styles/bootstrap4/bootstrap.min.css')}}">
<link href="{{ asset('template/unicat/plugins/font-awesome-4.7.0/css/font-awesome.min.css')}}" rel="stylesheet" type="text/css">
<link rel="stylesheet" type="text/css" href="{{ asset('template/unicat/plugins/OwlCarousel2-2.2.1/owl.carousel.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('template/unicat/plugins/OwlCarousel2-2.2.1/owl.theme.default.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('template/unicat/plugins/OwlCarousel2-2.2.1/animate.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('template/unicat/styles/main_styles.css')}}">
<link rel="stylesheet" type="text/css" href="{{ asset('template/unicat/styles/responsive.css')}}">

@yield('head')
</head>
<body>

<div class="super_container">

	<!-- Header -->

	<header class="header">
			
		<!-- Top Bar -->
		<div class="top_bar">
			<div class="top_bar_container">
				<div class="container">
					<div class="row">
						<div class="col">
							<div class="top_bar_content d-flex flex-row align-items-center justify-content-start">
								<ul class="top_bar_contact_list">
									<li><div class="question">Kontak Kami</div></li>
									<li>
										<i class="fa fa-phone" aria-hidden="true"></i>
										<div>{{ $info->telp }}</div>
									</li>
									<li>
										<i class="fa fa-envelope-o" aria-hidden="true"></i>
										<div>{{ $info->email }}</div>
									</li>
								</ul>
								<div class="top_bar_login ml-auto">
									@if (isset(Auth::user()->id))
										<div class="login_button"><a href="{{ route('dashboard') }}">Dashboard</a></div>
									@else
										<div class="login_button"><a href="{{ url('login') }}">Login</a></div>
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>				
		</div>

		<!-- Header Content -->
		<div class="header_container">
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="header_content d-flex flex-row align-items-center justify-content-start">
							<div class="logo_container">
								<a href="#">
									<div class="logo_text">Jantung<span>Desa</span></div>
								</a>
							</div>
							<nav class="main_nav_contaner ml-auto">
								<ul class="main_nav">
									
									<li class="@if ($menu == 'beranda')
										active
									@endif"><a href="{{ url('/') }}">Beranda</a></li>

								<li class="nav-item dropdown @if ($menu == 'profil')
								active
							@endif">
									<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Info Profil</a>
									<div class="dropdown-menu">
									  <a class="dropdown-item" href="{{ url('halaman/profil') }}">Desa</a>
									  <a class="dropdown-item" href="{{ url('halaman/bumdes') }}">BUMDesa</a>
									  {{-- <div role="separator" class="dropdown-divider"></div> --}}
									  {{-- <a class="dropdown-item" href="#three"></a> --}}
									</div>
								  </li>

								  <li class="@if ($menu == 'produk')
									active
								@endif"><a href="{{ url('halaman/pasardesa') }}">Produk Desa</a></li>

								{{-- <li class="nav-item dropdown @if ($menu == 'layanan')
								active
							@endif">
									<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Layanan Mandiri</a>
									<div class="dropdown-menu">
									  <a class="dropdown-item" href="{{ url('halaman/forum') }}">Forum Desa</a>
									  <a class="dropdown-item" href="{{ url('halaman/pasardesa') }}">Produk Desa</a>
									  <div role="separator" class="dropdown-divider"></div>
									  <a class="dropdown-item" href="#three"></a>
									</div>
								  </li> --}}
									<li class="@if ($menu == 'berita')
									active
								@endif"><a href="{{ url('halaman/berita') }}">Berita</a></li>
									<li class="@if ($menu == 'kontak')
									active
								@endif"><a href="{{ url('halaman/kontak') }}">Kontak</a></li>
								</ul>
								<div class="search_button"><i class="fa fa-search" aria-hidden="true"></i></div>

								<!-- Hamburger -->

								{{-- <div class="shopping_cart"><i class="fa fa-shopping-cart" aria-hidden="true"></i></div> --}}
								<div class="hamburger menu_mm">
									<i class="fa fa-bars menu_mm" aria-hidden="true"></i>
								</div>
							</nav>
						</div>
						<marquee direction="left">Telah ditemukan beberapa varian baru covid 19 di desa gunung tanjung kecamatan selaawi kabupaten garut</marquee> 
					</div>
				</div>
			</div>
		</div>

		<!-- Header Search Panel -->
		<div class="header_search_container">
			<div class="container">
				<div class="row">
					<div class="col">
						<div class="header_search_content d-flex flex-row align-items-center justify-content-end">
							<form action="#" class="header_search_form">
								<input type="search" class="search_input" placeholder="Search" required="required">
								<button class="header_search_button d-flex flex-column align-items-center justify-content-center">
									<i class="fa fa-search" aria-hidden="true"></i>
								</button>
							</form>
						</div>
					</div>
				</div>
			</div>			
		</div>			
	</header>

	<!-- Menu -->

	<div class="menu d-flex flex-column align-items-end justify-content-start text-right menu_mm trans_400">
		<div class="menu_close_container"><div class="menu_close"><div></div><div></div></div></div>
		<div class="search">
			<form action="#" class="header_search_form menu_mm">
				<input type="search" class="search_input menu_mm" placeholder="Search" required="required">
				<button class="header_search_button d-flex flex-column align-items-center justify-content-center menu_mm">
					<i class="fa fa-search menu_mm" aria-hidden="true"></i>
				</button>
			</form>
		</div>
		<nav class="menu_nav">
			<ul class="menu_mm">
				<li class="menu_mm"><a href="{{ url('/') }}">Beranda</a></li>
				<li class="menu_mm"><a href="{{ url('halaman/profil') }}">Profil Desa</a></li>
				<li class="menu_mm"><a href="{{ url('halaman/pasardesa') }}">Pasar Desa</a></li>
				<li class="menu_mm"><a href="{{ url('halaman/berita') }}">Berita</a></li>
				<li class="menu_mm"><a href="{{ url('halaman/kontak') }}">Kontak Kami</a></li>
				@if (isset(Auth::user()->id))
					<li class="menu_mm"><a href="{{ route('dashboard') }}">Dashboard</a></li>
				@else
					<li class="menu_mm"><a href="{{ url('login') }}">Login</a></li>
				@endif
			</ul>
		</nav>
	</div>

    @yield('container')
	
	

	<!-- Footer -->

	<footer class="footer">
		<div class="footer_background" style="background-image:url(images/footer_background.png)"></div>
		<div class="container">
			<div class="row footer_row">
				<div class="col">
					<div class="footer_content">
						<div class="row">

							<div class="col-lg-3 footer_col">
					
								<!-- Footer About -->
								<div class="footer_section footer_about">
									<div class="footer_logo_container">
										<a href="#">
											<div class="footer_logo_text">Jantung<span>Desa</span></div>
										</a>
									</div>
									<div class="footer_about_text">
										<p>Desa {{ ucwords($desa->nama_desa) }} <br>
											{{ $info->tentang }}
										</p>
									</div>
									<div class="footer_social">
										<ul>
											<li><a href="{{ $info->link_fb }}" target="_blank"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
											{{-- <li><a href="{{ $info->link_fb }}" target="_blank"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li> --}}
											<li><a href="{{ $info->link_ig }}" target="_blank"><i class="fa fa-instagram" aria-hidden="true"></i></a></li>
											<li><a href="{{ $info->link_tw }}" target="_blank"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
										</ul>
									</div>
								</div>
								
							</div>

							<div class="col-lg-3 footer_col">
					
								<!-- Footer Contact -->
								<div class="footer_section footer_contact">
									<div class="footer_title">Kontak Kami</div>
									<div class="footer_contact_info">
										<ul>
											<li>Email: {{ $info->email }}</li>
											<li>Telepon: {{ $info->telp }}</li>
											<li>{{ $info->alamat }}</li>
										</ul>
									</div>
								</div>
								
							</div>

							<div class="col-lg-3 footer_col">
					
								<!-- Footer links -->
								<div class="footer_section footer_links">
									<div class="footer_title">Link Menu</div>
									<div class="footer_links_container">
										<ul>
											<li><a href="{{ url('/') }}">Beranda</a></li>
											<li><a href="{{ url('halaman/profil') }}">Profil Desa</a></li>
											<li><a href="{{ url('halaman/kontak') }}">Kontak Kami</a></li>
											{{-- <li><a href="#">Features</a></li> --}}
											{{-- <li><a href="courses.html">Courses</a></li> --}}
											{{-- <li><a href="#">Events</a></li> --}}
											{{-- <li><a href="#">Gallery</a></li> --}}
											{{-- <li><a href="#">FAQs</a></li> --}}
										</ul>
									</div>
								</div>
								
							</div>

							<div class="col-lg-3 footer_col clearfix">
					
								<!-- Footer links -->
								{{-- <div class="footer_section footer_mobile">
									<div class="footer_title">Mobile</div>
									<div class="footer_mobile_content">
										<div class="footer_image"><a href="#"><img src="images/mobile_1.png" alt=""></a></div>
										<div class="footer_image"><a href="#"><img src="images/mobile_2.png" alt=""></a></div>
									</div>
								</div> --}}
								
							</div>

						</div>
					</div>
				</div>
			</div>

			<div class="row copyright_row">
				<div class="col">
					<div class="copyright d-flex flex-lg-row flex-column align-items-center justify-content-start">
						<div class="cr_text"><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
Copyright &copy;{{ ambil_tahun() }} All rights reserved | Developer By <a href="https://cikarastudio.com/">Cikara Studio</a> This template is made with <i class="fa fa-heart-o" aria-hidden="true"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
<!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. --></div>
						<div class="ml-lg-auto cr_links">
							{{-- <ul class="cr_list">
								<li><a href="#">Copyright notification</a></li>
								<li><a href="#">Terms of Use</a></li>
								<li><a href="#">Privacy Policy</a></li>
							</ul> --}}
						</div>
					</div>
				</div>
			</div>
		</div>
	</footer>
</div>

<script src="{{ asset('template/unicat/js/jquery-3.2.1.min.js')}}"></script>
<script src="{{ asset('template/unicat/styles/bootstrap4/popper.js')}}"></script>
<script src="{{ asset('template/unicat/styles/bootstrap4/bootstrap.min.js')}}"></script>
<script src="{{ asset('template/unicat/plugins/greensock/TweenMax.min.js')}}"></script>
<script src="{{ asset('template/unicat/plugins/greensock/TimelineMax.min.js')}}"></script>
<script src="{{ asset('template/unicat/plugins/scrollmagic/ScrollMagic.min.js')}}"></script>
<script src="{{ asset('template/unicat/plugins/greensock/animation.gsap.min.js')}}"></script>
<script src="{{ asset('template/unicat/plugins/greensock/ScrollToPlugin.min.js')}}"></script>
<script src="{{ asset('template/unicat/plugins/OwlCarousel2-2.2.1/owl.carousel.js')}}"></script>
<script src="{{ asset('template/unicat/plugins/easing/easing.js')}}"></script>

<script src="{{ asset('template/unicat/plugins/parallax-js-master/parallax.min.js')}}"></script>
<script src="{{ asset('template/unicat/js/custom.js')}}"></script>
@yield('script')
</body>
</html>