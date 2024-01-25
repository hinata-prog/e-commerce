@extends('front.layouts.app')

@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
                <li class="breadcrumb-item">Checkout</li>
            </ol>
        </div>
    </div>
</section>

<section class="section-9 pt-4">
    <div class="col-md-12 my-3">
        @include('front.account.common.message')

    </div>
    <div class="container">
        <form action="" id="orderForm" name="orderForm" method="post">
        <div class="row">
            <div class="col-md-8">
                <div class="sub-title">
                    <h2>Delivery Address</h2>
                </div>
                <div class="card shadow-lg border-0">
                    <div class="card-body checkout-form">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" value="{{ (!empty($customerAddress)) ? $customerAddress->first_name : '' }}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" value="{{ (!empty($customerAddress)) ? $customerAddress->last_name : '' }}">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="text" name="email" id="email" class="form-control" placeholder="Email" value="{{ (!empty($customerAddress)) ? $customerAddress->email : '' }}">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <select name="province_id" id="province_id" class="form-control">
                                        <option value="">Select a Province</option>
                                        @if ($provinces->isNotEmpty())
                                        @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}" {{ (!empty($customerAddress) && $customerAddress->province_id == $province->id) ? 'selected' : '' }} >{{ $province->name }}</option>

                                        @endforeach

                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <select name="district_id" id="district_id" class="form-control">
                                        <option value="">Select a District</option>
                                        @if (!empty($districts))
                                        @foreach ($districts as $district)
                                        <option value="{{ $district->id }}" {{ (!empty($customerAddress) && $customerAddress->district_id == $district->id) ? 'selected' : '' }} >{{ $district->name }}</option>

                                        @endforeach

                                        @endif
                                    </select>
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <textarea name="municipality" id="municipality" cols="30" rows="3" placeholder="Municipality" class="form-control">{{ (!empty($customerAddress)) ? $customerAddress->municipality : '' }}</textarea>
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="text" name="house_no" id="house_no" class="form-control" placeholder="House No" value="{{ (!empty($customerAddress)) ? $customerAddress->house_no : '' }}">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <input type="text" name="city" id="city" class="form-control" placeholder="City" value="{{ (!empty($customerAddress)) ? $customerAddress->city : '' }}">
                                    <p></p>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="mb-3">
                                    <input type="number" name="mobile" id="mobile" class="form-control" placeholder="Mobile No." value="{{ (!empty($customerAddress)) ? $customerAddress->mobile : '' }}">
                                    <p></p>
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="mb-3">
                                    <textarea name="order_notes" id="order_notes" cols="30" rows="2" placeholder="Order Notes (optional)" class="form-control" value="{{ (!empty($customerAddress)) ? $customerAddress->notes : '' }}"></textarea>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mt-5 sub-title">
                    <h2>Order Summery</h3>
                </div>
                <div class="card cart-summery">
                    <div class="card-body">
                        @foreach (Cart::content() as $item)
                        <div class="d-flex justify-content-between pb-2">
                            <div class="h6">{{ $item->name }} X {{ $item->qty }}</div>
                            <div class="h6">NRs {{ $item->qty * $item->price }}</div>
                        </div>
                        @endforeach
                        <div class="d-flex justify-content-between summery-end">
                            <div class="h6"><strong>Subtotal</strong></div>
                            <div class="h6"><strong>NRs {{ Cart::subtotal() }}</strong></div>
                        </div>
                        <div class="d-flex justify-content-between summery-end">
                            <div class="h6"><strong>Discount</strong></div>
                            <div class="h6"><strong id="discount">NRs {{ $discount }}</strong></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2">
                            <div class="h6"><strong>Delivery</strong></div>
                            <div class="h6"><strong id="shippingAmount">NRs {{ number_format($shippingCharge,2) }}</strong></div>
                        </div>
                        <div class="d-flex justify-content-between mt-2 summery-end">
                            <div class="h5"><strong>Total</strong></div>
                            <div class="h5"><strong id="grandTotal">NRs {{ number_format( $grandTotal,2) }}</strong></div>
                        </div>
                    </div>

                </div>
                <div class="input-group apply-coupan mt-4">
                    <input type="text" placeholder="Coupon Code" class="form-control" name="discount_code" id="discount_code">
                    <button class="btn btn-dark" type="button" id="apply-discount">Apply Coupon</button>
                    <p></p>
                </div>

                <div id="discount-response-wrapper">
                    @if (Session::has('code'))
                    <div class="mt-4 mr-2" id="discount-response">
                        <strong>{{ Session::get('code')->code }}</strong>
                        <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
                    </div>

                    @endif
                </div>


                <div class=" mt-5 sub-title">
                    <h2>Payment Method</h3>
                </div>

                <div class="card payment-form" style="display: flex; flex-direction: column; align-items: left;">
                
                    <div style="display: flex; align-items: center;">
                        <input type="radio" name="payment_method" value="khalti" id="payment-method-one" checked style="width: 20px; height: 20px; margin-right: 10px; vertical-align: middle;">
                        <label for="payment-method-one" class="form-check-label">
                            <img src="{{ asset('/front-assets/images/khalti.png') }}" style="height: 40px; vertical-align: middle;">
                        </label>
                    </div>
                
                    <div style="display: flex; align-items: center;">
                        <input type="radio" name="payment_method" value="esewa" id="payment-method-two" style="width: 20px; height: 20px; margin-right: 10px; vertical-align: middle;">
                        <label for="payment-method-two" class="form-check-label">
                            <img src="{{ asset('/front-assets/images/esewa.png') }}" style=" height: 40px; vertical-align: middle;">
                        </label>
                    </div>
                
                    <div class="pt-4">
                        <button class="btn-dark btn btn-block w-100" type="submit">Pay Now</button>
                    </div>
                </div>


            </div>
        </div>
        </form>
    </div>
