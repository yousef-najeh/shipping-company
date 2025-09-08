<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Shipment;

class ShipmentController extends Controller
{
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

    public function get_by_id($id)
    {
        $shipment = Shipment::find($id);
        if (!$shipment) {
            return response()->json(['message' => 'Shipment not found'], 404);
        }
        return response()->json(['shipment' => $shipment]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'required|string',
            'tracking_number' => 'required|string|unique:shipments,tracking_number',
            'shipped_at' => 'nullable|date',
            'delivered_at' => 'nullable|date',
        ]);

        $shipment = Shipment::create([
            'user_id' => $request->user_id,
            'status' => $request->status,
            'tracking_number' => $request->tracking_number,
            'shipped_at' => $request->shipped_at,
            'delivered_at' => $request->delivered_at,
        ]);

        return response()->json(['message' => 'Created successfully', 'shipment' => $shipment]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'status' => 'sometimes|string',
            'tracking_number' => 'sometimes|string|unique:shipments,tracking_number,' . $id,
            'shipped_at' => 'nullable|date',
            'delivered_at' => 'nullable|date',
        ]);

        $shipment = Shipment::findOrFail($id);
        $shipment->update($request->only([
            'user_id',
            'status',
            'tracking_number',
            'shipped_at',
            'delivered_at'
        ]));
        return response()->json(['message' => 'Shipment updated successfully', 'shipment' => $shipment]);
    }

    public function destroy($id)
    {
        $shipment = Shipment::find($id);
        if (!$shipment) {
            return response()->json(['message' => 'Shipment not found'], 404);
        }
        $shipment->delete();
        return response()->json(['message' => 'Shipment deleted successfully']);
    }
}