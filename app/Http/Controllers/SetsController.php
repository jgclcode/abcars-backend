<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Set;

class SetsController extends Controller
{
    public function index(){       
        $sets = Set::paginate( 10 );
        $data = array(
            'code' => 200,
            'status' => 'success',
            'sets' => $sets
        );

        return response()->json($data, $data['code']);    
    }

    public function store(Request $request){
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if (is_array($request->all()) && $checkToken ) {

            $rules =[
                'vehicle_id' => 'required|exists:vehicles,id',                      
                'bill_id' => 'required|exists:bills,id',    
                'purchase_id' => 'required|exists:purchases,id',
                'expense_id' => 'required|exists:expenses,id'
            ];

            try{
                //validacion de tipado y campos requeridos 
                $validator = \Validator::make($request->all(), $rules);

                if ($validator->fails()){
                //existio un error en los campos enviados 
                $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'errors'  => $validator->errors()->all() 
                );
                }else{              
                // Crear el sets
                $sets = new Set();
                $sets->vehicle_id = $request->vehicle_id;
                $sets->bill_id = $request->bill_id;
                $sets->purchase_id = $request->purchase_id;
                $sets->expense_id = $request->expense_id;
                $sets->save();  

                $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'message' => 'sets creado exitosamente'
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

        if ( is_array ($request->all()) && $checkToken ) {
            $rules = [
                'vehicle_id' => 'required|exists:vehicles,id',                      
                'bill_id' => 'required|exists:bills,id',    
                'purchase_id' => 'required|exists:purchases,id',
                'expense_id' => 'required|exists:expenses,id'
            ];

            try{
                // Obtener package
                $validator = \Validator::make($request->all(), $rules);
                
                if ($validator->fails() ) {
                    // error en los datos ingresados
                    $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'errors'  => $validator->errors()->all() 
                    );
                }else{            
                    $sets = Set::find( $id );

                    if( is_object($sets) && !empty($sets) ){                
                        $sets->vehicle_id = $request->vehicle_id;
                        $sets->bill_id = $request->bill_id;
                        $sets->purchase_id = $request->purchase_id;
                        $sets->expense_id = $request->expense_id;
                        $sets->save();   

                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'sets actualizado correctamente'
                        );

                    }else{
                        $data = array(
                            'status' => 'error',
                            'code'   => '200',
                            'message' => 'id de sets no existe'
                        );
                    }  

                }
            }catch (Exception $e) {
                $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'message' => 'Los datos enviados no son correctos, ' . $e
                );
            }
            // Fin Try catch
        }else{
          $data = array(
            'status' => 'error',
            'code'   => '200',
            'message' => 'El usuario no está identificado');
        }
  
        return response()->json($data, $data['code']); 
    }

    public function destroy(Request $request, $id){
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if ( is_array ($request->all()) && $checkToken ){
            // Inicio Try catch
            try{
                $sets = Set::find( $id );

                if( is_object($sets) && !is_null($sets)){

                    try{
                        $sets->delete();
                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'sets ha sido eliminado correctamente'
                        );
                    }catch(\Illuminate\Database\QueryException $e){
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
                        'message' => 'El id del set no existe'
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
