<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service_incident;

class Service_incidentController extends Controller
{
    public function index()
    { 
        $Service_incident = Service_incident::paginate( 10 );
        $data = array(
          'code' => 200,
          'status' => 'success',
          'Service_incident' => $Service_incident
        );
  
        return response()->json($data, $data['code']);    }
 
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
            'name' => 'required|string',
            'description' => 'required|string',
            'status' => 'required|in:success,close,progress',
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id'                                    
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
                $service_incident = new Service_incident;
                $service_incident->name = $request->name;
                $service_incident->description = $request->description;
                $service_incident->status = $request->status;
                $service_incident->client_id = $request->client_id;
                $service_incident->service_id = $request->service_id;
                $service_incident->save();            
                
                $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'message' => 'el incidente se ha creado correctamente',
                    'incident' => $service_incident  
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

    public function update(Request $request, $id)
    {
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if ( is_array($request->all()) && $checkToken ) {

            $rules = [
                'name' => 'required|string',
                'description' => 'required|string',
                'status' => 'required|in:success,close,progress',
                'client_id' => 'required|exists:clients,id',
                'service_id' => 'required|exists:services,id'                           
            ];

            try {
                // Obtener service_incident
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails() ) {
                    // error en los datos ingresados
                    $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'errors'  => $validator->errors()->all()
                    );
                }else{            
                    $service_incident = Service_incident::find( $id );

                    if( is_object($service_incident) && !empty($service_incident)){                
                        $service_incident->name = $request->name;
                        $service_incident->description = $request->description;
                        $service_incident->status = $request->status;
                        $service_incident->client_id = $request->client_id;
                        $service_incident->service_id = $request->service_id;
                        $service_incident->save();  
                                                      
                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'service_incident incident actualizado correctamente'               
                        );
                    }else{
                        $data = array(
                            'status' => 'error',
                            'code'   => '200',
                            'message' => 'El id del service_incident  no existe'
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
            // Inicio Try catch
            try {
                $service_incident = Service_incident::find( $id );

                if( is_object($service_incident) && !is_null($service_incident)){

                    try{
                        $service_incident->delete();

                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'service incident ha sido eliminado correctamente'
                        );

                    }catch(\Illuminate\Database\QueryException $e){
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
                        'message' => 'El id del service incident  no existe'
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

    public function getAllByClientId( int $client_id ){
        $incidents = Service_incident::where('client_id', $client_id )->get();
        $data = array(
            'status' => 'success',
            'code'   => '200',
            'incidents' => $incidents->load(['service', 'client'])
        );  
        return response()->json($data, $data['code']);
    }

    public function getIncidents( String $word = '' ){        
        $incidents = Service_incident::select('service_incidents.*')
                                      ->join('clients', 'clients.id', 'service_incidents.client_id')
                                      ->join('users', 'users.id', 'clients.user_id')
                                      ->join('services', 'services.id', 'service_incidents.service_id')
                                      ->whereRaw(
                                          "
                                            service_incidents.name LIKE '%$word%' OR 
                                            service_incidents.description LIKE '%$word%' OR 
                                            service_incidents.status LIKE '%$word%' OR
                                            service_incidents.created_at LIKE '%$word%' OR 
                                            users.name LIKE '%$word%' OR
                                            users.surname LIKE '%$word%' OR 
                                            users.email LIKE '%$word%' OR
                                            services.name LIKE '%$word%'
                                          "
                                      )
                                      ->paginate(10);

        $incidents->load(['client', 'service']);
        $data = array(
            'code' => 200,
            'status' => 'success',
            'incidents' => $incidents
        );

        return response()->json($data, $data['code']);
    }

    public function updateStatus(Request $request, $id)
    {        
        if ( is_array($request->all()) ) {
            $rules = [                
                'status' => 'required|in:success,close,progress'                
            ];
            try {
                // Obtener service_incident
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails() ) {
                    // error en los datos ingresados
                    $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'errors'  => $validator->errors()->all()
                    );
                }else{            
                    $service_incident = Service_incident::find( $id );

                    if( is_object($service_incident) && !empty($service_incident)){                                        
                        $service_incident->status = $request->status;                        
                        $service_incident->save();  
                                                      
                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'La incidencia ha sido actualizada correctamente'               
                        );
                    }else{
                        $data = array(
                            'status' => 'error',
                            'code'   => '200',
                            'message' => 'El id del service_incident  no existe'
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
        }
        return response()->json($data, $data['code']); 
    }
}
