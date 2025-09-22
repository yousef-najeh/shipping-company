<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 
use App\Http\Controllers\Controller;
use App\Models\VendorShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorShopController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', VendorShop::class);
        $query = VendorShop::query()->with('user');

        if ($request->has('shop_name')) {
            $query->where('shop_name', 'like', '%' . $request->shop_name . '%');
        }

        if ($request->has('shop_phone')) {
            $query->where('shop_phone', $request->shop_phone);
        }

        if ($request->has('shop_email')) {
            $query->where('shop_email', $request->shop_email);
        }

        if ($request->has('created_from') && $request->has('created_to')) {
            $query->whereBetween('created_at', [$request->created_from, $request->created_to]);
        }

        $shops = $query->get();

        return response()->json(['shops' => $shops]);
    }

    public function get_by_id($id)
    {
        $this->authorize('view', VendorShop::class);
        $shop = VendorShop::with('client')->findOrFail($id);
        return response()->json(['shop' => $shop]); 
    }

    public function store(Request $request)
    {
        $this->authorize('create', VendorShop::class);
        $request->validate([
            'client_id' => 'required|integer',
            'shop_name' => 'required|string|max:255',
            'location.lat' => 'required|numeric',
            'location.lng' => 'required|numeric',
            'shop_phone' => 'required|string|max:255',
            'shop_email' => 'required|email|max:255',
            'shop_description' => 'required|string|max:255',
        ]);

        $location = $this->getLocation($request);

        $shop = VendorShop::create([
            'client_id' => $request->client_id,
            'shop_name' => $request->shop_name,
            'location' => $location,
            'shop_phone' => $request->shop_phone,
            'shop_email' => $request->shop_email,
            'shop_description' => $request->shop_description,
        ]);

        return response()->json(['shop' => $shop]);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update', VendorShop::class);
        $shop = VendorShop::findOrFail($id); 

        $location = $this->getLocation($request);

        $shop->update([
            "client_id" => $request->client_id,
            "shop_name" => $request->shop_name,
            "location" => $location,
            "shop_phone" => $request->shop_phone,
            "shop_email" => $request->shop_email,
            'shop_description' => $request->shop_description,
        ]);
        return response()->json(['shop' => $shop]);
    }

    public function destroy($id)
    {
        $this->authorize('delete', VendorShop::class);
        $shop = VendorShop::where('id', $id)->delete();
        return response()->json(['message' => 'shop deleted successfully', 'shop' => $shop]);
    }

    private function getLocation(Request $request)
    {
        
        $lat = $request->location['lat'];
        $lng = $request->location['lng'];

        if (!$lat || !$lng) {
            throw new \InvalidArgumentException('Latitude and longitude are required');
        }

        return DB::raw("ST_GeomFromText('POINT($lng $lat)')"); 
    }
}