<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    public function verifyKhaltiPayment(Request $request){
        $transactionId = $request->transactionId;
        $orderValue=Order::where('transaction_uuid', $transactionId)->first();
        $token = $request->token;
        $amount = $request->amount;

        $args = http_build_query(array(
        'token' => $token,
        'amount'  => $amount
        ));

        $url = "https://khalti.com/api/v2/payment/verify/";

        # Make the call using API.
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$args);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $secretKey = config('app.khalti_secret_key');

        $headers = ["Authorization: Key $secretKey" ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        // Response
        $responseData = curl_exec($ch);
        $response = json_decode($responseData);

        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if($response->amount == $amount){

            $orderValue->payment_status = 'paid';
            $orderValue->save();

            //Update Product Stock
            foreach (Cart::content() as $item) {
                $productData = Product::find($item->id);
                if ($productData->track_qty == 'Yes') {
                    $currentQty = $productData->qty;
                    $updatedQty = $currentQty - $item->qty;
                    $productData->qty = $updatedQty;
                    $productData->save();
                }
            }
            Cart::destroy();

            //send confirmation sms
            orderSMS($orderValue->id);
            $message = "You have successfully placed your order.";
            session()->flash("success", $message);

            return response()->json([
                'status' => true,
                'message'=> $message
            ]);

        }else{
            $message = "Order not successfully placed due to payment failure.";
            session()->flash("error", $message);

            return response()->json([
                'status' => false,
                'message'=> $message
            ]);
        }



    }

    public function esewaPaymentForm($transactionId){
        $order = Order::where('transaction_uuid',$transactionId)->first();
        return view('esewa.form', compact('order'));
    }

    public function esewaSuccess(){
        return view('esewa.success');
    }

    public function esewaFailure(){
        return view('esewa.failure');
    }

    public function verifyEsewaPayment(Request $request)
    {
        $encodedData = $request->data;
        $decodedData = base64_decode($encodedData);
        $jsonData = json_decode($decodedData, true);
        
        $order = Order::where('transaction_uuid', $jsonData['transaction_uuid'])->first();      
        
        // Use bcmul for precise multiplication with a precision of 2
        $amount = floatval(str_replace(",", "", $jsonData['total_amount']));
    
        $product_code = config('app.esewa_merchant_code');
        $total_amount = $order->grand_total;
        $transaction_uuid = $order->transaction_uuid;
    
        // Make a request to eSewa API
        $response = Http::get("https://uat.esewa.com.np/api/epay/transaction/status", [
            'product_code' => $product_code,
            'total_amount' => $total_amount,
            'transaction_uuid' => $transaction_uuid,
        ]);
    
        // Check if the request was successful
        if ($response->successful()) {
            $responseData = $response->json();
            $status = $responseData['status'];

            if ($status == 'CANCELED' && $amount == $order->grand_total) {
                $order->payment_status = 'unpaid';
                $order->save();                 
                Cart::destroy();                
    
                $message = "Your payment is cancelled and so is your order.";
                sendSMS($order->mobile, $message);
                return redirect()->route('esewaFailure', $order->id)->with("error", $message);
            }
    
            if ($status == 'PENDING' && $amount == $order->grand_total) {
                $order->payment_status = 'unpaid';
                $order->save();                 
                Cart::destroy();

                $message = "You payment is pending so is your order.";
                sendSMS($order->mobile, $message);

                return redirect()->route('esewaFailure', $order->id)->with("success", $message);
            }
    
            if ($status == 'COMPLETE') {
                $order->payment_status = 'paid';
                $order->save();                 

                //Update Product Stock
                foreach (Cart::content() as $item) {
                    $productData = Product::find($item->id);
                    if ($productData->track_qty == 'Yes') {
                        $currentQty = $productData->qty;
                        $updatedQty = $currentQty - $item->qty;
                        $productData->qty = $updatedQty;
                        $productData->save();
                    }
                }
                Cart::destroy();

                
                // Send confirmation sms
                orderSMS($order->id);
    
                $message = "You have successfully placed your order.";
                return redirect()->route('front.thankyou', $order->id)->with("success", $message);
            }
            $message = "Order not successfully placed due to payment failure.";
            return redirect()->route('esewaFailure')->with('error', $message);
        }
    
        $message = "Order not placed due to the error in esewa verification";
        return redirect()->route('esewaFailure')->with('error', $message);
    }
}
