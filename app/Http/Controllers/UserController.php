<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request){
        // $request->validate([
        //     'password'=>"required|confirmed"
        // ]);
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        $token = $user->createtoken("myToken")->plainTextToken;
        return response()->json([
            "Message"=>"Created",
            "user"=>$user,
            "token"=>$token
        ]);
    }
    public function login(Request $request){
        // $request->validate([
        //     "password"=>"required|confirmed",
        // ]);

        $user = User::where("email",$request->email)->first();
        // if(!$user || !Hash)
        if(!$user || !Hash::check($request->password, $user->password)){
            return response()->json(["message"=>"badLogin"],401);
        }
        $token = $user->createToken("mytoken")->plainTextToken;
        return response()->json([
            "user"=>$user,
            "token"=>$token
        ]);
    }

    public function logOut(Request $request){
        auth()->user()->tokens()->delete();
        return response()->json(["message"=>"Signing out"]);
    }
    public function getUser(){
        return User::get();
    }
}
//4|tQU4qpvWPaivrBmxOKk5iKy4fSBo9ZWXI4q2kwtr
