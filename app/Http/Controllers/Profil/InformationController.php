<?php

namespace App\Http\Controllers\Profil;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rules;

class InformationController extends Controller
{
    //
    public function store_1(Request $request){
        {
            $request->validate([
                'nom' => ['required', 'string', 'max:255'],
                'prenom' => ['required', 'string', 'max:255'],
                'ville' => ['required', 'string', 'max:255'],
                'tele' => ['required', 'string', 'max:14'],

            ]);

            request()->user()->update([
                'nom' => $request->input('nom'),
                'prenom' => $request->input('prenom'),
                'ville' => $request->input('ville'),
                'tele' => $request->input('telephone'),

            ]);

            return Response::json([
                'message' => 'Votre compte mise a jour avec succes !',
                'status' => 'succes',
            ],200);
        }
    }


    //only doctors
    public function store_2(Request $request){
        {
            $request->validate([
                'specialite' => ['required', 'string',  'max:255'],
                'cabinet_adresse' => ['required', 'string',  'max:255'],
                'description' => ['required', 'string',  'max:255'],
                'prix' => ['required', 'integer'],
                'tele_cabinet' => ['required', 'string', 'max:14'],

            ]);

            request()->user()->update([

                'specialite' => $request->input('specialite'),
                'tele_cabinet' => $request->input('telephone_cabinet'),
                'cabinet_adresse' => $request->input('adresse'),
                'description' => $request->input('description'),
                'prix' => $request->input('prix'),

            ]);

            return Response::json([
                'message' => 'Votre compte mise a jour avec succes !',
                'status' => 'succes',
            ],200);
        }
    }
}
