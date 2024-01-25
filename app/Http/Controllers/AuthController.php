<?php

namespace App\Http\Controllers;

use App\Models\Province;
use App\Models\CustomerAddress;
use App\Models\District;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Page;
use App\Models\PasswordResetToken;
use App\Models\User;
use App\Models\WishList;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function login(){
        return view("front.account.login");
    }

    public function register(){
        $page = Page::where("slug","terms-and-conditions")->first();

        return view("front.account.register",compact("page"));
    }

    public function processRegister(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'phone_number' => 'required|unique:users',
        ]);
        $password = Str::random(8);

        if ($validator->passes()){
            $user = User::create([
                'name' => $request->name,
                'phone_number' => $request->phone_number,
                'password' => $password
                ]);
            if($user){
                try {
                    $passwordRoute = route('account.showChangePasswordForm');
        
                    $client = new Client();
                    $response = $client->post('https://sms.aakashsms.com/sms/v3/send', [
                        'form_params' => [
                            'auth_token' => config('app.sms_token'),
                            'to' => $user->phone_number,
                            'text' => "You have successfully registered in Look Me Cosmetics. Your password is: $password"
                        ],
                    ]);
        
                    if ($response->getStatusCode() === 200) {
                        Auth::login($user);
    
                        $message = 'You have registered successfully. Your auto-generated password is sent to your phone number. You can reset it.';
                        session()->flash('success',$message);
                        
                        return response()->json([
                            'status'=> true,
                        ]);
        
                    } else {
                        $message = 'Failed to register.';
                        User::where('id', $user->id)->first()->delete();
                        session()->flash('error',$message);
                        return response()->json(['error' => $message, 'status' => false]);
                    }
                } catch (\Exception $e) {
                    $message = 'Failed to send SMS. Check your Internet Connection.';
                    User::where('id', $user->id)->first()->delete();
    
                    session()->flash('error',$message);
                    return response()->json(['error' => 'Failed to send SMS: ' . $e->getMessage(), 'message' => $message]);
                }
            }else{
                return response()->json([
                    'status'=> false,
                    'errors'=> 'Some errors in registering'
                ]);
            }


        }else{
            return response()->json([
                'status'=> 'false',
                'errors'=> $validator->errors()
            ]);
        }
    }
    

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "phone_number" => "required|string",
            "password" => "required"
        ]);
        
        $user = User::where('phone_number',$request->phone_number)->first();
        if($user == null){
            return redirect()->back()
            ->with('error','User Not Found');
        }
        if($user->status == 0){
           return redirect()->back()
            ->with('error','Your account status is deactivated.');
        }

        if ($validator->passes()) {
            if (Auth::attempt([
                'phone_number' => $request->phone_number,
                'password' => $request->password
            ], $request->get('remember'))) {
               if (session()->has('url.intended') && session('url.intended') !== route('account.logout')) {
                    return redirect(session('url.intended'));
                }
                else{
                    return redirect()->route('account.profile');

                }
            } else {
                return redirect()->route('account.login')
                ->withInput($request->only('phone_number'))
                ->with('error', 'Either phone number or password is incorrect');
            }
        } else {
            return redirect()->back()
                ->with('error', 'Either phone number or password is incorrect')
                ->withErrors($validator)
                ->withInput($request->only('phone_number'));
        }
    }

    public function profile(){
        $provinces = Province::orderBy('name','ASC')->get();
        $districts = [];

        $user = User::where('id',Auth::user()->id)->first();
        $userAddress = CustomerAddress::where('user_id',$user->id)->first();

        if ($userAddress){
            $districts = District::where('province_id',$userAddress->province_id)->get();
        }

        return view('front.account.profile',compact('user','provinces','userAddress','districts'));
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('account.login')
        ->with('success', 'You successfully logged out.');
    }
    
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        
        // Validate the incoming request
        $request->validate([
            'password' => 'required|string',
        ]);
    
        // Check if the provided password matches the user's current password
        if (Hash::check($request->password, $user->password)) {
            $user = User::find($user->id);
            // Delete the user
            $user->delete();
    
            // Logout the user
            Auth::logout();
    
            // Redirect to a confirmation page or any other page
            return redirect()->route('account.register')->with('success', 'Your account has been deleted successfully.');
        } else {
            // Password doesn't match
            return redirect()->back()->with('error', 'Incorrect password. Please try again.');
        }
    }

    public function orders(){

        $user = Auth::user();
        if(!$user){
            return redirect()->route('account.login');
        }

        $orders = Order::where('user_id', $user->id)->where('payment_status','paid')->orderBy('created_at','DESC')->get();
        return view('front.account.order', compact('orders'));
    }

    public function orderDetail($id){
        $user = Auth::user();
        
        if(!$user){
            return redirect()->route('account.login');
        }

        $order = Order::where('user_id',$user->id)->where('id', $id)->orderBy('created_at','DESC')->first();
        
        if(!$order){
            return redirect()->back()->with('error', 'Order not found');
        }
        
        $orderItems = OrderItem::where('order_id', $id)->get();
        $orderItemsCount = OrderItem::where('order_id', $id)->count();

        return view('front.account.order-detail', compact('order','orderItems','orderItemsCount'));
    }

    public function wishlist(){
        $wishlists = WishList::where('user_id',Auth::user()->id)->with('product')->get();

        return view('front.account.wishlist', compact('wishlists'));
    }

    public function removeProductFromWishlist(Request $request){
        $wishlist = WishList::where('user_id',Auth::user()->id)->where('product_id',$request->id)->first();

        if($wishlist == null){
            $message = 'Product already removed.';
            session()->flash('error', $message);
            return response()->json([
                'message'=> $message,
                'status'=>true
            ]);
        }else{
            $wishlist->delete();
            $message = 'Product successfully removed from your wishlist.';
            session()->flash('success', $message);
            return response()->json([
                'message'=> $message,
                'status'=>true
            ]);
        }

    }

    public function updateProfile(Request $request){
        $userId = Auth::user()->id;
        
        if(!$userId){
            return redirect()->route('account.login')->with('error', 'User not found');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'phone_number' => 'required|unique:users,phone_number,' . $userId . ',id',
        ]);

        if($validator->passes()){

            $user = User::find($userId);
            $user->name = $request->name;
            $user->phone_number = $request->phone_number;
            $user->save();

            $message = 'Profile updated successfully';

            session()->flash('success', $message);
            return response()->json([
                'status' => true,
                'message' => $message
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }


    }

    public function updateAddress(Request $request){
        $userId = Auth::user()->id;
        
        if($userId == null){
            return redirect(route('account.login'))->with('error', 'User not found');
        }

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
            'zip' => 'required',
        ]);


        if($validator->passes()){

            //step-2 save user address
            CustomerAddress::updateOrCreate([
                'user_id'=> $userId,
            ],[
                'user_id'=> $userId,
                'first_name'=> $request->first_name,
                'last_name'=> $request->last_name,
                'email'=> $request->email,
                'municipality'=> $request->municipality,
                'city'=> $request->city,
                'district_id'=> $request->district_id,
                'zip'=> $request->zip,
                'province_id'=> $request->province_id,
                'mobile'=> $request->mobile,
                'house_no'=> $request->house_no
            ]);


            $message = 'Customer Address updated successfully';

            session()->flash('success', $message);

            return response()->json([
                'status' => true,
                'message' => $message
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function showChangePasswordForm(){
        return view('front.account.change-password');
    }

    public function changePassword(Request $request){

        $validator = Validator::make($request->all(), [
            'password'=> 'required|confirmed|min:5',
            'old_password'=> 'required',
        ]);

        if($validator->passes()){
            $user = User::select('id','password')->where('id',Auth::user()->id)->first();
            if(!$user){
                return redirect()->route('account.login')->with('error', 'User not found');
            }
            
            if(Hash::check($request->old_password,$user->password)){
                $user = User::where('id',$user->id)->update([
                    'password' => bcrypt($request->password),
                ]);

                $message = 'Password updated successfully!';
                session()->flash('success', $message);

                return response()->json([
                    'status'=> true,
                    'message'=> $message
                ]);
            }else{
                $message = 'Old password is incorrect. Please try again.';
                session()->flash('error', $message);
                return response()->json([
                    'status'=> true,
                    'error'=> $message
                ]);
            }


        }else{
            return redirect()->route('front.forgotPassword')->withInput()->withErrors($validator);
        }
    }


    public function showForgotPasswordForm(){
        return view('front.account.forgot-password');
    }

    public function sendcode(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => [
                'required',
                'numeric',
                'digits:10',
                Rule::exists('users')->where(function ($query) use ($request) {
                    // Include a where clause to check for the phone_number in the users table.
                    $query->where('phone_number', $request->phone_number);
                }),
            ],
        ]);
        if ($validator->fails()) {

            return redirect()->back()
            ->with('error', 'Phone Number is incorrect')
            ->withErrors($validator)
            ->withInput($request->only('phone_number'));

        }else{

            $phone_number = strval($request->input('phone_number'));

            $code = mt_rand(10000, 99999);
            $encryptedCode= Hash::make($code);

            $phone_verification = PasswordResetToken::updateOrCreate(
                [
                    'phone_number'=> $phone_number,
                ],
                [
                'phone_number' => $phone_number,
                'code' => $encryptedCode,
                'expires_at' => now()->addMinutes(1),
                'status' => false,
                ]);
            try {
                $client = new Client();
                $queryParams = http_build_query(['phone_number' => $phone_number, 'code' => $code]);
                $resetRoute = url('reset-password') . '?' . $queryParams;
                $response = $client->post('https://sms.aakashsms.com/sms/v3/send', [
                    'form_params' => [
                        'auth_token' => config('app.sms_token'),
                        'to' => $phone_number,
                        'text' => "Reset your password here: $resetRoute",
                    ],
                ]);

                if ($response->getStatusCode() === 200) {
                    $message = 'Password reset url sent successfully to your phone number.';
                    return redirect()->route('account.login')->with('success', $message);
                } else {
                    return redirect()->back()
                    ->with('error', 'Failed to send verification code');
                }
            } catch (\Exception $e) {
                $message = 'Failed to send verification code. Check your Internet Connection.';
                return redirect()->back()->with('error', $message);
            }
        }


    }



    public function showResetPasswordForm(){
        return view('front.account.password-input');
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone_number' => ['required', 'numeric'],
            'code' => ['required', 'numeric'],
            'password'=> 'required|confirmed|min:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'=> false,
                'errors'=> $validator->errors()
            ]);

        }

        $phone_number = $request->phone_number;
        $code = $request->code;

        $verificationRecord = DB::table('password_reset_tokens')
            ->where('phone_number', $phone_number)
            ->latest()->first();

        if (!$verificationRecord) {
            $message =  'Verification code is missing.';
            return redirect()->back()->with('error', $message);

           

        }else{
            $storedcode = $verificationRecord->code;

            if (Hash::check($code, $storedcode)) {
                if($verificationRecord->expires_at < now()){
                    $message =  'Verification code is expired.';
                    return redirect()->back()->with('error', $message);

                    
                }else{

                    $user = User::where('phone_number', $request->phone_number)->first();
                    if (!$user) {
                        $message = 'User with the given phone number not found! Try again';
                        return redirect()->back()->with('error', $message);

                        

                    }else{
                        $user->password = Hash::make($request->password);
                        $user->save();
                        $verificationRecord = PasswordResetToken::where('phone_number',$verificationRecord->phone_number)->first();
                        if ($verificationRecord) {
                            // A record was found, delete it
                            $verificationRecord->delete();
                        }

                        $message ='Password successfully reset.';
                        return redirect()->route('account.login')->with('success', $message);

                        
                    }

                }

            } else {
                $message = 'Invalid verification code. Try regenerating again.';
                return redirect()->back()->with('error', $message);

                

            }
        }


    }


}



