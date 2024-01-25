@extends('admin.layouts.app')

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid my-2">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Order: {{ $order->id }}</h1>
            </div>
            <div class="col-sm-6 text-right">
                <a href="{{ route('orders.index') }}" class="btn btn-primary">Back</a>
            </div>
        </div>
    </div>
    <!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                @include('admin.message')
                <div class="card">
                    <div class="card-header pt-3">
                        <div class="row invoice-info">
                            <div class="col-12">
                            @if($order->status == 'pending')
                                <h1 class="badge bg-danger" style="font-size: 24px;">Pending</h1>
                            @elseif ($order->status == 'shipped')
                                <h1 class="badge bg-info" style="font-size: 24px;">Shipped</h1>
                            @elseif ($order->status == 'delivered')
                                <h1 class="badge bg-success" style="font-size: 24px;">Delivered</h1>
                            @else
                                <h1 class="badge bg-danger" style="font-size: 24px;">Cancelled</h1>
                            @endif
                            </div>
                            <div class="col-sm-6 invoice-col">

                            <h1 class="h5 mb-3">Shipping Address</h1>
                            <address>
                                <strong>{{ $order->first_name }} {{ $order->last_name }}</strong><br>
                                {{ $order->municipality }}<br>
                                {{ $order->city }}, {{ $order->zip }}<br> {{ $order->province->name }}, {{ $order->district->name }}<br>
                                Phone: {{ $order->mobile }}<br>
                                Email: {{ $order->email }}
                            </address>
                            <strong>Shipped Date</strong>
                                @if(!empty($order->shipped_date))
                                    {{ \Carbon\Carbon::parse($order->shipped_date)->format('d M, Y') }}

                                @else
                                    n/a
                                @endif
                            </div>



                            <div class="col-sm-6 invoice-col">
                                <h1 class="h5 mb-3">Shipping Address</h1>
                                <b>Order ID:</b> {{ $order->id }}<br>
                                <b>Total:</b> NRs {{ $order->grand_total }}<br>
                                <b>Payment Method:</b>
                                    @if($order->payment_method == 'esewa')
                                    <img src="{{ asset('/front-assets/images/esewa.png') }}" style=" height: 40px; vertical-align: middle;">
                                    @elseif($order->payment_method == 'khalti')
                                    <img src="{{ asset('/front-assets/images/khalti.png') }}" style=" height: 40px; vertical-align: middle;">
                                    @endif 
                               
                            </div>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-3">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th width="100">Price</th>
                                    <th width="100">Qty</th>
                                    <th width="100">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($order->orderItems))
                                @foreach ($order->orderItems as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->price }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ $item->total }}</td>
                                </tr>

                                @endforeach

                                @endif

                                <tr>
                                    <th colspan="3" class="text-right">Subtotal:</th>
                                    <td>NRs {{ number_format($order->subtotal) }}</td>
                                </tr>

                                <tr>
                                    <th colspan="3" class="text-right">Discount {{ (!empty($order->coupon_code)) ? '(' . $order->coupon_code . ')' : '' }}:</th>
                                    <td>NRs {{ number_format($order->discount) }}</td>
                                </tr>

                                <tr>
                                    <th colspan="3" class="text-right">Shipping:</th>
                                    <td>NRs {{ number_format($order->shipping,2) }}</td>
                                </tr>
                                <tr>
                                    <th colspan="3" class="text-right">Grand Total:</th>
                                    <td>NRs {{ number_format($order->grand_total,2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card">
                    <form action="" method="post" name="changeOrderStatusForm" id="changeOrderStatusForm">
                    <div class="card-body">
                        <h2 class="h4 mb-3">Order Status</h2>
                        
                            <div class="mb-3">
                                <select name="status" id="status" class="form-control">
                                    <option value="pending" {{ ($order->status == 'pending') ? 'selected' : '' }}>Pending</option>
                                    <option value="shipped" {{ ($order->status == 'shipped') ? 'selected' : '' }}>Shipped</option>
                                    <option value="delivered" {{ ($order->status == 'delivered') ? 'selected' : '' }}>Delivered</option>
                                </select>
                            </div>

                        <div class="mb-3">
                            <label for="shipped_date">Shipped Date</label>
                            <input value="{{ $order->shipped_date }}" type="datetime" class="form-control" name="shipped_date" id="shipped_date" autocomplete="off" placeholder="Shipped Date">
                        </div>
                        <div class="mb-3">
                            <button class="btn btn-primary">Update</button>
                        </div>
                    </div>
                </form>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post" name="sendInvoiceSMS" id="sendInvoiceSMS">
                            <h2 class="h4 mb-3">Send Inovice SMS</h2>
                            <div class="mb-3">
                                <select name="user" id="user" class="form-control">
                                    <option value="customer">Customer</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.card -->
</section>
<!-- /.content -->

@endsection

@section('customJs')

<script>
    $(document).ready(function(){
        $('#shipped_date').datetimepicker({
            // options here
            format:'Y-m-d H:i:s',
        });
    });

    $("#changeOrderStatusForm").submit(function(event){
        event.preventDefault();

        $.ajax({
            url: '{{ route('orders.changeOrderStatus', $order->id) }}',
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response){
                window.location.href = '{{ route('orders.detail',$order->id) }}';
            }

        })
    });

    $("#sendInvoiceSMS").submit(function(event){
        event.preventDefault();

        $.ajax({
            url: '{{ route('orders.sendInvoiceSMS', $order->id) }}',
            type: 'post',
            data: $(this).serializeArray(),
            dataType: 'json',
            success: function(response){
                window.location.href = '{{ route('orders.detail',$order->id) }}';
            }

        })
    });
</script>

@endsection
