<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\GeneralCode;
use Illuminate\Support\Facades\Response;

class SearchController extends Controller
{
    //
    use GeneralCode;
    public function Doctors(Request $request)
    {
        //
        if($request->input('ville') == null && $request->input('specialite') == null){
            return Response::json([
                'error' => 'select items',
                'status' => 'false'
            ],400);
        }
        if($request->input('specialite') != null && $request->input('ville') == null){
            return $this->search($request->input('specialite'),'specialite',true);
        }else if($request->input('specialite') == null && $request->input('ville') != null){
            return $this->search($request->input('ville'),'ville',true);
        }else {
            return $this->searchByvilleAndSpeciality($request->input('ville'),$request->input('specialite'),true);
        }


    }
}
