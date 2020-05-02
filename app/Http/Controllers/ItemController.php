<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Item;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function createItem(Request $request){
        $success = false;
        DB::beginTransaction();
        try {
            //creamos el modelo
            $item = new Item;
            $item->name = $request->get('name');
            $item->category = $request->get('category');
            $item->price = $request->get('price');
            $item->discount = $request->get('discount');
            $item->quantity = $request->get('quantity');

            //guardamos en la base de datos con el método save
            if($item->save())
            {
                $success = true;
            }
        } catch (\Exception $e) {
            // maybe log this exception, but basically it's just here so we can rollback if we get a surprise
            echo $e->getTraceAsString();
            exit();
        }

        //ahora creamos una variable de sesion antes de hacer la redirección
        if ($success) {
            DB::commit();
            return response()->json([], 200); 
        } else {
            DB::rollback();
            return response()->json([], 500); 
        }	
	
    }

    public function updateItem(Request $request){
        $success = false;
        DB::beginTransaction();
        try {
            //creamos el modelo
            $item;
            if(Item::find($request->get('barcode'))->exists()){
                $item = Item::find($request->get('barcode'));
            }
            else{
                return response()->json([], 404); 
            }
            $item->name = $request->get('name');
            $item->category = $request->get('category');
            $item->price = $request->get('price');
            $item->discount = $request->get('discount');
            $item->quantity = $request->get('quantity');

            //guardamos en la base de datos con el método save
            if($item->save())
            {
                $success = true;
            }
        } catch (\Exception $e) {
            // maybe log this exception, but basically it's just here so we can rollback if we get a surprise
            echo $e->getTraceAsString();
            exit();
        }

        //ahora creamos una variable de sesion antes de hacer la redirección
        if ($success) {
            DB::commit();
            return response()->json([], 200); 
        } else {
            DB::rollback();
            return response()->json([], 500); 
        }	
	
    }

    public function deleteItem(Request $request){
        try {
            $item = Item::find($request->get('barcode'))->get();
            $item->delete();
            return response()->json([], 200); 

        } catch (\Exception $e) {
            // maybe log this exception, but basically it's just here so we can rollback if we get a surprise
            echo $e->getTraceAsString();
            exit();
        }
    }

    public function getItems(Request $request){
        try {
            return response()->json(Item::all(), 200); 
        } catch (\Exception $e) {
            // maybe log this exception, but basically it's just here so we can rollback if we get a surprise
            echo $e->getTraceAsString();
            exit();
        }
    }

    public function getItemByQuery(Request $request){
        try {
            if($request->query('barcode') != null){
                return response()->json(Item::find($request->query('barcode')), 200); 
            }
            else if( $request->query('category') != null ){
                return response()->json(Item::where('category','=',$request->query('category')), 200); 
            }
            else if($request->query('name') != null){
                return response()->json(Item::where('name','=',$request->query('name')), 200); 
            }
            else if($request->query('discount') != null){
                return response()->json(Item::where('discount','>',$request->query('discount')), 200); 
            }
        } catch (\Exception $e) {
            // maybe log this exception, but basically it's just here so we can rollback if we get a surprise
            echo $e->getTraceAsString();
            exit();
        }
    }
}
