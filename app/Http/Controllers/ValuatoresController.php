<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Valuator;
use App\Models\User;

class ValuatoresController extends Controller
{
  //index es para mostrar datos
  public function index(){
    $valuator = valuator::paginate( 10 );

    $data = array(
      'code' => 200,
      'status' => 'success',
      'valuators' => $valuator
    );
      
    return response()->json($data, $data['code']);
  }
  //store se utiliza para insertar datos
  public function store(Request $request){
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);

      //verificacion de datos que este lleno 
      if (is_array($request->all()) && $checkToken ) {
        //especificacion de tipado y campos requeridos 
        $rules =[
          'rfc' => 'required|max:255|string',          
          'user_id' => 'required|exists:users,id'                    
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
            // Crear el valuator
            $valuator = new valuator();
            $valuator->rfc = $request->rfc;
            $valuator->status = 'active';
            $valuator->user_id = $request->user_id;                
            $valuator->save();  

            $data = array(
              'status' => 'success',
              'code'   => '200',
              'message' => 'el valuator se creo exitosamente'
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

  //update se utiliza para actualizar datos
  public function update(Request $request, $id){

    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);
    if ( is_array($request->all()) && $checkToken ) {

      $rules = [
        'rfc' => 'required|max:255|string',
        'status' => 'in:active,inactive',    
        'user_id' => 'required|exists:users,id'                    
      ];
      
      try {
        // Obtener valuator
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails() ) {
          // error en los datos ingresados
          $data = array(
            'status' => 'error',
            'code'   => '200',
            'errors'  => $validator->errors()->all()
          );
        }else{            
          $valuator = Valuator::find( $id );

          if( is_object($valuator) && !empty($valuator) ){                
              $valuator->rfc = $request->rfc;
              $valuator->status = $request->status;
              $valuator->user_id = $request->user_id;                
              $valuator->save();  

              $data = array(
                  'status' => 'success',
                  'code'   => '200',
                  'message' => 'valuator se ha actualizado correctamente'  
              );
          }else{
              $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'La valuator no existe'
              );
          }  

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
        'message' => 'El usuario no está identificado'
      );
    }

    return response()->json($data, $data['code']);    
  }

  //destroy se utiliza para eliminar datos 
  public function destroy(Request $request, $id){
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);

    if ( is_array($request->all()) && $checkToken ) {
        // Inicio Try catch
        try{
          $valuator = valuator::find( $id );

          if( is_object($valuator) && !is_null($valuator) ){

            try{
              $valuator->delete();

              $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'valuator ha sido eliminada correctamente'
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
              'message' => 'El id del la valuator no existe'
            );
          }
        }catch(Exception $e){
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

  public function get_valuators(){
    $valuators = User::role('valuator')->get();
    if (is_object($valuators) && !is_null($valuators)) {
      $data = array(
        'status' => 'success',
        'code' => '200',
        'valuators' => $valuators
      );
    }
    // return $valuators;
    return response()->json($data, $data['code']);
  }
}
