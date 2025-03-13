<?php

namespace App\Http\Controllers\Api;

use App\Helpers\UniqueCodeHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SkuController extends Controller
{
    public function index($userId){
       // get all vendor by user id
        $vendor = \App\Models\Vendor::where('user_id', $userId)->get();
        // get all event by vendor id
        $event = \App\Models\Event::whereIn('vendor_id', $vendor->pluck('id'))->get();
        // get sku by all event id
        $sku = \App\Models\Sku::with(['event'])->whereIn('event_id', $event->pluck('id'))->get();
        return response()->json([
            'status' => 'success',
            'data' => $sku
        ]);
    }

    public function store(Request $request){
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'category' => 'required',
            'event_id' => 'required',
            'price' => 'required',
            'stock' => 'required',
            'day_type' => 'required',
        ]);

        $data = $request->all();
        $sku = \App\Models\Sku::create($data);
        // create ticket for loop stock sku
        for ($i = 0; $i < $data['stock']; $i++) {
            $ticket_code = UniqueCodeHelper::generateUniqueCode('tickets', 'ticket_code');
            $ticket = \App\Models\Ticket::create([
                'event_id' => $data['event_id'],
                'sku_id' => $sku->id,
                'ticket_code' => $ticket_code,
                'status' => 'available',
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Sku created successfully',
            'data' => $sku
        ]);

    }
}
