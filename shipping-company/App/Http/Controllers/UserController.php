<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $this->authorize('viewAny', User::class);

        $query = User::query();

        if ($request->has('first_name')) {
            $query->where('first_name', 'like', '%' . $request->first_name . '%');
        }

        if ($request->has('last_name')) {
            $query->where('last_name', 'like', '%' . $request->last_name . '%');
        }

        if ($request->has('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        if ($request->has('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->get();

        return response()->json(['users' => $users], 200);
    }

public function get_by_id($id){
    $user = User::findOrFail($id);
    $this->authorize('view', $user); 
    return ['user' => $user];
}


    public function store(Request $request)
    {
        $this->authorize('create', User::class);
        try {
            $user = User::create([
                'email' => $request->email,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'password' => bcrypt($request->password),
                'phone_number' => $request->phone_number,
                'role' => $request->role,
            ]);
            return response()->json(['user' => $user], 201);
        }catch(\Exception $e) {
            return response()->json(['message' => 'Error creating user'. $e->getMessage()], 500);
        }
    }

    public function update (Request $request, $id){
        $this->authorize('update', User::class);
            $user = User::findOrFail($id);
            $user->update($request->all());
            return response()->json(['user' => $user], 200);
    }

    public function delete ($id ){
        $this->authorize('delete', User::class);
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(['message' => 'User deleted successfully'], 200);
    }
}