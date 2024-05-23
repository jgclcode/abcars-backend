<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Sell_your_car;
use App\Models\Painting_work;

class Painting_workController extends Controller
{

    public function index()
    {
        //
    }

    public function store(Request $request) {        
        if(is_array($request->all())){
            //especificacion de tipado y campos requeridos 
            $rules = [
                'name' =>'required|max:255|string',
                'amount' =>'required|numeric',
                'status' => 'in:approved,pre approved,on hold,rejected',
                'picture' => 'required|File',
                /* 'hours' =>'required|numeric',
                'type_part' => 'in:original,generic,used',
                'priceOriginal' => 'numeric',                
                'timeOriginal' => 'date',
                'priceGeneric' => 'numeric',    
                'timeGeneric' => 'date',
                'priceUsed' => 'numeric',
                'timeUsed' => 'date', */              
                'sell_your_car_id' => 'required|exists:sell_your_cars,id'
            ];

            try {
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
                    $painting_work = new Painting_work;

                    $painting_work->name = $request->name;
                    $painting_work->amount = $request->amount;
                    $painting_work->status = $request->status;
                    ////////////////////////////////////////////  
                    // Verify exists folder paintingWorks_images
                    $folder_name = 'paintingWorks_images';
                    $folder = storage_path() . '/app/' . $folder_name;
                    if (!file_exists($folder)) {
                        mkdir($folder, 0777, true);
                    }
                    // End verify exists folder paintingWorks_images
                    $image = $request->file('picture');
                    if (is_object($image) && !empty($image)) {
                        $name = \ImageHelper::upload($image, $folder_name);
                        $painting_work->img_damage = $name;
                    }
                    ////////////////////////////////////////////
                    /* $painting_work->hours = $request->hours;
                    $painting_work->type_part = $request->type_part;

                    if (!is_null($request->priceOriginal)) {
                        $painting_work->priceOriginal = $request->priceOriginal;
                        $painting_work->timeOriginal = $request->timeOriginal;
                    }

                    if (!is_null($request->priceGeneric)) {
                        $painting_work->priceGeneric = $request->priceGeneric;
                        $painting_work->timeGeneric = $request->timeGeneric;
                    }

                    if (!is_null($request->priceUsed)) {
                        $painting_work->priceUsed = $request->priceUsed;
                        $painting_work->timeUsed = $request->timeUsed;
                    } */

                    $painting_work->sell_your_car_id = $request->sell_your_car_id;
                    $painting_work->save();

                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'El registro HyP ha sido creado correctamente'
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
                'message'  => "Ocurrio un error"
            );
        }
            
        return response()->json($data, $data['code']);
    }

    public function update(Request $request, $id) {
        if(is_array($request->all())){
            //especificacion de tipado y campos requeridos 
            $rules = [
                'type_part' => 'in:original,generic,used',            
                'status' => 'in:approved,pre approved,on hold,rejected',            
                'priceOriginal' => 'required|numeric',
                'timeOriginal' => 'date',
                'priceGeneric' => 'required|numeric',
                'timeGeneric' => 'date',
                'priceUsed' => 'required|numeric',
                'timeUsed' => 'date',
                'comments' => 'string'
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
                } else {
                    // Update spare part
                    $painting_work = Painting_work::find($id);
                                        
                    if (!is_null($request->type_part)) {
                        $painting_work->type_part = $request->type_part;
                    }

                    if (!is_null($request->status)) {
                        $painting_work->status = $request->status;             
                    }

                    $painting_work->priceOriginal = $request->priceOriginal;
                    $painting_work->timeOriginal = $request->timeOriginal;
                    $painting_work->priceGeneric = $request->priceGeneric;
                    $painting_work->timeGeneric = $request->timeGeneric;
                    $painting_work->priceUsed = $request->priceUsed;
                    $painting_work->timeUsed = $request->timeUsed;

                    if (!is_null($request->comments)) {
                        $painting_work->comments = $request->comments;             
                    }

                    if ($painting_work->save()) {                    
                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'La hojalatería y pintura ha sido actualizada',
                            'painting_work' => $painting_work
                        );                
                    } else {
                        $data = array(
                            'code'   => '400',
                            'status' => 'error',
                            'message' => 'La hojalatería y pintura no se actualizo correctamente.'
                        );                
                    }
                }
            } catch(Exception $e) {
              $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'Los datos enviados no son correctos, ' . $e
              );
            }      
        } else {
            $data = array(
                'status' => 'error',
                'code'   => '200',
                'message'  => "Ocurrio un error"
            );
        }
            
        return response()->json($data, $data['code']);
    }

    public function destroy($id){}

    public function updateStatus( Request $request, $id ){
        if(is_array($request->all())){
            //especificacion de tipado y campos requeridos 
            $rules = [
                'status' => 'required|in:approved,rejected,on hold',
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
                $painting_work = Painting_work::find($id);
                $painting_work->status = $request->status;             
                $painting_work->save();   
      
                $data = array(
                  'status' => 'success',
                  'code'   => '200',
                  'message' => 'La hojalatería y pintura ha sido actualizada'
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
                'message'  => "Ocurrio un error"
            );
        }
            
        return response()->json($data, $data['code']);
    }

    public function getPainting_worksBySellYourCar( int $sell_your_car_id ){
        $sell_your_car_id = Sell_your_car::find($sell_your_car_id);

        if( is_object($sell_your_car_id) ){
            $painting_works = $sell_your_car_id->painting_works()->get();
            $data = array(
                'code' => 200,
                'status' => 'success',
                'painting_works' => $painting_works
            );
        }else{
            $data = array(
                'code' => 200,
                'status' => 'error',
                'message' => 'Este registro no existe'
            );
        }
        return response()->json( $data, $data['code']);
    }

    public function imgDamage($filename){
        header('Access-Control-Allow-Origin: *');
        $file = '';
        try{
            $file = Storage::disk('paintingWorks_images')->get($filename);            
        }catch( \Exception $e ){     
            $file = Storage::disk('vehicles')->get('principal.png');
        }
        return new Response($file, 200);
    }
}
