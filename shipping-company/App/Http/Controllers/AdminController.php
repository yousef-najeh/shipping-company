<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 
use App\Models\AdminProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    use AuthorizesRequests;


    public function getLocation(Request $request){
        $lat = $request->location['lat'];
        $lng = $request->location['lng'];

        if (!$lat || !$lng) {
            throw new \InvalidArgumentException('Latitude and longitude are required');
        }

        return DB::raw("ST_GeomFromText('POINT($lng $lat)')");
    }


    public function index(Request $request){
        $this->authorize('viewAny', AdminProfile::class);

        $query = AdminProfile::with('user');

        if ($request->has('first_name')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->first_name . '%');
            });
        }


        if ($request->has('last_name')) {
                $query->whereHas('user', function($q) use ($request) {
                    $q->where('last_name', 'like', '%' . $request->last_name . '%');
                });
        }

        if ($request->has('minSalary') && $request->has('maxSalary')) {
            $query->whereBetween('salary', [$request->minSalary, $request->maxSalary]);
        } elseif ($request->has('minSalary')) {
            $query->where('salary', '>=', $request->minSalary);
        } elseif ($request->has('maxSalary')) {
            $query->where('salary', '<=', $request->maxSalary);
        }

        $admins = $query->get();

        return response()->json(['admins' => $admins], 200);
}

    public function show($id )
    {
                $this->authorize('view', AdminProfile::class);

        try {
            $admin = AdminProfile::with('user')
                ->where('admin_profiles.id', $id)
                ->firstOrFail();

            if (!$admin) {
                return response()->json(['error' => 'Admin profile not found'], 404);
            }

            return response()->json(['admin' => $admin], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve admin profile: '.$e->getMessage()], 500);
        }
    }


    public function store(Request $request){
        $this->authorize('create', AdminProfile::class);

        try{
            $admin = AdminProfile::create([
                "user_id"=>$request->user_id,
                "salary"=>$request->salary,
                "location"=> $this->getLocation($request), 
            ]);
            return response()->json(['admin' => $admin], 200);
        }catch(\Exception $e) {
            return response()->json(['message' => 'Error creating admin profile: '.$e->getMessage()], 500);
        }
        
    }

    public function update(Request $request,$id){
        $this->authorize('update', AdminProfile::class);
        try{
            $admin = AdminProfile::findOrFail($id);
            $admin->update([
                "user_id"=>$request->user_id,
                "salary"=>$request->salary,
                "location"=> $this->getLocation($request), 
            ]);
            return response()->json(['admin' => $admin], 200);
        }catch(\Exception $e) {
            return response()->json(['message' => 'Error updating admin profile: '.$e->getMessage()], 500);
        }
    }

    public function destroy ($id){
        $this->authorize('delete', AdminProfile::class);
        try{
            $admin =AdminProfile::where('id', $id)->delete();
            if (!$admin) {
                return response()->json(['message' => 'Admin profile not found'], 404);
            }
        return response()->json(['message' => 'Admin profile deleted successfully'], 200);
        } catch(\Exception $e) {
            return response()->json(['message' => 'Error deleting admin profile: '.$e->getMessage()], 500);
        }
    }
}