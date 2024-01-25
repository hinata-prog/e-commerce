@extends('front.layouts.app')

@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item">Register</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-10">
    <div class="container">
        <div class="col-md-12">
            @include('front.account.common.message')
        </div>
        <div class="login-form">
            <form action="" method="post" name="registrationForm" id="registrationForm">
                <h4 class="modal-title">Register Now</h4>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Name" id="name" name="name">
                    <p></p>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Phone" id="phone_number" name="phone_number">
                    <p></p>
                </div>
                <button type="submit" class="btn btn-dark btn-block btn-lg" value="Register">Register</button>
                @if (!empty($page))
                <div class="text-center small">By creating an account you are accepting our <a href="{{ route('front.page',$page->slug) }}"><strong>Terms and Conditions</strong></a></div>

                @endif

            </form>
            <div class="text-center small">Already have an account? <a href="{{ route('account.login') }}">Login Now</a></div>
        </div>
    </div>
</section>

@endsection

@section('customJs')

<script type="text/javascript">
    $("#registrationForm").submit(function(event){
    event.preventDefault();
    $("button[type=submit]").prop('disabled', true);


    $.ajax({
        url: '{{ route('account.processRegister') }}',
        type: 'post',
        data: $(this).serializeArray(),
        dataType: 'json',
        success: function(response){
            $("button[type=submit]").prop('disabled', false);

            if (response.status == true) {
                $(".error").removeClass('invalid-feedback');
                $('input[type="text"], select').removeClass('is-invalid');
                var redirectUrl = "{{ route('account.profile') }}";

                @if(session()->has('url.intended') 
                && session('url.intended') !== route('account.logout') 
                && session('url.intended') !== route('admin.login') 
                && session('url.intended') !== route('admin.showVerificationCodeForm') 
                && session('url.intended') !== route('admin.showForgotPasswordForm') 
                && session('url.intended') !== route('admin.showResetPasswordForm'))
                
                    redirectUrl = "{{ session('url.intended') }}";
                @endif

                window.location.href = redirectUrl;


            } else {
                console.log('error');
                var errors = response.errors;
                $.each(errors, function(key, value){
                    $(`#${key}`).addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(value);
                })
            }
        },
        error: function(jQSHR, exception){
            console.log("Something went wrong")
        }

    })
})
</script>

@endsection
