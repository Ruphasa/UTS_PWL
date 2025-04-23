<!doctype html>
<html class="no-js" lang="en">

<head>
	<!-- meta data -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

	<!--font-family-->
	<link
		href="https://fonts.googleapis.com/css?family=Poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i"
		rel="stylesheet">

	<link href="https://fonts.googleapis.com/css?family=Rufina:400,700" rel="stylesheet">

	<!-- title of site -->
	<title>SAKAMOTO</title>

	<!-- For favicon png -->
	<link rel="shortcut icon" type="image/icon" href="{{asset('awo/assets/logo/favicon.png')}}" />

	<!--font-awesome.min.css-->
	<link rel="stylesheet" href="{{asset('awo/assets/css/font-awesome.min.css')}}">

	<!--linear icon css-->
	<link rel="stylesheet" href="{{asset('awo/assets/css/linearicons.css')}}">

	<!--flaticon.css-->
	<link rel="stylesheet" href="{{asset('awo/assets/css/flaticon.css')}}">

	<!--animate.css-->
	<link rel="stylesheet" href="{{asset('awo/assets/css/animate.css')}}">

	<!--owl.carousel.css-->
	<link rel="stylesheet" href="{{asset('awo/assets/css/owl.carousel.min.css')}}">
	<link rel="stylesheet" href="{{asset('awo/assets/css/owl.theme.default.min.css')}}">

	<!--bootstrap.min.css-->
	<link rel="stylesheet" href="{{asset('awo/assets/css/bootstrap.min.css')}}">

	<!-- bootsnav -->
	<link rel="stylesheet" href="{{asset('awo/assets/css/bootsnav.css')}}">

	<!--style.css-->
	<link rel="stylesheet" href="{{asset('awo/assets/css/style.css')}}">

	<!--responsive.css-->
	<link rel="stylesheet" href="{{asset('awo/assets/css/responsive.css')}}">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

	<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

</head>

