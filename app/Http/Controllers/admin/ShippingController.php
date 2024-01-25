<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Province;
use App\Models\ShippingCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippingController extends Controller
{
    public function create(){
        $provinces = Province::all();

        $shippingCharges = ShippingCharge::select('shipping_charges.*','provinces.name')->leftJoin('provinces','provinces.id','shipping_charges.province_id')->get();

        return view("admin.shipping.create",compact("provinces","shippingCharges"));

    }

    public function store(Request $request){
        $validator = Validator::make($request->all(),[
            'province_id' => 'required',
            'amount' => 'required|numeric'
        ]);

        if ($validator->passes()){

            $count = ShippingCharge::where('province_id',$request->province_id)->count();
            if($count > 0){
                session()->flash('error','Delivery Charge already added.');
                return response()->json([
                    'status'=> true,
                ]);
            }
            $shipping = new ShippingCharge;
            $shipping->province_id = $request->province_id;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success', 'Delivery Charge added successfully.');
            return response()->json([
                'status' => true,
                'errors' => 'Delivery Charge added successfully.'
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function edit($id){
        $provinces = Province::all();
        $shippingCharge = ShippingCharge::find($id);

        return view('admin.shipping.edit',compact('shippingCharge','provinces'));

    }

    public function update(Request $request, $id){
        $shipping = ShippingCharge::find($id);

        $validator = Validator::make($request->all(),[
            'province_id' => 'required',
            'amount' => 'required|numeric'
        ]);

        if ($validator->passes()){

            if($shipping == null){
                session()->flash('error','Delivery Charge charge not found');

                return response()->json([
                    'status'=> true
                ]);
            }

            $shipping->province_id = $request->province_id;
            $shipping->amount = $request->amount;
            $shipping->save();

            session()->flash('success', 'Delivery Charge updated successfully.');
            return response()->json([
                'status' => true,
                'errors' => 'Delivery Charge updated successfully.'
            ]);

        }else{
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ]);
        }
    }

    public function destroy($id){
        $shippingCharge = ShippingCharge::find($id);
        
        if($shippingCharge->province_id == 'everywhere'){
            session()->flash('error','Default Delivery Charge cannot be deleted. It can be set to 0 though.');

            return response()->json([
                'status'=> true
            ]);
        }

        if($shippingCharge == null){
            session()->flash('error','Delivery charge not found');

            return response()->json([
                'status'=> true
            ]);
        }else{
            $shippingCharge->delete();
            session()->flash('success','Delivery charge deleted successfully.');

            return response()->json([
                'status' => true,
                'message' => 'Delivery deleted successfully.'
            ]);
        }
    }
}
