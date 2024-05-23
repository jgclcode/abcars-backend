<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;


class BillController extends Controller
{
    public function index(){
        $bill = Bill::paginate( 10 );
        $data = array (
            'code' => 200,
            'status' => 'success',
            'bills' => $bill
        );

        return response()->json($data, $data['code']);
    }

    public function store(Request $request){
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        //verificacion de datos que este lleno 
        if (is_array($request->all()) && $checkToken ){
            //especificacion de tipado y campos requeridos 
            $rules = [
                'name' => 'max:255|string',
                'type' => 'max:255|string', 
                'lastHolding' => 'max:255|string',
                'lastVerification' => 'max:255|string'
        
            ];

            try{
                //validacion de tipado y campos requeridos 
                $validator = \Validator::make($request->all(), $rules);

                if($validator->fails()) {
                    //existio un error en los campos enviados 
                    $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'errors'  => $validator->errors()->all()
                    );
                }else{              
                    // Crear el bill
                    $bill = new Bill();
                    $bill->name = $request->name;
                    $bill->type = $request->type;                
                    $bill->lastHolding = $request->lastHolding;                
                    $bill->lastVerification = $request->lastVerification;                
                    $bill->save(); 

                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'el bill se creo exitosamente'
                    );                
                }

            }catch (Exception $e){
                $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'message' => 'Los datos enviados no son correctos, ' . $e
                );
            }   
            
        }else{
            $data = array(
                'status' => 'error',
                'code'   => '200',
                'message'  => "El usuario no esta identificado"
            );
        }
        return response()->json($data, $data['code']);
    }

    public function update(Request $request, $id){
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if ( is_array($request->all()) && $checkToken ){
            $rules = [
                'name' => 'max:255|string',
                'type' => 'max:255|string', 
                'lastHolding' => 'max:255|string',
                'lastVerification' => 'max:255|string'                   
            ];

            try {
                // Obtener bill
                $validator = \Validator::make($request->all(), $rules);

                if ($validator->fails()){
                    // error en los datos ingresados
                    $data = array(
                     'status' => 'error',
                     'code'   => '200',
                     'errors'  => $validator->errors()->all()
                    );
                }else{            
                    $bill = Bill::find( $id );

                    if( is_object($bill) && !empty($bill)){                
                        $bill->name = $request->name;
                        $bill->type = $request->type;                
                        $bill->lastHolding = $request->lastHolding;                
                        $bill->lastVerification = $request->lastVerification;                
                        $bill->save(); 

                        $data = array(
                         'status' => 'success',
                         'code'   => '200',
                         'message' => 'bill se ha actualizado correctamente'                
                        );
                    }else{
                        $data = array(
                         'status' => 'error',
                         'code'   => '200',
                         'message' => 'La bill no existe'
                        );
                    }            
                }
            }catch (Exception $e){
                $data = array(
                 'status' => 'error',
                 'code'   => '200',
                 'message' => 'Los datos enviados no son correctos, ' . $e
                );
            }       

        }else{
            $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'El usuario no está identificado'
            );
        }
        
        return response()->json($data, $data['code']);    
    }

    public function destroy(Request $request, $id){
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if( is_array($request->all()) && $checkToken ){
            // Inicio Try catch
            try {
                $bill = Bill::find( $id );

                if( is_object($bill) && !is_null($bill) ){
                    try {
                        $bill->delete();

                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'bill ha sido eliminada correctamente'
                        );

                    }catch (\Illuminate\Database\QueryException $e){
                     //throw $th;
                        $data = array(
                            'status' => 'error',
                            'code'   => '400',
                            'message' => $e->getMessage()
                        );
                    }

                }else{
                    $data = array(
                        'status' => 'error',
                        'code'   => '404',
                        'message' => 'El id del la bill no existe'
                    );
                }

            }catch (Exception $e){
                $data = array(
                    'status' => 'error',
                    'code'   => '404',
                    'message' => 'Los datos enviados no son correctos, ' . $e
                );
            }

        }else{
            $data = array(
                'status' => 'error',
                'code'   => '404',
                'message' => 'El usuario no está identificado'
            );
        }

        return response()->json($data, $data['code']);
    }
}