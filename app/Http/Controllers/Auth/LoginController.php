<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;

class LoginController extends Controller
{
    //
    public function store(Request $request){
        $request -> validate([
            'email' => 'required',
            'password' => 'required',
            'device_name' => 'required'
        ]);
        //Auth::validate($request->only('email','password'));
        $user = User::where('email', '=', $request->email)->first();

        if($user && Hash::check($request->password,$user->password)){

            $token = $user->createToken($request->device_name);

            if (!$user->hasVerifiedEmail()) {
                //request()->user()->sendEmailVerificationNotification();
                return Response::json([
                    'token' => $token->plainTextToken,
                    'message' =>'Valider votre email',
                    'valide' => false
                ],400);
            }
            return Response::json([
                'token' => $token->plainTextToken,
                'user' => $user,
                'roles' => $user->roles()->select('name')->get(),
                'valide' => true
            ], 200);
        }



        return Response::json([
            'message' =>'invalid informations',
            'valide' => null
        ],400);
    }

}
