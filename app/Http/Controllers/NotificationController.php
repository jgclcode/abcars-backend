<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\Vehicle;
use App\Models\Client;
use App\Models\User;
use App\Models\Log;

class NotificationController extends Controller
{
  public function index() {
    $notification = Notification::paginate( 10 );
    
    $data = array(
      'code' => 200,
      'status' => 'success',
      'notification' => $notification
    );

    return response()->json($data, $data['code']);
  }

  public function store(Request $request) {
    //verificacion de datos que este lleno 
    if (is_array($request->all())) {
      //especificacion de tipado y campos requeridos 
      $rules = [
        'status' => 'required|max:255|string',
        'client_id' => 'required|exists:clients,id',                               
        'vehicle_id' => 'required|exists:vehicles,id'                               
      ];
    
      try {
        //validacion de tipado y campos requeridos 
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
          // existio un error en los campos enviados 
          $data = array(
            'status' => 'error',
            'code' => '200',
            'errors' => $validator->errors()->all()
          );
        } else {         
          //Send Email
          $vehicle = Vehicle::where('id', $request->vehicle_id)->get()->first();
          $client = Client::select("user_id")->where('id',  $request->client_id)->get()->first();
          $user = User::where('id',  $client->user_id)->get()->first(); 
          $description = $vehicle ->description.' '. $vehicle->yearModel.' Color:'. $vehicle->colorExt;
          $vin = $vehicle->vin;
          
          \EmailHelper::Email_Notification( $user->email, $user->name, $user->surname ,$description,$vin, $vehicle->name); 
                         
          // Crear el notification
          $notification = new Notification();
          $notification->status = $request->status;
          $notification->client_id = $request->client_id;  
          $notification->vehicle_id = $request->vehicle_id;                              
          $notification->save();                

          $data = array(
            'status' => 'success',
            'code' => '200',
            'message' => 'Notification creado exitosamente'
          );                
        }
      } catch (Exception $e) {
        $data = array(
          'status' => 'error',
          'code' => '200',
          'message' => 'Los datos enviados no son correctos, ' . $e
        );
      }   
    } else {
      $data = array(
        'status' => 'error',
        'code' => '200',
        'message' => "El usuario no esta identificado"
      );
    }
      
    return response()->json($data, $data['code']);
  }

  public function update(Request $request, $id) {
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);

    if( is_array($request->all()) && $checkToken) {
      $rules = [
        'status' => 'required|max:255|string',
        'client_id' => 'required|exists:clients,id',                               
        'vehicle_id' => 'required|exists:vehicles,id'                        
      ];

      try {
        // Obtener notification
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          // error en los datos ingresados
          $data = array(
            'status' => 'error',
            'code' => '200',
            'errors' => $validator->errors()->all()
          );
        } else {            
          $notification = Notification::find( $id );

          if (is_object($notification) && !empty($notification)) {                
            $notification->status = $request->status;
            $notification->client_id = $request->client_id;  
            $notification->vehicle_id = $request->vehicle_id;                              
            $notification->save();      

            $data = array(
              'status' => 'success',
              'code' => '200',
              'message' => 'notification se ha actualizado correctamente'               
            );
          } else {
            $data = array(
              'status' => 'error',
              'code' => '200',
              'message' => 'id de notification no existe'
            );
          }

        }
      } catch (Exception $e) {
        $data = array(
          'status' => 'error',
          'code' => '200',
          'message' => 'Los datos enviados no son correctos, ' . $e
        );
      }
    } else {
      $data = array(
        'status' => 'error',
        'code' => '200',
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

    if(is_array($request->all()) && $checkToken) {
      // Inicio Try catch
      try {
        $notification = Notification::find( $id );

        if ( is_object($notification) && !is_null($notification) ) {
          try {
            $notification->delete();

            $data = array(
              'status' => 'success',
              'code' => '200',
              'message' => 'notification ha sido eliminado correctamente'
            );
          } catch (\Illuminate\Database\QueryException $e) {
            //throw $th;
            $data = array(
              'status' => 'error',
              'code' => '400',
              'message' => $e->getMessage()
            );
          }
        } else {
          $data = array(
            'status' => 'error',
            'code' => '404',
            'message' => 'El id del notification no existe'
          );
        }
      } catch (Exception $e) {
        $data = array(
          'status' => 'error',
          'code' => '404',
          'message' => 'Los datos enviados no son correctos, ' . $e
        );
      }
      // Fin Try catch
    } else {
      $data = array(
        'status' => 'error',
        'code' => '404',
        'message' => 'El usuario no está identificado'
      );
    }

    return response()->json($data, $data['code']);
  }

  public function sendEmailAvailableVehicle(){
    $logs = Log::where("process", "vehiculo-disponible")->whereNotNull("page")->get();
    $data = array(
      "code" => 200,
      "status" => "success",
      "emails_enviados" => 0
    );
    foreach( $logs as $log ){
      $notifications = Notification::where("vehicle_id", $log->page)->get();
      $vehicle = Vehicle::where("id", $log->page)->first();
      foreach( $notifications as $notification ){        
        if( is_object($notification) && is_object($vehicle) ){
          $client = $notification->client()->first();
          $user = is_object( $client ) ? $client->user()->first() : null;

          \EmailHelper::Email_VehicleAvailable( $user->email, $user->name, $user->surname, $vehicle->name, $vehicle->vin );
          $data["emails_enviados"] = $data["emails_enviados"] + 1;
        }
      }
      $log->page = null;
      $log->save();
    }
    return response()->json($data, $data['code']);
  }

  public function sendEmailSaleVehicle(){
    $logs = Log::where("process", "vehiculo-vendido")->whereNotNull("page")->get();
    $data = array(
      "code" => 200,
      "status" => "success",
      "emails_enviados" => 0
    );

    foreach( $logs as $log ){
      $notifications = Notification::where("vehicle_id", $log->page)->get();
      $vehicle = Vehicle::where("id", $log->page)->first();
      foreach( $notifications as $notification ){        
        if( is_object($notification) && is_object($vehicle) ){
          $client = $notification->client()->first();
          $user = is_object( $client ) ? $client->user()->first() : null;

          \EmailHelper::Email_VehicleSale( $user->email, $user->name, $user->surname, $vehicle->name, $vehicle->vin );
          
          $data["emails_enviados"] = $data["emails_enviados"] + 1;
        }
      }
      $log->page = null;
      $log->save();
    }
    return response()->json($data, $data['code']);
  }
}