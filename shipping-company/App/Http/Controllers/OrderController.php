<?php
namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller{

    public function index(Request $request)
    {
        $query = Order::query()
            ->with('shipment')
            ->with('itemOrder') 
            ->with('vendor');

        if ($request->has('order_status')) {
            $query->where('order_status', $request->order_status);
        }

        if ($request->has('vendor_id')) {
            $query->where('vendor_id', $request->vendor_id);
        }

        if ($request->has('shipment_id')) {
            $query->where('shipment_id', $request->shipment_id);
        }

        if ($request->has('size')) {
            $query->where('size', $request->size);
        }

        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        if ($request->has('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }

        if ($request->has('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        if ($request->has('shipment_status')) {
            $query->whereHas('shipment', function($q) use ($request) {
                $q->where('status', $request->shipment_status);
            });
        }

        if ($request->has('shop_name')) {
            $query->whereHas('vendor', function($q) use ($request) {
                $q->where('shop_name', 'like', '%' . $request->shop_name . '%');
            });
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('order_status', 'like', "%{$search}%")
                  ->orWhere('size', 'like', "%{$search}%")
                  ->orWhere('weight', 'like', "%{$search}%")
                  ->orWhereHas('vendor', function($vendorQuery) use ($search) {
                      $vendorQuery->where('shop_name', 'like', "%{$search}%");
                  });
            });
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
        $allowedSorts = ['created_at', 'price', 'order_status', 'size'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        }

        $perPage = $request->get('per_page', 15);
        $perPage = min($perPage, 100); 

        if ($request->has('paginate') && $request->paginate == 'true') {
            $orders = $query->paginate($perPage);
            return response()->json($orders);
        }

        $orders = $query->get();
        return response()->json(['orders' => $orders]);
    }

}