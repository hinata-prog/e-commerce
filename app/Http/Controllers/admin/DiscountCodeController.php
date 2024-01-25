<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\DiscountCoupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class DiscountCodeController extends Controller
{
    public function index(Request $request)
    {
        $query = DiscountCoupon::orderBy("created_at", "desc");

        if ($request->has('keyword') && !empty($request->get('keyword'))) {
            $query->where('code', 'like', '%' . $request->get('keyword') . '%');
        }

        $coupons = $query->paginate(10);

        return view("admin.coupon.list", compact('coupons'));
    }

    public function create(){
        return view("admin.coupon.create");
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:255|unique:discount_coupons,code',
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'max_uses' => 'nullable|integer',
            'max_uses_user' => 'nullable|integer',
            'type' => 'required|in:percent,fixed',
            'discount_amount' => 'required|numeric',
            'min_amount' => 'required|numeric',
            'status' => 'required|integer|in:1,0',
            'starts_at' => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:now',
            'expires_at' => 'nullable|date_format:Y-m-d H:i:s|after:starts_at',

        ]);

        if ($validator->passes()) {
            $discountCode = new DiscountCoupon();
            $discountCode->code = $request->code;
            $discountCode->name = $request->name;
            $discountCode->description = $request->description;
            $discountCode->type = $request->type;
            $discountCode->max_uses = $request->max_uses;
            $discountCode->max_uses_user = $request->max_uses_user;
            $discountCode->discount_amount = $request->discount_amount;
            $discountCode->min_amount = $request->min_amount;
            $discountCode->status = $request->status;

            // Convert starts_at and expires_at to timestamps
            $discountCode->starts_at = $request->starts_at ;
            $discountCode->expires_at = $request->expires_at ;

            $discountCode->save();

            $message = 'Discount coupon added successfully.';
            session()->flash('success', $message);

            return response()->json([
                'status' => true,
                'message' => $message,
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }
    }

    public function edit($id){
        $coupon = DiscountCoupon::find($id);
        if(!$coupon){
            session()->flash('error', 'Discount Coupon not found');
            return redirect()->route('coupons.index');
        }

        return view('admin.coupon.edit', compact('coupon'));
    }

    public function update(Request $request, $id){
        $coupon = DiscountCoupon::find($id);
        if(!$coupon){
            session()->flash('error','Discount Coupon not found');
            return response()->json([
                'status'=> false,
                'error'=> 'Coupon not found',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'code' =>  "required|unique:discount_coupons,code," . $coupon->id . ",id",
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'max_uses' => 'nullable|integer',
            'max_uses_user' => 'nullable|integer',
            'type' => 'required|in:percent,fixed',
            'discount_amount' => 'required|numeric',
            'min_amount' => 'required|numeric',
            'status' => 'required|integer|in:1,0',
            'starts_at' => 'nullable|date_format:Y-m-d H:i:s|after_or_equal:now',
            'expires_at' => 'nullable|date_format:Y-m-d H:i:s|after:starts_at',

        ]);

        if ($validator->passes()) {
            $coupon ->update([
                'code' => $request->code,
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
                'max_uses' => $request->max_uses,
                'max_uses_user' => $request->max_uses_user,
                'discount_amount' => $request->discount_amount,
                'min_amount' => $request->min_amount,
                'starts_at' => $request->starts_at,
                'expires_at' => $request->expires_at,
                'status' => $request->status
            ]);

            $message = 'Discount coupon updated successfully.';
            session()->flash('success', $message);

            return response()->json([
                'status' => true,
                'message' => $message,
            ]);

        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ]);
        }

    }

    public function destroy($id){
        $coupon =  DiscountCoupon::find($id);
        if(empty($coupon)){
            session()->flash('error','Discount Coupon not found');
            return response()->json([
                'status' => false,
                'message' => 'Discount Coupon not fpund'
            ]);
        }

        $coupon->delete();

        session()->flash('success','Discount Coupon deleted successfully');
        return response()->json([
            'status' => true,
            'message' => 'Discount Coupon deleted successfully'
        ]);
    }
}
