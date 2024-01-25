@extends('front.layouts.app')

@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">Login</li>
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
            <form action="{{ route('account.authenticate') }}" method="post">
                @csrf
                <h4 class="modal-title">Login to Your Account</h4>
                <div class="form-group">
                    <input type="text" class="form-control" @error('phone_number') is-invalid @enderror placeholder="Phone Number" required="required" name="phone_number" id="phone_number" value="{{ old('phone_number') }}">
                    @error('phone_number')
                        <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" @error('password') is-invalid @enderror placeholder="Password" required="required" name="password" id="password">
                    @error('password')
                        <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group small">
                    <a href="{{ route('account.showForgotPasswordForm') }}" class="forgot-link">Forgot Password?</a>
                </div>
                <input type="submit" class="btn btn-dark btn-block btn-lg" value="Login">
            </form>
            <div class="text-center small">Don't have an account? <a href="{{ route('account.register') }}">Sign up</a></div>
        </div>
    </div>
</section>

@endsection