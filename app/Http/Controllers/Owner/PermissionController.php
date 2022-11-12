<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;


class PermissionController extends Controller
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
        $permissions = Permission::all();
        $roles = Role::all();
        foreach ($permissions as $permission){
            $RolesAndPermission [] = [
                'Permission_id'  => $permission['id'],
                'Permission_name' => $permission['name'],
                'Assign_to_role' => $permission->roles()->select('id','name')->get()
            ];
        }
        return Response::json([
            'Permission_And_Roles' => $RolesAndPermission,
            'Roles' => $roles,
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
            'name' => 'required|unique:permissions,name',
            'role_id' => 'sometimes|required|array',
        ]);

        $permission = Permission::create(['name' => $request->input('name')]);

        if($request->input('role_id') != null){

            foreach ((object)  $request->input('role_id') as $role){
                $role = Role::find($role);
                $role->attachPermission($permission->name);
            }

        }

        return Response::json([
            'message' => 'permission a été crée avec succes'
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
        $permission = Permission::where('id' ,$id)->select('id','name')->get()->first();
        if($permission){

            return Response::json([
                'permission' => $permission,
                'permissionRoles' => $permission->roles()->select('id','name')->get(),
                'roles' => Role::all(),
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
            'role_id' => 'required|array',
        ]);

        $permission = Permission::find($id);
        $permission->name = $request->input('name');
        $permission->save();
        if($request->input('role_id') != null)
        foreach ((object)  $request->input('role_id') as $role){

            $role = Role::find($role);
            $role->attachPermission($permission->name);

        }
        return Response::json([
            'message' => 'permission a été mise a jour avec succes',
            'permission' => $permission,
            'permissionRoles' => $permission->roles()->select('id','name')->get()
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
        $permission = Permission :: where('id', $id)->first();
        if($permission){

            $permission->delete();

            return Response::json([
                'message' => 'permission a été supprimer !',
            ]);
        }
        return Response::json([
            'message' => 'id invalid',
        ]);
    }
}
