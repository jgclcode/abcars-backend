<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $total = 10 )
    {
        //
        $roles = Role::paginate( $total );
        $data = array(
          'status' => 'success',
          'code'   => '200',
          'roles' => $roles
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
                'name'       => 'required|max:255|string',
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
                }else {
                    $role = Role::where('name', $request->name )->first();
                    if( !is_object($role) && empty($role) ){
                        $role = new Role();
                        $role->name = $request->name;
                        $role->guard_name = 'web';
                        $role->save();
        
                        $data = array(
                          'status' => 'success',
                          'code'   => '200',
                          'message' => 'El rol ha sido creado correctamente',
                          'role' => $role
                        );
                    }else {
                        $data = array(
                          'status' => 'error',
                          'code'   => '404',
                          'message' => 'El rol de usuario ya existe'
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
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if (is_array($request->all()) && $checkToken) {
            $rules = [
                'name' => 'required|max:255|string'
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
                $role = Role::find( $id );
                if( is_object($role) && !empty($role) ){
                    $role->name = $request->name;                    
                    $role->save();

                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'El rol ha sido actualizado correctamente',
                        'rol' => $role
                    );
                }else{
                    $data = array(
                        'status' => 'error',
                        'code'   => '404',
                        'message' => 'El rol de usuario no existe'
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if ($checkToken) {
            // Inicio try -  catch
            try {
                $role = Role::find( $id );
                if( is_object($role) && !empty($role) ){
                    // Borrar rol
                    // if ( $role->permissions->count() == 0 && $role->users->count() == 0 ){
                      $role->delete();
        
                      $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'El rol ha sido eliminado correctamente'
                      );
                    /* }else{
                      $data = array(
                        'status' => 'error',
                        'code'   => '400',
                        'message' => 'Algunos usuarios y permisos dependen de este rol'
                      );
                    } */
                }else{
                    $data = array(
                      'status' => 'error',
                      'code'   => '404',
                      'message' => 'El rol de usuario no existe'
                    );
                }
            } catch (Exception $e) {
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
                'message' => 'El usuario no está identificado'
            );
        }
        
        return response()->json($data, $data['code']);

    }

    public function getRolByNameAndUserId( String $name, int $user_id ){
        $rol = Role::where('name', trim($name))->first();
        $user = User::find($user_id);
        
        $data = array(
            "code" => 200,
            "status" => "success",
            "rol" => ((is_object($rol) && is_object($user)) && $user->roles->pluck('id')[0] == $rol->id ) ? $rol : null
        );
        return response()->json($data, $data['code']);
    }

      // get role by id 
  public function GetRoleById($id_role ){
    $role = Role::find( $id_role );
    $data = array(
      'code' => 200,
      'status' => 'success',
      'role' => $role
    );
    return response()->json($data, $data['code']);
  }
}
