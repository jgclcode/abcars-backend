<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Shield;
use App\Models\Vehicle;

class ShieldController extends Controller
{    
    public function index( int $total = 10 )
    {
        $shields = Shield::paginate($total);
        $data = array(
            'code' => 200,
            'status' => 'success',
            'shields' => $shields
        );
        return response()->json($data, $data['code']);
    }

    public function store(Request $request)
    {
        if( is_array($request->all()) ){
            $rules =[
                'name' => 'required|max:255|string',
                'picture' => 'required|File'          
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
                    $shield = new Shield;                         
                    $shield->name = $request->name;
                    ////////////////////////////////////////////  
                    // Verificar si existe la carpeta
                    $nombre_directorio = 'shields';
                    $directorio = storage_path() . '/app/' . $nombre_directorio;
                    if (!file_exists($directorio)) {                
                        mkdir($directorio, 0777, true);
                    }
                    // Fin verificar si existe la carpeta                           
                    $image = $request->file('picture');
                    if( is_object( $image ) && !empty( $image )){
                        $nombre = \ImageHelper::upload($image, $nombre_directorio);
                        $shield->path = $nombre;
                    }                            
                    ////////////////////////////////////////////
                    $shield->save();                
                    
                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'La garantía ha sido creada con exito',
                        'vehicle' => $shield
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
                'message'  => "No se recibio la información apropiada"
            );
        }
        return response()->json( $data, $data['code'] );
    }
    
    public function update(Request $request, $id)
    {

    }
    
    public function destroy($id)
    {
        $shield = Shield::find($id);
        if( is_object($shield) && !is_null($shield)){
            try{
                $shield->delete();
                $data = array(
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'La garantía ha sido eliminada con exito'
                );
            }catch (\Illuminate\Database\QueryException $e){
                //throw $th;
                $data = array(
                    'status' => 'error',
                    'code'   => '400',
                    'message' => $e->getMessage()
                );
            }            
        }else{
            $data = array(
                'code' => 200,
                'status' => 'error',
                'message' => 'La garantía no existe'
            );
        }
        return response()->json($data, $data['code']);
    }    
    
    public function updateWithImage( Request $request, $id ){
        if( is_array($request->all()) ){
            $rules =[
                'name' => 'required|max:255|string',
                'picture' => 'required|File'          
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
                    $shield = Shield::find($id);  
                    if( is_object($shield) && !is_null($shield) ){
                        $shield->name = $request->name;
                        ////////////////////////////////////////////  
                        // Verificar si existe la carpeta vehicles
                        $nombre_directorio = 'shields';
                        $directorio = storage_path() . '/app/' . $nombre_directorio;
                        if (!file_exists($directorio)) {                
                            mkdir($directorio, 0777, true);
                        }
                        // Eliminar imagen antigua
                        \ImageHelper::delete($nombre_directorio, $shield->path);

                        // Fin verificar si existe la carpeta vehicles                           
                        $image = $request->file('picture');
                        
                        if( is_object( $image ) && !empty( $image )){
                            $nombre = \ImageHelper::upload($image, $nombre_directorio);
                            $shield->path = $nombre;
                        }                            
                        ////////////////////////////////////////////
                        $shield->save();                
                        
                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'La garantía ha sido actualizada con exito',
                            'vehicle' => $shield
                        );  
                    }else{
                        $data = array(
                            'status' => 'error',
                            'code'   => '200',
                            'message' => 'La garantía no existe'
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
        }else{
            $data = array(
                'status' => 'error',
                'code'   => '200',
                'message'  => "No se recibio la información apropiada"
            );
        }
        return response()->json($data, $data['code']);
    }

    public function getImage($filename){
        header('Access-Control-Allow-Origin: *');
        $file = '';
        try{
            $file = Storage::disk('shields')->get($filename);            
        }catch( \Exception $e ){
            $file = Storage::disk('shields')->get('principal.png');
        }
        return new Response($file, 200);
    }

    public function assignShield(int $vehicle_id, int $shield_id){
        $vehicle = Vehicle::find( $vehicle_id );
        $shield = Shield::find( $shield_id );

        if( is_object($vehicle) && is_object($shield) ){ 
            $vehicle->shields()->detach($shield->id);             
            $vehicle->shields()->attach($shield->id);
            $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'La garantía se ha agregado correctamente al vehículo',
                'vehicle' => $vehicle->load('shields')
              );
        }else{
            $data = array(
                'code' => '200',
                'status' => 'error',
                'message' => 'Elementos no encontrados'
            );
        }
        return response()->json($data, $data['code']);
    }
    
    public function removeShield(int $vehicle_id, int $shield_id){
        $vehicle = Vehicle::find( $vehicle_id );
        $shield = Shield::find( $shield_id );

        if( is_object($vehicle) && is_object($shield) ){              
            $vehicle->shields()->detach($shield->id);
            $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'La garantía se ha eliminado correctamente del vehículo',
                'vehicle' => $vehicle->load('shields')
              );
        }else{
            $data = array(
                'code' => '200',
                'status' => 'error',
                'message' => 'Elementos no encontrados'
            );
        }
        return response()->json($data, $data['code']);
    }

    public function existsShieldIntoVehicle( int $vehicle_id, int $shield_id ){
        $vehicle = Vehicle::find( $vehicle_id );        

        if( is_object($vehicle) ){              
            $vehicle = $vehicle->join(
                                    'shield_vehicle', 'shield_vehicle.vehicle_id', 'vehicles.id'
                                )
                                ->join(
                                    'shields', 'shields.id', 'shield_vehicle.shield_id'
                                )
                                ->where('shields.id', $shield_id)
                                ->first();            
        }

        $data = array(
            'status' => 'success',
            'code'   => '200',                
            'vehicle' => $vehicle
        );

        return response()->json($data, $data['code']);
    }

    public function getShieldById( int $shield_id ){
        $shield = Shield::find( $shield_id );
        $data = array(
            'code' => 200, 
            'status' => 'success',
            'shield' => $shield
        );
        return response()->json($data, $data['code']);
    }

    public function changeOrderShieldImages(Request $request){
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if ($checkToken) {
            $rules = [
                'id_vehicle' => 'required',
                'new_order' => 'required|array'
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
                }else {
                    $vehicle = Vehicle::find( $request->id_vehicle );
                    $vehicle->shields()->detach();
                    for ($i=0; $i < count($request->new_order); $i++) { 
                        $vehicle->shields()->attach($request->new_order[$i]['id']);
                    }
                    $data = array(
                        'status'  => 'success',
                        'code'    => '200',
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
