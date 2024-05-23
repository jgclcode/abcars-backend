<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sale;


class SaleController extends Controller
{
    public function index() {
        $sale = Sale::paginate( 10 );
        $data = array (
            'code' => 200,
            'status' => 'success',
            'sales' => $sale
        );
        return response()->json($data, $data['code']);
    }

    public function store(Request $request) {
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        //verificacion de datos que este lleno 
        if (is_array($request->all()) && $checkToken ) {
                //especificacion de tipado y campos requeridos 
                $rules = [
                    'offer' => 'max:255|string',
                    'adviser_id'=> 'required|exists:advisers,id',
                    'saleDate' => 'date',
                    'totalInventoryDays' => 'integer',
                    'expenses' => 'numeric',   
                    'statusExpenses' => 'numeric',   
                    'grossProfit' => 'numeric',   
                    'iva' => 'numeric',   
                    'netProfit' => 'numeric',   
                    'cashSale' => 'numeric',   
                    'financial' => 'max:255|string',
                    'apartDate' => 'date',
                    'hitch' => 'numeric',   
                    'amountFinance' => 'numeric',   
                    'insuranceSale' => 'numeric',   
                    'insuranceAmount' => 'numeric',   
                    'financingCommission' => 'numeric',   
                    'statusCommision' => 'max:255|string',
                    'financialCommission' => 'numeric',   
                    'totalNetIncome' => 'numeric',   
                    'billingDate' => 'date',
                    'dateDelivery' => 'date',
                    'client_id' => 'required|exists:clients,id',    
                    'vehicle_id' => 'required|exists:vehicles,id'    
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
                        // Crear el sale
                        $sale = new Sale();
                        $sale->offer = $request->offer; 
                        $sale->adviser_id = $request->adviser_id; 
                        $sale->saleDate = $request->saleDate; 
                        $sale->totalInventoryDays = $request->totalInventoryDays; 
                        $sale->expenses = $request->expenses; 
                        $sale->statusExpenses = $request->statusExpenses; 
                        $sale->grossProfit = $request->grossProfit; 
                        $sale->iva = $request->iva; 
                        $sale->netProfit = $request->netProfit; 
                        $sale->cashSale = $request->cashSale; 
                        $sale->financial = $request->financial; 
                        $sale->apartDate = $request->apartDate; 
                        $sale->hitch = $request->hitch; 
                        $sale->amountFinance = $request->amountFinance; 
                        $sale->insuranceSale = $request->insuranceSale; 
                        $sale->insuranceAmount = $request->insuranceAmount; 
                        $sale->financingCommission = $request->financingCommission; 
                        $sale->statusCommision = $request->statusCommision; 
                        $sale->financialCommission = $request->financialCommission; 
                        $sale->totalNetIncome = $request->totalNetIncome; 
                        $sale->billingDate = $request->billingDate; 
                        $sale->dateDelivery = $request->dateDelivery; 
                        $sale->client_id = $request->client_id; 
                        $sale->vehicle_id = $request->vehicle_id; 
                        $sale->save(); 

                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'el sale se creo exitosamente'
                        );                
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
            $rules = [
                'offer' => 'max:255|string',
                'adviser_id'=> 'required|exists:advisers,id',
                'saleDate' => 'date',
                'totalInventoryDays' => 'integer',
                'expenses' => 'numeric',   
                'statusExpenses' => 'numeric',   
                'grossProfit' => 'numeric',   
                'iva' => 'numeric',   
                'netProfit' => 'numeric',   
                'cashSale' => 'numeric',   
                'financial' => 'max:255|string',
                'apartDate' => 'date',
                'hitch' => 'numeric',   
                'amountFinance' => 'numeric',   
                'insuranceSale' => 'numeric',   
                'insuranceAmount' => 'numeric',   
                'financingCommission' => 'numeric',   
                'statusCommision' => 'max:255|string',
                'financialCommission' => 'numeric',   
                'totalNetIncome' => 'numeric',   
                'billingDate' => 'date',
                'dateDelivery' => 'date',
                'client_id' => 'required|exists:clients,id',    
                'vehicle_id' => 'required|exists:vehicles,id' 
            ];

            try {
                // Obtener sale
                $validator = \Validator::make($request->all(), $rules);

                if($validator->fails()){
                    // error en los datos ingresados
                    $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'errors'  => $validator->errors()->all()
                    );
                }else{            
                    $sale = Sale::find( $id );
                    
                    if( is_object($sale) && !empty($sale)){                
                        $sale->offer = $request->offer; 
                        $sale->adviser_id = $request->adviser_id; 
                        $sale->saleDate = $request->saleDate; 
                        $sale->totalInventoryDays = $request->totalInventoryDays; 
                        $sale->expenses = $request->expenses; 
                        $sale->statusExpenses = $request->statusExpenses; 
                        $sale->grossProfit = $request->grossProfit; 
                        $sale->iva = $request->iva; 
                        $sale->netProfit = $request->netProfit; 
                        $sale->cashSale = $request->cashSale; 
                        $sale->financial = $request->financial; 
                        $sale->apartDate = $request->apartDate; 
                        $sale->hitch = $request->hitch; 
                        $sale->amountFinance = $request->amountFinance; 
                        $sale->insuranceSale = $request->insuranceSale; 
                        $sale->insuranceAmount = $request->insuranceAmount; 
                        $sale->financingCommission = $request->financingCommission; 
                        $sale->statusCommision = $request->statusCommision; 
                        $sale->financialCommission = $request->financialCommission; 
                        $sale->totalNetIncome = $request->totalNetIncome; 
                        $sale->billingDate = $request->billingDate; 
                        $sale->dateDelivery = $request->dateDelivery; 
                        $sale->client_id = $request->client_id; 
                        $sale->vehicle_id = $request->vehicle_id; 
                        $sale->save();                           

                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'sale se ha actualizado correctamente'                
                        );
                    }else{
                        $data = array(
                            'status' => 'error',
                            'code'   => '200',
                            'message' => 'La sale no existe'
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

        if ( is_array($request->all()) && $checkToken ) {
            // Inicio Try catch
            try{
                $sale = Sale::find( $id );
                if(is_object($sale) && !is_null($sale) ){

                    try{
                        $sale->delete();

                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'sale ha sido eliminada correctamente'
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
                    'message' => 'El id del la sale no existe'
                    );
                }

            }catch(Exception $e){
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
