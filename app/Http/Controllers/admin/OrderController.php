<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class OrderController extends Controller
{
    public function index(Request $request){
        $orders = Order::with('user')->where('payment_status','paid');
        // dd($orders);

        if ($request->has('keyword') && $request->keyword != "") {
            $orders = $orders->where(function ($query) use ($request) {
                $query->where('id', $request->keyword)->orWhere('mobile',$request->keyword)->orWhere('email',$request->keyword)
                    ->orWhereHas('user', function ($userQuery) use ($request) {
                        $userQuery->where('name', 'like', '%' . $request->keyword . '%')
                            ->orWhere('phone_number', 'like', '%' . $request->keyword . '%');
                    });
            });
        }

        // Fetch the data from the database
        $orders = $orders->paginate(10);

        return view("admin.orders.list", compact('orders'));
    }
    
    public function pendingOrders(Request $request){
        $orders = Order::with('user')->where('payment_status','paid')->where('status','pending');
        // dd($orders);

        if ($request->has('keyword') && $request->keyword != "") {
            $orders = $orders->where(function ($query) use ($request) {
                $query->where('id', $request->keyword)->orWhere('mobile',$request->keyword)->orWhere('email',$request->keyword)
                    ->orWhereHas('user', function ($userQuery) use ($request) {
                        $userQuery->where('name', 'like', '%' . $request->keyword . '%')
                            ->orWhere('phone_number', 'like', '%' . $request->keyword . '%');
                    });
            });
        }

        // Fetch the data from the database
        $orders = $orders->paginate(10);

        return view("admin.orders.list", compact('orders'));
    }
    
    public function shippedOrders(Request $request){
        $orders = Order::with('user')->where('payment_status','paid')->where('status','shipped');
        // dd($orders);

        if ($request->has('keyword') && $request->keyword != "") {
            $orders = $orders->where(function ($query) use ($request) {
                $query->where('id', $request->keyword)->orWhere('mobile',$request->keyword)->orWhere('email',$request->keyword)
                    ->orWhereHas('user', function ($userQuery) use ($request) {
                        $userQuery->where('name', 'like', '%' . $request->keyword . '%')
                            ->orWhere('phone_number', 'like', '%' . $request->keyword . '%');
                    });
            });
        }

        // Fetch the data from the database
        $orders = $orders->paginate(10);

        return view("admin.orders.list", compact('orders'));
    }
    
    public function deliveredOrders(Request $request){
        $orders = Order::with('user')->where('payment_status','paid')->where('status','delivered');
        // dd($orders);

        if ($request->has('keyword') && $request->keyword != "") {
            $orders = $orders->where(function ($query) use ($request) {
                $query->where('id', $request->keyword)->orWhere('mobile',$request->keyword)->orWhere('email',$request->keyword)
                    ->orWhereHas('user', function ($userQuery) use ($request) {
                        $userQuery->where('name', 'like', '%' . $request->keyword . '%')
                            ->orWhere('phone_number', 'like', '%' . $request->keyword . '%');
                    });
            });
        }

        // Fetch the data from the database
        $orders = $orders->paginate(10);

        return view("admin.orders.list", compact('orders'));
    }
    



    public function detail($orderId){
        $order = Order::with('province')->with('district')->with('orderItems')->where('id',$orderId)->first();

        return view("admin.orders.detail",compact("order"));
    }

    public function changeOrderStatus(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);

        $order->status = $request->status;

        // If the selected status is neither 'shipped' nor 'delivered', set shipped_date to null
        if ($request->status !== 'shipped' && $request->status !== 'delivered') {
            $order->shipped_date = null;
        } else {
            $order->shipped_date = $request->shipped_date;
        }

        $order->save();
       

        $message = 'Order status updated successfully!';
        session()->flash('success', $message);

        return response()->json([
            'status' => true,
            'message' => $message,
        ]);
    }


    public function sendInvoiceSMS(Request $request, $orderId)
    {
        if ($request->user == 'customer') {
            orderSMS($orderId);

            return response()->json([
                'status' => true,
                'message' => 'SMS for customer initiated successfully',
            ]);
        } elseif ($request->user == 'admin') {
            $this->orderSMSToAdmin($orderId);

            return response()->json([
                'status' => true,
                'message' => 'SMS for admin initiated successfully',
            ]);
        } else {

            return response()->json([
                'status' => false,
                'message' => 'User is not correct',
            ]);
        }
    }


    function orderSMSToAdmin($orderId){
        $order = Order::with('orderItems')->find($orderId);
        $user = Auth::user();

        if ($order) {
            $invoiceLink = route('front.invoice',$orderId); // Replace with the actual route name for your invoice
            $text = "You have recieved an order::: $invoiceLink";
            sendSMS( $user->phone_number, $text);
        }

    }
    
    



}
