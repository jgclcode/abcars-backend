<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Vehiclebody;

class VehiclebodyController extends Controller
{

  public function index(Request $request){
 
      $vehiclebody = Vehiclebody::paginate( 10 );
      $data = array(
        'code' => 200,
        'status' => 'success',
        'vehiclebody' => $vehiclebody
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
        $rules = [
            'name' => 'required|max:255|string',
            'description' => 'max:255|string'                    
        ];
      
        try {
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
              // Crear el vehiclebody
              $vehiclebody = new Vehiclebody();
              $vehiclebody->name = $request->name;
              $vehiclebody->description= is_null($request->description) ? "" : $request->description;              
              $vehiclebody->save();     

              $data = array(
                  'status' => 'success',
                  'code'   => '200',
                  'message' => 'vehiclebody creado exitosamente'
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
    if( is_array($request->all()) && $checkToken ) {

      $rules = [
          'name' => 'required|max:255|string',
          'description' => 'required|max:255|string'                      
      ];
      try {
          // Obtener Vehiclebody
          $validator = \Validator::make($request->all(), $rules);
          if($validator->fails() ) {
              // error en los datos ingresados
              $data = array(
                'status' => 'error',
                'code'   => '200',
                'errors'  => $validator->errors()->all()
              );
          }else{            
              $vehiclebody = Vehiclebody::find( $id );

              if( is_object($vehiclebody) && !empty($vehiclebody) ){                
                  $vehiclebody->name = $request->name;
                  $vehiclebody->description = $request->description;
                  $vehiclebody->save();    

                  $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'message' => 'vehiclebody actualizado correctamente'                
                  );
              }else{
                  $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'message' => 'El vehiclebody no existe'
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

    if ( is_array($request->all()) && $checkToken ) {
        // Inicio Try catch
        try {
          $vehiclebody = Vehiclebody::find( $id );

          if( is_object($vehiclebody) && !is_null($vehiclebody) ){

              try {
                  $vehiclebody->delete();

                  $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'message' => 'vehiclebody ha sido eliminado correctamente'
                  );

              } catch (\Illuminate\Database\QueryException $e) {
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
                'message' => 'El id del vehiclebody no existe'
              );
          }
        }catch (Exception $e) {
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

  public function vehiclebodyByName( String $name ,Request $request){
 
      $name = str_replace('&', ' ', $name);    
      $vehiclebody = Vehiclebody::where('name', strtolower(trim($name)) )->first();    
  
      $data = array(
        "status"      => "success",
        "code"        => 200,
        "vehiclebody" => $vehiclebody      
      );
 


    return response()->json($data, $data['code']);
  }

  public function vehiclebodyByID(int $id,Request $request) {
 
      $vehiclebody = Vehiclebody::find($id);
      $data = array(
        'code' => 200,
        'status' => 'success',
        'vehiclebody' => $vehiclebody
      );
    


    return response()->json($data, $data['code']);
  }
}
