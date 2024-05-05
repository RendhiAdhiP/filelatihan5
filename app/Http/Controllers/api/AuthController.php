<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validate = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:5',

        ]);

        $data = $request->only(['email','password']);

        if (Auth::attempt($data)) {
            $token = $request->user()->createToken('login_token')->plainTextToken;
            $user = $request->user();

            $i = [
                'name' => $user->name,
                'email' => $user->email,
                'accessToken' => $token,
            ];

            return response()->json(['messaage'=>'Login success','user'=>$i],200);
        }

        return response()->json(['messaage'=>'Email or password incorrect'],401);

    }

    public function logout(Request $request){
        $request->user()->tokens()->delete();
        return response()->json(['messaage'=>'Logout success'],200);

    }
}

/**
 * 
 *public function name(){
 *   return $this->belongsTo(model::clas, 'foreign','id)
 *}
 * public function name(Request $request){
 * $validate = $request->validate([
 *      'data'=>'required',
 *      'data'=>'required',
 *      'data'=>'required',
 *      'data'=>'required',
 *      'data'=>'required',
 * ]);
 * 
 * 
 * }
 
 * 
 * $i = [
 *      'data'=>$request->data,
 *      'data'=>$request->data,
 *      'data'=>$request->data,
 *      'data'=>$request->data,
 * ];
 * 
 * 
 * 
 */
