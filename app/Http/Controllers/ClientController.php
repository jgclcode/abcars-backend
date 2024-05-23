<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Vehicle;
use App\Models\Quote;
use App\Models\Brand;
use App\Models\Carmodel;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{

  public function index( int $total = 10 ){
      $clients = Client::paginate( $total );
      $data = array(
        'code' => 200,
        'status' => 'success',
        'clients' => $clients
      );

      return response()->json($data, $data['code']);
  }

  public function store(Request $request){
    //verificacion de datos que este lleno 
    if (is_array($request->all())) {
      //especificacion de tipado y campos requeridos 
      $rules = [
        'phone1' => 'required|integer',
        'phone2' => 'integer',                    
        'curp' => 'max:255|string',                    
        'points' => 'required|int',    
        //new
        'address' => 'max:255|string',                    
        'municipality' => 'max:255|string',                    
        'state' => 'max:255|string',                    
        'cp' => 'max:255|string',                    
        'rfc' => 'max:255|string',   
        //                  
        'user_id' => 'required|exists:users,id',                    
        'source_id' => 'required|exists:sources,id'                    
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
            // Crear el cliente
            $client = new Client();
            $client->phone1 = $request->phone1;
            $client->phone2 = $request->phone2;   
            if( isset($request->curp) && $request->curp ){
              $client->curp = strtoupper($request->curp);                
            }                                 
            $client->points = $request->points;     

            // Generate reference rewards of 7 characters dinamic            
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';                        
            $flag = true;
            
            // Checking reference rewards
            while ($flag) {
              $reference = substr(str_shuffle($permitted_chars), 0, 8);
              $rewardExists = Client::where('rewards', $reference)->first(); 

              if (!is_object($rewardExists)) {
                $client->rewards = $reference;
                $flag = false;
              }
            }
            $client->address =is_null($request->address) ? "" : $request->address;
            $client->municipality =is_null($request->municipality) ? "" : $request->municipality;
            $client->state =is_null($request->state) ? "" : $request->state;
            $client->cp =is_null($request->cp) ? "" : $request->cp;
            $client->rfc =is_null($request->rfc) ? "" : $request->rfc;
            $client->user_id = $request->user_id;                
            $client->source_id = $request->source_id;                             
            $client->save();   

            $data = array(
              'status' => 'success',
              'code'   => '200',
              'message' => 'el cliente se creo exitosamente',
              'client' => $client
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
          'phone1' => 'required|integer',
          'phone2' => 'integer',                    
          'curp' => 'required|max:255|string',                    
          'points' => 'required|int',     
          'address' => 'max:255|string',                    
          'municipality' => 'max:255|string',                    
          'state' => 'max:255|string',                    
          'cp' => 'max:255|string',                    
          'rfc' => 'max:255|string',   
          'user_id' => 'required|exists:users,id',                    
          'source_id' => 'required|exists:sources,id'                     
      ];
      try {
          // Obtener cliente
          $validator = \Validator::make($request->all(), $rules);
          if ($validator->fails() ) {
            // error en los datos ingresados
            $data = array(
              'status' => 'error',
              'code'   => '200',
              'errors'  => $validator->errors()->all()
            );
          }else{            
            $client = Client::find( $id );
            if( is_object($client) && !empty($client) ){                
                $client->phone1 = $request->phone1;
                $client->phone2 = $request->phone2;                
                $client->curp = strtoupper($request->curp);
                $client->points = $request->points;     
                $client->address =is_null($request->address) ? "" : $request->address;
                $client->municipality =is_null($request->municipality) ? "" : $request->municipality;
                $client->state =is_null($request->state) ? "" : $request->state;
                $client->cp =is_null($request->cp) ? "" : $request->cp;
                $client->rfc =is_null($request->rfc) ? "" : $request->rfc;           
                $client->user_id = $request->user_id;                
                $client->source_id = $request->source_id;    
                $client->save();                                
                $data = array(
                  'status' => 'success',
                  'code'   => '200',
                  'message' => 'cliente actualizado correctamente'                
                );
            }else{
                $data = array(
                  'status' => 'error',
                  'code'   => '200',
                  'message' => 'El cliente no existe'
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
          $client = Client::find( $id );
          if( is_object($client) && !is_null($client) ){

              try {
                  $client->delete();

                  $data = array(
                      'status' => 'success',
                      'code'   => '200',
                      'message' => 'El cliente ha sido eliminado correctamente'
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
              'message' => 'El id del cliente no existe'
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

  /**
   * Get vehicles by client
   */
  public function vehiclesByClient(Request $request, int $user_id) {
    // Checkin user token
    $jwtAuth = new \App\Helpers\JwtAuth();
    $token = $request->header('Authorization');
    $checkToken = $jwtAuth->checkToken($token);

    // Check is exists token and valid
    if (is_array($request->all()) && $checkToken) {
      // Get client by user_id
      $client = Client::where('user_id', $user_id)->first();

      // Check is exist client
      if (is_object($client) && !is_null($client)) {
        // Get vehicles by client_id
        $vehicles = Vehicle::where('client_id', $client->id)->get();

        // Response
        $data = array(
          'code' => 200,
          'status' => 'success',
          'vehicles' => $vehicles->load(['carmodel', 'branch', 'vehicle_images'])
        );
      } else {
        $data = array(
          'code' => 404,
          'status' => 'error',
          'message' => 'El usuario ingresado no ha sido encontrado en clientes, verifique.'
        );
      }
    } else {
      $data = array(
        'code' => 401,
        'status' => 'error',
        'message' => 'El usuario no está identificado.'
      );
    }

    return response()->json($data, $data['code']);
  }

  /**
   * Get added services a client
   */
  public function servicesAddedClient(Request $request, String $type, int $user_id) {
    // Checkin user token
    $jwtAuth = new \App\Helpers\JwtAuth();
    $token = $request->header('Authorization');
    $checkToken = $jwtAuth->checkToken($token);

    // Check is exists token and valid
    if (is_array($request->all()) && $checkToken) {
      // Get client by user_id
      $client = Client::where('user_id', $user_id)->first();

      // Check is exist client
      if (is_object($client) && !is_null($client)) {
        // Get quotes services by client_id
        $quotes = Quote::where('type', $type)->where('client_id',  $client->id)->groupBy('vin')->pluck('vin');

        $vehicles = array();
        foreach ($quotes as $key => $quote) {
          // Get quote by vin
          $q = Quote::where('vin', $quote)->get();

          // Get vehicles by vin
          $vehicle = Vehicle::where('vin', $quote)->first();    

          if (is_object($vehicle)) {
            $vehicles[$key]['vehicle'] = $vehicle->load(['carmodel', 'vehicle_images']);
            $vehicles[$key]['quotes'] = $q->load('services');          
          } else {
            $vehicles[$key]['vehicle'] = [
              "id" => $key,
              "name" => Carmodel::find($q[0]->carmodel_id)->name,
              "vin" => $q[0]->vin,
              "yearModel" => 0,
              "km" => 0,
              "transmission" => "Trans. Desconocida",
              "carmodel" => [
                "brand" => [
                  "name" => Brand::find($q[0]->brand_id)->name
                ]
              ],
              "vehicle_images" => [],
            ];
            $vehicles[$key]['quotes'] = $q->load('services');
          }
        } 
       
        // Response
        $data = array(
          'code' => 200,
          'status' => 'success',
          'vehicles' => $vehicles
        );
      } else {
        $data = array(
          'code' => 404,
          'status' => 'error',
          'message' => 'El usuario ingresado no ha sido encontrado en clientes, verifique.'
        );
      }
    } else {
      $data = array(
        'code' => 401,
        'status' => 'error',
        'message' => 'El usuario no está identificado.'
      );
    }

    return response()->json($data, $data['code']);
  }

  /**
   * Get client by user_id
   */
  public function getClientByUser(Request $request, int $user_id) {
    // Checkin user token
    $jwtAuth = new \App\Helpers\JwtAuth();
    $token = $request->header('Authorization');
    $checkToken = $jwtAuth->checkToken($token);

    // Check is exists token and valid
    if (is_array($request->all()) && $checkToken) {
      // Get client by user_id
      $client = Client::where('user_id', $user_id)->first();

      // Check is exist client
      if (is_object($client) && !is_null($client)) {
        // Response
        $data = array(
          'code' => 200,
          'status' => 'success',
          'client' => $client
        );
      } else {
        $data = array(
          'code' => 404,
          'status' => 'error',
          'message' => 'El usuario ingresado no ha sido encontrado en clientes, verifique.'
        );
      }
    } else {
      $data = array(
        'code' => 401,
        'status' => 'error',
        'message' => 'El usuario no está identificado.'
      );
    }

    return response()->json($data, $data['code']);
  }

  public function getClientById( int $id ){
    $client = Client::find( $id );
    $client->load(['user']);
    $data = array(
      'code' => 200,
      'status' => 'success',
      'client' => $client
    );

    return response()->json($data, $data['code']);
  }

  public function getProspectusToInsurancePolicies( String $word ){
    $clients = Client::join('users', 'users.id', 'clients.user_id')
                        ->join('choices', 'choices.client_id', 'clients.id')
                        ->join('vehicles', 'vehicles.id', 'choices.vehicle_id')
                        ->where('choices.status', 'apartado')
                        ->where('vehicles.status', 'sale')
                        ->whereRaw(
                          "
                            users.email LIKE '%$word%' OR
                            clients.phone1 LIKE '%$word%' 
                          "
                        )
                        ->select(
                          'clients.id',
                          'users.name',
                          'users.surname',
                          'users.email',
                          'clients.phone1'                          
                        )
                        ->groupBy('clients.id')
                        ->get();
                        $clients->load(['choices_with_vehicle', 'policies']);
    $data = array(
      'code' => 200,
      'status' => 'success',
      'clients' => $clients
    );
    
    return response()->json($data, $data['code']);
  }

  /**
   * Search Client in proces Quote Services
  */
  public function getClientsQuoteServices(String $word, $quotes = 'inactive', String $status = 'progress') {
    if (isset($word)) {
      // Load quotes if is active
      if ($quotes === 'active') {
        $response = Quote::where('client_id', $word)->where('type', 'servicio')->where('status', $status)->paginate(10);
        $response->load(['brand', 'carmodel']);
        $response->load(['services' => function ($query) {
          $query->select('services.id', 'services.name');
        }]);

        $data = array(
          'code' => 200,
          'status' => 'success',
          'quotes' => $response
        );
      } else {
        $clients = Client::join('users', 'users.id', 'clients.user_id')
        ->join('model_has_roles', 'model_id', 'users.id')
        ->where('model_has_roles.role_id', 3)
        ->whereRaw(
          "
            users.email LIKE '%$word%' OR
            clients.phone1 LIKE '%$word%' OR
            clients.phone2 LIKE '%$word%'
          "
        )
        ->select(
          'clients.id',
          'users.name',
          'users.surname',
          'users.email',
          'clients.phone1'                          
        )
        ->get();

        $data = array(
          'code' => 200,
          'status' => 'success',
          'clients' => $clients
        );
      }      
    } else {
      $data = array(
        'code' => 400,
        'status' => 'error',
        'message' => 'Se requiere un correo o telefono para su busqueda'
      );
    }

    return response()->json($data, $data['code']);
  }

  public function updateDataToPolicie(Request $request, $id){
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);
    if ( is_array($request->all()) && $checkToken ) {

      $rules =[              
          'address' => 'required|max:255|string',                    
          'municipality' => 'required|max:255|string',                    
          'state' => 'required|max:255|string',                    
          'cp' => 'required|max:255|string',                    
          'rfc' => 'required|max:255|string'                             
      ];
      try {
          // Obtener cliente
          $validator = \Validator::make($request->all(), $rules);
          if ($validator->fails() ) {
            // error en los datos ingresados
            $data = array(
              'status' => 'error',
              'code'   => '200',
              'errors'  => $validator->errors()->all()
            );
          }else{            
            $client = Client::find( $id );
            if( is_object($client) && !empty($client) ){                                  
                $client->address =is_null($request->address) ? "" : $request->address;
                $client->municipality =is_null($request->municipality) ? "" : $request->municipality;
                $client->state =is_null($request->state) ? "" : $request->state;
                $client->cp =is_null($request->cp) ? "" : $request->cp;
                $client->rfc =is_null($request->rfc) ? "" : $request->rfc;                             
                $client->save();                                
                $data = array(
                  'status' => 'success',
                  'code'   => '200',
                  'message' => 'cliente actualizado correctamente'                
                );
            }else{
                $data = array(
                  'status' => 'error',
                  'code'   => '200',
                  'message' => 'El cliente no existe'
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

}