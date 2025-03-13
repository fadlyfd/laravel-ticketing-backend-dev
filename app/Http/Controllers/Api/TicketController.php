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

    // public function updateTicketStatus($id){
    //     $ticket = \App\Models\Ticket::find($id);
    //     $ticket->status = 'redeem';
    //     $ticket->ticket_date = now();
    //     $ticket->save();
    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Ticket updated successfully',
    //         'data' => $ticket
    //     ]);
    // }

    public function checkTicketValid(Request $request){
        $request->validate([
            'ticket_code' => 'required'
        ]);

        $ticket = \App\Models\Ticket::where('ticket_code', $request->ticket_code)->first();
        if ($ticket) {
            if ($ticket->status == 'available') {
                $ticket->status = 'redeem';
                $ticket->ticket_date = now();
                $ticket->save();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Ticket redeemed successfully',
                    'isValid' => true
                ], 200);
            } else{
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ticket already redeemed',
                    'isValid' => false
                ], 400);
            }
        } else{
            return response()->json([
                'status' => 'error',
                'message' => 'Ticket not found',
                'isValid' => false

            ], 404);
        }
    }
}
