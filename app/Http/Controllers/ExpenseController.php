<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;


class ExpenseController extends Controller
{
    public function index() {
        $expense = Expense::paginate( 10 );
        $data = array (
            'code' => 200,
            'status' => 'success',
            'expenses' => $expense
        );
        return response()->json($data, $data['code']);
    }

    public function store(Request $request){
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        //verificacion de datos que este lleno 
        if (is_array($request->all()) && $checkToken){
            //especificacion de tipado y campos requeridos 
            $rules =[
                'takeCommission'=> 'numeric',
                'statusCommision'=> 'string',
                'expenses'=> 'numeric',
                'serviceType'=> 'string',
                'serviceDate'=> 'date',
                'expenseMechanicService'=> 'numeric',
                'serviceHyp'=> 'string',
                'hypServiceDate'=> 'date',
                'expenseHypService'=> 'numeric',
                'deliveryConditioning'=> 'numeric',
                'floorRegistrationDate'=> 'date',
                'expenseWarranty'=> 'numeric',
                'expenseGas'=> 'numeric',
                'advertisingExpenses'=> 'numeric',
                'takeTotalCost'=> 'numeric',
                ' partner'=> 'string'
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
                    // Crear el expense
                    $expense = new Expense();
                    $expense->takeCommission = $request->takeCommission; 
                    $expense->statusCommision = $request->statusCommision; 
                    $expense->expenses = $request->expenses;                            
                    $expense->serviceType = $request->serviceType; 
                    $expense->serviceDate = $request->serviceDate; 
                    $expense->expenseMechanicService = $request->expenseMechanicService;
                    $expense->serviceHyp = $request->serviceHyp;                            
                    $expense->hypServiceDate = $request->hypServiceDate;                            
                    $expense->expenseHypService = $request->expenseHypService;                            
                    $expense->deliveryConditioning = $request->deliveryConditioning;                            
                    $expense->floorRegistrationDate = $request->floorRegistrationDate;                            
                    $expense->expenseWarranty = $request->expenseWarranty;                            
                    $expense->expenseGas = $request->expenseGas;                            
                    $expense->advertisingExpenses = $request->advertisingExpenses;                            
                    $expense->takeTotalCost = $request->takeTotalCost;                            
                    $expense->partner = $request->partner;                            
                    $expense->save(); 

                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'el expense se creo exitosamente'
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

        if ( is_array($request->all()) && $checkToken){
            $rules = [
                'takeCommission'=> 'numeric',
                'statusCommision'=> 'string',
                'expenses'=> 'numeric',
                'serviceType'=> 'string',
                'serviceDate'=> 'date',
                'expenseMechanicService'=> 'numeric',
                'serviceHyp'=> 'string',
                'hypServiceDate'=> 'date',
                'expenseHypService'=> 'numeric',
                'deliveryConditioning'=> 'numeric',
                'floorRegistrationDate'=> 'date',
                'expenseWarranty'=> 'numeric',
                'expenseGas'=> 'numeric',
                'advertisingExpenses'=> 'numeric',
                'takeTotalCost'=> 'numeric',
               ' partner'=> 'string'
            ];
            try {
                // Obtener expense
                $validator = \Validator::make($request->all(), $rules);

                if ($validator->fails()){
                    // error en los datos ingresados
                    $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'errors'  => $validator->errors()->all()
                    );
                }else{            
                    $expense = expense::find( $id );
                    if( is_object($expense) && !empty($expense) ){                
                        $expense->takeCommission = $request->takeCommission; 
                        $expense->statusCommision = $request->statusCommision; 
                        $expense->expenses = $request->expenses;                            
                        $expense->serviceType = $request->serviceType; 
                        $expense->serviceDate = $request->serviceDate; 
                        $expense->expenseMechanicService = $request->expenseMechanicService;
                        $expense->serviceHyp = $request->serviceHyp;                            
                        $expense->hypServiceDate = $request->hypServiceDate;                            
                        $expense->expenseHypService = $request->expenseHypService;                            
                        $expense->deliveryConditioning = $request->deliveryConditioning;                            
                        $expense->floorRegistrationDate = $request->floorRegistrationDate;                            
                        $expense->expenseWarranty = $request->expenseWarranty;                            
                        $expense->expenseGas = $request->expenseGas;                            
                        $expense->advertisingExpenses = $request->advertisingExpenses;                            
                        $expense->takeTotalCost = $request->takeTotalCost;                            
                        $expense->partner = $request->partner;                            
                        $expense->save(); 

                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'expense se ha actualizado correctamente'                
                        );
                    }else{
                        $data = array(
                            'status' => 'error',
                            'code'   => '200',
                            'message' => 'La expense no existe'
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
                $expense = Expense::find( $id );
                if( is_object($expense) && !is_null($expense)){
                    try{
                        $expense->delete();

                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'expense ha sido eliminada correctamente'
                        );
                    } catch (\Illuminate\Database\QueryException $e){
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
                    'message' => 'El id del la expense no existe'
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
