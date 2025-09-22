<?php
namespace App\Http\Controllers;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests; 

use illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Item;

class ItemController extends Controller{

    use AuthorizesRequests;


    public function index(Request $request){
        $this->authorize('viewAny', Item::class);
        $query = Item::query()->with('itemsOrders');

        if ($request->has('name')){
            $query->where('where','like','%' . $request->name . '%');
        }

        if($request->has('minPrice')&& $request->has('maxPrice')){
            $query->whereBetween('price',[$request->minPrice,$request->maxPrice]);
        }

        return $query->get();
    }

    public function get_by_id($id){
        $this->authorize('view', Item::class);
        $item= Item::findOrFail($id);
        return response()->json(['Item'=>$item]);
    }




    public function store(Request $request)
    {
        $this->authorize('create', Item::class);
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);
        $item = Item::create($validated);
        return response()->json(['success' => true,'item'    => $item], 201);
    }



    public function update(Request $request,$id){
        $this->authorize('update', Item::class);
        $item= Item::where('id',$id)->get();
        $item->update($request->all());
        return response()->json(["item",$item,"message"=>"the item has been updated successfully"]);
    }

    public function destroy($id){
        $this->authorize('delete', Item::class);
        $item = Item::findOrFail($id);
        $item->delete($id);
        return response()->json(['message'=>"item delete successfully"]);
    }
}
