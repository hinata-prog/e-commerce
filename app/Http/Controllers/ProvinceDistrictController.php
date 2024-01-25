<?php

namespace App\Http\Controllers;

use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceDistrictController extends Controller
{
    public function index(Request $request)
    {
        if (!empty($request->province_id)) {
            $province = Province::with('districts')->find($request->province_id);

            $districts = $province->districts->sortBy('name');

            return response()->json([
                'status' => true,
                'districts' => $districts
            ]);
        } else {
            return response()->json([
                'status' => false,
                'districts' => []
            ]);
        }
    }
}
