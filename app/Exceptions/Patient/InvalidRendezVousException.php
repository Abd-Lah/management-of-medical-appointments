<?php

namespace App\Exceptions\Patient;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class InvalidRendezVousException extends Exception
{
    //
    public function render(Request $request){
        return Response::json([
            'status' => false,
            'message' => $this->getMessage(),
        ],220);

    }
}
