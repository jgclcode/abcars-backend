<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service_response;

class Service_responseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $Service_responses = Service_response::paginate( 10 );
        $data = array(
            'code' => 200,
            'status' => 'success',
            'Service_responses' => $Service_responses
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
    public function store(Request $request){
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if (is_array($request->all()) ) {

            $rules =[
                'status' => 'required|in:done,denied,na',
                'quote_id' => 'required|exists:quotes,id',                     
                'service_feature_id' => 'required|exists:service_features,id'                     
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
                // Crear el Service_responses
                $Service_responses = new Service_response();
                $Service_responses->status = $request->status;
                $Service_responses->quote_id = $request->quote_id;
                $Service_responses->service_feature_id = $request->service_feature_id;
                $Service_responses->save();  

                $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'message' => 'Service_responses creado exitosamente'
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
    public function update(Request $request, $id){
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if ( is_array ($request->all()) && $checkToken ) {
            $rules = [
                'status' => 'required|in:done,denied,na',
                'quote_id' => 'required|exists:quotes,id',                     
                'service_feature_id' => 'required|exists:service_features,id'     
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
                    $Service_responses = Service_response::find( $id );

                    if( is_object($Service_responses) && !empty($Service_responses) ){                
                        $Service_responses->status = $request->status;
                        $Service_responses->quote_id = $request->quote_id;
                        $Service_responses->service_feature_id = $request->service_feature_id;
                        $Service_responses->save();  

                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'Service_responses actualizado correctamente'
                        );

                    }else{
                        $data = array(
                            'status' => 'error',
                            'code'   => '200',
                            'message' => 'id de Service_responses no existe'
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id){
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if ( is_array ($request->all()) && $checkToken ){
            // Inicio Try catch
            try{
                $Service_responses = Service_response::find( $id );

                if( is_object($Service_responses) && !is_null($Service_responses)){

                    try{
                        $Service_responses->delete();
                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'Service_responses ha sido eliminado correctamente'
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
                        'message' => 'El id del Service_response no existe'
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

    public function getfeaturesbyquote($id_quote){
 
        $features = Service_response::where('quote_id', $id_quote)
        ->join('service_features', 'service_features.id', 'service_responses.service_feature_id')
        ->get();
        $data = array(
            'code' => 200,
            'status' => 'success',
            'Service_responses' => $features
        );
        return response()->json($data, $data['code']);

    }
}
