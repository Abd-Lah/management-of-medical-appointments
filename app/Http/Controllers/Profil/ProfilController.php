<?php

namespace App\Http\Controllers\Profil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ProfilController extends Controller
{
    //

    public function index(){

        $loggedUser = request()->user();

        return Response::json([
            'UserData' => $loggedUser,
            'RoleUser' => $loggedUser->roles()->select('name')->get(),
        ],200);
    }
}