</section>

@endsection

@section('customJs')

<script>



    $("#orderForm").submit(function(event){
        event.preventDefault();
        $("button[type=submit]").prop('disabled', true);

        $.ajax({
            url: '{{ route('front.processCheckout') }}',
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response){
                $("button[type=submit]").prop('disabled', false);
                $(".error").removeClass('invalid-feedback');
                $('input[type="text"],textarea, select').removeClass('is-invalid');

                if (response.status == true) {
                    if (response.payment_method == 'khalti'){
                        var transactionId = response.transaction_id;
                        var orderId = response.orderId;
                        var config = {
                            // replace the publicKey with yours
                            "publicKey": "{{ config('app.khalti_public_key') }}",
                            "productIdentity": response.transactionId,
                            "productName": "Order",
                            "productUrl": "{{ route('account.orderDetail', ['orderId' => ':orderId']) }}".replace(':orderId', orderId),
                            "paymentPreference": [
                                "KHALTI",
                                "EBANKING",
                                "MOBILE_BANKING",
                                "CONNECT_IPS",
                                "SCT",
                                ],
                            "eventHandler": {
                                onSuccess: function (payload) {
                                    $.ajax({
                                        url: '{{ route('front.verifyKhaltiPayment') }}',
                                        type: 'post',
                                        data: {
                                            transactionId: payload.product_identity,
                                            token: payload.token,
                                            amount: payload.amount,
                                            "_token" : "{{ csrf_token() }}"
                                        },
                                        dataType: 'json',
                                        success: function(response){
                                            if(response.status == true){
                                                window.location.href = "{{ url('/thanks/') }}/" + response.orderId;
                                            }
                                        },
                                        error: function(){
                                        }
                                    })
                                },
                                onError: function (error) {
                                    window.location.href= "{{ route('esewaFailure') }}";
                                },
                                onClose: function () {
                                    window.location.href= "{{ route('esewaFailure') }}";
                                }
                            }
                        };
                        var khaltiAmount = parseInt(response.grandTotal) * 100;

                        var khaltiCheckout = new KhaltiCheckout(config);
                        khaltiCheckout.show({
                            amount: khaltiAmount
                        });
                    }else{
                        var transactionId = response.transactionId;

                        var routeUrl = "{{ route('esewaPaymentForm', ['transactionId' => ':transactionId']) }}";

                        routeUrl = routeUrl.replace(':transactionId', transactionId);

                        window.location.href = routeUrl;
                    }

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
            error: function(){

            }
        })
    })


    $("#province_id").change(function(event){
        $.ajax({
            url: '{{ route('front.getOrderSummary') }}',
            type: 'post',
            data: {province_id: $(this).val()},
            dataType: 'json',
            success: function(response){
                if(response.status == true){
                    $("#shippingAmount").html('NRs ' + response.shippingCharge);
                    $("#grandTotal").html('NRs ' + response.grandTotal);

                }
            },
            error: function(){

            }
        });

        var province_id = $(this).val();
        $.ajax({
            url: '{{ route('province-districts.index') }}',
            type: 'get',
            data: {province_id: province_id},
            dataType: 'json',
            success: function(response){
                $("#district_id").find("option").not(":first").remove();
                $.each(response['districts'],function(key,item){
                    $("#district_id").append(`<option value='${item.id}'> ${item.name} </option>`)
                })
            },
            error: function (xhr, status, error) {
                console.log("AJAX Request Failed:", status, error);
            }

        });
    })

    $('#apply-discount').click(function(){
        $.ajax({
            url: '{{ route('front.applyDiscount') }}',
            type: 'post',
            data: {code: $('#discount_code').val(), province_id:$('#province').val()},
            dataType: 'json',
            success: function(response){
                if(response.status == true){
                    $("#shippingAmount").html('NRs ' + response.shippingCharge);
                    $("#grandTotal").html('NRs ' + response.grandTotal);
                    $("#discount").html('NRs ' + response.discount);
                    $("#discount-response-wrapper").html(response.discountString);
                    $("#discount_code").removeClass('is-invalid')
                        .siblings('p')
                        .removeClass('invalid-feedback')
                        .html("");


                }
                if(response.status == false){
                    if(response.error){
                    $("#discount_code").addClass('is-invalid')
                    .siblings('p')
                    .addClass('invalid-feedback')
                    .html(response.error);
                    }

                }
            },
            error: function(){

            }
        })
    })

    $('body').on('click','#remove-discount',function(){
        $.ajax({
            url: '{{ route('front.removeCoupon') }}',
            type: 'post',
            data: {province_id:$('#province').val()},
            dataType: 'json',
            success: function(response){
                if(response.status == true){
                    $("#shippingAmount").html('NRs ' + response.shippingCharge);
                    $("#grandTotal").html('NRs ' + response.grandTotal);
                    $("#discount").html('NRs ' + response.discount);
                    $("#discount-response").html('');
                    $('#discount_code').val('');
                }

            },
            error: function(){

            }
        })
    })


</script>



@endsection
