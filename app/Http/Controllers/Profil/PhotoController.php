<?php

namespace App\Http\Controllers\Profil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;

class PhotoController extends Controller
{
    //
    public function store(Request $request){
       $data = request()->user();
        $request->validate([
            'photo' => 'required|mimes:jpg,png',
        ]);
        if($data->photo != null){
            // Value is  URL
            if(File::exists($data->photo)) {
                File::delete($data->photo);
            }
        }

        $data->photo = $request->file('photo')->store('profile');
        $data->save();
        return Response::json([
            'status' => true,
            'message' => 'photo de profil changer avec succes',
            'user' => $data
        ],200);
    }

    public function destroy(){
        $data = request()->user();
        if($data->photo != null){
            // Value is  URL
            if(File::exists($data->photo)) {
                File::delete($data->photo);
            }
            return Response::json([
                'status' => true,
                'message' => 'photo de profil supprimer avec succes',
                'user' => $data
            ],200);
        }else{
            return Response::json([
                'status' => false,
                'message' => 'il n\'ya pas de photo !',
                'user' => $data
            ],222);
        }
    }

}
