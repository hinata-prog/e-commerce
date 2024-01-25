@extends('front.layouts.app')

@section('content')

<section class="container">
    <div class="col-md-12 text-center py-5">
        @if (Session::has('success'))
            <div class="col-md-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ Session::get('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if (Session::has('error'))
            <div class="col-md-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ Session::get('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
        @endif

        <h1 class="display-4">OOps!</h1>
        <p class="lead">Your Order not placed because your payment is not successful.</p>

            <img src="{{ asset('front-assets/images/sad.gif') }}" class="card-img-top img-fluid" style="height: 150px; width: 150px" alt="Thank You Image">
            <div class="card-body">
                <h5 class="card-title">TRY AGAIN!</h5>
                <p class="card-text">Thank you for choosing our services. Your satisfaction is our priority.</p>
            </div>

    </div>
</section>


@endsection
