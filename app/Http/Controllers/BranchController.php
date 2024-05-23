<?php

namespace App\Http\Controllers;
use App\Models\Branch;

use Illuminate\Http\Request;

class BranchController extends Controller
{
  // Index es para mostrar datos
  public function index(Request $request){
 
      $branch = Branch::paginate( 10 );
      $branch->load('state');
      $data = array (
        'code' => 200,
        'status' => 'success',
        'branchs' => $branch
      );
  
 

    return response()->json($data, $data['code']);
  }

  public function store(Request $request) {
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);

    //verificacion de datos que este lleno 
    if (is_array($request->all()) && $checkToken ){
      //especificacion de tipado y campos requeridos 
      $rules = [
        'name' => 'required|max:255|string',
        'state_id' => 'required|exists:states,id'                    
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
          // Crear el branch
          $branch = new Branch();
          $branch->name = $request->name;
          $branch->state_id = $request->state_id;                
          $branch->save(); 

          $data = array(
            'status' => 'success',
            'code'   => '200',
            'message' => 'el branch se creo exitosamente'
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

  //update se utiliza para actualizar datos
  public function update(Request $request, $id){
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);

    if ( is_array($request->all()) && $checkToken ) {
        $rules = [
            'name' => 'required|max:255|string',
            'state_id' => 'required|exists:states,id'                    
        ];

      try{
          $validator = \Validator::make($request->all(), $rules);

          if ($validator->fails() ) {
            // error en los datos ingresados
            $data = array(
              'status' => 'error',
              'code'   => '200',
              'errors'  => $validator->errors()->all()
            );
          }else{            
            $branch = Branch::find( $id );

            if( is_object($branch) && !empty($branch) ){                
              $branch->name = $request->name;
              $branch->state_id = $request->state_id;
              $branch->save();  

              $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'Branch se ha actualizado correctamente'                
              );
            }else{
              $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'La Branch no existe'
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


  public function destroy(Request $request, $id){
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);

    if ( is_array($request->all()) && $checkToken ){
        // Inicio Try catch
        try{
          $branch = Branch::find( $id );
          if( is_object($branch) && !is_null($branch) ){
            try{
                $branch->delete();
                $data = array(
                  'status' => 'success',
                  'code'   => '200',
                  'message' => 'Branch ha sido eliminada correctamente'
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
                'message' => 'El id del la branch no existe'
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

  public function getBranches( string $word = '' ,Request $request){
 
        $branches = Branch::select('branches.*')
        ->join('states', 'states.id', 'branches.state_id')
        ->whereRaw(
          "
            branches.name LIKE '%$word%' OR 
            states.name LIKE '%$word%'
          "
        )
              ->paginate( 10 );
        $branches->load('state');
        $data = array (
        'code' => 200,
        'status' => 'success',
        'branches' => $branches
        );
 


    return response()->json($data, $data['code']);
  }

  public function getBranchById( int $id,Request $request ){
 
      $branch = Branch::find( $id );
      $branch = is_object( $branch ) && !is_null( $branch ) ? $branch->load(['state']) : null;
      $data = array(
        'code' => 200,
        'status' => 'success',
        'branch' => $branch 
      );
 

     
    return response()->json($data, $data['code']);
  }
}
