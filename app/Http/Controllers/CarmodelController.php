<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Carmodel;
use App\Models\Brand;

class CarmodelController extends Controller
{
  public function index(Request $request){
 
      $carmodel = Carmodel::paginate( 10 );
      $carmodel->load(['brand']);
      $data = array(
        'code' => 200,
        'status' => 'success',
        'carmodel' => $carmodel
      );
 


    return response()->json($data, $data['code']);
  }

  public function store(Request $request){
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);

    //verificacion de datos que este lleno 
    if(is_array($request->all()) && $checkToken){
      //especificacion de tipado y campos requeridos 
      $rules = [
        'name' => 'required|max:255|string',
        'description' => 'max:255|string',                    
        'brand_id' => 'required|exists:brands,id'                    
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
            // Crear el carmodel
            $carmodel = new Carmodel();
            $carmodel->name = $request->name;
            $carmodel->description =is_null($request->description) ? "" : $request->description;
            $carmodel->brand_id = $request->brand_id;                              
            $carmodel->save();  

            $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'Carmodel creado exitosamente'
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

  public function update(Request $request, $id) {
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);

    if (is_array($request->all()) && $checkToken){
      $rules = [
        'name' => 'required|max:255|string',
        'description' => 'required|max:255|string',                    
        'brand_id' => 'required|exists:brands,id'                           
      ];

      try{
        // Obtener Carmodel
        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails()){
          // error en los datos ingresados
          $data = array(
            'status' => 'error',
            'code'   => '200',
            'errors'  => $validator->errors()->all()
          );
        }else{            
          $carmodel = Carmodel::find( $id );

          if(is_object($carmodel) && !empty($carmodel)){                
            $carmodel->name = $request->name;
            $carmodel->description = $request->description;  
            $carmodel->brand_id = $request->brand_id;               
            $carmodel->save();      

            $data = array(
              'status' => 'success',
              'code'   => '200',
              'message' => 'Carmodel se ha actualizado correctamente'               
            );
          }else{
            $data = array(
              'status' => 'error',
              'code'   => '200',
              'message' => 'ID de carmodel no existe'
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
  
    if ( is_array($request->all()) && $checkToken ){
        // Inicio Try catch
        try {
          $carmodel = Carmodel::find( $id );

          if( is_object($carmodel) && !is_null($carmodel)){

            try{
              $carmodel->delete();
              $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'El carmodel ha sido eliminado correctamente'
              );
            }catch (\Illuminate\Database\QueryException $e){
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
              'message' => 'El id del carmodel no existe'
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

  public function carmodelByName( String $name, int $brand_id ,Request $request){
 
      $name = str_replace('&', ' ', $name);
      $carmodel = Carmodel::where('name', strtolower(trim($name)) )->where('brand_id', $brand_id)->first();    
  
      $data = array(
        "status"      => "success",
        "code"        => 200,
        "carmodel" => $carmodel
      );
 

    return response()->json($data, $data['code']);
  }

  public function carmodelByID(int $id,Request $request){
 
      $carmodel = Carmodel::find($id);
      $data = array(
        'code' => 200,
        'status' => 'success',
        'carmodel' => $carmodel
      );
 


    return response()->json($data, $data['code']);
  }

  public function search_model(String $word = '', int $cantidad)
  {
    // dd($word);
    // Word
    if ($word === 'a') {
      $word_condition = "carmodels.name LIKE NOT NULL";
    }else{
      $word_condition = "carmodels.name LIKE '%$word%' OR brands.name LIKE '%$word%' ";
    }

    $models = Carmodel::select('carmodels.*')
          ->join('brands', 'carmodels.brand_id', 'brands.id')
          ->whereRaw(
            "
              (
                $word_condition
              )
            "
          )
          ->paginate($cantidad);
    $models->load(['brand']);
    $data = array(
      'code' => 200,
      'status' => 'success',
      'models' => $models
    );

    return response()->json($data, $data['code']);

  }
}
