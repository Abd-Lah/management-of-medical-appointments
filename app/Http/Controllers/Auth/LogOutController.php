<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class LogOutController extends Controller
{
    //
    public function index(){

        $user = request()->user();


        return Response::json([
            'data' => $user->tokens()
                ->where('tokenable_id', Auth::user()->id)
                ->select('id','name','last_used_at')
                ->orderBy('id','desc')
                ->get(),
        ]);

    }
    public function destroyFromOne($id){

        $user = request()->user();
        $user->tokens()->where('id', $id)->delete();

        return Response::json([
            'message' => 'logout avec succes',
        ]);
    }
    public function destroyFromCurrent(){
        $user = request()->user();
        $user->tokens()->where('id', $user->currentAccessToken()->id)->delete();
        return Response::json([
            'message' => 'logout avec succes',
        ]);
    }

    public function destroyFromAll(){
        $user = request()->user();
        $user->tokens()->where('tokenable_id', Auth::user()->id)->delete();
        return Response::json([
            'message' => 'logout a partir de tous les appariels avec succes',
        ]);
    }
}
