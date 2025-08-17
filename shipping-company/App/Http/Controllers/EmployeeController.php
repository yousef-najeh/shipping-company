<?php
namespace App\Http\Controllers;


use App\Models\EmployeeProfile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{


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
        $query = EmployeeProfile::with('user');

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

        $employees = $query->get();

        return response()->json(['employee' => $employees], 200);
    }

    public function get_by_id($id )
    {
        try {
            $employee = EmployeeProfile::with('user')
                ->where('employee_profiles.id', $id)
                ->firstOrFail();
            if (!$employee) {
                return response()->json(['error' => 'employee profile not found'], 404);
            }

            return response()->json(['employee' => $employee], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve employee profile: '.$e->getMessage()], 500);
        }
    }


    public function store(Request $request){
        
        try{
            $employee = EmployeeProfile::create([
                "user_id"=>$request->user_id,
                "salary"=>$request->salary,
                "location"=> $this->getLocation($request), 
            ]);
            return response()->json(['employee' => $employee], 200);
        }catch(\Exception $e) {
            return response()->json(['message' => 'Error creating employee profile: '.$e->getMessage()], 500);
        }
        
    }

    public function update(Request $request,$id){
        try{
            $employee = EmployeeProfile::findOrFail($id);
            $employee->update([
                "user_id"=>$request->user_id,
                "salary"=>$request->salary,
                "location"=> $this->getLocation($request), 
            ]);
            return response()->json(['employee' => $employee], 200);
        }catch(\Exception $e) {
            return response()->json(['message' => 'Error updating employee profile: '.$e->getMessage()], 500);
        }
    }

    public function delete ($id){
        try{
            $employee =EmployeeProfile::where('id', $id)->delete();
            if (!$employee) {
                return response()->json(['message' => 'employee profile not found'], 404);
            }
        return response()->json(['message' => 'employee profile deleted successfully'], 200);
        } catch(\Exception $e) {
            return response()->json(['message' => 'Error deleting employee profile: '.$e->getMessage()], 500);
        }
    }

    public function get_by_salary($minSalary, $maxSalary = null){
        try{
            $employee = EmployeeProfile::with('user')
                ->orderBy('salary', 'asc')
                ->get();
            return response()->json(['employee' => $employee], 200);
        }catch(\Exception $e) {
            return response()->json(['message' => 'Error retrieving employee profiles by salary: '.$e->getMessage()], 500);
        }
    }
}