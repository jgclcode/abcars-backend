<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Foreing_review;

class ForeingReviewController extends Controller
{
    public function index()
    {
        $foreing_reviews = Foreing_review::all();
        $data = array(
            'code' => 200, 
            'status' => 'success',
            'foreing_reviews' => $foreing_reviews
        );
        return response()->json($data, $data['code']);
    }

    public function store(Request $request)
    {        
        if (is_array($request->all()) ){
            //especificacion de tipado y campos requeridos 
            $rules = [
                'sell_your_car_id' => 'required|exists:sell_your_cars,id'                                      
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
                    $foreing_review = new Foreing_review();
                    for( $i = 1; $i < 23; $i++){
                        $field = 'req' . $i;
                        $this->verify_is_not_null( $request, $foreing_review, $field);                        
                    }                    
                    $foreing_review->commentary = $request->commentary;
                    $foreing_review->sell_your_car_id = $request->sell_your_car_id;
                    
                    $foreing_review->save(); 

                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'el foreing_review se creo exitosamente'
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
                'message'  => "Ocurrio un error"
            );
        }

        return response()->json($data, $data['code']);
    }

    public function update(Request $request, $id)
    {
        if (is_array($request->all()) ){
            //especificacion de tipado y campos requeridos 
            $rules = [
                'sell_your_car_id' => 'required|exists:sell_your_cars,id'                                      
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
                    $foreing_review = Foreing_review::find($id);
                    if( is_object($foreing_review) && !is_null($foreing_review) ){
                        for( $i = 1; $i < 23; $i++){
                            $field = 'req' . $i;
                            $this->verify_is_not_null( $request, $foreing_review, $field);                        
                        }                    
                        $foreing_review->commentary = $request->commentary;
                        
                        $foreing_review->sell_your_car_id = $request->sell_your_car_id;
                        
                        $foreing_review->save(); 
    
                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'el foreing_review se actualizo exitosamente'
                        );
                    }else{
                        $data = array(
                            'status' => 'error',
                            'code'   => '200',
                            'message'  => "El elemento no existe"
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
                'message'  => "Ocurrio un error"
            );
        }

        return response()->json($data, $data['code']);
    }

    public function destroy($id)
    {
        $foreing_review = Foreing_review::find($id);
        if( is_object($foreing_review) && !is_null($foreing_review) ){            
            $foreing_review->delete(); 

            $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'el foreing_review se elimino exitosamente'
            );
        }else{
            $data = array(
                'status' => 'error',
                'code'   => '200',
                'message'  => "El elemento no existe"
            );
        } 
        return response()->json($data, $data['code']);
    }

    public function getForeingReviewById( int $id ){
        $foreing_review = Foreing_review::find($id);
        if( is_object($foreing_review) && !is_null($foreing_review) ){
            $data = array(
                'code' => 200, 
                'status' => 'success',
                'foreing_review' => $foreing_review
            );
        }else{
            $data = array(
                'code' => 200, 
                'status' => 'error',
                'message' => 'El elemento no existe'
            );
        }
        
        return response()->json($data, $data['code']);
    }

    public function verify_is_not_null( Request $request, Foreing_review $foreing_review, string $field){        
        if( !is_null($request->$field) ){            
            if( $request->$field == 'a1' || $request->$field == 'a2' || $request->$field == 'a3' ){
                $foreing_review->$field = $request->$field;
            }            
        }        
    }

    public function getForeingReviewBySellYourCarId( int $sell_your_car_id ){
        $foreing_review = Foreing_review::where('sell_your_car_id', $sell_your_car_id)->first();
        if( is_object($foreing_review) && !is_null($foreing_review) ){
            $data = array(
                'code' => 200, 
                'status' => 'success',
                'foreing_review' => $foreing_review
            );
        }else{
            $data = array(
                'code' => 200, 
                'status' => 'error',
                'message' => 'El elemento no existe'
            );
        }
        
        return response()->json($data, $data['code']);
    }

    public function GetForeingnbyId($id){
        $Foreing_review = Foreing_review::where('sell_your_car_id', $id)->get();
        $data = array(
            'status' => 'success',
            'code'   => '200',
            'Foreing_review' => $Foreing_review
        );  
        return response()->json($data, $data['code']);    
    }
}
