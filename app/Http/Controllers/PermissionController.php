<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Permission;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $permissions = Permission::paginate( 5 );
        $data = array(
        'status' => 'success',
        'code'   => '200',
        'permissions' => $permissions
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
                'guard_name' => 'required|max:255|string'
            ];
            // Inicio try - catch
            try {
                // Obtener usuario
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails() ) {
                    // Esta condición se ejecuta si la validación de uno o más campos es incorrecta
                    $data = array(
                        'status' => 'error',
                        'code'   => '404',
                        'errors'  => $validator->errors()->all()
                    );
                }else{
                    $permission = Permission::where('name', $request->name )->first();
                    if (!is_object($permission) && empty($permission)) {
                        $permission = new Permission();
                        $permission->name = $request->name;
                        $permission->guard_name = 'web';
                        $permission->save();

                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'El permiso ha sido creado correctamente',
                            'permission' => $permission
                        );
                    }else{
                        $data = array(
                            'status' => 'error',
                            'code'   => '404',
                            'message' => 'El permiso ya existe'
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
        }else {
            $data = array(
                'status' => 'error',
                'code'   => '404',
                'message' => 'No está identificado el permiso'
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
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if (is_array($request->all()) && $checkToken) {
            $rules = [
                'name'        => 'required|max:255|string',
                'guard_name'  => 'required|max:255|string'
            ];
            // Inicio Try catch
            try {
                // Obtener usuario
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails() ) {
                    // Esta condición se ejecuta si la validación de uno o más campos es incorrecta
                    $data = array(
                        'status' => 'error',
                        'code'   => '404',
                        'errors'  => $validator->errors()->all()
                    );
                }else {
                    $permission = Permission::find( $id );
                    if( is_object( $permission ) && !empty( $permission ) ){
                        $permission->name = $request->name;
                        $permission->guard_name = $request->guard_name;
                        $permission->save();
        
                        $data = array(
                          'status' => 'success',
                          'code'   => '200',
                          'message' => 'El permiso ha sido actualizado correctamente',
                          'permission' => $permission
                        );
                    }else{
                        $data = array(
                          'status' => 'error',
                          'code'   => '404',
                          'message' => 'El permiso no existe'
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
            // Fin Try catch
        }else {
            $data = array(
                'status' => 'error',
                'code'   => '404',
                'message' => 'No está identificado el permiso'
            );
        }
        
        return response()->json($data, $data['code']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        //
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if ($checkToken) {
            // Inicio Try catch
            try{
                $permission = Permission::find( $id );
                if( is_object( $permission ) && !empty( $permission ) ){
                    // if( $permission->roles->count() == 0 ){
                      //Eliminar permiso
                      $permission->delete();
      
                      $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'El permiso ha sido eliminado correctamente',
                        'permission' => $permission
                      );
                    /* }else{
                      $data = array(
                        'status' => 'error',
                        'code'   => '400',
                        'message' => 'El permiso esta enlazado a uno o más roles de usuario'
                      );
                    } */
                }else{
                    $data = array(
                      'status' => 'error',
                      'code'   => '404',
                      'message' => 'El permiso no existe'
                    );
                }
            }catch (Exception $e){
                $data = array(
                    'status' => 'error',
                    'code'   => '404',
                    'message' => 'Los datos enviados no son correctos, ' . $e
                );
            }
            // Fin Try catch
        } else {
            $data = array(
                'status' => 'error',
                'code'   => '404',
                'message' => 'No está identificado el permiso'
            );
        }
        
        return response()->json($data, $data['code']);

    }
}
