<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:owner');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        //
        $RolesAndPermission = array();
        $roles = Role::all();
        foreach ($roles as $role){
            $RolesAndPermission [] = [
                'Role_id'  => $role['id'],
                'Role_name' => $role['name'],
                'Permission_role' => $role->permissions()->select('id','name')->get(),
                'Number_users' => User::whereRoleIs($role['name'])->count(),
            ];
        }
        return Response::json([
            'data' => $RolesAndPermission,
            'permission' => Permission::all('id','name'),
        ]);
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
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required|array',
        ]);

        $role = Role::create(['name' => $request->input('name')]);

        $role->syncPermissions($request->input('permission'));


        return Response::json([
            'data' => $role,
            'message' => 'role a été crée avec succes'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        //
        $role = Role::where('id' ,$id)->first();
        if($role){
            $rolePermissions = $role->permissions()->select('id','name')->get();
            return Response::json([
                'role' => $role,
                'permissionsRole' => $rolePermissions,
                'permissions' => Permission::all('id','name'),
            ]);
            //$rolePermissions;

        }

        return Response::json('id not found !');
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
            'name' => 'required',
            'permission' => 'required|array',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');

        $role->save();
        $role->syncPermissions($request->input('permission'));
        return Response::json([
            'message' => 'role a été mise a jour avec succes',
            'data' => ([
                'role' => $role->name,
                'permissionsRole' => $role->permissions()->select('id','name')->get()
            ]),
        ]);
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
        $role = Role :: where('id', $id)->first();
        if($role){

            $user = User::whereRoleIs($role->name);
            if($user->count() > 0){
                $user -> delete();
            }
            $role->delete();

            return Response::json([
                'message' => 'role  a été supprimer avec leurs utilisateurs !',
                'users' => $user,
            ]);
        }
        return Response::json([
            'message' => 'id invalid',

        ]);
    }
}
