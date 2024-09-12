<?php

namespace App\Http\Controllers;

use App\Exports\PrintValuationsExport;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Http\Request;
use App\Models\Sell_your_car;
use App\Models\Spare_part;

class Spare_partController extends Controller
{
    public function index() { }

    public function store(Request $request) {        
        if(is_array($request->all())){
            //especificacion de tipado y campos requeridos 
            $rules = [
                'name' =>'required|max:255|string',
                'amount' =>'required|integer',
                'hours' =>'required|numeric',
                'type_part' => 'in:original,generic,used',
                'priceOriginal' => 'numeric',                
                'timeOriginal' => 'date',
                'priceGeneric' => 'numeric',    
                'timeGeneric' => 'date',
                'priceUsed' => 'numeric',
                'timeUsed' => 'date',              
                'sell_your_car_id' => 'required|exists:sell_your_cars,id'
            ];

            try {
                //validacion de tipado y campos requeridos 
                $validator = \Validator::make($request->all(), $rules);

                if($validator->fails()){
                    //existio un error en los campos enviados 
                    $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'errors'  => $validator->errors()->all()
                    );
                }else{                                  
                    $spare_part = new Spare_part();

                    $spare_part->name = $request->name;
                    $spare_part->amount = $request->amount;
                    $spare_part->hours = $request->hours;
                    $spare_part->type_part = $request->type_part;

                    if (!is_null($request->priceOriginal)) {
                        $spare_part->priceOriginal = $request->priceOriginal;
                        $spare_part->timeOriginal = $request->timeOriginal;
                    }

                    if (!is_null($request->priceGeneric)) {
                        $spare_part->priceGeneric = $request->priceGeneric;
                        $spare_part->timeGeneric = $request->timeGeneric;
                    }

                    if (!is_null($request->priceUsed)) {
                        $spare_part->priceUsed = $request->priceUsed;
                        $spare_part->timeUsed = $request->timeUsed;
                    }

                    $spare_part->sell_your_car_id = $request->sell_your_car_id;
                    $spare_part->save();

                    $isLastSparePart = $request->has('is_last') && $request->is_last === true;
                    if ($isLastSparePart) {
                        \EmailHelper::sendMailSpareParts('vguzman@chevroletbalderrama.com', $spare_part->name, $spare_part->amount, $spare_part->sell_your_car_id);
                    }

                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'La refacción ha sido creada correctamente'
                    );                
                }
            }catch(Exception $e){
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
                'message'  => "Ocurrio un error"
            );
        }
            
        return response()->json($data, $data['code']);
    }

    public function update(Request $request, $id) {
        if(is_array($request->all())){
            //especificacion de tipado y campos requeridos 
            $rules = [
                'type_part' => 'in:original,generic,used',            
                'status' => 'in:approved,pre approved,on hold,rejected',            
                'priceOriginal' => 'required|numeric',
                'timeOriginal' => 'date',
                'priceGeneric' => 'required|numeric',
                'timeGeneric' => 'date',
                'priceUsed' => 'required|numeric',
                'timeUsed' => 'date',
                'comments' => 'string'
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
                } else {
                    // Update spare part
                    $spare_part = Spare_part::find($id);
                                        
                    if (!is_null($request->type_part)) {
                        $spare_part->type_part = $request->type_part;
                    }

                    if (!is_null($request->status)) {
                        $spare_part->status = $request->status;             
                    }

                    $spare_part->priceOriginal = $request->priceOriginal;
                    $spare_part->timeOriginal = $request->timeOriginal;
                    $spare_part->priceGeneric = $request->priceGeneric;
                    $spare_part->timeGeneric = $request->timeGeneric;
                    $spare_part->priceUsed = $request->priceUsed;
                    $spare_part->timeUsed = $request->timeUsed;

                    if (!is_null($request->comments)) {
                        $spare_part->comments = $request->comments;             
                    }

                    if ($spare_part->save()) {                    
                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'La refacción ha sido actualizada',
                            'spare_part' => $spare_part
                        );                
                    } else {
                        $data = array(
                            'code'   => '400',
                            'status' => 'error',
                            'message' => 'La refacción no se actualizo correctamente.'
                        );                
                    }
                }
            } catch(Exception $e) {
              $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'Los datos enviados no son correctos, ' . $e
              );
            }      
        } else {
            $data = array(
                'status' => 'error',
                'code'   => '200',
                'message'  => "Ocurrio un error"
            );
        }
            
        return response()->json($data, $data['code']);
    }

    public function destroy($id) { }

    public function updateStatus( Request $request, $id ){
        if(is_array($request->all())){
            //especificacion de tipado y campos requeridos 
            $rules = [
                'status' => 'required|in:approved,rejected,on hold',
            ];
      
            try{
              //validacion de tipado y campos requeridos 
              $validator = \Validator::make($request->all(), $rules);
      
              if($validator->fails()){
                //existio un error en los campos enviados 
                $data = array(
                  'status' => 'error',
                  'code'   => '200',
                  'errors'  => $validator->errors()->all()
                );
              }else{                                
                // Crear el Source
                $spare_part = Spare_part::find($id);
                $spare_part->status = $request->status;             
                $spare_part->save();   
      
                $data = array(
                  'status' => 'success',
                  'code'   => '200',
                  'message' => 'La refacción ha sido actualizada'
                );                
              }
            }catch(Exception $e){
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
                'message'  => "Ocurrio un error"
            );
        }
            
        return response()->json($data, $data['code']);
    }

    public function getSpare_partsBySellYourCar( int $sell_your_car_id ){
        $sell_your_car_id = Sell_your_car::find($sell_your_car_id);

        if( is_object($sell_your_car_id) ){
            $spare_parts = $sell_your_car_id->spare_parts()->get();
            $dist = $sell_your_car_id->check_list()->get();
            $data = array(
                'code' => 200,
                'status' => 'success',
                'distributor' => $dist,
                'spare_parts' => $spare_parts
            );
        }else{
            $data = array(
                'code' => 200,
                'status' => 'error',
                'message' => 'Este registro no existe'
            );
        }
        return response()->json( $data, $data['code']);
    }

    public function getValuationsCount(){
        $countTotal = Sell_your_car::count();
        $countStandBy = Sell_your_car::where('status', 'stand_by')
                                        // ->whereYear('created_at', date('2022'))
                                        // ->whereMonth('created_at', date('09'))
                                        ->whereYear('created_at', date('Y'))
                                        ->whereMonth('created_at', date('m'))
                                        ->count();
        $countToValued = Sell_your_car::where('status', 'to_valued')
                                        ->whereYear('created_at', date('Y'))
                                        ->whereMonth('created_at', date('m'))
                                        ->count();
        $countValued = Sell_your_car::where('status', 'valued')
                                        ->whereYear('created_at', date('Y'))
                                        ->whereMonth('created_at', date('m'))
                                        ->count();
        $data = array(
            'code' => 200,
            'status' => 'success',
            'count_total' => $countTotal,
            'count_standBy' => $countStandBy,
            'count_to_valued' => $countToValued,
            'count_valued' => $countValued
        );
        return response()->json( $data, $data['code']);
    }

    public function getNowPrintValuation(String $datePrint, String $dateEndPrint){
        $from = date($datePrint);
        $to = date($dateEndPrint);

        $dateNowPrintValuation = Sell_your_car::whereIn('sell_your_cars.status', ['stand_by', 'to_valued', 'valued'])
                                                ->join('sell_your_car_valuator','sell_your_car_valuator.sell_your_car_id', '=', 'sell_your_cars.id')
                                                ->join('valuators', 'valuators.id', '=', 'sell_your_car_valuator.valuator_id')
                                                ->join('users', 'users.id', '=', 'valuators.user_id')
                                                ->join('check_lists', 'check_lists.sell_your_car_id', '=', 'sell_your_cars.id')
                                                ->join('users AS usersT', 'usersT.id', '=', 'check_lists.technician_id')
                                                // ->whereYear('sell_your_cars.created_at', date('Y'))
                                                // ->whereMonth('sell_your_cars.created_at', date('m'))
                                                ->whereBetween('sell_your_cars.created_at', [$from, $to])
                                                ->select(
                                                    'users.name AS name', 'users.surname AS surname', 'users.id AS id'
                                                    )
                                                ->distinct()
                                                ->get();
        $data = array(
            'code' => 200,
            'status' => 'success',
            'valuators' => $dateNowPrintValuation
        );
        return response()->json( $data, $data['code']);
    }

    public function export(int $valuatorid = 0, String $datePrint = '', String $dateEndPrint = '' ){
        $export = new PrintValuationsExport();
        $export->setIdUser($valuatorid, $datePrint, $dateEndPrint);
        return Excel::download($export, 'valuations_print.csv');
    }

}
