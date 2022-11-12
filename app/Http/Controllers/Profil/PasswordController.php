<?php

namespace App\Http\Controllers\Profil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rules;

class PasswordController extends Controller
{
    //
    public function store(Request $request){
        $request->validate([
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        request()->user()->update(['password' => Hash::make($request->input('password'))]);
        return Response::json([
            'status' => 'succes'
        ]);
    }
}
