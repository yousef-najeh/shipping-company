<?php
namespace App\Http\Controllers;

use illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Item;

class ItemController extends Controller{

    public function index(Request $request){
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
        $item= Item::findOrFail($id);
        return response()->json(['Item'=>$item]);
    }




    public function store(Request $request)
    {
        // dd($request->method()); 
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);
        $item = Item::create($validated);
        return response()->json(['success' => true,'item'    => $item], 201);
    }



    public function update(Request $request,$id){
        $item= Item::where('id',$id)->get();
        $item->update($request->all());
        return response()->json(["item",$item,"message"=>"the item has been updated successfully"]);
    }

    public function destroy($id){
        $item = Item::findOrFail($id);
        $item->delete($id);
        return response()->json(['message'=>"item delete successfully"]);
    }
}
