<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Requests;
use App\Models\Carmodel;
use App\Models\Brand;
use App\Models\Client;
use App\Models\User;



class RequestController extends Controller
{
    public function index(){       
        $request = Requests::paginate(10);
        $request->load(['carmodel', 'brand', 'client']);

        $data = array(
            'code' => 200,
            'status' => 'success',
            'request' => $request
        );

        return response()->json($data, $data['code']);    
    }

    public function store(Request $request){
        $rules = [
            'status' => 'in:active,inactive',  
            'year' => 'required|integer', 
            'comment' => 'required|string', 
            'transmission' => 'required|in:automatico,manual,cvt,triptronic',  
            'mileage' => 'required|integer',
            #'release' => 'required|in:inmediatamente,en un mes,en tres meses,en seis meses',
            #'type_purchase' => 'required|in:contado,financiamiento',
            #'amount_pay' => 'required|integer',
            'version' => 'required|max:255|string', 
            'carmodel_id' => 'required|exists:carmodels,id',
            'brand_id' => 'required|exists:brands,id',
            'client_id' => 'required|exists:clients,id'             
        ];

        try{
            //validacion de tipado y campos requeridos 
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()){
                //existio un error en los campos enviados 
                $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'errors'  => $validator->errors()->all() 
                );
            }else{   
                $modelo = Carmodel::select("name")->where('id', $request->carmodel_id)->get()->first();
                $brand = Brand::select("name")->where('id',  $request->brand_id)->get()->first();
                $client = Client::select("user_id")->where('id',  $request->client_id)->get()->first();
                $user = User::where('id',  $client->user_id)->get()->first();

                // Crear el request
                $requests = new Requests();
                $requests->status = 'active';
                $requests->year = $request->year;
                $requests->comment = $request->comment;
                $requests->transmission = $request->transmission;
                $requests->mileage = $request->mileage;
                // $requests->release = $request->release;
                // $requests->type_purchase = $request->type_purchase;
                // $requests->amount_pay = $request->amount_pay;
                $requests->version = $request->version;
                $requests->carmodel_id = $request->carmodel_id;
                $requests->brand_id = $request->brand_id;
                $requests->client_id = $request->client_id;
                
                $requests->save();
                  
                #\EmailHelper::sendEmail_Request( $user->email, $user->name, $user->surname, $brand->name, $modelo->name, $request->year, $request->version, $request->release, $request->type_purchase, $request->amount_pay);
                // \EmailHelper::sendEmail_Request( $user->email, $user->name, $user->surname, $brand->name, $modelo->name, $request->year, $request->version);

                $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'message' => 'request creado exitosamente'
                );                         
            }
        }catch (Exception $e){
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

        if ( is_array ($request->all()) && $checkToken ) {
            $rules = [
                'status' => 'in:active,inactive',  
                'year' => 'required|integer', 
                'comment' => 'required|string', 
                'transmission' => 'required|in:automatico,manual,cvt,triptronic',  
                'release' => 'required|in:inmediatamente,en un mes,en tres meses,en seis meses',  
                'type_purchase' => 'required|in:contado,financiamiento',  
                'mileage' => 'required|integer', 
                'amount_pay' => 'required|integer', 
                'version' => 'required|max:255|string', 
                'carmodel_id' => 'required|exists:carmodels,id',
                'brand_id' => 'required|exists:brands,id',
                'client_id' => 'required|exists:clients,id'      
            ];

            try{
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
                    $requests = Requests::find( $id );

                    if( is_object($requests) && !empty($requests) ){                
                        $requests->status = $request->status;
                        $requests->year = $request->year;
                        $requests->comment = $request->comment;
                        $requests->transmission = $request->transmission;
                        $requests->release = $request->release;
                        $requests->type_purchase = $request->type_purchase;
                        $requests->mileage = $request->mileage;
                        $requests->amount_pay = $request->amount_pay;
                        $requests->version = $request->version;
                        $requests->carmodel_id = $request->carmodel_id;
                        $requests->brand_id = $request->brand_id;
                        $requests->client_id = $request->client_id;
                        $requests->save();  

                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'request actualizado correctamente',
                            'request' => $requests
                        );

                    }else{
                        $data = array(
                            'status' => 'error',
                            'code'   => '200',
                            'message' => 'id de request no existe'
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
            'message' => 'El usuario no está identificado');
        }
  
        return response()->json($data, $data['code']); 
    }

    public function destroy(Request $request, $id){
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if ( is_array ($request->all()) && $checkToken ){
            // Inicio Try catch
            try{
                $Requests = Requests::find( $id );

                if( is_object($Requests) && !is_null($Requests)){

                    try{
                        $Requests->delete();
                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'Requests ha sido eliminado correctamente'
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
                        'message' => 'El id del Requests no existe'
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
}
