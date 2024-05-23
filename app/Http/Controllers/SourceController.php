<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Source;

 
class SourceController extends Controller
{
  public function index(){
    $sources = Source::paginate( 10 );
    $data= array(
      'code' => 200,
      'status' => 'success',
      'sources' => $sources
      );
        
    return response()->json($data, $data['code']);    
  }

  public function store(Request $request){
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);
  
    //verificacion de datos que este lleno 
    if(is_array($request->all()) && $checkToken ){
      //especificacion de tipado y campos requeridos 
      $rules = [
          'name' =>'required|max:255|string'
      ];

      try{
        //validacion de tipado y campos requeridos 
        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails()){
          //existio un error en los campos enviados 
          $data = array(
            'status' => 'error',
            'code'   => '200',
            'errors'  => $validator->errors()->all()
          );
        }else{              
          // Crear el Source
          $source = new Source();
          $source->name = $request->name;             
          $source->save();   

          $data = array(
            'status' => 'success',
            'code'   => '200',
            'message' => 'el source se creo exitosamente'
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

    if( is_array($request->all()) && $checkToken ){

      $rules =[
          'name' => 'required|max:255|string'        
      ];

      try {
        // Obtener Branch
        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails() ){
          // error en los datos ingresados
          $data = array(
            'status' => 'error',
            'code'   => '200',
            'errors'  => $validator->errors()->all()
          );
        }else{            
          $source = Source::find( $id );

          if( is_object($source) && !empty($source) ){                
            $source->name = $request->name;
            $source->save();  

            $data = array(
              'status' => 'success',
              'code'   => '200',
              'message' => 'source actualizado correctamente'                
            );
          }else{
              $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'source no existe'
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

    if( is_array($request->all()) && $checkToken ){
      // Inicio Try catch
      try {
        $source = Source::find( $id );

        if( is_object($source) && !is_null($source) ){

          try{
            $source->delete();
            $data = array(
              'status' => 'success',
              'code'   => '200',
              'message' => 'la source ha sido eliminada correctamente'
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
            'message' => 'El id del source no existe'
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

  public function getAll(){
    $sources = Source::all();
    $data= array(
      'code' => 200,
      'status' => 'success',
      'sources' => $sources
      );
        
    return response()->json($data, $data['code']);    
  }
}
