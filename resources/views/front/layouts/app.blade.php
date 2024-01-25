<!DOCTYPE html>
<html class="no-js" lang="en_AU" />
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo (!empty($title)) ? 'Title-'.$title: 'Look Me Cosmetic || Feel the Beauty'; ?></title>
	<link rel="icon" type="image/png" href="{{ asset('/front-assets/images/logo.png') }}" size="32x32">
	<meta name="description" content="" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=no" />

	<meta name="HandheldFriendly" content="True" />
	<meta name="pinterest" content="nopin" />

	<meta property="og:locale" content="en_AU" />
	<meta property="og:type" content="website" />
	<meta property="fb:admins" content="" />
	<meta property="fb:app_id" content="" />
	<meta property="og:site_name" content="" />
	<meta property="og:title" content="" />
	<meta property="og:description" content="" />
	<meta property="og:url" content="" />
	<meta property="og:image" content="" />
	<meta property="og:image:type" content="image/jpeg" />
	<meta property="og:image:width" content="" />
	<meta property="og:image:height" content="" />
	<meta property="og:image:alt" content="" />

	<meta name="twitter:title" content="" />
	<meta name="twitter:site" content="" />
	<meta name="twitter:description" content="" />
	<meta name="twitter:image" content="" />
	<meta name="twitter:image:alt" content="" />
	<meta name="twitter:card" content="summary_large_image" />


	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/slick.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/slick-theme.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/video-js.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/style.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('front-assets/css/ion.rangeSlider.min.css') }}" />

	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;500&family=Raleway:ital,wght@0,400;0,600;0,800;1,200&family=Roboto+Condensed:wght@400;700&family=Roboto:wght@300;400;700;900&display=swap" rel="stylesheet">

	<!-- Fav Icon -->
	<link rel="shortcut icon" type="image/x-icon" href="#" />
	<style>
        @media (max-width: 760px) {
            /* Set background to light for small devices */
            .navbar {
                background-color: #f1f1f1 !important; /* Change this to your desired light color */
                color: white !important;
            }
            
            .menu-btn{
                font-size: 1.5rem !important;

            }
            
            .navbar-collapse{
                background-color:#000000;
            }
            
            .fa-shopping-cart{
                color:#a08310  !important;
                font-size: 1.5rem;
            }
            
            header .btn{
                font-size: 15px;
            }
            
            .fa-bars{
                font-size: 1.5em !important;
                margin-right:1.5rem !important;
            }
            header img{
                margin-left: 1.5rem !important;
            }
            
            header .navbar-collapse{
                background-color: #a08310 !important;
            }
        
        }

        @media (min-width: 760px) {

            .top-header {
                position: fixed;
                top: 0;
                width: 100%;
                z-index: 1000; 
            }
        
            .bg-nav-bar{
                background-color: #a08310 !important;
                color: white !important;
                margin-top: 6rem !important; 
            }
            
            .navbar-toggler-icon{
                color:white !important;
            }
            .top-header{
                max-height: 10rem !important;
                overflow: auto;
            }
            
            .fa-shopping-cart{
                color:#a08310  !important;
                margin-left:2rem;
                font-size: 2rem;
            }
        
        }
        
        @media (min-width: 1200px) {
            .dropdown-menu-dark {
                background-color: #a08310 !important;
            }
        }
        
        header .btn-red{
            color:#a08310 !important;
        }
        
        header .btn-red.active{
            color:blue !important;
        }

        .main-container {
            min-height: 45rem !important;
        }
        
        .nav-item .btn-dark.active{
            color:yellow !important;
            background-color: transparent !important;
        }
        
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body data-instant-intensity="mousedown">

