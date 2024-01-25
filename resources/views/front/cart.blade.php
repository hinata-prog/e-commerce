@extends('front.layouts.app')

@section('content')

<section class="section-5 pt-3 pb-3 mb-3 bg-white">
    <div class="container">
        <div class="light-font">
            <ol class="breadcrumb primary-color mb-0">
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('home') }}">Home</a></li>
                <li class="breadcrumb-item"><a class="white-text" href="{{ route('front.shop') }}">Shop</a></li>
                <li class="breadcrumb-item">Cart</li>
            </ol>
        </div>
    </div>
</section>

<section class=" section-9 pt-4">
    <div class="container">
        <div class="row">
            @if (Session::has('success'))
            <div class="col-md-12">
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {!! Session::get('success') !!}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif

            @if (Session::has('error'))
            <div class="col-md-12">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {!! Session::get('error') !!}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>
            @endif

            @if (Cart::count() > 0)
            <div class="col-md-8">
                <div class="table-responsive">
                    <table class="table" id="cart">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Remove</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach($cartContent as $item)
                            <tr>
                                <td class="text-start">
                                    <div class="d-flex align-items-center">
                                        @if ($item->options->has('productImage') && $item->options->get('productImage') !== '')
                                            <img src="{{ asset('uploads/products/small/' . $item->options->get('productImage')) }}" alt="Product Image">
                                        @else
                                        <img class="" src="{{ asset('admin-assets/img/default-150x150.png') }}" alt="">
                                        @endif
                                        <h2>{{ $item->name }}</h2>
                                    </div>
                                </td>
                                <td>NRs {{ $item->price }}</td>
                                <td>
                                    <div class="input-group quantity mx-auto" style="width: 100px;">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-dark btn-minus p-2 pt-1 pb-1 sub" data-id="{{ $item->rowId }}">
                                                <i class="fa fa-minus"></i>
                                            </button>
                                        </div>
                                        <input type="text" class="form-control form-control-sm  border-0 text-center" value="{{ $item->qty }}">
                                        <div class="input-group-btn">
                                            <button class="btn btn-sm btn-dark btn-plus p-2 pt-1 pb-1 add" data-id="{{ $item->rowId }}">
                                                <i class="fa fa-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    NRs {{ $item->price*$item->qty }}
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger" onclick="deleteItem('{{ $item->rowId }}')" ><i class="fa fa-times"></i></button>
                                </td>
                            </tr>

                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card cart-summary">
                    <div class="sub-title">
                        <h2 class="bg-white">Cart Summary</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between items-center pb-2">
                            <div>Subtotal</div>
                            <div  class="ml-2">NRs {{ Cart::subtotal() }}</div>
                        </div>
                        <div class="d-flex justify-content-between pb-2">
                            <div>Delivery</div>
                            <div  class="ml-2">NRs 0</div>
                        </div>
                        <div class="d-flex justify-content-between summery-end">
                            <div>Total</div>
                            <div class="ml-2">NRs {{ Cart::subtotal() }}</div>
                        </div>
                        <div class="pt-5">
                            <a href="{{ route('front.checkout') }}" class="btn-dark btn btn-block w-100">Proceed to Checkout</a>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body d-flex justify-content-center align-items-center p-5">
                        <h4>Your cart is empty!</h4>

                    </div>
                    <div class="card-body d-flex justify-content-center align-items-center">
                        <img src="{{ asset('front-assets/images/empty-cart.gif') }}" style="height: 150px; width: 150px" alt="">
                    </div>

                </div>
            </div>

            @endif

             <!-- Add this modal for confirming deletion  -->
             <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Deletion</h5>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to remove this item?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('customJs')

<script>
    $('.add').click(function(){
      var qtyElement = $(this).parent().prev(); // Qty Input
      var qtyValue = parseInt(qtyElement.val());
      if (qtyValue < 1000) {
          qtyElement.val(qtyValue+1);
          var rowId = $(this).data('id');
          var newQty = qtyElement.val();
          updateCart(rowId, newQty)
      }
    });

    $('.sub').click(function(){
        var qtyElement = $(this).parent().next();
        var qtyValue = parseInt(qtyElement.val());
        if (qtyValue > 1) {
            qtyElement.val(qtyValue-1);
            var rowId = $(this).data('id');
            var newQty = qtyElement.val();
            updateCart(rowId, newQty)
        }
    });

    function updateCart(rowId, qty) {
        $.ajax({
            url : "{{ route('front.updateCart') }}",
            type: 'post',
            data: {rowId:rowId, qty:qty},
            dataType: 'json',
            success: function(response){
                window.location.href = "{{ route('front.cart') }}";

                if (response.status == true){
                }

            }
        })
    }



    function deleteItem(rowId) {
        // Show the delete confirmation modal
        $('#deleteConfirmationModal').modal('show');

        // Handle the click on the "Delete" button in the modal
        $('#confirmDeleteBtn').click(function() {
            // Close the modal
            $('#deleteConfirmationModal').modal('hide');
            $.ajax({
                url : "{{ route('front.deleteItem.cart') }}",
                type: 'post',
                data: {rowId:rowId},
                dataType: 'json',
                success: function(response){
                    window.location.href = "{{ route('front.cart') }}";

                    if (response.status == true){
                    }

                }
            })
        });
    }
</script>

@endsection
