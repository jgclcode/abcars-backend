<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Vehicle;
use App\Models\Vehicle_360_image;
use Illuminate\Validation\Rule;

class Vehicle_360_imageController extends Controller
{
    public function index()
    {
        //
    }

    public function store(Request $request)
    {
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if ($checkToken ) {
            $rules = [
                'vehicle_id' => 'required|exists:vehicles,id',
                'picture'    => 'required|File'
            ];
            try {
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    // Esta condición se ejecuta si la validación de uno o más campos es incorrecta
                    $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'errors'  => $validator->errors()->all()
                    );
                }else{

                    // Crear la instancia de Vehicle_360_image
                    $vehicle_360_image = new Vehicle_360_image;
                    $vehicle_360_image->vehicle_id = $request->vehicle_id;

                    // Verifica si existe la carpeta vehicles
                    $nombre_directorio = '360';
                    $directorio = storage_path() . '/app/' . $nombre_directorio;
                    if (!file_exists($directorio)) {
                        mkdir($directorio, 0777, true);
                    }

                    // Verifica que la imagen esté dentro de la solicitud para almacenarla
                    $image = $request->file('picture');
                    if( is_object( $image ) && !empty( $image )){
                        $nombre = \ImageHelper::upload_vehicleImage($image, $nombre_directorio, $vehicle_360_image->vehicle_id);

                        // Verifica que el proceso de guardado de la imágen se completara de manera satisfactoria
                        if($nombre){
                            $vehicle_360_image->path = $nombre;
                        } else {
                            $data = array(
                            'status' => 'error',
                            'code'   => '200',
                            'message' => 'La imagen no se ha podido guardar correctamente.'
                            );

                            return response()->json($data, $data['code']);
                        }
                    }

                    // Guardado de la imagen en la base de datos
                    $vehicle_360_image->save();

                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'La imagen ha sido subida correctamente',
                        'vehicle' => $vehicle_360_image->vehicle->load('vehicle_360_images')
                    );
                }
            } catch (Exception $e) {
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

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Request $request, $id){
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if ( is_array ($request->all()) && $checkToken ){
            // Inicio Try catch
            try{
                $Vehicle_360_image = Vehicle_360_image::find( $id );

                if( is_object($Vehicle_360_image) && !is_null($Vehicle_360_image)){

                    try{
                        $Vehicle_360_image->delete();
                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'Imagen del vehiculo fue eliminada correctamente'
                        );
                    }catch(\Illuminate\Database\QueryException $e){
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
                        'message' => 'El id del Vehicle_360_image no existe'
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

    public function upload360Images(Request $request){
        if ( is_array ($request->all()) ) {
            $rules = [
                'vehicle_id' => 'required|exists:vehicles,id',
                'pictures'    => 'required|Array',
                'pictures.*' => 'required|mimes:jpg,jpeg,png,bmp,webp|max:10000',
                'pictures.*.required' => 'Please upload an image only',
                'pictures.*.mimes' => 'Only jpeg, png, jpg, webp and bmp images are allowed',
                'pictures.*.max' => 'Sorry! Maximum allowed size for an image is 10MB',
            ];
            try {
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    // Esta condición se ejecuta si la validación de uno o más campos es incorrecta
                    $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'errors'  => $validator->errors()->all()
                    );
                }else{                          
                    for($i = 1; $i <= count($request->pictures); $i++ ){ 
                        sleep( $i );                                                                     
                        ////////////////////
                        // Crear la instancia de Vehicle_360_image
                        $vehicle_360_image = new Vehicle_360_image;
                        $vehicle_360_image->vehicle_id = $request->vehicle_id;

                        // Verifica si existe la carpeta vehicles
                        $nombre_directorio = '360';
                        $directorio = storage_path() . '/app/' . $nombre_directorio;
                        if (!file_exists($directorio)) {
                            mkdir($directorio, 0777, true);
                        }

                        // Verifica que la imagen esté dentro de la solicitud para almacenarla
                        $image = $request->pictures[$i - 1];
                        if( is_object( $image ) && !empty( $image )){
                            $nombre = \ImageHelper::upload_vehicleImage($image, $nombre_directorio, $vehicle_360_image->vehicle_id);

                            // Verifica que el proceso de guardado de la imágen se completara de manera satisfactoria
                            if($nombre){
                                $vehicle_360_image->path = $nombre;
                            } else {
                                $data = array(
                                    'status' => 'error',
                                    'code'   => '200',
                                    'message' => 'La imagen no se ha podido guardar correctamente.'
                                );

                                return response()->json($data, $data['code']);
                            }
                        }

                        // Guardado de la imagen en la base de datos
                        $vehicle_360_image->save();
                        ////////////////////                        
                    }                                              

                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'Las imagenes han sido subidas correctamente',
                        'vehicle' => $vehicle_360_image->vehicle->load('vehicle_360_images')
                    );
                }
            } catch (Exception $e) {
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

    public function getImage($filename){
        header('Access-Control-Allow-Origin: *');
        $file = '';
        try{
            $file = Storage::disk('360')->get($filename);
        }catch( \Exception $e ){
            $file = Storage::disk('vehicles')->get('principal.png');
        }
        return new Response($file, 200);
    }

    public function delete360Images(Request $request){
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if ($checkToken ) {
            $rules = [
                'vehicle_id' => 'required|exists:vehicles,id'                
            ];
            try {
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    // Esta condición se ejecuta si la validación de uno o más campos es incorrecta
                    $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'errors'  => $validator->errors()->all()
                    );
                }else{
                    $vehicle_360_images = Vehicle_360_image::where('vehicle_id', $request->vehicle_id)->get();                    
                    $nombre_directorio = '360';
                    $directorio = storage_path() . '/app/' . $nombre_directorio;
                    if (!file_exists($directorio)) {
                        mkdir($directorio, 0777, true);
                    }
                    // Fin verificar si existe la carpeta vehicles

                    foreach( $vehicle_360_images as $image ){
                        if( is_object( $image ) && !empty( $image )){
                            \ImageHelper::delete($nombre_directorio, $image->path);
                            $image->delete();
                        }
                    }                    

                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'Imagenes eliminadas correctamente'
                    );

                }
            } catch (Exception $e) {
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
}
