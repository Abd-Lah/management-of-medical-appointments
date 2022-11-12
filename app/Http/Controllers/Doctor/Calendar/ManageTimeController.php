<?php

namespace App\Http\Controllers\Doctor\Calendar;

use App\Http\Controllers\Controller;
use  \Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class ManageTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return request()->user()->timework()->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'jours' => 'required|array',
            'debut' => 'required|string',
            'fin' => 'required|string',
            'dure'=> 'required|integer'
        ]);

        if(request()->user()->timework()->first() == null){
            request()->user()->timework()->create($request->all());
            return Response::json([
                'status' => true,
                'data' => request()->user()->timework()->get(),
            ]);
        }else{
            return Response::json([
                'status' => false,
                'data' => request()->user()->timework()->get(),
            ]);
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request)
    {
        //
        $request->validate([
            'jours' => 'required|array',
            'debut' => 'required|string',
            'fin' => 'required|string',
            'dure'=> 'required|integer'
        ]);
        $user = request()->user();
        $user->timework()->update($request->all());
        return Response::json([
            'status' => true,
            'data' => request()->user()->timework()->get(),
        ]);

    }

}
