<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Vendor;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function getTickeUser($id){
        // Ambil semua vendor berdasarkan user_id
        $vendors = Vendor::where('user_id', $id)->get();

        // Ambil semua event ID yang terkait dengan vendor-vendor ini
        $eventIds = Event::whereIn('vendor_id', $vendors->pluck('id'))->pluck('id');

        // ambil semua ticket berdasarkan event_id
        $tickets = \App\Models\Ticket::with(['sku', 'event'])->whereIn('event_id', $eventIds)->get();
        return response()->json([
            'status' => 'success',
            'data' => $tickets
        ]);
    }
}
