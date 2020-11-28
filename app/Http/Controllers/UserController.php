<?php

namespace App\Http\Controllers;
use App\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'bio' => 'required|string',
            'profile'=>'required'
        ]);
         
        $path= "";
        if($request->hasFile('profile')){
        $img_Name = time().$request->file('profile')->getClientOriginalName();
        $as = $request->profile->storeAs('images',$img_Name);
        $man = 'app\images\\'.$img_Name;
        $path  = storage_path($man);
        }
        
        $user = new User([
            'name'=> $request->name,
            'email'=> $request->email,
            'bio'=>$request->bio,
            'profile'=>$path,
        ]);
        if($user->save()){
            return response()->json([
                'message' => 'Data has been saved',
                'data'=>$user,
            ],200);
        }else{
            return response()->json([
                'message' => 'something went wrong! please check',
            ]);
        }
    }

    public function getUser()
    {
        $user =  new User();
       $users =  $user->get()->toJson(JSON_PRETTY_PRINT);
       return response($users, 200);
    }
}
