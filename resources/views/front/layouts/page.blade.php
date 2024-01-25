<!DOCTYPE html>
<html class="no-js" lang="en_AU" />
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title><?php echo (!empty($title)) ? 'Title-'.$title: 'Look Me Cosmetic || Feel the Beauty'; ?></title>
	<link rel="icon" type="image/png" href="{{ asset('/front-assets/images/logo.png') }}">
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
        .blue-line {
            border: 1px solid blue;
            margin: 2.5rem 0; /* Adjust margin as needed */
        }
        .page-container {
            min-height: 60rem !important;
        }
        
        @media (max-width: 760px) {
            /* Set background to light for small devices */
            .bg-nav-bar {
                background-color: ##f1f1f1 !important; /* Change this to your desired light color */
                color: white !important;
            }
            
            .navbar-collapse{
                background-color:#000000;
            }
            .top-header{
                display:none;
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
                background-color: #a6151b !important;
                color: white !important;
                margin-top: 6rem; 
            }
            
            .fa-shopping-cart{
                color:white !important;
            }
            
            .navbar-toggler-icon{
                color:white !important;
            }
            .top-header{
                display:block;
            }
        }
        

        
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>
<body data-instant-intensity="mousedown">

<div class="bg-light top-header fixed-top">
	<div class="container">
		<div class="row align-items-center py-3 justify-content-between">
			<div class="col-md-4 logo">
				<a href="{{ route('home') }}" class="text-decoration-none">
                    <img src="{{ asset('/front-assets/images/logo.png') }}" style="height: 60px; width:120px; opacity: .8"/>
				</a>
			</div>
			<div class="col-md-6 col-6 text-left  d-flex justify-content-end align-items-center">
                @if (Auth::check())
				<a href="{{ route('account.profile') }}" class="nav-link text-dark">My Account</a>
                @else
                <a href="{{ route('account.login') }}" class="nav-link text-dark">Login</a>
                <a href="{{ route('account.register') }}" class="nav-link text-dark">Register</a>

                @endif
				
			</div>
		</div>
	</div>
</div>

<header class="bg-nav-bar">
    <div class="container">
        <nav class="navbar navbar-expand-xl" id="navbar">
            <a href="{{ route('home') }}" class="text-decoration-none mobile-logo d-md-none d-lg-none">
                <img src="{{ asset('/front-assets/images/logo.png') }}" style="height: 30px; opacity: .8;"/>
            </a>
            <button class="navbar-toggler menu-btn justify-content-end ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <!-- <span class="navbar-toggler-icon icon-menu"></span> -->
                <i class="navbar-toggler-icon fas fa-bars"></i>
            </button>
    		<div class="collapse navbar-collapse" id="navbarSupportedContent">
      			<ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    @if (staticPages()->isNotEmpty())
                    @foreach (staticPages() as $item )
                        @if($item->show_in_header == 1)
                            <li class="nav-item dropdown">
                                @if ($item->for_registered == 0 || ($item->for_registered == 1 && Auth::check()))
                                    <a class="btn btn-dark" href="{{ route('front.page',[$item->slug]) }}">{{ $item->name }}</a>
                                @endif
                               
        					</li>
    					@endif
                    @endforeach
                    @endif
      			</ul>
      		</div>
        </nav>
    </div>
</header>
<section class="page-container">
<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">{{ $page->name }}</li>
            </ol>
        </div>
    </div>
</section>

@if($page->slug == 'contact-us')
<section class=" section-10">
    <div class="container">
        <div class="section-title mt-5 ">
            <h2>Love to Hear From You</h2>
        </div>
    </div>
</section>

<section>
    <div class="container">
        <div class="row">
            <div class="col-md-6 mt-3 pe-lg-5">
                {!! $page->content !!}
            </div>

            <div class="col-md-6">
                <form class="shake" role="form" method="post" id="contactForm" name="contact-form">
                    <div class="mb-3">
                        <label class="mb-2" for="name">Name</label>
                        <input class="form-control" id="name" type="text" name="name" required data-error="Please enter your name">
                        <div class="help-block with-errors"></div>
                    </div>

                    <div class="mb-3">
                        <label class="mb-2" for="email">Email</label>
                        <input class="form-control" id="email" type="email" name="email" required data-error="Please enter your Email">
                        <div class="help-block with-errors"></div>
                    </div>

                    <div class="mb-3">
                        <label class="mb-2">Subject</label>
                        <input class="form-control" id="msg_subject" type="text" name="subject" required data-error="Please enter your message subject">
                        <div class="help-block with-errors"></div>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="mb-2">Message</label>
                        <textarea class="form-control" rows="3" id="message" name="message" required data-error="Write your message"></textarea>
                        <div class="help-block with-errors"></div>
                    </div>

                    <div class="form-submit">
                        <button class="btn btn-dark" id="form-submit"><i class="material-icons mdi mdi-message-outline"></i> Send Message</button>
                        <div id="msgSubmit" class="h3 text-center hidden"></div>
                        <div class="clearfix"></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@else
<section class=" section-10">
    <div class="container">
        <h1 class="my-3">{{ $page->name }}</h1>
        {!! $page->content !!}

    </div>
</section>
@endif
</section>
<footer class="bg-dark">
	<div class="container pb-5 pt-3">
		<div class="row">
			<div class="col-md-4">
				<div class="footer-card">
					<h3>Get In Touch</h3>
					<p>Corporate Office : <strong>Itahari, Sunsari</strong><br>
					Phone Number : <strong>025586765</strong> <br>
					Mobile Number : <strong>9804094094</strong> <br>
					Email:: <strong>lookmenepal@gmail.com</strong>

				</div>
			</div>

			<div class="col-md-4">
				<div class="footer-card">
					<h3>Important Links</h3>
					<ul>
                      @if (staticPages()->isNotEmpty())
                        @foreach (staticPages() as $staticPage)
                            @if($staticPage->show_in_footer == 1)
                                @if ($staticPage->for_registered == 0 || ($staticPage->for_registered == 1 && Auth::check()))
                                    <li><a href="{{ route('front.page', $staticPage->slug) }}" title="{{ $staticPage->name }}">{{ $staticPage->name }}</a></li>
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
						<li><a href="{{ route('account.login') }}" title="Sell">Login</a></li>
						<li><a href="{{ route('account.register') }}" title="Advertise">Register</a></li>
						<li><a href="{{ route('account.orders') }}" title="Contact Us">My Orders</a></li>
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
						<p>© Copyright || Look Me Cosmetics. <br> 
						All Rights Reserved  Made With ♥ By Kafals</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</footer>



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

</script>
</body>
</html>

