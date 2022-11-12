<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class AcceuilController extends Controller
{
    //
    public function acceuil(){
        $Medecins = User::whereRoleIs('doctor')
            ->where('status',true)
            ->limit(50) // here is yours limit
            ->orderBy('score','desc')
            ->orderBy('nombre_reservations','desc')
            ->orderBy('created_at','asc')
            ->select
            (
                'id',
                'nom',
                'prenom',
                'photo',
                'email',
                'specialite',
                'ville',
                'cabinet_adresse',
                'registercomerce',
                'tele',
                'tele_cabinet',
                'score',
                'nombre_reservations',
                'created_at',
            )
            ->get();

        return Response::json([
            'data' => $Medecins
        ],200);
    }
}
