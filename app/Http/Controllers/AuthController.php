<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request  $request){
        $user = new User();
        $user->name = $request->name;
        $user->email=$request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        //create Token

        $token = $user->createToken('laravel8',['user:show']);
        return response()->json([
            'status'=>200,
            'message'=>'successfully register',
            'token'=>$token->plainTextToken
        ]);


    }
    public function login(Request  $request){
        if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
            $user = auth()->user();

            $token = $user->createToken('laravel8');
            return response()->json([
                'status'=>200,
                'message'=>'successfully register',
                'token'=>$token->plainTextToken
            ]);
        }
    }

    public function profile(Request $request){
          $user = auth()->user();
        return response()->json([
            'status'=>200,
            'message'=>'success',
            'data'=>$user
        ]);
    }

    public function userList(Request  $request){
           if(!auth()->user()->tokenCan('user:list')){
               return response()->json([
                   'status'=>403,
                    'message'=>'Unauthorized'

               ]);
           }
           $users = User::all();
           return response()->json([
            'status'=>200,
            'message'=>'success',
            'data'=>$users
        ]);

    }


}