<body>
	<!--[if lte IE 9]>
            <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
        <![endif]-->

	<!--welcome-hero start -->
	<section id="home" class="welcome-hero">

		<!-- top-area Start -->
		<div class="top-area">
			<div class="header-area">
				<!-- Start Navigation -->
				<nav class="navbar navbar-default bootsnav  navbar-sticky navbar-scrollspy"
					data-minus-value-desktop="70" data-minus-value-mobile="55" data-speed="1000">

					<div class="container">

						<!-- Start Header Navigation -->
						<div class="navbar-header">
							<button type="button" class="navbar-toggle" data-toggle="collapse"
								data-target="#navbar-menu">
								<i class="fa fa-bars"></i>
							</button>
							<a class="navbar-brand" href="index.html">SAKAMOTO<span></span></a>

						</div><!--/.navbar-header-->
						<!-- End Header Navigation -->

						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="collapse navbar-collapse menu-ui-design" id="navbar-menu">
							<ul class="nav navbar-nav navbar-right" data-in="fadeInDown" data-out="fadeOutUp">
								<li class=" scroll active"><a href="#home">home</a></li>
								<li class="scroll"><a href="#service">service</a></li>
								<li class="scroll"><a href="#new-goods">new goods</a></li>
								<li class="scroll"><a href="#featured-goods">featured goods</a></li>
								<li class="nav-item">
									<a href="/bayar"><i class="fas fa-shopping-cart"></i></a>
								</li>
								<li class="nav-item dropdown">
									<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#">
										<img src="/SAKAMOTO/public/Adminlte/dist/img/{{ Auth::user()->username }}.png"
											alt="" class="img-size-32 img-circle mr-2">
										<span>{{ Auth::user()->username }}</span>
									</a>
									<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
										<div class="dropdown-item">
											<div class="media">
												<img src="/SAKAMOTO/public/Adminlte/dist/img/{{ Auth::user()->username }}.png"
													alt="" class="img-size-50 img-circle mr-3">
												<div class="media-body">
													<h3 class="dropdown-item-title">
														{{ Auth::user()->username }}
													</h3>
													<p class="text-sm text-muted">{{ Auth::user()->getRole()}}</p>
												</div>
											</div>
										</div>
										<div class="dropdown-divider"></div>
										<a href="#" class="dropdown-item">
											<i class="fas fa-user mr-2"></i> Profile
										</a>
										<a href="#" class="dropdown-item">
											<i class="fas fa-cog mr-2"></i> Settings
										</a>
										<a href="{{ url('/logout') }}" class="dropdown-item">
											<i class="fas fa-sign-out-alt mr-2"></i> Logout
										</a>
									</div>
								</li>
							</ul><!--/.nav -->
						</div><!-- /.navbar-collapse -->
					</div><!--/.container-->
				</nav><!--/nav-->
				<!-- End Navigation -->
			</div><!--/.header-area-->
			<div class="clearfix"></div>

		</div><!-- /.top-area-->
		<!-- top-area End -->

		<div class="container">
			<div class="welcome-hero-txt">
				<h2>いてらしゃい</h2>
				<p>
					Selamat datang di Toko Serba SAKAMOTO, Toko yang menjual berbagai macam barang kebutuhan
					sehari-hari.
				</p>
				<button class="welcome-btn" onclick="window.location.href='#'">contact us</button>
			</div>
		</div>

		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="model-search-content">
						<div class="row align-items-end">
							<div class="col-md-4 col-sm-6">
								<div class="single-model-search">
									<h2>Select Kategori</h2>
									<div class="model-select-icon">
										<select class="form-control">
											<option value="default">Kategori</option>
											@foreach($kategori as $l)
												<option value="{{ $l->kategori_id }}">{{ $l->kategori_nama }}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-4 col-sm-6">
								<div class="single-model-search">
									<h2>Select Price</h2>
									<div class="model-select-icon">
										<select class="form-control">
											<option value="default">Price</option>
											<option value="0-10000">Rp 0 - Rp 10.000</option>
											<option value="10000-50000">Rp 10.000 - Rp 50.000</option>
											<option value="50000+">Rp 50.000+</option>
										</select>
									</div>
								</div>
							</div>
							<div class="col-md-4 col-sm-6">
								<div class="single-model-search text-center">
									<button class="welcome-btn model-search-btn" onclick="window.location.href='#'">
										Search
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

	</section><!--/.welcome-hero-->
	<!--welcome-hero end -->

	<!--service start -->
	<section id="service" class="service">
		<div class="container">
			<div class="service-content">
				<div class="row">
					<div class="col-md-4 col-sm-6">
						<div class="single-service-item">
							<div class="single-service-icon">
								<i class="flaticon-car"></i>
							</div>
							<h2><a href="#">Lingkungan <span> yang</span> Ramah</a></h2>
							<p>
								Kami berkomitmen untuk memberikan pelayanan yang ramah dan profesional kepada setiap
								pelanggan kami.
							</p>
						</div>
					</div>
					<div class="col-md-4 col-sm-6">
						<div class="single-service-item">
							<div class="single-service-icon">
								<i class="flaticon-car-repair"></i>
							</div>
							<h2><a href="#">Keamanan Yang Terjamin</a></h2>
							<p>
								<br>
								Kami memiliki sistem keamanan yang terkuat untuk melindungi Anda.
								<br>
								<br>
							</p>
						</div>
					</div>
					<div class="col-md-4 col-sm-6">
						<div class="single-service-item">
							<div class="single-service-icon">
								<i class="flaticon-car-1"></i>
							</div>
							<h2><a href="#">insurence support</a></h2>
							<p>
								Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut den fugit sed quia.
							</p>
						</div>
					</div>
				</div>
			</div>
		</div><!--/.container-->

	</section><!--/.service-->
	<!--service end-->

	<!--new-goods start -->
	<section id="new-goods" class="new-goods">
		<div class="container">
			<div class="section-header">
				<p>checkout <span>the</span> latest cars</p>
				<h2>Newest Goods</h2>
			</div><!--/.section-header-->
			<div class="new-goods-content">
				<div class="owl-carousel owl-theme" id="new-cars-carousel">
					@foreach ($stok->take(3) as $s)
						<div class="new-goods-item">
							<div class="single-new-goods-item">
								<div class="row">
									<div class="col-md-7 col-sm-12">
										<div class="new-goods-img">
											<img src="{{asset('awo/assets/images/new-goods-model/ncm1.png')}}" alt="img" />
										</div><!--/.new-goods-img-->
									</div>
									<div class="col-md-5 col-sm-12">
										<div class="new-goods-txt">
											<h2><a href="#">{{$s->barang->barang_nama}}</a></h2>
											<span><br>Kategori : {{$s->barang->kategori->kategori_nama}}</span>
											<p>
												Stok Tersisa : {{$s->stok_jumlah}}<br>
											</p>
											<p class="new-goods-para2">
												Produk dari {{$s->supplier->supplier_nama}}<br>
											</p>
											<button class="welcome-btn new-goods-btn" onclick="window.location.href='#'">
												view details
											</button>
										</div><!--/.new-goods-txt-->
									</div><!--/.col-->
								</div><!--/.row-->
							</div><!--/.single-new-goods-item-->
						</div><!--/.new-goods-item-->
					@endforeach
				</div><!--/#new-goods-carousel-->
			</div><!--/.new-goods-content-->
		</div><!--/.container-->

	</section><!--/.new-goods-->
	<!--new-goods end -->

	<!--featured-goods start -->
	<section id="featured-goods" class="featured-goods">
		<div class="container">
			<div class="section-header">
				<p>checkout <span>the</span> featured goods</p>
				<h2>featured goods</h2>
			</div><!--/.section-header-->
			<div class="featured-goods-content">
				<div class="row">
					@foreach ($stok->take(8) as $s)
						<div class="col-lg-3 col-md-4 col-sm-6">
							<div class="single-featured-cars">
								<div class="featured-img-box">
									<div class="featured-cars-img">
										<img src="{{asset('awo/assets/images/featured-goods/fc1.png')}}" alt="cars">
									</div>
									<div class="featured-model-info">
										<p>
											Kategori : {{$s->barang->kategori->kategori_nama}}<br>
										</p>
									</div>
								</div>
								<div class="featured-goods-txt">
									<h2><a href="#">{{$s->barang->barang_nama}}</a></h2>
									<h3>Rp. {{$s->barang->harga_jual}}</h3>
									<p>
										Stok Tersedia : {{$s->stok_jumlah}}<br>
									</p>
								</div>
							</div>
						</div>
					@endforeach
				</div>
			</div>
		</div><!--/.container-->

	</section><!--/.featured-goods-->
	<!--featured-goods end -->

	<!--brand strat -->
	<section id="brand" class="brand">
		<div class="container">
			<div class="brand-area">
				<div class="owl-carousel owl-theme brand-item">
					@foreach ($stok->take(6) as $b)
						<div class="item">
							<a href="#">
								<img src="{{asset('awo/assets/images/brand/br' . $s->stok_id . '.png')}}" alt="brand-image" />
							</a>
						</div><!--/.item-->
					@endforeach
				</div><!--/.owl-carousel-->
			</div><!--/.clients-area-->

		</div><!--/.container-->

	</section><!--/brand-->
	<!--brand end -->

	<!--blog start -->
	<section id="blog" class="blog"></section><!--/.blog-->
	<!--blog end -->

	<!--contact start-->
	<footer id="contact" class="contact">
		<div class="container">
			<div class="footer-top">
				<div class="row">
					<div class="col-md-3 col-sm-6">
						<div class="single-footer-widget">
							<div class="footer-logo">
								<a href="index.html">SAKAMOTO</a>
							</div>
							<p>
								Ased do eiusm tempor incidi ut labore et dolore magnaian aliqua. Ut enim ad minim
								veniam.
							</p>
							<div class="footer-contact">
								<p>info@themesine.com</p>
								<p>+1 (885) 2563154554</p>
							</div>
						</div>
					</div>
					<div class="col-md-2 col-sm-6">
						<div class="single-footer-widget">
							<h2>about devloon</h2>
							<ul>
								<li><a href="#">about us</a></li>
								<li><a href="#">career</a></li>
								<li><a href="#">terms <span> of service</span></a></li>
								<li><a href="#">privacy policy</a></li>
							</ul>
						</div>
					</div>
					<div class="col-md-3 col-xs-12">
						<div class="single-footer-widget">
							<h2>top brands</h2>
							<div class="row">
								<div class="col-md-7 col-xs-6">
									<ul>
										<li><a href="#">BMW</a></li>
										<li><a href="#">lamborghini</a></li>
										<li><a href="#">camaro</a></li>
										<li><a href="#">audi</a></li>
										<li><a href="#">infiniti</a></li>
										<li><a href="#">nissan</a></li>
									</ul>
								</div>
								<div class="col-md-5 col-xs-6">
									<ul>
										<li><a href="#">ferrari</a></li>
										<li><a href="#">porsche</a></li>
										<li><a href="#">land rover</a></li>
										<li><a href="#">aston martin</a></li>
										<li><a href="#">mersedes</a></li>
										<li><a href="#">opel</a></li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-offset-1 col-md-3 col-sm-6">
						<div class="single-footer-widget">
							<h2>news letter</h2>
							<div class="footer-newsletter">
								<p>
									Subscribe to get latest news update and informations
								</p>
							</div>
							<div class="hm-foot-email">
								<div class="foot-email-box">
									<input type="text" class="form-control" placeholder="Add Email">
								</div><!--/.foot-email-box-->
								<div class="foot-email-subscribe">
									<span><i class="fa fa-arrow-right"></i></span>
								</div><!--/.foot-email-icon-->
							</div><!--/.hm-foot-email-->
						</div>
					</div>
				</div>
			</div>
			<div class="footer-copyright">
				<div class="row">
					<div class="col-sm-6">
						<p>
							&copy; copyright.designed and developed by <a
								href="https://www.themesine.com/">themesine</a>.
						</p><!--/p-->
					</div>
					<div class="col-sm-6">
						<div class="footer-social">
							<a href="#"><i class="fa fa-facebook"></i></a>
							<a href="#"><i class="fa fa-instagram"></i></a>
							<a href="#"><i class="fa fa-linkedin"></i></a>
							<a href="#"><i class="fa fa-pinterest-p"></i></a>
							<a href="#"><i class="fa fa-behance"></i></a>
						</div>
					</div>
				</div>
			</div><!--/.footer-copyright-->
		</div><!--/.container-->

		<div id="scroll-Top">
			<div class="return-to-top">
				<i class="fa fa-angle-up " id="scroll-top" data-toggle="tooltip" data-placement="top" title=""
					data-original-title="Back to Top" aria-hidden="true"></i>
			</div>

		</div><!--/.scroll-Top-->

	</footer><!--/.contact-->
	<!--contact end-->



	<!-- Include all js compiled plugins (below), or include individual files as needed -->

	<script src="{{asset('awo/assets/js/jquery.js')}}"></script>

	<!--modernizr.min.js-->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js')}}"></script>

	<!--bootstrap.min.js-->
	<script src="{{asset('awo/assets/js/bootstrap.min.js')}}"></script>

	<!-- bootsnav js -->
	<script src="{{asset('awo/assets/js/bootsnav.js')}}"></script>

	<!--owl.carousel.js-->
	<script src="{{asset('awo/assets/js/owl.carousel.min.js')}}"></script>

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"></script>

	<!--Custom JS-->
	<script src="{{asset('awo/assets/js/custom.js')}}"></script>

</body>

</html>