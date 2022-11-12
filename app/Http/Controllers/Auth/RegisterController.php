<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    //
    public function store(Request $request){
        {
            $request->validate([
                'nom' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'ville' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'telephone' => ['required', 'string', 'max:14'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'role' => 'required'

            ]);

            if($request->input('role') != 'doctor' && $request->input('role') != 'patient'){
                return Response::json([
                    'message' => 'something wrong try again !'
                ]);
            }

            if($request->input('role') == 'doctor'){
                $request->validate([
                    'adresse' => ['required', 'string',  'max:255'],
                    'telephone_cabinet' => ['required', 'string', 'max:14'],
                    'registercomerce' => ['required', 'string', 'max:14'],
                ]);
            }


            $user = User::create([
                'nom' => $request->input('nom'),
                'prenom' => $request->input('prenom'),
                'email' => $request->input('email'),
                'ville' => $request->input('ville'),
                'tele' => $request->input('telephone'),
                'tele_cabinet' => $request->input('telephone_cabinet'),
                'cabinet_adresse' => $request->input('adresse'),
                'registercomerce' => $request->input('registercomerce'),
                'password' => Hash::make($request->input('password')),
            ]);
            $user->attachRole($request->input('role'));

            if($request->input('role') == 'doctor'){
                $temps = new WorkTime();
                $temps->jours = ['0','1','2','3','4','5','6'];
                $temps->debut = '07:00:00';
                $temps->fin = '19:00:00';
                $temps->dure = '30';
                $user->timework()->save($temps);
            }
            return Response::json([
                'message' => 'Votre compte crée avec success !'
            ]);
        }
    }
}
