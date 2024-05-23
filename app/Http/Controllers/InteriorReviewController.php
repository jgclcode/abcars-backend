<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sell_your_car;
use App\Models\Interior_review;


class InteriorReviewController extends Controller
{

    public function index()
    {
        $Interior_review = Interior_review::paginate( 10 );
        $data = array(
          'code' => 200,
          'status' => 'success',
          'Interior_review' => $Interior_review
        );
    
        return response()->json($data, $data['code']);
    }

    public function store(Request $request){
        //verificacion de datos que este lleno 
        if(is_array($request->all()) ) {
          //especificacion de tipado y campos requeridos 
          $rules = [   
            'iq1'=> 'in:a1,a2,a3',     
            'iq2'=> 'in:a1,a2,a3',     
            'iq3'=> 'in:a1,a2,a3',    
            'iq4'=> 'in:a1,a2,a3',     
            'iq5'=> 'in:a1,a2,a3',     
            'iq6'=> 'in:a1,a2,a3',     
            'iq7'=> 'in:a1,a2,a3',     
            'iq8'=> 'in:a1,a2,a3',     
            'iq9'=> 'in:a1,a2,a3',     
            'iq10'=> 'in:a1,a2,a3',     
            'iq11'=> 'in:a1,a2,a3',     
            'iq12'=> 'in:a1,a2,a3',     
            'iq13'=> 'in:a1,a2,a3',     
            'iq14'=> 'in:a1,a2,a3',    
            'iq15'=> 'in:a1,a2,a3',     
            'iq16'=> 'in:a1,a2,a3',     
            'iq17'=> 'in:a1,a2,a3',
            'sell_your_car_id' => 'required|exists:sell_your_cars,id'                                 
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
              // Crear el Interior_review
              $Interior_review = new Interior_review();
              $Interior_review->iq1 = $request-> iq1;   
              $Interior_review->iq2 = $request-> iq2;   
              $Interior_review->iq3 = $request-> iq3;   
              $Interior_review->iq4 = $request-> iq4;   
              $Interior_review->iq5 = $request-> iq5;   
              $Interior_review->iq6 = $request-> iq6;   
              $Interior_review->iq7 = $request-> iq7;   
              $Interior_review->iq8 = $request-> iq8;   
              $Interior_review->iq9 = $request-> iq9;   
              $Interior_review->iq10 = $request-> iq10;    
              $Interior_review->iq11 = $request-> iq11;    
              $Interior_review->iq12 = $request-> iq12;    
              $Interior_review->iq13 = $request-> iq13;    
              $Interior_review->iq14 = $request-> iq14;    
              $Interior_review->iq15 = $request-> iq15;    
              $Interior_review->iq16 = $request-> iq16;    
              $Interior_review->iq17 = $request-> iq17;   
              $Interior_review->sell_your_car_id = $request-> sell_your_car_id;   
              $Interior_review->save();  
    
              $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'Interior review creado exitosamente',
                'Interior_review' => $Interior_review
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
      if ( is_array($request->all()) && $checkToken ) {
  
        $rules =[
          'iq1'=> 'in:a1,a2,a3',     
          'iq2'=> 'in:a1,a2,a3',     
          'iq3'=> 'in:a1,a2,a3',    
          'iq4'=> 'in:a1,a2,a3',     
          'iq5'=> 'in:a1,a2,a3',     
          'iq6'=> 'in:a1,a2,a3',     
          'iq7'=> 'in:a1,a2,a3',     
          'iq8'=> 'in:a1,a2,a3',     
          'iq9'=> 'in:a1,a2,a3',     
          'iq10'=> 'in:a1,a2,a3',     
          'iq11'=> 'in:a1,a2,a3',     
          'iq12'=> 'in:a1,a2,a3',     
          'iq13'=> 'in:a1,a2,a3',     
          'iq14'=> 'in:a1,a2,a3',    
          'iq15'=> 'in:a1,a2,a3',     
          'iq16'=> 'in:a1,a2,a3',     
          'iq17'=> 'in:a1,a2,a3',
          'sell_your_car_id' => 'required|exists:sell_your_cars,id'                   
        ];
        try {
            // Obtener Interior_reviewe
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails() ) {
              // error en los datos ingresados
              $data = array(
                'status' => 'error',
                'code'   => '200',
                'errors'  => $validator->errors()->all()
              );
            }else{            
              $Interior_review = Interior_review::find( $id );
              if( is_object($Interior_review) && !empty($Interior_review) ){                
                $Interior_review->iq1 = $request-> iq1;   
                $Interior_review->iq2 = $request-> iq2;   
                $Interior_review->iq3 = $request-> iq3;   
                $Interior_review->iq4 = $request-> iq4;   
                $Interior_review->iq5 = $request-> iq5;   
                $Interior_review->iq6 = $request-> iq6;   
                $Interior_review->iq7 = $request-> iq7;   
                $Interior_review->iq8 = $request-> iq8;   
                $Interior_review->iq9 = $request-> iq9;   
                $Interior_review->iq10 = $request-> iq10;    
                $Interior_review->iq11 = $request-> iq11;    
                $Interior_review->iq12 = $request-> iq12;    
                $Interior_review->iq13 = $request-> iq13;    
                $Interior_review->iq14 = $request-> iq14;    
                $Interior_review->iq15 = $request-> iq15;    
                $Interior_review->iq16 = $request-> iq16;    
                $Interior_review->iq17 = $request-> iq17;   
                $Interior_review->sell_your_car_id = $request-> sell_your_car_id;   
                $Interior_review->save();                            
                  $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'message' => 'Interior_reviewe actualizado correctamente'                
                  );
              }else{
                  $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'message' => 'El Interior_reviewe no existe'
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

    public function destroy(Request $request,$id){

      // Comprobar si el usuario esta identificado
      $token = $request->header('Authorization');
      $jwtAuth = new \App\Helpers\JwtAuth();
      $checkToken = $jwtAuth->checkToken($token);
  
      if ( is_array($request->all()) && $checkToken ) {
          // Inicio Try catch
          try {
            $Interior_review = Interior_review::find( $id );
            if( is_object($Interior_review) && !is_null($Interior_review) ){
  
                try {
                    $Interior_review->delete();
  
                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'El Interior_reviewe ha sido eliminado correctamente'
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
                'message' => 'El id del Interior_reviewe no existe'
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

    public function GetInteriorbyId($id){
      $Interior_review = Interior_review::where('sell_your_car_id', $id)->get();
      $data = array(
          'status' => 'success',
          'code'   => '200',
          'Interior_review' => $Interior_review
      );  
      return response()->json($data, $data['code']);    
    }

    public function getinterior_review($id){
      $id = Sell_your_car::firstWhere('id', $id);
      $data_interior_review = Interior_review::firstWhere('sell_your_car_id', $id->id);
      if (is_object($data_interior_review) && !is_null($data_interior_review)) {
        $data = array(
          'status' => 'success',
          'code' => '200',
          'DataInteriorReview' => $data_interior_review
        );
      }

      return response()->json($data, $data['code']);
    }
}
