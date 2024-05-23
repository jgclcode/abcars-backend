<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Service;

class ServiceController extends Controller
{

  public function index(){
    $service = Service::paginate( 10 );
    $data = array(
      'code' => 200,
      'status' => 'success',
      'service' => $service
    );

    return response()->json($data, $data['code']);
  }


  public function store(Request $request){

    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);

    //verificacion de datos que este lleno 
    if (is_array($request->all()) && $checkToken ) {
        //especificacion de tipado y campos requeridos 
      $rules =[
        'name' => 'required|max:255|string',
        'description' => 'required|max:255|string',                    
        'amount' => 'required|numeric',
        'points' => 'required|integer ',
        'interchange' => 'required|integer'            
      ];
  
      try{
        //validacion de tipado y campos requeridos 
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
        //existio un error en los campos enviados 
          $data = array(
            'status' => 'error',
            'code'   => '200',
            'errors'  => $validator->errors()->all()
          );
        }else{              
          // Crear el Service
          $service = new Service();
          $service->name = $request->name;
          $service->description = $request->description;  
          $service->amount = $request->amount;                
          $service->points = $request->points;                
          $service->interchange = $request->interchange;                
          $service->save(); 

          $data = array(
            'status' => 'success',
            'code'   => '200',
            'message' => 'Service creado exitosamente'
          );                
        }

      }catch(Exception $e){
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


  public function update(Request $request,$id){
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);
    if( is_array($request->all()) && $checkToken ){

      $rules =[
          'name' => 'required|max:255|string',
          'description' => 'required|max:255|string',                    
          'amount' => 'required|numeric',
          'points' => 'required|integer ',
          'interchange' => 'required|integer'                        
      ];

      try {
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
          $service = Service::find( $id );

          if( is_object($service) && !empty($service) ){                
              $service->name = $request->name;
              $service->description = $request->description;  
              $service->amount = $request->amount;                
              $service->points = $request->points;                
              $service->interchange = $request->interchange;                
              $service->save();   

              $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'service  actualizado correctamente'               
              );
          }else{
              $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'id de service no existe'
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



  public function destroy(Request $request,$id){

    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);

    if ( is_array($request->all()) && $checkToken ) {
        // Inicio Try catch
        try {
          $service = Service::find( $id );
          if( is_object($service) && !is_null($service) ){

              try{
                  $service->delete();

                  $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'message' => 'service eliminado correctamente'
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
              'message' => 'El id del service no existe'
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

  // Services Customer Clear
  public function servicesCustomerClear() {
    $services = $service = Service::all();

    $data = array(
      'code' => 200,
      'status' => 'success',
      'services' => $services
    );

    return response()->json($data, $data['code']);
  }
}
