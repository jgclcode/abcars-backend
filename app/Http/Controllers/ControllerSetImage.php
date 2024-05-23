<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SetImage;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;


use Illuminate\Support\Facades\File;


class ControllerSetImage extends Controller
{
    public function getSetvehicles(){       
          $SetImage = SetImage::with(['vehicle' => function($table){
            $table->withTrashed();
          }])->get();
          //$SetImage;

          $data = array(
            'code' => 200,
            'status' => 'success',
            'quote' => $SetImage
          );
      
          return response()->json($data, $data['code']);
    }

    public function addSetImage (Request $request){

        if(is_array($request->all())){
         //especificacion de tipado y campos requeridos 
         /*$rules = [
            'vehicle_id' => 'required|exists:vehicles,id',                    
            'path' => 'File|required',                    
         ];*/

         $rules = [
          'vehicle_id' => 'required|exists:vehicles,id',
          'pictures'    => 'required|Array',
          'pictures.*' => 'required|mimes:jpg,jpeg,png,bmp,webp|max:5000',
          'pictures.*.required' => 'Please upload an image only',
          'pictures.*.mimes' => 'Only jpeg, png, jpg, webp and bmp images are allowed',
          'pictures.*.max' => 'Sorry! Maximum allowed size for an image is 5MB',
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
                
               $vehicle = Vehicle::where('id', $request->vehicle_id)->withTrashed()->first();
                
               $nombre_directorio = 'check/'.$vehicle->vin.'';
               $directorio = storage_path() . '/app/' . $nombre_directorio;
               if (!file_exists($directorio)) {
                   mkdir($directorio, 0777, true);
               }
 
                // Verifica que la imagen esté dentro de la solicitud para almacenarla
                $images = $request->file('pictures');
                 
                foreach ($images as $image) {
                  $setimages = new SetImage();
                  $setimages->vehicle_id = $request->vehicle_id;

                  if( is_object( $image ) && !empty( $image )){
  
                    $nombre = \ImageHelper::upload_vehicleImage($image, $nombre_directorio, $setimages->vehicle_id);

                  
                    // Verifica que el proceso de guardado de la imágen se completara de manera satisfactoria
                    if($nombre){
                        $setimages->path = $nombre;
                    } else {
                        $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'message' => 'La imagen no se ha podido guardar correctamente.'
                        );
 
                        return response()->json($data, $data['code']);
                    }
                }

                $setimages->save();                  
                $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'message' => 'setimages registrado exitosamente'
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
             'message'  => "Complete todos los campos"
         );
       }
         
       return response()->json($data, $data['code']);
   }


   public function getsetImages($vin){

  
    $vehicle_id = Vehicle::where('vin', $vin)->withTrashed()->first();
    $nombresImagenes = SetImage::where('vehicle_id', $vehicle_id->id) ->orderBy('id', 'desc')->get();


    $data = array(
      'status' => 'succes',
      'code'   => '200',
      'images'  => $nombresImagenes
  );


  return response()->json($data, $data['code']);

  }


  public function getSetImage( $filename, $vin){
    header('Access-Control-Allow-Origin: *');
      $file = '';
      try{
          $file = Storage::disk('check')->get($vin . '/' .$filename);          
      }catch( \Exception $e ){
          $file = Storage::disk('check')->get('principal.png');
      }
      //echo "abraham se la come";
      
      return Response($file, 200);
  }

  public function deleteSetImages($vin){

   // echo 'check/'.$vin.'' ;

    Storage::deleteDirectory('check/'.$vin.'');

    dd("ya se elimino alv");

    //die();


  }

}
