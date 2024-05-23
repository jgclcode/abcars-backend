<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Purchase;


class PurchaseController extends Controller
{
    public function index(){
        $Purchase = Purchase::paginate( 10 );
        $data = array (
            'code' => 200,
            'status' => 'success',
            'Purchases' => $Purchase
        );
        return response()->json($data, $data['code']);
    }

    public function store(Request $request){
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        //verificacion de datos que este lleno 
        if(is_array($request->all()) && $checkToken ) {
            //especificacion de tipado y campos requeridos 
            $rules = [
                'purchaseDate' => 'date',
                'inventoryCode' => 'string', 
                'type' => 'max:255|string',
                'typePurchase' => 'required|in:tac,canal desabasto,compra directa',
                'status' => 'max:255|string',
                'socialReason' => 'required|in:moral,fisica',
                'realPrice' => 'numeric'
            ];

            try{
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
                    // Crear el Purchase
                    $purchase = new Purchase();
                    $purchase->purchaseDate = $request->purchaseDate;
                    $purchase->inventoryCode = $request->inventoryCode;                
                    $purchase->type = $request->type;                
                    $purchase->typePurchase = $request->typePurchase;  
                    $purchase->status = $request->status;                
                    $purchase->socialReason = $request->socialReason;                
                    $purchase->realPrice = $request->realPrice;                              
                    $purchase->save(); 

                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'el Purchase se creo exitosamente'
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

    public function update(Request $request, $id){
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if( is_array($request->all()) && $checkToken ){
            $rules = [
                'purchaseDate' => 'date',
                'inventoryCode' => 'string', 
                'type' => 'max:255|string',
                'typePurchase' => 'required|in:tac,canal desabasto,compra directa',
                'status' => 'max:255|string',
                'socialReason' => 'required|in:moral,fisica',
                'realPrice' => 'numeric'
            ];

            try {
                // Obtener Purchase
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()){
                    // error en los datos ingresados
                    $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'errors'  => $validator->errors()->all()
                    );
                }else{            
                    $purchase = Purchase::find( $id );

                    if( is_object($purchase) && !empty($purchase) ){                
                        $purchase->purchaseDate = $request->purchaseDate;
                        $purchase->inventoryCode = $request->inventoryCode;                
                        $purchase->type = $request->type;                
                        $purchase->typePurchase = $request->typePurchase;  
                        $purchase->status = $request->status;                
                        $purchase->socialReason = $request->socialReason;                
                        $purchase->realPrice = $request->realPrice;                              
                        $purchase->save(); 

                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'Purchase se ha actualizado correctamente'                
                        );
                    }else{
                        $data = array(
                            'status' => 'error',
                            'code'   => '200',
                            'message' => 'La Purchase no existe'
                        );
                    }            
                }
            }catch(Exception $e){
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

    public function destroy(Request $request, $id) {
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if( is_array($request->all()) && $checkToken ){
            // Inicio Try catch
            try{
                $purchase = Purchase::find( $id );
                if( is_object($purchase) && !is_null($purchase) ){

                    try{

                        $purchase->delete();

                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'Purchase ha sido eliminada correctamente'
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
                        'message' => 'El id del la Purchase no existe'
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
}
