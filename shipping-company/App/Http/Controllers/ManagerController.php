<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 

use App\Models\ManagerProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ManagerController extends Controller
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


    public function index(Request $request)
    {
        $this->authorize('viewAny', ManagerProfile::class);
        $query = ManagerProfile::with('user');

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

        $managers = $query->get();

        return response()->json(['managers' => $managers], 200);
    }

    public function get_by_id($id )
    {
        $this->authorize('view', ManagerProfile::class);
        try {
            $manager = ManagerProfile::with('user')
                ->where('manager_profiles.id', $id)
                ->firstOrFail();
            if (!$manager) {
                return response()->json(['error' => 'managers profile not found'], 404);
            }

            return response()->json(['manager' => $manager], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve managers profile: '.$e->getMessage()], 500);
        }
    }


    public function store(Request $request){
        $this->authorize('create', ManagerProfile::class);
        try{
            $manager = ManagerProfile::create([
                "user_id"=>$request->user_id,
                "salary"=>$request->salary,
                "location"=> $this->getLocation($request), 
            ]);
            return response()->json(['manager' => $manager], 200);
        }catch(\Exception $e) {
            return response()->json(['message' => 'Error creating managers profile: '.$e->getMessage()], 500);
        }
        
    }

    public function update(Request $request,$id){
        $this->authorize('update', ManagerProfile::class);
        try{
            $manager = ManagerProfile::findOrFail($id);
            $manager->update([
                "user_id"=>$request->user_id,
                "salary"=>$request->salary,
                "location"=> $this->getLocation($request), 
            ]);
            return response()->json(['manager' => $manager], 200);
        }catch(\Exception $e) {
            return response()->json(['message' => 'Error updating managers profile: '.$e->getMessage()], 500);
        }
    }

    public function delete ($id){
        $this->authorize('delete', ManagerProfile::class);
        try{
            $manager =ManagerProfile::where('id', $id)->delete();
            if (!$manager) {
                return response()->json(['message' => 'managers profile not found'], 404);
            }
        return response()->json(['message' => 'managers profile deleted successfully'], 200);
        } catch(\Exception $e) {
            return response()->json(['message' => 'Error deleting managers profile: '.$e->getMessage()], 500);
        }
    }

    public function get_by_salary($minSalary, $maxSalary = null){
        $this->authorize('viewAny', ManagerProfile::class);
        try{
            $manager = ManagerProfile::with('user')
                ->orderBy('salary', 'asc')
                ->get();
            return response()->json(['manager' => $manager], 200);
        }catch(\Exception $e) {
            return response()->json(['message' => 'Error retrieving managers profiles by salary: '.$e->getMessage()], 500);
        }
    }
}