<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle_incident;

class Vehicle_incidentController extends Controller
{
    public function index(){
        $Vehicle_incident = Vehicle_incident::paginate( 10 );
        $Vehicle_incident->load('client');

        $data = array(
            'code' => 200,
            'status' => 'success',
            'Vehicle_incident' => $Vehicle_incident
        );

        return response()->json($data, $data['code']);
    }

    public function store(Request $request){
        if (!is_array($request->all())) {
            $data = array(
              'status' => 'error',
              'code'   => '200',
              'message'  => "request must be an array"
            );
        }
        $rules = [
            'name' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|in:success,close,progress',
            'client_id' => 'required|exists:clients,id',
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
                $vehicle_incident = new Vehicle_incident;
                $vehicle_incident->name = $request->name;
                $vehicle_incident->description = $request->description;
                $vehicle_incident->status = $request->status;
                $vehicle_incident->client_id = $request->client_id;
                $vehicle_incident->vehicle_id = $request->vehicle_id;
                $vehicle_incident->save();            
                
                $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'message' => 'el incidente se ha creado correctamente',
                    'incident' => $vehicle_incident  
                );                
    
            }
        }catch (Exception $e) {
            $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'Los datos enviados no son correctos, ' . $e
            );
        }

        return response()->json($data, $data['code']);
    }

    public function update(Request $request, $id){
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if ( is_array($request->all()) && $checkToken ){

            $rules = [
                'name' => 'required|string',
                'description' => 'required|string',
                'status' => 'required|in:success,close,progress',
                'client_id' => 'required|exists:clients,id',
                'vehicle_id' => 'required|exists:vehicles,id'                          
            ];

            try {
                // Obtener Vehicle_incident
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails() ) {
                    // error en los datos ingresados
                    $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'errors'  => $validator->errors()->all()
                    );
                }else{            
                $vehicle_incident = Vehicle_incident::find( $id );

                    if( is_object($vehicle_incident) && !empty($vehicle_incident) ){                
                        $vehicle_incident->name = $request->name;
                        $vehicle_incident->description = $request->description;
                        $vehicle_incident->status = $request->status;
                        $vehicle_incident->client_id = $request->client_id;
                        $vehicle_incident->vehicle_id = $request->vehicle_id;
                        $vehicle_incident->save();  

                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'vehicle incident actualizado correctamente'               
                        );
                    }else{
                        $data = array(
                            'status' => 'error',
                            'code'   => '200',
                            'message' => 'El id del Vehicle incident no existe'
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

        }else{
        $data = array(
            'status' => 'error',
            'code'   => '200',
            'message' => 'El usuario no está identificado'
        );
        }

        return response()->json($data, $data['code']); 
    }

    public function destroy(Request $request, $id)
    {
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth(); 
        $checkToken = $jwtAuth->checkToken($token);

        if ( is_array($request->all()) && $checkToken ) {

            try {
                $vehicle_incident = Vehicle_incident::find( $id );

                if( is_object($vehicle_incident) && !is_null($vehicle_incident) ){
                    try {
                        $vehicle_incident->delete();

                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'vehicle incident ha sido eliminado correctamente'
                        );

                    } catch (\Illuminate\Database\QueryException $e) {

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
                        'message' => 'El id del vehicle incident no existe'
                    );
                }
            }catch (Exception $e) {
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

    public function getAllByClientId( int $client_id ){
        $incidents = Vehicle_incident::where('client_id', $client_id )->get();
        $data = array(
            'status' => 'success',
            'code'   => '200',
            'incidents' => $incidents->load(['vehicle', 'client'])
        );  
        return response()->json($data, $data['code']);
    }
}
