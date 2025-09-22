<?php
namespace App\Http\Controllers;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DriverProfile;
use Illuminate\Support\Facades\DB;



class DriverController extends Controller{

    use AuthorizesRequests;

    public function getLocation(Request $request){
        $lat = $request->location['lat'];
        $lng = $request->location['lng'];

        if (!$lat || !$lng) {
            throw new \InvalidArgumentException('Latitude and longitude are required');
        }

        return DB::raw("ST_GeomFromText('POINT($lng $lat)')");
    }


    public function index(Request $request)
    {
        $this->authorize('viewAny', DriverProfile::class);
        $query = DriverProfile::with('user');

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

        if ($request->has(['minSalary','maxSalary'])) {
            $query->whereBetween('salary', [$request->minSalary, $request->maxSalary])
            ->orderBy('salary', 'asc');
        }

        $driver = $query->get();

        return response()->json(['driver' => $driver], 200);
    }

    public function get_by_id($id )
    {
        $this->authorize('view', DriverProfile::class);
        try {
            $driver = DriverProfile::with('user')
                ->where('driver_profiles.id', $id)
                ->firstOrFail();
            if (!$driver) {
                return response()->json(['error' => 'driver profile not found'], 404);
            }

            return response()->json(['driver' => $driver], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve driver profile: '.$e->getMessage()], 500);
        }
    }


    public function store(Request $request){
        $this->authorize('create', DriverProfile::class);
        try{
            $driver = DriverProfile::create([
                "user_id"=>$request->user_id,
                "salary"=>$request->salary,
                "location"=> $this->getLocation($request), 
                'license_type'=>$request->license_type,
                'license_number'=>$request->license_number,

            ]);
            return response()->json(['driver' => $driver], 200);
        }catch(\Exception $e) {
            return response()->json(['message' => 'Error creating driver profile: '.$e->getMessage()], 500);
        }
        
    }

    public function update(Request $request,$id){
        $this->authorize('update', DriverProfile::class);
        try{
            $driver = DriverProfile::findOrFail($id);
            $driver->update([
                "user_id"=>$request->user_id,
                "salary"=>$request->salary,
                "location"=> $this->getLocation($request),
                'license_type'=>$request->license_type,
                'license_number'=>$request->license_number, 
            ]);
            return response()->json(['driver' => $driver], 200);
        }catch(\Exception $e) {
            return response()->json(['message' => 'Error updating employee profile: '.$e->getMessage()], 500);
        }
    }

    public function delete ($id){
        $this->authorize('delete', DriverProfile::class);
        try{
            $driver =DriverProfile::where('id', $id)->delete();
            if (!$driver) {
                return response()->json(['message' => 'driver profile not found'], 404);
            }
        return response()->json(['message' => 'driver profile deleted successfully'], 200);
        } catch(\Exception $e) {
            return response()->json(['message' => 'Error deleting driver profile: '.$e->getMessage()], 500);
        }
    }

    public function get_by_salary($minSalary, $maxSalary = null){
        $this->authorize('viewAny', DriverProfile::class);
        try{
            $driver = DriverProfile::with('user')
                ->orderBy('salary', 'asc')
                ->get();
            return response()->json(['driver' => $driver], 200);
        }catch(\Exception $e) {
            return response()->json(['message' => 'Error retrieving driver profiles by salary: '.$e->getMessage()], 500);
        }
    }


}