<?php
namespace App\Http\Controllers;


use App\Models\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
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
        $query = Client::with('user');

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

        $clients = $query->get();

        return response()->json(['client' => $clients], 200);
    }

    public function get_by_id($id )
    {
        try {
            $client = Client::with('user')
                ->where('clients.id', $id)
                ->firstOrFail();
            if (!$client) {
                return response()->json(['error' => 'client profile not found'], 404);
            }
            return response()->json(['client' => $client], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve client profile: '.$e->getMessage()], 500);
        }
    }


    public function store(Request $request){
        try{
            $client = Client::create([
                "user_id"=>$request->user_id,
                "location"=> $this->getLocation($request), 
            ]);
            return response()->json(['client' => $client], 200);
        }catch(\Exception $e) {
            return response()->json(['message' => 'Error creating client profile: '.$e->getMessage()], 500);
        }
        
    }

    public function update(Request $request,$id){
        try{
            $client = Client::findOrFail($id);
            $client->update([
                "user_id"=>$request->user_id,
                "location"=> $this->getLocation($request), 
            ]);
            return response()->json(['client' => $client], 200);
        }catch(\Exception $e) {
            return response()->json(['message' => 'Error updating client profile: '.$e->getMessage()], 500);
        }
    }

    public function delete ($id){
        try{
            $client =Client::where('id', $id)->delete();
            if (!$client) {
                return response()->json(['message' => 'client profile not found'], 404);
            }
        return response()->json(['message' => ' client deleted successfully'], 200);
        } catch(\Exception $e) {
            return response()->json(['message' => 'Error deleting client profile: '.$e->getMessage()], 500);
        }
    }
}