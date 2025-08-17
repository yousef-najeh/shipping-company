<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use illuminate\Http\Request;
use App\Models\Shipment;

class ShipmentController extends Controller{

    public function index(Request $request)
    {
        $query = Shipment::query()->with('user');

        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('tracking_number')) {
            $query->where('tracking_number', 'like', '%' . $request->tracking_number . '%');
        }

        if ($request->has('shipped_from') && $request->has('shipped_to')) {
            $query->whereBetween('shipped_at', [$request->shipped_from, $request->shipped_to]);
        }

        if ($request->has('delivered_from') && $request->has('delivered_to')) {
            $query->whereBetween('delivered_at', [$request->delivered_from, $request->delivered_to]);
        }

        return response()->json($query->get());
    }

    public function get_by_id($id){
        $shipment = Shipment::where('id',$id)->get();
        return response()->json(["shipment"=>$shipment]);
    }

    public function update (Request $request,$id){
        $shipment = Shipment::findOrFail($id);

        $shipment->update($request->all());
        return response()->json(["message"=>$shipment]);
    }


}