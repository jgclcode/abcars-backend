<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;

class LogsController extends Controller
{
    public function index(){       
        $logs = log::paginate( 10 );
        $data = array(
            'code' => 200,
            'status' => 'success',
            'logs' => $logs
        );

        return response()->json($data, $data['code']);    
    }

    public function store(Request $request){
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if (is_array($request->all()) ) {

            $rules =[
                'process' => 'required|string',                      
                'error_description' => 'required|string',    
                'url' => 'required|string',
                'page' => 'required|numeric'
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
                // Crear el logs
                $logs = new log();
                $logs->process = $request->process;
                $logs->error_description = $request->error_description;
                $logs->url = $request->url;
                $logs->page = $request->page;
                $logs->save();  

                $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'message' => 'logs creado exitosamente'
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

        if ( is_array ($request->all())) {
            $rules = [
                'process' => 'required|string',                      
                'error_description' => 'required|string',    
                'url' => 'required|string',
                'page' => 'required|numeric'
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
                    $logs = log::find( $id );

                    if( is_object($logs) && !empty($logs) ){                
                        $logs->process = $request->process;
                        $logs->error_description = $request->error_description;
                        $logs->url = $request->url;
                        $logs->page = $request->page;
                        $logs->save();  
                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'logs actualizado correctamente'
                        );

                    }else{
                        $data = array(
                            'status' => 'error',
                            'code'   => '200',
                            'message' => 'id de logs no existe'
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
                $logs = log::find( $id );

                if( is_object($logs) && !is_null($logs)){

                    try{
                        $logs->delete();
                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'logs ha sido eliminado correctamente'
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

    public function getLastProcess( String $process_name ){
        $log = Log::where("process", $process_name)->orderBy('id', 'desc')->first();
        $data = array(
            "code" => 200,
            "status" => "success",
            "log" => $log
        );
        return response()->json($data, $data["code"]);
    }
}

