<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use App\Models\Damage_image;
use App\Models\Sell_your_car;

class Damage_imageController extends Controller
{
    public function index()
    {
        // 
    }
    
    public function store(Request $request)
    {
        if (!is_array($request->all())) {
            $data = array(
              'status' => 'error',
              'code'   => '200',
              'message'  => "request must be an array"
            );
        }

        $rules = [
            'sell_your_car_id' => 'required|exists:sell_your_cars,id', 
            'damage_id' => 'required|exists:damages,id',
            'status' => 'required|in:before,after', 
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
                // Crear
                $damage_image = new Damage_image;                         
                $damage_image->sell_your_car_id = $request->sell_your_car_id;
                $damage_image->damage_id = $request->damage_id;
                $damage_image->status = $request->status;
                // Buscar el vin en Sell_your_car
                $sell_your_car = Sell_your_car::firstWhere('id', $damage_image->sell_your_car_id);
                // Fin buscar vin
                ////////////////////////////////////////////  
                // Verificar si existe la carpeta damage_images
                $nombre_directorio = 'damage_images/' . $sell_your_car->vin;
                $directorio = storage_path() . '/app/' . $nombre_directorio;
                if (!file_exists($directorio)) {                
                    mkdir($directorio, 0777, true);
                }
                // Fin verificar si existe la carpeta damage_images                           
                $image = $request->file('picture');
                if( is_object( $image ) && !empty( $image )){
                    $nombre = \ImageHelper::upload_damageImage($image, $nombre_directorio, $damage_image->sell_your_car_id);

                    // Verifica que el proceso de guardado de la imágen se completara de manera satisfactoria
                    if ($nombre) {
                        $damage_image->path = $nombre;
                    } else {
                        $data = array(
                            'status' => 'error',
                            'code'   => '200',
                            'message' => 'La imagen no se ha podido guardar correctamente.'
                        );

                        return response()->json($data, $data['code']);
                    }
                }                            
                ////////////////////////////////////////////
                $damage_image->save();                
                
                $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'message' => 'La imagen ha sido subida correctamente',
                    'damage' => $damage_image
                );                

            }
        } catch (Exception $e) {
                $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'message' => 'Los datos enviados no son correctos, ' . $e
                );
        }

        return response()->json($data, $data['code']);
    }    
    
    public function update(Request $request, $id)
    {
        
    }
   
    public function destroy($id)
    {
        //
    }

    public function getDamageImage( int $sell_your_car_id, int $damage_id ){
        $total = Damage_image::where('sell_your_car_id', $sell_your_car_id)->where('damage_id', $damage_id)->count();

        $damage_image = Damage_image::where('sell_your_car_id', $sell_your_car_id)
                                        ->where('damage_id', $damage_id)
                                        ->first();  
        // Buscar el vin en Sell_your_car
        $sell_your_car = Sell_your_car::firstWhere('id', $sell_your_car_id);
        // Fin buscar vin
        $damage_images = null;
        if($total >= 1){
            $damage_images = Damage_image::where('sell_your_car_id', $sell_your_car_id)
                                        ->where('damage_id', $damage_id)
                                        ->get();   
        }                                       
        $data = array(
            'code' => 200,
            'status' => 'success',
            'damage_image' => $damage_image,
            'damage_images' => $damage_images,
            'sell_your_car_vin' => $sell_your_car->vin
        );

        return response()->json($data, $data['code']);
    }

    public function update_image(Request $request, int $id){
        if (!is_array($request->all())) {
            $data = array(
              'status' => 'error',
              'code'   => '200',
              'message'  => "request must be an array"
            );
        }

        $rules = [             
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
                // Crear
                $damage_image = Damage_image::find($id); 
                // Buscar el vin en Sell_your_car
                $sell_your_car = Sell_your_car::firstWhere('id', $damage_image->sell_your_car_id);
                // Fin buscar vin
                if( is_object( $damage_image ) ){
                    ////////////////////////////////////////////  
                    // Verificar si existe la carpeta damage_images
                    $nombre_directorio = 'damage_images/' . $sell_your_car->vin;
                    $directorio = storage_path() . '/app/' . $nombre_directorio;
                    if (!file_exists($directorio)) {                
                        mkdir($directorio, 0777, true);
                    }
                    // Fin verificar si existe la carpeta damage_images                           
                    $image = $request->file('picture');
                    if( is_object( $image ) && !empty( $image )){
                        \ImageHelper::delete( $nombre_directorio, $damage_image->path );
                        $nombre = \ImageHelper::upload_damageImage($image, $nombre_directorio, $damage_image->sell_your_car_id);

                        // Verifica que el proceso de guardado de la imágen se completara de manera satisfactoria
                        if ($nombre) {
                            $damage_image->path = $nombre;
                        } else {
                            $data = array(
                                'status' => 'error',
                                'code'   => '200',
                                'message' => 'La imagen no se ha podido guardar correctamente.'
                            );
    
                            return response()->json($data, $data['code']);
                        }
                    }                            
                    ////////////////////////////////////////////
                    $damage_image->save();                
                    
                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'La imagen ha sido actualizada correctamente',
                        'damage' => $damage_image->damage->load('damage_images')
                    ); 
                }else{
                    $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'message' => 'El registro no existe'
                    );
                }                                                                       
            }
        } catch (Exception $e) {
                $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'message' => 'Los datos enviados no son correctos, ' . $e
                );
        }

        return response()->json($data, $data['code']);
    }

    public function getImage($filename){
        header('Access-Control-Allow-Origin: *');
        $file = '';
        try{
            $file = Storage::disk('damage_images')->get($filename);            
        }catch( \Exception $e ){     
            $file = Storage::disk('vehicles')->get('principal.png');
        }
        return new Response($file, 200);
    }
    
    public function getImg($filename, $vin){
        header('Access-Control-Allow-Origin: *');
        $file = '';
        $file = Storage::disk('damage_images/')->get($vin . '/' .$filename);
        return new Response($file, 200);
    }

    public function downloadDamageImagen(String $filename) {
        header('Access-Control-Allow-Origin: *');
        $file = '';

        try{
            $file = Storage::disk('damage_images')->download($filename);
        }catch( \Exception $e ){     
            $file = Storage::disk('vehicles')->download('principal.png');
        }
        
        return $file;        
    }
}
