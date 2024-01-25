@extends('front.layouts.app')

@section('content')

<section class="section-1">
    @if($bannerImages->isNotEmpty() && count($bannerImages) > 1)
        <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="false">
            <div class="carousel-inner">
                @php 
                    $i = 0; 
                @endphp
                @foreach($bannerImages as $bannerImage)
                    <div class="carousel-item {{ $i == 0 ? 'active' : '' }}">
                        <picture>
                            <source media="(max-width: 799px)" srcset="{{ asset('uploads/banner/small/'. $bannerImage->image) }}" />
                            <source media="(min-width: 800px)" srcset="{{ asset('uploads/banner/small/'. $bannerImage->image) }}" />
                            <img src="{{ asset('uploads/banner/large/'. $bannerImage->image) }}" alt="" />
                        </picture>
                    </div>
                    @php 
                        $i++; 
                    @endphp
                @endforeach
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>
    @elseif($bannerImages->isNotEmpty() && count($bannerImages) == 1)
        @foreach($bannerImages as $bannerImage)
            <div>
                <picture>
                    <source media="(max-width: 799px)" srcset="{{ asset('uploads/banner/small/'. $bannerImage->image) }}" />
                    <source media="(min-width: 800px)" srcset="{{ asset('uploads/banner/small/'. $bannerImage->image) }}" />
                    <img src="{{ asset('uploads/banner/large/'. $bannerImage->image) }}" alt="" />
                </picture>
            </div>
        @endforeach
    @endif
</section>


<section class="section-2">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="box shadow-lg d-flex justify-content-between align-items-center">
                    <div class="fa icon fa-check text-primary m-0 mr-3"></div>
                    <h2 class="font-weight-semi-bold m-0">Premium Quality</h2>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="box shadow-lg d-flex justify-content-between align-items-center">
                    <div class="fa icon fa-shipping-fast text-primary m-0 mr-3"></div>
                    <h2 class="font-weight-semi-bold m-0">All Nepal Delivery</h2>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="box shadow-lg d-flex justify-content-between align-items-center">
                    <div class="fa icon fa-exchange-alt text-primary m-0 mr-3"></div>
                    <h2 class="font-weight-semi-bold m-0">Diverse Product Range</h2>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="box shadow-lg d-flex justify-content-between align-items-center">
                    <div class="fa icon fa-phone-volume text-primary m-0 mr-3"></div>
                    <h2 class="font-weight-semi-bold m-0">24/7 Support</h2>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="section-3">
    <div class="container">
        <div class="section-title">
            <h2>Categories</h2>
        </div>
        <div class="row pb-3">
            @if (getCategories()->isNotEmpty())
                @foreach (getCategories() as $category )
                <div class="col-lg-3">
                    <a href="{{ route('front.shop', [$category->slug]) }}">

                    <div class="cat-card">
                        <div class="left">
                             <!-- Display image thumbnail if category->image is not empty -->
                             @if(!empty($category->image))
                             <img src="{{ asset('uploads/category/thumb/' . $category->image) }}" alt="Category Image" class="img-fluid">
                             @else
                             <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="" class="img-fluid">
                         @endif
                        </div>
                        <div class="right">
                            <div class="cat-data">
                                <h2>{{ $category->name }}</h2>
                                <p>{{ $category->products_count }} Products</p>
                            </div>
                        </div>
                    </div>
                    </a>
                </div>

                @endforeach
            @endif

        </div>
    </div>
</section>

