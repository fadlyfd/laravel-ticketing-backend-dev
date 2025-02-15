<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Models\EventCategory;
use App\Models\Vendor;

class EventController extends Controller
{
    //index
    public function index(Request $request)
    {
        //event by category_id

        // dd($request);
        // dd($categoryId);
        // dd($request->input('category_id'));
        $categoryId = $request->input('category_id');
        $event = [];
        //if category id all
        if ($categoryId == 'all') {
            $events = Event::all();
        } else {
            $events = Event::where('event_category_id', $categoryId)->get();
        }
        //all event
        //$events = Event::all();
        //load event_category and vendor
        $events->load('eventCategory', 'vendor');
        return response()->json([
            'status' => 'success',
            'message' => 'Events fetched successfully',
            'data' => $events,
        ]);
    }

    //get all event categories
    public function categories()
    {
        //get all event categories
        $categories = EventCategory::all();
        return response()->json([
            'status' => 'success',
            'message' => 'Event categories fetched successfully',
            'data' => $categories,
        ]);
    }

    //detail event and sku by event_id
    public function detail(Request $request)
    {
        //event by event_id
        $event = Event::find($request->event_id);
        //load event_category and vendor
        $event->load('eventCategory', 'vendor');
        $skus = $event->skus;
        $event['skus'] = $skus;
        return response()->json([
            'status' => 'success',
            'message' => 'Event fetched successfully',
            'data' => $event,
        ]);
    }

    public function getAllEvents()
    {
        // get events with vendor and event category
        $events = Event::with(['vendor', 'eventCategory', 'tickets.sku'])->get();
        return response()->json([
            'status' => 'success',
            'data' => $events
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required',
            'event_category_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'image' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $data = $request->all();

        $event = \App\Models\Event::create($data);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/events'), $filename);
            $event->image = $filename;
            $event->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Event created successfully',
            'data' => $event
        ], 201);
    }

    public function update(Request $request, $id){
        $request->validate([
            'vendor_id' => 'required',
            'event_category_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'image' => 'nullable',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);

        $data = $request->all();

        $event = \App\Models\Event::findOrFail($id);
        $event->update($data);
        if ($request->hasFile('image')) {
            // remove image sebelumnya
            if ($event->image) {
                $image_path = public_path('images/events/' . $event->image);
                if (file_exists($image_path)) {
                    unlink($image_path);
                }

            }
            $file = $request->file('image');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/events'), $filename);
            $event->image = $filename;
            $event->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Event updated successfully',
            'data' => $event
        ], 200);

    }

    public function delete($id)
    {
        $event = \App\Models\Event::findOrFail($id);
        $event->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Event deleted successfully',
        ]);
    }

    public function getEventByUser($id)
    {
        // Ambil semua vendor berdasarkan user_id
        $vendors = Vendor::where('user_id', $id)->get();

        // Ambil semua event ID yang terkait dengan vendor-vendor ini
        $eventIds = Event::whereIn('vendor_id', $vendors->pluck('id'))->pluck('id');

        // ambil semua event berdasarkan event_id
        $events = Event::with(['vendor', 'eventCategory', 'tickets.sku'])->whereIn('id', $eventIds)->get();
        return response()->json([
            'status' => 'success',
            'data' => $events
        ]);
    }
}
