@extends('front.layouts.app')

@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">Verify Code</li>
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
            <form action="{{ route('account.resetPassword') }}" method="post">
                @csrf
                <h4 class="modal-title">Reset Password</h4>

                <div class="form-group">
                    <input type="password" class="form-control" @error('password') is-invalid @enderror placeholder="Password" required="required" name="password" id="password">
                    @error('password')
                        <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" @error('password_confirmation') is-invalid @enderror placeholder="Password Confirmation" required="required" name="password_confirmation" id="password_confirmation">
                    @error('password_confirmation')
                        <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="text" hidden readOnly class="form-control" @error('phone_number') is-invalid @enderror placeholder="Phone" required="required" name="phone_number" id="phone_number" >
                    @error('phone_number')
                        <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>
                <div class="form-group">
                    <input type="text" hidden readOnly class="form-control" @error('code') is-invalid @enderror placeholder="Phone" required="required" name="code" id="code" >
                    @error('code')
                        <p class="invalid-feedback">{{ $message }}</p>
                    @enderror
                </div>

                <input type="submit" class="btn btn-dark btn-block btn-lg" value="Reset">
            </form>
            <div class="text-center small">Remember your password? <a href="{{ route('account.login') }}">Login</a></div>
        </div>
    </div>
</section>

@endsection
@section('customJs')

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Function to get URL parameters by name
        function getParameterByName(name, url) {
            if (!url) url = window.location.href;
            name = name.replace(/[\[\]]/g, "\\$&");
            var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
                results = regex.exec(url);
            if (!results) return null;
            if (!results[2]) return '';
            return decodeURIComponent(results[2].replace(/\+/g, " "));
        }

        // Get the values of phone_number and code from the URL
        var phoneNumber = getParameterByName('phone_number');
        var code = getParameterByName('code');

        document.getElementById("phone_number").value = phoneNumber;
        document.getElementById("code").value = code;


    });
</script>
    
@endsection
