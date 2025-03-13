<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Event;
use App\Models\Sku;
use App\Models\Ticket;
use App\Models\OrderTicket;
use App\Models\Vendor;
use App\Services\Midtrans\CreatePaymentUrlService;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    //create order
    public function create(Request $request)
    {
        //validate the request
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'order_details' => 'required|array',
            'order_details.*.sku_id' => 'required|exists:skus,id',
            'quantity' => 'required|integer|min:1',
            'event_date' => 'required',
        ]);

        // check ticket availability by sku id
        foreach ($request->order_details as $orderDetail) {
            $sku = Sku::find($orderDetail['sku_id']);
            $qty = $orderDetail['qty'];
            $tickets = Ticket::where('sku_id', $sku->id)
                ->where('status', 'available')
                ->get();

            if ($qty > $tickets->count()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ticket "' . $sku->name . '" is not available. Only ' . $tickets->count() . ' tickets left.'
                ], 422);
            }
        }

        $total = 0;
        foreach ($request->order_details as $orderDetail) {
            $sku = Sku::find($orderDetail['sku_id']);
            $qty = $orderDetail['qty'];
            $total += $sku->price * $qty;
        }

        //create order
        $order = Order::create([
            'user_id' => $request->user()->id,
            'event_id' => $request->event_id,
            'event_date' => $request->event_date,
            'quantity' => $request->quantity,
            'total_price' => $total,
        ]);


        foreach ($request->order_details as $orderDetail) {
            $sku = Sku::find($orderDetail['sku_id']);
            $qty = $orderDetail['qty'];

            for ($i = 0; $i < $qty; $i++) {
                //ticket by sku and available
                Log::info('Looking for ticket with sku_id: ' . $sku);
                $ticket = Ticket::where('sku_id', $sku->id)
                    ->where('status', 'available')
                    ->first();
                //insert order ticket
                OrderTicket::create([
                    'order_id' => $order->id,
                    'ticket_id' => $ticket->id,
                ]);
                //ticket status to sold
                $ticket->update([
                    'status' => 'booked',
                ]);
            }
        }

        $midtrans =  new CreatePaymentUrlService();
        $user = $request->user();

        $paymentUrl = $midtrans->getPaymentUrl($request->order_details, $order);
        $order->update([
            'payment_url' => $paymentUrl,
        ]);
        $order['user'] = $user;
        $order['orderItems'] = $request->order_details;
        //return response
        return response()->json([
            'status' => 'success',
            'message' => 'Order created successfully',
            'data' => $order,
        ], 201);
    }

    public function updateStatus (Request $request, $id){
        $request->validate([
            'status' => 'required|string',
        ]);
        $order = \App\Models\Order::find($id);
        $order->status = $request->status;
        $order->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Order updated successfully',
            'data' => $order
        ], 200);
    }

    public function getOrderByUserId($id){
        // Fetch orders with relationships
        $orders = Order::with(['user', 'event.vendor', 'orderTickets.ticket.sku'])
            ->where('user_id', $id)
            ->get();

        // Manipulate the data to merge duplicate ticket_ids in orderDetails
        $orders = $orders->map(function ($order) {
            // Group orderDetails by ticket_id
            $groupedTickets = $order->orderTickets->groupBy('ticket_id')->map(function ($details, $ticketId) {
                $firstDetail = $details->first();
                $totalQuantity = $details->count(); // Count total occurrences of this ticket_id

                // Add a custom total_quantity field to the first detail
                $firstDetail->total_quantity = $totalQuantity;
                return $firstDetail;
            });

            // Replace the original orderDetails with the grouped ones
            $order->orderTickets = $groupedTickets->values();
            return $order;
        });

        return response()->json([
            'status' => 'success',
            'message' => 'Get all order history',
            'data' => $orders
        ]);
    }

    public function getOrderByVendor($id){
        // Ambil semua vendor berdasarkan user_id
        $vendors = Vendor::where('user_id', $id)->get();

        // Ambil semua event ID yang terkait dengan vendor-vendor ini
        $eventIds = Event::whereIn('vendor_id', $vendors->pluck('id'))->pluck('id');

        // Ambil semua order berdasarkan event_id
        $orders = Order::with(['user', 'event.vendor', 'orderTickets.ticket.sku'])
            ->whereIn('event_id', $eventIds)
            ->get();



        return response()->json([
            'status' => 'success',
            'message' => 'Get all order history by vendor',
            'data' => $orders
        ]);
    }

    public function getOrderTotalByVendor($id){
        // Ambil semua vendor berdasarkan user_id
        $vendors = Vendor::where('user_id', $id)->get();

        // Ambil semua event ID yang terkait dengan vendor-vendor ini
        $eventIds = Event::whereIn('vendor_id', $vendors->pluck('id'))->pluck('id');

        // Ambil semua order berdasarkan event_id
        $orders = Order::with(['user', 'event.vendor', 'orderTickets.ticket.sku'])
            ->whereIn('event_id', $eventIds)
            ->get();

        $sumTotalOrder = $orders->sum('total_price');

        if ($orders->isNotEmpty()) {
            Log::info($orders[0]->total_price);
        } else {
            Log::info('No orders found.');
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Get total price order history by vendor',
            'data' => $sumTotalOrder
        ]);
    }
}
