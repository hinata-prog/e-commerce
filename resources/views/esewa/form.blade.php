@extends('front.layouts.app')

@section('content')
    <div class="container col-md-12">
        <div class="spinner-grow text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-grow text-secondary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-grow text-success" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-grow text-danger" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-grow text-warning" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-grow text-info" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-grow text-light" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="spinner-grow text-dark" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
         <form id="esewaForm" action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST">
            @csrf
             <input type="text" id="amount" name="amount" value="{{ $order->grand_total }}" required hidden>
             <input type="text" id="tax_amount" name="tax_amount" value ="0" required hidden>
             <input type="text" id="total_amount" name="total_amount" value="{{ $order->grand_total }}" required hidden>
             <input type="text" id="transaction_uuid" name="transaction_uuid" value="{{ $order->transaction_uuid }}" required hidden>
             <input type="text" id="product_code" name="product_code" value="{{ config('app.esewa_merchant_code') }}" required hidden>
             <input type="text" id="product_service_charge" name="product_service_charge" value="0" required hidden>
             <input type="text" id="product_delivery_charge" name="product_delivery_charge" value="0" required hidden>
             <input type="text" id="success_url" name="success_url" value="{{ route('esewaSuccess') }}" required hidden>
             <input type="text" id="failure_url" name="failure_url" value="{{ route('esewaFailure') }}" required hidden>
             <input type="text" id="signed_field_names" name="signed_field_names" value="total_amount,transaction_uuid,product_code" required hidden>
            <input type="text" id="signature" name="signature" required hidden>
            <input value="Submit" type="submit" hidden>
         </form>
        
    </div>
@endsection
@section('customJs')
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/crypto-js.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/hmac-sha256.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/crypto-js/3.1.9-1/enc-base64.min.js"></script>

    <script>
        function submitForm() {
      

        var secret = decodeURIComponent("{!! config('app.esewa_secret_key') !!}");
        console.log(secret);
    
        var total_amount = document.getElementById("total_amount").value;
        var transaction_uuid = document.getElementById("transaction_uuid").value;
        var product_code = document.getElementById("product_code").value;

        var hash = CryptoJS.HmacSHA256(
            `total_amount=${total_amount},transaction_uuid=${transaction_uuid},product_code=${product_code}`,
            `${secret}`);
        var hashInBase64 = CryptoJS.enc.Base64.stringify(hash);
        document.getElementById("signature").value = hashInBase64;

        document.getElementById("esewaForm").submit();    
        
    }
    
    window.onload = submitForm;

    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

@endsection
