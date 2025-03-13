<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function getVendorByUser($id){
        $vendor = \App\Models\Vendor::where('user_id', $id)->get();
        return response()->json([
            'status' => 'success',
            'message' => 'Get vendor by user',
            'data' => $vendor
        ], 200);
    }

    public function createVendor(Request $request){
        $request->validate([
            'user_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'location' => 'required',
            'phone' => 'required',
            'city' => 'required',
        ]);

        $data = $request->all();
        $data['verify_status'] = 'pending';
        $user = \App\Models\User::find($data['user_id']);
        $user->is_vendor = 1;
        $user->save();

        $vendor = \App\Models\Vendor::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Vendor created successfully',
            'data' => $vendor
        ], 201);



    }
}
