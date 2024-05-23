<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Package;

class PackageController extends Controller
{

  public function index(){
    $package = Package::paginate( 10 );
    $data = array(
      'code' => 200,
      'status' => 'success',
      'package' => $package
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
              // Crear el package
              $package = new Package();
              $package->name = $request->name;
              $package->description = $request->description;  
              $package->amount = $request->amount;                
              $package->points = $request->points;                
              $package->interchange = $request->interchange;                
              $package->save();                

              $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'package creado exitosamente'
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
      $rules = [
          'name' => 'required|max:255|string',
          'description' => 'required|max:255|string',                    
          'amount' => 'required|numeric',
          'points' => 'required|integer ',
          'interchange' => 'required|integer'                        
      ];

      try {
        // Obtener package
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()){
          // error en los datos ingresados
          $data = array(
            'status' => 'error',
            'code'   => '200',
            'errors'  => $validator->errors()->all()
          );
        }else{            
          $package = Package::find( $id );

          if(is_object($package) && !empty($package)){                
            $package->name = $request->name;
            $package->description = $request->description;  
            $package->amount = $request->amount;                
            $package->points = $request->points;                
            $package->interchange = $request->interchange;                
            $package->save();   

            $data = array(
              'status' => 'success',
              'code'   => '200',
              'message' => 'package se ha actualizado correctamente'               
            );
          }else{
            $data = array(
              'status' => 'error',
              'code'   => '200',
              'message' => 'id de package no existe'
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

    if(is_array($request->all()) && $checkToken){
      // Inicio Try catch
      try {
        $package = Package::find( $id );
        if( is_object($package) && !is_null($package) ){

          try{
            $package->delete();

            $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'package ha sido eliminado correctamente'
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
            'message' => 'El id del package no existe'
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
