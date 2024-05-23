<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service_feature;


class Service_featureController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        $Service_feature = Service_feature::paginate( 10 );
        $data = array (
            'code' => 200,
            'status' => 'success',
            'Service_feature' => $Service_feature
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
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        //verificacion de datos que este lleno 
        if (is_array($request->all())  ){
            //especificacion de tipado y campos requeridos 
            $rules = [
                'name' => 'max:255|string|required',
                'service_id' => 'required|exists:services,id'
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
                    // Crear el Service_feature
                    $Service_feature = new Service_feature();
                    $Service_feature->name = $request->name;
                     $Service_feature->service_id = $request->service_id;                
                    $Service_feature->save(); 

                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'el Service_feature se creo exitosamente'
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
    public function update(Request $request, $id) {
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if ( is_array($request->all())  && $checkToken ) {

            $rules = [
                'name' => 'max:255|string|required',
                 'service_id' => 'required|exists:services,id'                         
            ];

            try {
                // Obtener Service_feature
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails() ) {
                    // error en los datos ingresados
                    $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'errors'  => $validator->errors()->all()
                    );
                }else{            
                    $Service_feature = Service_feature::find( $id );

                    if( is_object($Service_feature) && !empty($Service_feature)){                
                        $Service_feature->name = $request->name;
                         $Service_feature->service_id = $request->service_id;                
                        $Service_feature->save(); 
                                                      
                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'Service_feature incident actualizado correctamente'               
                        );
                    }else{
                        $data = array(
                            'status' => 'error',
                            'code'   => '200',
                            'message' => 'El id del Service_feature  no existe'
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

        }else{
            $data = array(
                'status' => 'error',
                'code'   => '200',
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
    public function destroy(Request $request, $id){
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if ( is_array($request->all())  && $checkToken ) {
            // Inicio Try catch
            try {
                $Service_feature = Service_feature::find( $id );

                if( is_object($Service_feature) && !is_null($Service_feature)){

                    try{
                        $Service_feature->delete();

                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'Service_feature ha sido eliminado correctamente'
                        );

                    }catch(\Illuminate\Database\QueryException $e){
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
                        'message' => 'El id del Service_feature  no existe'
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

   public function getServiceFeatureById($service_id ){
        $features = Service_feature::where('service_id', $service_id )->get();
        $data = array(
            'status' => 'success',
            'code'   => '200',
            'features' => $features
        );  
        return response()->json($data, $data['code']);   
    }
}
