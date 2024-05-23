<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Policie;
use Barryvdh\DomPDF\Facade as PDF;
use Carbon\Carbon;
use App\Models\Client;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Brand;


class PolicieController extends Controller
{
 
    public function index(){       
        $Policie = Policie::paginate( 10 );
        $data = array(
          'code' => 200,
          'status' => 'success',
          'Policie' => $Policie
        );
    
        return response()->json($data, $data['code']);
    }
    


      public function store(Request $request){
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
    
        //verificacion de datos que este lleno 
        if(is_array($request->all()) ) {
          //especificacion de tipado y campos requeridos 
          $rules = [              
              'signature_place' => 'required|max:255|string',                    
              'warranty_period' => 'required|max:255|string',                                   
              'start_date_gm'=> 'required|date',
              'start_date_ge'=> 'required|date',
              'ending_date_gm'=> 'required|date',
              'ending_date_ge'=> 'required|date',
              'km_next_service' => 'required|int',                    
              'km_last_service' => 'required|int',                    
              'business' => 'required|max:255|string',                    
              'buyer' => 'required|max:255|string',                    
              'client_id' => 'required|exists:clients,id',                        
              'vehicle_id' => 'required|exists:vehicles,id'                      
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
              // Crear el Policie
              $policie = new Policie();
              $policie->signature_date = Carbon::now()->format('Y-m-d');
              $policie->signature_place = $request->signature_place;
              $policie->warranty_period = $request->warranty_period;
              $policie->id_warranty = rand(100000000, 999999999);
              $policie->start_date_gm = $request->start_date_gm;
              $policie->start_date_ge = $request->start_date_ge;
              $policie->ending_date_gm = $request->ending_date_gm;
              $policie->ending_date_ge = $request->ending_date_ge;
              $policie->km_next_service = $request->km_next_service;
              $policie->km_last_service = $request->km_last_service;
              $policie->business = $request->business;
              $policie->buyer = $request->buyer;
              $policie->client_id = $request->client_id;
              $policie->vehicle_id = $request->vehicle_id;
              $policie->save();  
    
              $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'policie creado exitosamente',
                'policie' => $policie
              );                
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
            'message'  => "El usuario no esta identificado"
          );
        }
        
        return response()->json($data, $data['code']);
      }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id){
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
    
        if( is_array($request->all()) && $checkToken ){
    
          $rules =[
            'signature_date' => 'required|date',
            'signature_place' => 'required|max:255|string',                    
            'warranty_period' => 'required|max:255|string',                    
            'id_warranty' => 'required|int',  
            'start_date_gm'=> 'required|date',
            'start_date_ge'=> 'required|date',
            'ending_date_gm'=> 'required|date',
            'ending_date_ge'=> 'required|date',
            'km_next_service' => 'required|max:255|string',                    
            'km_last_service' => 'required|max:255|string',                    
            'business' => 'required|max:255|string',                    
            'buyer' => 'required|max:255|string',                    
            'client_id' => 'required|exists:clients,id',                        
            'vehicle_id' => 'required|exists:vehicles,id'                            
          ];
    
          try {
            // Obtener package
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails() ) {
              // error en los datos ingresados
              $data = array(
                'status' => 'error',
                'code'   => '200',
                'errors'  => $validator->errors()->all()
              );
            }else{            
              $policie = Policie::find( $id );
    
              if( is_object($policie) && !empty($policie) ){                
                $policie->signature_date = $request->signature_date;
                $policie->signature_place = $request->signature_place;
                $policie->warranty_period = $request->warranty_period;
                $policie->id_warranty = $request->id_warranty;
                $policie->start_date_gm = $request->start_date_gm;
                $policie->start_date_ge = $request->start_date_ge;
                $policie->ending_date_gm = $request->ending_date_gm;
                $policie->ending_date_ge = $request->ending_date_ge;
                $policie->km_next_service = $request->km_next_service;
                $policie->km_last_service = $request->km_last_service;
                $policie->business = $request->business;
                $policie->buyer = $request->buyer;
                $policie->client_id = $request->client_id;
                $policie->vehicle_id = $request->vehicle_id;
                $policie->save();  
    
                $data = array(
                  'status' => 'success',
                  'code'   => '200',
                  'message' => 'policie  actualizado correctamente'               
                );
              }else{
                  $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'message' => 'id de policie no existe'
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id){
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
    
        if( is_array($request->all()) && $checkToken ){
          // Inicio Try catch
          try{
            $Policie = Policie::find( $id );
    
            if( is_object($Policie) && !is_null($Policie) ){
    
              try{
                  $Policie->delete();
    
                  $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'message' => 'Policie ha sido eliminado correctamente'
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
                  'status' => 'error',
                  'code'   => '404',
                  'message' => 'El id del Policie no existe'
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

    public function getPoliciebyid($id ){
      $policie = Policie::firstWhere('id', $id);

      if( is_object($policie) && !is_null($policie) ){

        $client= Client::firstWhere('id', $policie->client_id);
        $user= User::firstWhere('id', $client->user_id);
        $vehicle= Vehicle::firstWhere('id', $policie->vehicle_id);
        $vehicle->load(['carmodel']);
        $marca= Brand::firstWhere('id', $vehicle->carmodel->brand_id);

        $report = PDF::loadView('policie.policie',compact(['policie','client','user','vehicle','marca']));
 
        return ($report->download('policies.pdf'));
      }
      else{
        
        $data = array(
          'status' => 'error',
          'code'   => '404',
          'message' => 'El moro se la come, cualquier duda preguntenle a el'
        );
        
        return response()->json($data, $data['code']); 
      }   
    }
}
