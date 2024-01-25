<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\CustomerAddress;
use App\Models\DiscountCoupon;
use App\Models\District;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ShippingCharge;
use Carbon\Carbon;
use Cixware\Esewa\Client;
use Cixware\Esewa\Config;
use Gloudemans\Shoppingcart\Facades\Cart;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{

    public function addToCart(Request $request)
    {
        $product = Product::with('images')->find($request->id);

        if ($product == null) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ]);
        }

        $productImage = (!empty($product->images) && $product->images->isNotEmpty())
            ? $product->images->last()->image
            : '';

        if (Cart::content()->count() > 0) {


            //Product found in cart
            //check if this product already in the cart
            //return a message that product already added in your cart
            //if product not found in cart then add product add in cart

            $cartContent = Cart::content();
            $productAlreadyExist = false;
            foreach ($cartContent as $item) {
                if ($item->id == $product->id) {
                    $productAlreadyExist = true;

                }
            }
            if (!$productAlreadyExist) {
               Cart::add($product->id, "{$product->title} - {$product->measurement_value} {$product->measurement_unit}", 1, $product->price, [
                    'productImage' => $productImage
                ]);

                $status = true;
                $message = '<strong>' . $product->title . '</strong> added in your cart successfully.';
                session()->flash('success',$message);

            }else{
                $status = false;
                $message = $product->title . ' already added in cart.';
            }
        } else {
            // If the cart is empty, add the product to it
            Cart::add($product->id, "{$product->title} - {$product->measurement_value} {$product->measurement_unit}", 1, $product->price, [
                    'productImage' => $productImage
                ]);


            $status = true;
            $message = '<strong>' . $product->title . '</strong> added in your cart successfully.';
            session()->flash('success',$message);

        }

        // You may want to return a response indicating success or any additional information
        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }


    public function cart(){
        session()->forget('code');
        $cartContent = Cart::content();

        return view("front.cart", compact('cartContent'));
    }

    public function updateCart(Request $request){
        $rowId = $request->rowId;
        $qty = $request->qty;

        //check qty available in stock
        $itemInfo = Cart::get($rowId);
        $product = Product::find($itemInfo->id);

        if ($product->track_qty == 'Yes'){
            if ($qty <= $product->qty){
                Cart::update($rowId, $qty);
                $status = true;
                $message = 'Cart updated successfully';
                session()->flash('success', $message);


            }else{
                $message = 'Requested quantity (' . $qty . ') not available in stock';
                $status = false;
                session()->flash('error', $message);

            }
        }else{
            Cart::update($rowId, $qty);
            $status = true;
            $message = 'Cart updated successfully';
            session()->flash('success', $message);

        }


        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function deleteItem(Request $request) {
        $itemInfo = Cart::get($request->rowId);

        if($itemInfo == null){
            $status = false;
            $message = 'Item not found in cart';
            session()->flash('error', $message);
        }else{
            Cart::remove($request->rowId);
            $status = true;
            $message = 'Item deleted successfully';
            session()->flash('success', $message);
        }

        return response()->json([
            'status' => $status,
            'message' => $message
        ]);
    }

    public function checkout() {
        //if cart is empty redirect to cart page
        if(Cart::count() == 0){
            return redirect()->route('front.cart');
        }

        //if user is not logged in redirect to login page
        if (Auth::check() == false){
            session(['url.intended' => url()->current()]);

            return redirect()->route('account.login');
        }
        $subTotal = Cart::subtotal(2,'.','');
        $totalQty = 0 ;
        $shippingCharge = 0;
        $grandTotal = 0;
        $discount = 0;

        foreach (Cart::content() as $item) {
            $totalQty += $item->qty;
        }

        $customerAddress = CustomerAddress::where('user_id', Auth::user()->id)->first();

        session()->forget('url.intended');
        $provinces = Province::orderBy('name','ASC')->get();
        $districts =[];
        if ($customerAddress){
            $districts = District::where('province_id',$customerAddress->province_id)->get();
        }


        //apply discount here
        if(session()->has('code')) {
            $code = session()->get('code');
            if($code->type == 'percent'){
                $discount = ($code->discount_amount/100)*$subTotal;
                $grandTotal -= $discount;
            }else{

                $discount = $code->discount_amount;
                $grandTotal -= $discount;

            }
        }

        if($customerAddress != null){
            $userProvince  = $customerAddress->province_id;
            $shippingInfo =  ShippingCharge::where('province_id', $userProvince)->first();

            if ($shippingInfo != null){
                $shippingCharge = $totalQty * $shippingInfo->amount;
            }else{
                $shippingInfo =  ShippingCharge::where('province_id', 'everywhere')->first();
                $shippingCharge = $totalQty * $shippingInfo->amount;
            }
        }

        $grandTotal = ($subTotal-$discount)+$shippingCharge;

        return view('front.checkout', compact('provinces', 'customerAddress','shippingCharge', 'grandTotal','discount','districts'));
    }

    public function processCheckout(Request $request){
        //step-1 validates data
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required',
            'province_id' => 'required',
            'district_id' => 'required|exists:districts,id',
            'municipality' => 'required',
            'city' => 'required',
            'house_no' => 'required',
            'notes' => 'nullable|max:500',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'status'=> false,
                'errors'=> $validator->errors(),
                'message'=> 'Please fix the errors'
            ]);
        }
        $user = Auth::user();

        //step-2 save user address
        CustomerAddress::updateOrCreate([
            'user_id'=> $user->id,
        ],[
            'user_id'=> $user->id,
            'first_name'=> $request->first_name,
            'last_name'=> $request->last_name,
            'province_id'=> $request->province_id,
            'email'=> $request->email,
            'municipality'=> $request->municipality,
            'city'=> $request->city,
            'district_id'=> $request->district_id,
            'mobile'=> $request->mobile,
            'house_no'=> $request->house_no
        ]);

        $discountCodeId = NULL;
        $promoCode = NULL;
        $subtotal = Cart::subtotal(2,'.','');
        $shippingCharge = 0;
        $discount = 0;
        $grandTotal = $subtotal;
        $totalQty = 0;

        //apply discount here
        if(session()->has('code')) {
            $code = session()->get('code');
            if($code->type == 'percent'){
                $discount = ($code->discount_amount/100)*$subtotal;
                $grandTotal -= $discount;

            }else{

                $discount = $code->discount_amount;
                $grandTotal -= $discount;
            }

            $discountCodeId = $code->id;
            $promoCode = $code->code;
        }

        if($request->province){
            $shippingInfo =  ShippingCharge::where('province_id', $request->province)->first();

            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null){
                $shippingCharge = $totalQty * $shippingInfo->amount;
                $grandTotal += $shippingCharge;
            }else{
                $shippingInfo =  ShippingCharge::where('province_id', 'rest_of_world')->first();
                $shippingCharge = $totalQty * $shippingInfo->amount;
                $grandTotal +=  $shippingCharge;

            }
        }else{
            $shippingCharge = 0;
        }


        $order = new Order;
        $order->user_id = $user->id;
        $order->subtotal = $subtotal;
        $order->shipping = $shippingCharge;
        $order->discount = $discount;
        $order->discount_coupon_id = $discountCodeId;
        $order->coupon_code = $promoCode;
        $order->payment_status = 'unpaid';
        $order->status = 'pending';
        $order->grand_total = $grandTotal;

        $order->first_name = $request->first_name;
        $order->last_name = $request->last_name;
        $order->email = $request->email;
        $order->house_no = $request->house_no;
        $order->city = $request->city;
        $order->municipality = $request->municipality;
        $order->province_id = $request->province_id;
        $order->district_id = $request->district_id;
        $order->mobile = $request->mobile;
        $order->notes = $request->notes;
        $order->transaction_uuid = Carbon::now()->format('ymd-His');
        $order->payment_method = $request->payment_method;

        $order->save();

        //step-4 store order items in order items table
        foreach (Cart::content() as $item) {
            $orderItem = new OrderItem;
            $orderItem->product_id = $item->id; 
            $orderItem->order_id = $order->id;
            $orderItem->name = $item->name;
            $orderItem->qty = $item->qty;
            $orderItem->price = $item->price;
            $orderItem->total = $item->price * $item->qty;
            $orderItem->save();



        }

        session()->forget('code');

        
        return response()->json([
            'status'=> true,
            'payment_method'=> $request->payment_method,
            'transactionId'=> $order->transaction_uuid,
            'grandTotal'=> $order->grand_total,
            'message'=> 'Order saved successfully.',
            'orderId' => $order->id,
        ]);



    }

    public function updateOrderStatus(Request $request){
        $order = Order::find($request->order_id);
        $order->status = $request->status;
        $order->save();

        $message = 'You have successfully placed your order';

        session()->flash('success', $message);
        return response()->json([
            'status'=> true,
            'message'=> $message
        ]);
    }



    public function thankyou($id) {
        return view('front.thanks',compact('id'));
    }

    public function getOrderSummary(Request $request) {
        $subTotal = Cart::subtotal(2,'.','');
        $totalQty = 0 ;
        $shippingCharge = 0;
        $grandTotal = $subTotal;
        $discount = 0;
        $discountString = '';

        //apply discount here
        if(session()->has('code')) {
            $code = session()->get('code');
            if($code->type == 'percent'){
                $discount = ($code->discount_amount/100)*$subTotal;
                $grandTotal -= $discount;
            }else{

                $discount = $code->discount_amount;
                $grandTotal -= $discount;

            }

            $discountString = ' <div class="mt-4 mr-2" id="discount-response">
                                    <strong>'.session()->get('code')->code.'</strong>
                                    <a class="btn btn-sm btn-danger" id="remove-discount"><i class="fa fa-times"></i></a>
                                </div>';
        }



        if($request->province_id > 0){
            $shippingInfo =  ShippingCharge::where('province_id', $request->province_id)->first();


            foreach (Cart::content() as $item) {
                $totalQty += $item->qty;
            }

            if ($shippingInfo != null){
                $shippingCharge = $totalQty * $shippingInfo->amount;
                $grandTotal += $shippingCharge;
                return response()->json([
                    'status'=> true,
                    'grandTotal'=> number_format( $grandTotal,2),
                    'shippingCharge' =>number_format($shippingCharge,2),
                    'discount' => number_format($discount,2),

                    'discountString'=> $discountString
                ]);
            }else{
                $shippingInfo =  ShippingCharge::where('province_id', 'everywhere')->first();
                $shippingCharge = $totalQty * $shippingInfo->amount;
                $grandTotal +=  $shippingCharge;
                return response()->json([
                    'status'=> true,
                    'grandTotal'=> number_format( $grandTotal,2),
                    'shippingCharge' =>number_format($shippingCharge,2),
                    'discount' => number_format($discount,2),

                    'discountString'=> $discountString

                ]);

            }

        }else{
            return response()->json([
                'status'=> true,
                'grandTotal'=> number_format( $grandTotal,2),
                'shippingCharge' =>number_format($shippingCharge,2),
                'discount' => number_format($discount,2),

                'discountString'=> $discountString

            ]);


        }

    }

    public function applyDiscount(Request $request){
        $code = DiscountCoupon::where('code', $request->code)->first();

        if ($code == null) {
            return response()->json([
                'status' => false,
                'error' => 'Invalid discount coupon',
            ]);
        }

        // Check if coupon start date is valid
        $now = Carbon::now();

        if ($code->starts_at && $now < $code->starts_at) {
            return response()->json([
                'status' => false,
                'error' => 'Discount coupon is not yet active',
            ]);
        }

        // Check if coupon has expired
        if ($code->expires_at && $now > $code->expires_at) {
            return response()->json([
                'status' => false,
                'error' => 'Discount coupon has expired',
            ]);
        }


        if($code->max_uses > 0){
            // max uses check
            $couponUsed = Order::where('discount_coupon_id',$code->id)->count();

            if($couponUsed >= $code->max_uses){
                return response()->json([
                    'status' => false,
                    'error' => 'Discount coupon has been used.',
                ]);
            }
        }

        if($code->max_uses_user > 0){
            //max user uses check
            $couponUsedByUser = Order::where('discount_coupon_id',$code->id)->where('user_id',Auth::user()->id)->count();
            if($couponUsedByUser >= $code->max_uses_user){
                return response()->json([
                    'status' => false,
                    'error' => 'You have already finished your discount coupon use.',
                ]);
            }
        }

       $subTotal = Cart::subtotal(2,'.','');
       //Min amount condition check
       if($code->min_amount > 0) {
            if($subTotal < $code->min_amount){
                return response()->json([
                    'status' => false,
                    'error' => 'Your min expense must be NRs' . $code->min_amount . '.',
                ]);
            }
       }



        // Your further logic for applying the discount...
        session()->put('code', $code);

       return $this->getOrderSummary($request);

    }

    public function removeCoupon(Request $request){
        session()->forget('code');
        return $this->getOrderSummary($request);

    }
}


