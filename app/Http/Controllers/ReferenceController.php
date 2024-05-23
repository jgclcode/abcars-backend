<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reference;


class ReferenceController extends Controller
{
    public function index(){       
      $Reference = Reference::paginate( 10 );
      $data = array(
        'code' => 200,
        'status' => 'success',
        'Reference' => $Reference
      );
  
      return response()->json($data, $data['code']);
    }
  
    public function store(Request $request){
      //verificacion de datos que este lleno 
      if(is_array($request->all()) ) {
        //especificacion de tipado y campos requeridos 
        $rules = [
            'name' => 'required|max:255|string',
            'surname' => 'required|max:255|string',                    
            'phone' => 'required|max:15|string',                    
            'relationship' => 'required|max:255|string',
            'financing_id' => 'required|exists:financings,id'                                
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
            // Crear el Reference
            $Reference = new Reference();
            $Reference->name = $request->name;
            $Reference->surname = $request->surname;                           
            $Reference->phone = $request->phone;
            $Reference->relationship = $request->relationship;
            $Reference->financing_id = $request->financing_id;
            $Reference->save();  
  
            $data = array(
              'status' => 'success',
              'code'   => '200',
              'message' => 'Reference creado exitosamente',
              'reference' => $Reference
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
  
      if( is_array($request->all()) && $checkToken){
  
        $rules =[
          'name' => 'required|max:255|string',
          'surname' => 'required|max:255|string',                    
          'phone' => 'required|max:15|string',                    
          'relationship' => 'required|max:255|string',
          'financing_id' => 'required|exists:financings,id'                        
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
            $Reference = Reference::find( $id );
  
            if( is_object($Reference) && !empty($Reference) ){                
              $Reference->name = $request->name;
              $Reference->surname = $request->surname;                           
              $Reference->phone = $request->phone;
              $Reference->relationship = $request->relationship;
              $Reference->financing_id = $request->financing_id;
              $Reference->save();  
  
                $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'message' => 'Reference se ha actualizado correctamente',
                    'reference' => $Reference           
                );
            }else{
                $data = array(
                  'status' => 'error',
                  'code'   => '200',
                  'message' => 'id de Reference no existe'
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
  
    public function destroy(Request $request,$id){
      // Comprobar si el usuario esta identificado
      $token = $request->header('Authorization');
      $jwtAuth = new \App\Helpers\JwtAuth();
      $checkToken = $jwtAuth->checkToken($token);
  
      if( is_array($request->all()) && $checkToken ){
        // Inicio Try catch
        try{
          $Reference = Reference::find( $id );
  
          if( is_object($Reference) && !is_null($Reference) ){
  
            try{
                $Reference->delete();
  
                $data = array(
                  'status' => 'success',
                  'code'   => '200',
                  'message' => 'Reference ha sido eliminado correctamente'
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
                'message' => 'El id del Reference no existe'
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

}
  