@if($featuredProducts->isNotEmpty())
    <section class="section-4 pt-5">
        <div class="container">
            <div class="section-title">
                <h2>Featured Products</h2>
            </div>
            <div class="row pb-3">
                @foreach ($featuredProducts as $featuredProduct)
                    <div class="col-md-3">
                                        <div class="card product-card">
                    <div class="product-image position-relative">
                        <a href="{{ route('front.product',$featuredProduct->slug) }}" class="product-img">
                            @if (!empty($featuredProduct->images) && count($featuredProduct->images) > 0)

                                <img src="{{ asset('uploads/products/small/' . $featuredProduct->images->last()->image) }}" class="card-img-top">

                            @else
                                <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="" class="mr-2">
                            @endif
                        </a>
                        <a onclick="addToWishList('{{ $featuredProduct->id }}')" href="javascript:void(0)" class="whishlist" href="222"><i class="far fa-heart"></i></a>

                        <div class="product-action">
                            @if($featuredProduct->track_qty == 'Yes')
                                @if ($featuredProduct->qty > 0)
                                    <a class="btn btn-dark" href="javascript:void(0)" onclick="addToCart('{{ $featuredProduct->id }}')">
                                        <i class="fa fa-shopping-cart"></i> &nbsp;ADD TO CART
                                    </a>
                                @else
                                    <a class="btn btn-dark" href="javascript:void(0)" >
                                        <i class="fa fa-shopping-cart"></i> OUT OF STOCK
                                    </a>
                                @endif
                            @else
                                <a class="btn btn-dark" href="javascript:void(0)" onclick="addToCart('{{ $featuredProduct->id }}')">
                                    <i class="fa fa-shopping-cart"></i> &nbsp;ADD TO CART
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body text-center mt-3">
                        <a class="h6 link" href="product.php">{{ $featuredProduct->title }} - {{ $featuredProduct->measurement_value }} {{ $featuredProduct->measurement_unit }}</a>
                        <div class="price mt-2">
                            <span class="h5"><strong>NRs. {{ $featuredProduct->price }}</strong></span>
                            @if ($featuredProduct->compare_price)
                            <span class="h6 text-underline"><del>NRs. {{ $featuredProduct->compare_price }}</del></span>
                            @endif
                        </div>
                    </div>
                </div>

                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif


<section class="section-4 pt-5">
    <div class="container">
        <div class="section-title">
            <h2>Latest Produsts</h2>
        </div>
        <div class="row pb-3">
            @if ($latestProducts->isNotEmpty())
            @foreach ($latestProducts as $latestProduct)
            <div class="col-md-3">
                <div class="card product-card">
                    <div class="product-image position-relative">
                        <a href="{{ route('front.product',$latestProduct->slug) }}" class="product-img">
                            @if (!empty($latestProduct->images) && count($latestProduct->images) > 0)

                            <img src="{{ asset('uploads/products/small/' . $latestProduct->images->last()->image) }}" class="card-img-top">

                        @else
                            <img src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="" class="mr-2">
                        @endif
                        </a>
                        <a onclick="addToWishList('{{ $latestProduct->id }}')" href="javascript:void(0)" class="whishlist" href="222"><i class="far fa-heart"></i></a>

                        <div class="product-action">
                            @if($latestProduct->track_qty == 'Yes')
                                @if ($latestProduct->qty > 0)
                                    <a class="btn btn-dark" href="javascript:void(0)" onclick="addToCart('{{ $latestProduct->id }}')">
                                        <i class="fa fa-shopping-cart"></i> &nbsp;ADD TO CART
                                    </a>
                                @else
                                    <a class="btn btn-dark" href="javascript:void(0)" >
                                        <i class="fa fa-shopping-cart"></i> OUT OF STOCK
                                    </a>
                                @endif
                            @else
                                <a class="btn btn-dark" href="javascript:void(0)" onclick="addToCart('{{ $latestProduct->id }}')">
                                    <i class="fa fa-shopping-cart"></i> &nbsp;ADD TO CART
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="card-body text-center mt-3">
                        <a class="h6 link" href="product.php">{{ $latestProduct->title }} - {{ $latestProduct->measurement_value }} {{ $latestProduct->measurement_unit }}</a>
                        <div class="price mt-2">
                            <span class="h5"><strong>NRs {{ $latestProduct->price }}</strong></span>
                            @if ($latestProduct->compare_price)
                            <span class="h6 text-underline"><del>NRs. {{ $latestProduct->compare_price }}</del></span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
            @endif
        </div>
    </div>
</section>

@endsection