<div class="bg-light top-header">
	<div class="container">
		<div class="row align-items-center py-3 d-none d-md-flex justify-content-between">
			<div class="col-md-4 logo">
				<a href="{{ route('home') }}" class="text-decoration-none">
                    <img src="{{ asset('/front-assets/images/logo.png') }}" style="height: 60px; width:120px; opacity: .8"/>
				</a>
			</div>

			<div class="col-lg-6 col-6 text-left  flex-wrap  d-flex justify-content-end align-items-center">
                
				<form action="{{ route('front.shop') }}">
					<div class="input-group">
						<input type="text" placeholder="Search For Products" class="form-control" name="search" id="search" value="{{ Request::get('search') }}">
						<button class="input-group-text" type="submit">
							<i class="fa fa-search"></i>
                        </button>
					</div>
				</form>
				<div class="right-nav py-0">
    				<a href="{{ route('front.cart') }}" class="ml-3 d-flex pt-2">
    					<i class="fas fa-shopping-cart text-secondary"></i>
    				</a>
    			</div>
			</div>
		</div>
	</div>
</div>
<header class="bg-nav-bar">
	<div class="container">
		<nav class="navbar navbar-expand-xl" id="navbar">
			<a href="{{ route('home') }}" class="text-decoration-none mobile-logo d-md-none d-lg-none d-flex align-items-center ml-2">
		        <img src="{{ asset('/front-assets/images/logo.png') }}" style="height: 40px; opacity: .8;"

			</a>
			<div class="right-nav py-0 d-md-none">
				<a href="{{ route('front.cart') }}" class="ml-3 d-flex pt-2">
					<i class="fas fa-shopping-cart text-secondary"></i>
				</a>
    		</div>
			<button class="navbar-toggler menu-btn d-flex d-xl-none align-items-center" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      			<!-- <span class="navbar-toggler-icon icon-menu"></span> -->
				  <i class="navbar-toggler-icon fas fa-bars"></i>
    		</button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @if (getCategories()->isNotEmpty())
                        <li class="nav-item dropdown">
                            <a class="btn btn-dark dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Products
                            </a>
                            <ul class="dropdown-menu dropdown-menu-light">
                                @foreach (getCategories() as $category)
                                    <li>
                                        <a class="btn btn-red {{ Route::is('front.shop*') && request()->route('categorySlug') === $category->slug ? 'active' : '' }}"
                                            href="{{ route('front.shop', [$category->slug]) }}">{{ $category->name }}</a>
                                    </li>
                                @endforeach
                            </ul>

                        </li>
                    @endif
            
                    @if (staticPages()->isNotEmpty())
                        @foreach (staticPages() as $item)
                            <li class="nav-item dropdown">
                                @if ($item->show_in_header == 1)
                                    @if ($item->for_registered == 0 || ($item->for_registered == 1 && Auth::check()))
                                        <a class="btn btn-dark {{ Route::is('front.page*') && request()->route('slug') === $item->slug ? 'active' : '' }}" href="{{ route('front.page', ['slug' => $item->slug]) }}">{{ $item->name }}</a>


                                    @endif
                                @endif
                            </li>
                        @endforeach
                    @endif
                    
                </ul>
                <ul class="navbar-nav ml-auto">
                    @if (Auth::check())
                        <li class="nav-item">
                            <a href="{{ route('account.profile') }}" class="btn btn-dark  {{ Route::is('account.profile') ? 'active' : '' }}">My Account</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('account.login') }}" class="btn btn-dark  {{ Route::is('account.login') ? 'active' : '' }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('account.register') }}" class="btn btn-dark  {{ Route::is('account.register') ? 'active' : '' }}">Register</a>
                        </li>
                    @endif
                </ul>
            </div>
				
      	</nav>
  	</div>
</header>
<main>
    <section class="main-container">@yield('content')</section>
</main>

