@extends('front.layouts.app')

@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">Forgot Password</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-10">
    <div class="container">
        @if (Session::has('success'))
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{  Session::get('success')  }}
            </div>
        </div>
        @endif

        @if (Session::has('error'))
        <div class="col-md-12">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{  Session::get('error')  }}
            </div>
        </div>
        @endif
        <div class="login-form">
            <form action="{{ route('account.sendcode') }}" method="post">
                @csrf
                <h4 class="modal-title">Forgot Password</h4>

                <div class="form-group">
                    <input type="number" class="form-control" @error('phone_number') is-invalid @enderror placeholder="Phone Number" name="phone_number" id="phone_number">
                    @error('phone_number')
                        <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>

                <input type="submit" class="btn btn-dark btn-block btn-lg" value="Send">
            </form>
            <div class="text-center small">Remember your password? <a href="{{ route('account.login') }}">Login</a></div>
        </div>
    </div>
</section>

@endsection
