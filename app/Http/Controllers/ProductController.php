<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Product;
use App\Model\User;

class ProductController extends Controller
{
   public function create_product(Request $request)
   {
    $request->validate([
        'name' => 'required|string',
        'mrp' => 'required|string',
        'description' => 'required|string',
        'userId'=>'required|string',
        'image'=>'required',
    ]);

    $users = new User();
    $user = $users->where('id',$request->userId)->first();
    if($user == null){
        return response()->json([
            'message' => 'You enter wrong User Id',
        ],404);
    }
     
    $pathImg= "";
    $img_path = [];
    if($request->hasFile('image')){
        foreach($request->file('image') as $image)
        {
            $img_Name = time().$image->getClientOriginalName();
            $image->storeAs('images',$img_Name);
             $man = 'app\images\\'.$img_Name;
             $path  = storage_path($man);
             array_push($img_path,$path);
        }
        $pathImg = implode(',',$img_path);
    }
    
    $product = new Product([
        'name'=> $request->name,
        'mrp'=> $request->mrp,
        'description'=>$request->description,
        'userId'=>$request->userId,
        'images'=>$pathImg,
    ]);
    if($product->save()){
        return response()->json([
            'message' => 'Data has been saved',
            'data'=>$product,
        ],200);
    }else{
        return response()->json([
            'message' => 'something went wrong! please check',
        ]);
    }
}

   public function listproduct()
   {
       $Products = new Product();
       if($Products != null){
            $Product =  $Products->get()->toJson(JSON_PRETTY_PRINT);
            return response($Product, 200);
       }
       return response()->json([
        'message' => 'Data not found',
    ]);
   }

   public function updateproducts(Request $request)
   {
    $products = new Product();
    $product = $products->where('id',$request->product_id)->first();
    if($product == null){
        return response()->json([
            'message' => 'You enter wrong Product Id',
        ],404);
    }
    $request->validate([
        // 'name' => 'required|string',
        // 'mrp' => 'required|string',
        // 'description' => 'required|string',
        // 'userId'=>'required|string',
        // 'image'=>'required',
        'product_id'=>'required',
    ]);
    if(!empty($request->name)){
        $product->name = $request->name;
    }
    if(!empty($request->mrp)){
        $product->mrp = $request->mrp;
    }
    if(!empty($request->description)){
        $product->description = $request->description;
    }
    if(!empty($request->userId)){
        $product->userId = $request->userId;
    }
    if($request->hasFile('image')){
        $img_path= [];
        foreach($request->file('image') as $image)
        {
            $img_Name = time().$image->getClientOriginalName();
            $image->storeAs('images',$img_Name);
             $man = 'app\images\\'.$img_Name;
             $path  = storage_path($man);
             array_push($img_path,$path);
        }
        $pathImg = implode(',',$img_path);
        $product->images = $pathImg;
    }

    if($product->save()){
        return response()->json([
            'message' => 'Product has been Updated',
            'data'=>$product,
        ],200);
    }else{
        return response()->json([
            'message' => 'something went wrong! please check',
        ]);
    }

   }

   public function deleteproduct(Request $request)
   {
        $product = Product::where('id', $request->id);
        if($product->exists()) {
            $product->delete();
            return response()->json([
                "message" => "record deleted",
                "id" => $request->id,
              ], 200);
        }else {
            return response()->json([
            "message" => "Product not found"
            ], 404);
        }
   }
}
