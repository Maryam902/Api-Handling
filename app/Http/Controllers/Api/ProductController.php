<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Productresource;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\error;

class ProductController extends Controller
{
    public function index()
    {

        $products = product::get();
        if ($products->count() > 0) {
            return Productresource::collection($products);
        } else {
            return response()->json(['no record available'], 200);
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|',
            'price' => 'required|numeric', 
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()
            ], 422);
        }
        // insertion
       $product =  product::create([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price
        ]);
        return response()->json([
           'message'=> 'Data inserted Successfully',
           'data'=>  new Productresource($product)
        ],200);
    }



    // showing data according to diff ids
    public function show(product $product) {
         return new  Productresource($product);
    }


    // updation of data
    public function update(Request $request, product $product) {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|',
            'price' => 'required|numeric', 
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->messages()
            ], 422);
        }
        // insertion for updation
       $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price
        ]);
        return response()->json([
           'message'=> 'Data updated Successfully',
           'data'=>  new Productresource($product)
        ],200);
    }

    // deletion
    public function destroy(product $product) {
       $product->delete();
       return response()->json([
        'message'=> 'Data deleted Successfully',
     ],200);

    }
}
 