<footer class="bg-dark mt-5">
	<div class="container pb-5 pt-3">
		<div class="row">
		    <div class="col-md-4">
    			<div class="footer-card">
                    <h3>Get In Touch</h3>
                    <p>Corporate Office : <strong>{{ optional(getContact())->corporate_office ?? 'Itahari, Sunsari' }}</strong><br>
                    Phone Number : <strong>{{ optional(getContact())->phone_number ?? '025586765' }}</strong> <br>
                    Mobile Number : <strong>{{ optional(getContact())->mobile_number ?? '9804094094' }}</strong> <br>
                    Email: <strong>{{ optional(getContact())->email ?? 'lookmenepal@gmail.com' }}</strong>
                </div>
            </div>


			<div class="col-md-4">
				<div class="footer-card">
					<h3>Quick Links</h3>
					<ul>
                      @if (staticPages()->isNotEmpty())
                        @foreach (staticPages() as $page)
                            @if($page->show_in_footer == 1)
                                @if ($page->for_registered == 0 || ($page->for_registered == 1 && Auth::check()))
                                    <li><a href="{{ route('front.page', $page->slug) }}" title="{{ $page->name }}">{{ $page->name }}</a></li>
                                @endif
                            @endif
                        @endforeach
                    @endif

					</ul>
				</div>
			</div>

			<div class="col-md-4">
                <div class="footer-card">
                    <h3>My Account</h3>
                    <ul>
                        @guest
                            <li><a href="{{ route('account.login') }}" title="Login">Login</a></li>
                            <li><a href="{{ route('account.register') }}" title="Register">Register</a></li>
                        @else
                            <li><a href="{{ route('account.orders') }}" title="My Orders">My Orders</a></li>
                        @endguest
                    </ul>
                </div>
            </div>

		</div>
	</div>
	<div class="copyright-area">
		<div class="container">
			<div class="row">
				<div class="col-12 mt-3">
					<div class="copy-right text-center">
						<p>© Copyright 2022 ||  Me Cosmetics. <br>
						All Rights Reserved  Made With ♥ By Kafals</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>


  <!-- Modal -->
  <div class="modal fade" id="wishlistModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="wishlistModalLabel">Success</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          ...
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          {{-- <button type="button" class="btn btn-primary">Save changes</button> --}}
        </div>
      </div>
    </div>
  </div>

<script src="{{ asset("front-assets/js/jquery-3.6.0.min.js") }}"></script>
<script src="{{ asset("front-assets/js/bootstrap.bundle.5.1.3.min.js") }}"></script>
<script src="{{ asset("front-assets/js/instantpages.5.1.0.min.js") }}"></script>
<script src="{{ asset("front-assets/js/lazyload.17.6.0.min.js") }}"></script>
<script src="{{ asset("front-assets/js/slick.min.js") }}"></script>
<script src="{{ asset("front-assets/js/custom.js") }}"></script>
<script src="{{ asset("front-assets/js/ion.rangeSlider.min.js") }}"></script>
<!-- Include Khalti SDK -->
<script src="https://khalti.s3.ap-south-1.amazonaws.com/KPG/dist/2020.12.17.0.0.0/khalti-checkout.iffe.js"></script>



<script>
window.onscroll = function() {myFunction()};

var navbar = document.getElementById("navbar");
var sticky = navbar.offsetTop;

function myFunction() {
  if (window.pageYOffset >= sticky) {
    navbar.classList.add("sticky")
  } else {
    navbar.classList.remove("sticky");
  }
}

$.ajaxSetup({
    headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function addToCart(id){
    $.ajax({
        url: '{{ route("front.addToCart") }}',
        type: 'post',
        data: {id:id},
        dataType: 'json',
        success: function(response){
            if(response.status == true){
                window.location.href = "{{ route('front.cart') }}"
            }else{
                alert(response.message);
            }
        }
    })
}

function addToWishList(id){
    $.ajax({
        url: '{{ route("front.addToWishList") }}',
        type: 'post',
        data: {id:id},
        dataType: 'json',
        success: function(response){
            if(response.status == true){
                $('#wishlistModal .modal-body').html(response.message);
                $("#wishlistModal").modal('show');

            }else{
                window.location.href = "{{ route('account.login') }}"
            }
        }
    })
}

</script>
@yield('customJs')
</body>
</html>

