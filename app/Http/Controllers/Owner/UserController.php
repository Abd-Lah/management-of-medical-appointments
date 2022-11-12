<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\WorkTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rules;
use App\Traits\GeneralCode;

class UserController extends Controller
{
    Use GeneralCode;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function __construct()
    {
        //only owner who has the ability to manage users

        $this->middleware('role:owner');

    }


    public function acceuil(){
        $doctors = User::whereRoleIs('doctor')->where('status',1)->count();
        $patients = User::whereRoleIs('patient')->count();
        return Response::json([
            'doctors' => $doctors,
            'patient' => $patients,
            'specialite' =>DB::table('users')->distinct('specialite')->whereNotNull('specialite')->count(),
            'ville' =>DB::table('users')->distinct('ville')->whereNotNull('ville')->count(),
            'bname' =>DB::table('users')->distinct('ville')->whereNotNull('ville')->select('ville')->get(),
            'total' => ($doctors+$patients),
        ]);
    }

    public function demandes()
    {
        //
        $valid = User::whereRoleIs('doctor')
            ->where('status','=',0)
            ->orderBy('nom')
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
                'created_at',
            )
            ->paginate(10);
        return Response::json([
            'Invalide_doctors' => $valid,
        ],200);
    }



    public function index()
    {
        //
        $valid = User::whereRoleIs('doctor')
            ->where('status','=',1)
            ->orderBy('id','desc')
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
            'created_at',
            )
            ->paginate(10);
        return Response::json([
            'data' => $valid,
        ],200);
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
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'ville' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'telephone' => ['required', 'string', 'max:14'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required'

        ]);



        if($request->input('role') == 'doctor'){
            $request->validate([
                'adresse' => ['required', 'string',  'max:255'],
                'telephone_cabinet' => ['required', 'string', 'max:14'],
                'registercomerce' => ['required', 'string', 'max:14'],
                'jours' => 'required|array',
                'debut' => 'required|string',
                'fin' => 'required|string',
                'dure'=> 'required|integer'
            ]);
        }


        $user = User::create([
            'nom' => $request->input('nom'),
            'prenom' => $request->input('prenom'),
            'email' => $request->input('email'),
            'email_verified_at' => now(),
            'ville' => $request->input('ville'),
            'tele' => $request->input('telephone'),
            'tele_cabinet' => $request->input('telephone_cabinet'),
            'cabinet_adresse' => $request->input('adresse'),
            'registercomerce' => $request->input('registercomerce'),
            'password' => Hash::make($request->input('password')),
            'status' => true,
        ]);
        $user->attachRole($request->input('role'));

        if($request->input('role') == 'doctor'){
            $temps = new WorkTime();
            $temps->jours = $request->input('jours');
            $temps->debut = $request->input('debut');
            $temps->fin = $request->input('fin');
            $temps->dure = $request->input('dure');
            $user->timework()->save($temps);
        }
        return Response::json([
            'message' => 'Le compte crée avec success !'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchDoctors(Request $request)
    {
        //
        if($request->input('ville') == null && $request->input('specialite') == null){
            return Response::json([
                'error' => 'select items',
                'status' => 'false'
            ],400);
        }
        if($request->input('specialite') != null && $request->input('ville') == null){
            return $this->search($request->input('specialite'),'specialite',$request->input('status'));
        }else if($request->input('specialite') == null && $request->input('ville') != null){
            return $this->search($request->input('ville'),'ville',$request->input('status'));
        }else {
                return $this->searchByvilleAndSpeciality($request->input('ville'),$request->input('specialite'),$request->input('status'));
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        //
        $this->validate($request, [
            'status' => 'required',
        ]);
        $user = User::find($id);

        $user->status = $request->input('status');

        $user->save();
        return Response::json([
            'status' => 'succes',
        ],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        //
        $user = User::where('id',$id)->first();
        if($user && $user->hasRole('doctor|patient')){
            if($user->photo != null){
                // Value is  URL
                if(File::exists($user->photo)) {
                    File::delete($user->photo);
                }
            }
            $user->delete() ;
            return Response::json([
                'status' => 'succes',
                'message' => 'Compte supprimer avec succes !',
            ],200);
        }
        return Response::json([
            'status' => 'error',
            'message' => 'id n \'existe pas',
        ],400);
    }
}
