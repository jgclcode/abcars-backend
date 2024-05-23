<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RoleHasPermissions;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $rolehaspermissions = RoleHasPermissions::paginate(10);
        $data = array(
            'status' => 'success',
            'code' => '200',
            'roleHasPermissions' => $rolehaspermissions
        );

        return response()->json($data, $data['code']);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if (is_array($request->all()) && $checkToken) {
            $rules = [
                'name' => 'required|max:255|string',
                'role_id'   => 'required|Integer|exists:roles,id', 
            ];
            // Inicio try - catch
            try {
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    // Esta condición se ejecuta si la validación de uno o más campos es incorrecta
                    $data = array(
                        'status' => 'error',
                        'code'   => '404',
                        'errors'  => $validator->errors()->all()
                    );
                }else{
                    // dd($request);
                    $permission = Permission::where('name', $request->name)->first();
                    if (is_object($permission) && !empty( $permission)) {
                        // Reset cached roles and permissions
                        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();    
                        
                        $role = Role::find($request->role_id);
                        $role->givePermissionTo($request->name);
    
                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'El permiso se ha asignado al rol correctamente',
                            'role' => $role
                        );
                        
                    }else{
                        $data = array(
                            'status' => 'error',
                            'code'   => '404',
                            'message' => 'El permiso '. $request->name . ' no existe.'
                        );
                    }
                }
            } catch (Exception $e) {
                $data = array(
                    'status' => 'error',
                    'code'   => '404',
                    'message' => 'Los datos enviados no son correctos, ' . $e
                );
            }

            // Fin try - catch

        } else {
            $data = array(
                'status' => 'error',
                'code'   => '404',
                'message' => 'El usuario no está identificado'
            );
        }

        return response()->json($data, $data['code']);
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
