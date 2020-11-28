<?php

namespace App\Http\Controllers;
use App\Model\Business;
use App\Model\User;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function create_business(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:businesses',
            'email' => 'required|string|email',
            'registrationNo'=>'required|string|unique:businesses'
        ]);
        $business = new Business([
            'name'=> $request->name,
            'email'=> $request->email,
            'registrationNo'=>$request->registrationNo,
        ]);

        // return $business;
        if($business->save()){
            return response()->json([
                'message' => 'Data has been saved',
                'data'=>$business,
            ],200);
        }else{
            return response()->json([
                'message' => 'something went wrong! please check',
            ]);
        }
    }
    public function listBusiness()
    {
        $business = new Business();
        $businesses = $business->get()->toJson(JSON_PRETTY_PRINT);
        return response($businesses, 200);
    }

    public function select_business(Request $request)
    {
        $users = new User();
        $user = $users->where('id',$request->user_id)->first();
        $same_id= [];
        if($user!= null){
            if(empty($user->businessId)){
                $user->businessId = $request->businessId;
            }else{
               $bz_id =  explode(',',$user->businessId);
               $b_id = explode(',',$request->businessId);
               foreach($b_id as $bid){
                  foreach($bz_id as $bzid){
                      if($bid == $bzid){
                        if(!in_array($bid,$same_id)){array_push($same_id,$bid);}
                        break;
                      }
                  }
                  //if(!in_array($bid,$dif_id)){array_push($dif_id,$bid);}
               }
               $result= array_merge($b_id,$same_id);
               $user->businessId = implode(',',array_unique($result));
            }
        }
        if($user->save()){
            return response()->json([
                'message' => 'Data has been updated',
                'data'=>$user,
                'allready added business'=>$same_id
            ],200);
        }else{
            return response()->json([
                'message' => 'something went wrong! please check',
            ]);
        }
    }
}
