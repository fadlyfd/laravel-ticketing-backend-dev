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
}
