<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assist;

class AssistController extends Controller
{
  public function index(){       
    $Assist = Assist::paginate( 10 );
    $data = array(
      'code' => 200,
      'status' => 'success',
      'Assist' => $Assist
    );

    return response()->json($data, $data['code']);
  }

  public function store(Request $request){
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);

    if (is_array($request->all()) && $checkToken ){
        //especificacion de tipado y campos requeridos 
        $rules = [
            'namePayment' => 'required|max:255|string',
            'status' => 'required|max:255|string',                    
            'reference' => 'required|max:255|string',
            'amountDate' => 'required|max:255|string',                       
            'client_id' => 'required|exists:clients,id',    
            'package_id' => 'required|exists:packages,id'
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
            // Crear el Assist
            $assist = new Assist();
            $assist->namePayment = $request->namePayment;
            $assist->status = $request->status;                           
            $assist->reference = $request->reference;
            $assist->amountDate = $request->amountDate;
            $assist->client_id = $request->client_id;                           
            $assist->package_id = $request->package_id;
            $assist->save();    
                        
              $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'Assist creado exitosamente'
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

  public function update(Request $request, $id){
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);

    if ( is_array($request->all()) && $checkToken){

      $rules = [
        'namePayment' => 'required|max:255|string',
        'status' => 'required|max:255|string',
        'reference' => 'required|max:255|string',
        'amountDate' => 'required|max:255|string',
        'client_id' => 'required|exists:clients,id',
        'package_id' => 'required|exists:packages,id'
      ];

      try{
        // Obtener package
        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails()){
          // error en los datos ingresados
          $data = array(
            'status' => 'error',
            'code'   => '200',
            'errors'  => $validator->errors()->all()
          );
        }else{            
          $assist = Assist::find( $id );

          if(is_object($assist) && !empty($assist) ){                
            $assist->namePayment = $request->namePayment;
            $assist->status = $request->status;                           
            $assist->reference = $request->reference;
            $assist->amountDate = $request->amountDate;
            $assist->client_id = $request->client_id;                           
            $assist->package_id = $request->package_id;
            $assist->save();  

            $data = array(
              'status' => 'success',
              'code'   => '200',
              'message' => 'Assist se ha actualizado correctamente'               
            );
          }else{
            $data = array(
              'status' => 'error',
              'code'   => '200',
              'message' => 'id de Assist no existe'
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

    if( is_array($request->all()) && $checkToken){

      try {
          $assists = Assist::find( $id );
          if( is_object($assists) && !is_null($assists) ){

            try {
              $assists->delete();
              $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'Assist ha sido eliminado correctamente'
              );
            } catch (\Illuminate\Database\QueryException $e){
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
                'message' => 'El id del Assist no existe'
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
