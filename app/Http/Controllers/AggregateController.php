<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Aggregate;

class AggregateController extends Controller
{
  public function index()
  {       
    $aggregate = Aggregate::paginate( 10 );
      $data = array(
        'code' => 200,
        'status' => 'success',
        'aggregate' => $aggregate
      );
     return response()->json($data, $data['code']);
  }

  public function store(Request $request)
  {
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
            'vehicle_id' => 'required|exists:vehicles,id'                    
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
                      // Crear el Aggregate
                      $aggregate = new Aggregate();
                      $aggregate->name = $request->name;
                      $aggregate->description = $request->description;                           
                      $aggregate->vehicle_id = $request->vehicle_id;
                      $aggregate->save();  

                      $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'aggregate creado exitosamente'
                        );                
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
            'message'  => "El usuario no esta identificado"
          );
      }
      
      return response()->json($data, $data['code']);
  }

  public function update(Request $request, $id)
  {
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);
    if ( is_array($request->all()) && $checkToken ) {
          $rules =[
            'name' => 'required|max:255|string',
            'description' => 'required|max:255|string',                    
            'vehicle_id' => 'required|exists:vehicles,id'                         
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
                    $aggregate = Aggregate::find( $id );
                    if( is_object($aggregate) && !empty($aggregate) ) 
                    {                
                      $aggregate->name = $request->name;
                      $aggregate->description = $request->description;                           
                      $aggregate->vehicle_id = $request->vehicle_id;
                      $aggregate->save(); 

                      $data = array( 
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'Aggregate se ha actualizado correctamente'               
                      );
                    }else{
                      $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'message' => 'id de Aggregate no existe'
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

  public function destroy(Request $request,$id)
  {
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);

    if ( is_array($request->all()) && $checkToken ) {
        // Inicio Try catch
        try {
        $aggregate = Aggregate::find( $id );
        if( is_object($aggregate) && !is_null($aggregate) ){
            try {
                $aggregate->delete();
                
                $data = array(
                  'status' => 'success',
                  'code'   => '200',
                  'message' => 'aggregate ha sido eliminado correctamente'
                );
            } catch (\Illuminate\Database\QueryException $e) {
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
            'message' => 'El id del aggregate no existe'
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