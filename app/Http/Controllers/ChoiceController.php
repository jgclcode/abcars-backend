<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use App\Models\Choice;
use App\Models\Vehicle;

class ChoiceController extends Controller
{

  public function index()
  {
      $choice = Choice::paginate( 10 );
      $data = array(
        'code' => 200,
        'status' => 'success',
        'Choice' => $choice
      );

      return response()->json($data, $data['code']);
  }

  public function store(Request $request)
  {

      // Comprobar si el usuario esta identificado
      $token = $request->header('Authorization');
      $jwtAuth = new \App\Helpers\JwtAuth();
      $checkToken = $jwtAuth->checkToken($token);

      //verificacion de datos que este lleno 
      if (is_array($request->all()) ) {
          //especificacion de tipado y campos requeridos 
          $rules =[
              'amount' => 'required|numeric',
              'namePayment' => 'required|max:255|string',                    
              'status' => 'required|in:apartado,devolución,progreso,pendiente,cancelado',                    
              'reference' => 'required|max:255|string',     
              'amountDate' => 'required|date',                    
              'vehicle_id' => 'required|exists:vehicles,id',                    
              'client_id' => 'required|exists:clients,id',
              'rewards' => 'max:255|string'
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
                  $choice = new Choice();
                  $choice->amount = $request->amount;
                  $choice->namePayment = $request->namePayment;
                  $choice->status = $request->status;
                  $choice->reference = $request->reference;
                  $choice->amountDate = $request->amountDate;
                  $choice->vehicle_id = $request->vehicle_id;
                  $choice->client_id = $request->client_id;
                  $choice->rewards = $request->rewards;
                  $choice->save();                
                  $data = array(
                      'status' => 'success',
                      'code'   => '200',
                      'message' => 'El reservado se creo exitosamente',
                      'choice' => $choice
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

  public function update(Request $request, $id) {
      // Comprobar si el usuario esta identificado
      $token = $request->header('Authorization');
      $jwtAuth = new \App\Helpers\JwtAuth();
      $checkToken = $jwtAuth->checkToken($token);
  
      if (is_array($request->all()) && $checkToken){
        $rules = [
          'amount' => 'required|numeric',
          'namePayment' => 'required|max:255|string',                    
          'status' => 'required|in:apartado,devolución,progreso,pendiente,cancelado',                    
          'reference' => 'required|max:255|string',     
          'amountDate' => 'required|date',                    
          'vehicle_id' => 'required|exists:vehicles,id',                    
          'client_id' => 'required|exists:clients,id'                            
        ];
  
        try{
          $validator = \Validator::make($request->all(), $rules);
  
          if($validator->fails()){
            // error en los datos ingresados
            $data = array(
              'status' => 'error',
              'code'   => '200',
              'errors'  => $validator->errors()->all()
            );
          }else{            
            $choice = Choice::find( $id );
  
            if(is_object($choice) && !empty($choice)){                
              $choice->amount = $request->amount;
              $choice->namePayment = $request->namePayment;
              $choice->status = $request->status;
              $choice->reference = $request->reference;
              $choice->amountDate = $request->amountDate;
              $choice->vehicle_id = $request->vehicle_id;
              $choice->client_id = $request->client_id;
              $choice->save();      
  
              $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'reservado se ha actualizado correctamente'               
              );
            }else{
              $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'ID de reservado no existe'
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
    
      if ( is_array($request->all()) && $checkToken ){
          // Inicio Try catch
          try {
            $choice = Choice::find( $id );
  
            if( is_object($choice) && !is_null($choice)){
  
              try{
                $choice->delete();
                $data = array(
                  'status' => 'success',
                  'code'   => '200',
                  'message' => 'El reservado ha sido eliminado correctamente'
                );
              }catch (\Illuminate\Database\QueryException $e){
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
                'message' => 'El id del reservado no existe'
              );
            }
          }catch(Exception $e){
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
  
  public function getChoices($user_id){
      $client = Client::where( 'user_id', $user_id )->first();

      $choice = Choice::where( 'client_id', $client->id)->paginate( 10 );
      $data = array(
          'code' => 200,
          'status' => 'success',
          'choice' => $choice
      );

      return response()->json($data, $data['code']);
  }

  public function getApartado($vin){
      $vehicle = Vehicle::where('vin', $vin)->first();
      $choice = Choice::where( 'vehicle_id', $vehicle->id)->first();
      if($choice){
          $data = array(
              'code' => 200,
              'status' => 'success',
              'choice' => $choice
          );
      }else{
          $data = array(
              'status' => 'error',
              'code'   => 202,
              'message'  => "El vehículo no se encuentra"
          );
      }
      return response()->json($data, $data['code']);
  }

  public function getChoicesWithUser() {
    $choice = Choice::paginate(10);
    $choice->load(['vehicle', 'clientWithUser']);

    $data = array(
      'code' => 200,
      'status' => 'success',
      'Choice' => $choice
    );

    return response()->json($data, $data['code']);
  }

  public function getChoicesByClient($user_id){
    // Client
    $client = Client::where('user_id', $user_id)->first();

    if (is_object($client)) {
      // Get choices
      $choices = Choice::where('client_id', $client->id)->get();
      $choices->load('vehicle');

      if (is_object($choices)) {
        $data = array(
          'code' => 200,
          'status' => 'success',
          'choices' => $choices
        );
      }
    } else {
      $data = array(
        'code' => 404,
        'status' => 'error',
        'message' => 'Cliente no encontrado'
      );
    }

    return response()->json($data, $data['code']);
  }

  public function DeleteApart($vin){
    $vehicle = Vehicle::where('vin', $vin)->first();
    //verificar si el vin existe en el inventario xd
    if(is_object($vehicle)){
      $choice = Choice::where( 'vehicle_id', $vehicle->id)->first();
      if(is_object($choice)){
        //obtener informacion del cliente
        $cliente = User::where( 'id', $choice->client_id)->first();
        //aqui se debe de mandar el correos
        \EmailHelper::choice( $cliente->email, $cliente->name, $cliente->surname, $vehicle->name); 
        //aqui se cambia el apartado a inanctivo o se borra
        $choice->delete();
        $data = array(
          'code' => 200,
          'status' => 'error',
          'message' => 'El apartado se ha removido con exito'
        );
      }
      else{
        $data = array(
          'code' => 404,
          'status' => 'error',
          'message' => 'El vin no se encuentra apartado'
        );
      }
    }
    else{
      $data = array(
        'code' => 404,
        'status' => 'error',
        'message' => 'El vin no exite'
      );
    }
    return response()->json($data, $data['code']);  
  }

  public function apartar_y_desapartar( String $vin ,Request $request){

    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);
    if($checkToken){
      $vehicle = Vehicle::where('vin', $vin)->first();
      if( is_object( $vehicle ) && !is_null( $vehicle ) ){
        $choice = Choice::where( 'vehicle_id', $vehicle->id )->first();
  
        if( is_object( $choice ) && !is_null( $choice ) ){
          $choice->delete();
          $data = array(
            'status' => 'success',
            'code'   => 200,
            'message'  => "Vehículo desapartado correctamente"
          );
        }else{
          $choice = new Choice;
          $choice->amount = 0;
          $choice->namePayment = 'ninguno';
          $choice->status = 'apartado';
          $choice->reference = 'ninguno';
          $choice->amountDate = 'ninguno';
          $choice->vehicle_id = $vehicle->id;
          $choice->client_id = 1;
          $choice->rewards = 0;
          $choice->save();        
          $data = array(
            'status' => 'success',
            'code'   => 200,
            'message'  => "Vehículo apartado correctamente"
          );
        }
  
      }else{
        $data = array(
            'status' => 'error',
            'code'   => 200,
            'message'  => "El vehículo no se encuentra"
        );
      }
    }
    else{
      $data = array(
        'status' => 'error',
        'code'   => '200',
        'message'  => "El usuario no esta identificado"
    );
    }
      
    return response()->json($data, $data['code']);
  }

}
