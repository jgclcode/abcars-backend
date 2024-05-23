<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Vehicle;
use App\Models\Vehicle_image;
use Illuminate\Validation\Rule;

class Vehicle_ImageController extends Controller
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

                    // Crear la instancia de Vehicle_image
                    $vehicle_image = new Vehicle_image;
                    $vehicle_image->vehicle_id = $request->vehicle_id;

                    // Verifica si existe la carpeta vehicles
                    $nombre_directorio = 'vehicles';
                    $directorio = storage_path() . '/app/' . $nombre_directorio;
                    if (!file_exists($directorio)) {
                        mkdir($directorio, 0777, true);
                    }

                    // Verifica que la imagen esté dentro de la solicitud para almacenarla
                    $image = $request->file('picture');
                    if( is_object( $image ) && !empty( $image )){
                        $nombre = \ImageHelper::upload_vehicleImage($image, $nombre_directorio, $vehicle_image->vehicle_id);

                        // Verifica que el proceso de guardado de la imágen se completara de manera satisfactoria
                        if($nombre){
                            $vehicle_image->path = $nombre;
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
                    $vehicle_image->save();

                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'La imagen ha sido subida correctamente',
                        'vehicle' => $vehicle_image->vehicle->load('vehicle_images')
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
                $Vehicle_image = Vehicle_image::find( $id );

                if( is_object($Vehicle_image) && !is_null($Vehicle_image)){

                    try{
                        $Vehicle_image->delete();
                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'Vehicle_image ha sido eliminado correctamente'
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
                        'message' => 'El id del Vehicle_image no existe'
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

    public function getImage($filename){
        header('Access-Control-Allow-Origin: *');
        $file = '';
        try{
            $file = Storage::disk('vehicles')->get($filename);
        }catch( \Exception $e ){
            $file = Storage::disk('vehicles')->get('principal.png');
        }
        return new Response($file, 200);
    }

    public function setImageWithoutFile(Request $request)
    {
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if ($checkToken ) {
            $rules = [
                'vehicle_id' => 'required|exists:vehicles,id',
                'path' => 'required'
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
                    // Crear el usuario
                    $vehicle_image = new Vehicle_image;
                    $vehicle_image->vehicle_id = $request->vehicle_id;
                    $vehicle_image->path = $request->path;
                    $vehicle_image->external_website = 'yes';
                    $vehicle_image->save();

                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'La path ha sido asignada correctamente',
                        'vehicle' => $vehicle_image->vehicle->load('vehicle_images')
                    );

                }
            } catch (Exception $e) {
                    $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'message' => 'Los datos enviados no son correctos, ' . $e
                    );
            }
        }
        else{
            $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'El usuario no está identificado'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function deleteImagesToExternalWebSite(Request $request){
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if ($checkToken ) {
            $rules = [
                'vehicle_id' => 'required|exists:vehicles,id',
                'external_website'    => 'required|in:yes,no'
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
                    $vehicle_images = Vehicle_image::where('vehicle_id', $request->vehicle_id)
                                        ->where('external_website', $request->external_website)
                                        ->get();
                    if( $request->external_website == "no"){
                        // Verificar si existe la carpeta vehicles
                        $nombre_directorio = 'vehicles';
                        $directorio = storage_path() . '/app/' . $nombre_directorio;
                        if (!file_exists($directorio)) {
                            mkdir($directorio, 0777, true);
                        }
                        // Fin verificar si existe la carpeta vehicles

                        foreach( $vehicle_images as $image ){
                            if( is_object( $image ) && !empty( $image )){
                                \ImageHelper::delete($nombre_directorio, $image->path);
                                $image->delete();
                            }
                        }
                    } else{
                        foreach( $vehicle_images as $image ){
                            if( is_object( $image ) && !empty( $image )){
                                $image->delete();
                            }
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

    public function changeOrder(Request $request){
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if ($checkToken ) {
            $rules = [
                'vehicle_id' => 'required|exists:vehicles,id',
                'new_order'    => 'required|array'
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
                    $vehicle_images = Vehicle_image::where('vehicle_id', $request->vehicle_id)
                                        ->orderBy('id', 'asc')
                                        ->get();

                    foreach( $vehicle_images as $key => $image){
                        if(
                            isset($request->new_order[$key]['path']) &&
                            isset($request->new_order[$key]['external_website'])
                        ){
                            if($request->new_order[$key]['external_website'] === "no"){
                                $string = $request->new_order[$key]['path'];
                                $url =  explode('/image_vehicle/', $string);
                                $image->path = $url[ count($url) - 1 ];
                            }else{
                                $image->path = $request->new_order[$key]['path'];
                            }
                            $image->external_website = $request->new_order[$key]['external_website'];
                            $image->save();
                        }
                    }

                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'Orden de las imágenes cambiado correctamente'
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
