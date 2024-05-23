<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Main;
use App\Models\Maintenance;

class MaintenanceController extends Controller
{   

    public function index(Request $request){

        $appointments = Maintenance::paginate( 10 );
        $data = array(
            'code' => 200,
            'status' => 'success',
            'appointments' => $appointments
        );

        return response()->json($data);
    }

    /**
     * Maintenance Appointment save and send to spread sheet
    */
    public function store(Request $request) {
        
        if (is_array($request->all())) {

            // Type and required fields
            $rules =[
                'name' => 'required|max:255|string',
                'surname' => 'required|max:255|string',
                'email' => 'required|max:255|string',
                'phone' => 'required|numeric',
                'year' => 'required|numeric',
                'km' => 'required|numeric',
                'comment' => 'required|max:1000|string',
                'hour' => 'required|string',
                'date' => 'required|string',
                'model' => 'required|string',
                'brand' => 'required|string'
            ];

            try {
                // Validator
                $validator = \Validator::make($request->all(), $rules);
                    if ($validator->fails()) {
                        // Validator errors
                        $data = array(
                            'status' => 'error',
                            'code'   => '200',
                            'errors'  => $validator->errors()->all()
                        );
                    } else {

                        // Create maintenance appointment
                        $maintenance = new Maintenance();

                        $maintenance->name = $request->name;
                        $maintenance->surname = $request->surname;
                        $maintenance->email = $request->email;
                        $maintenance->phone = $request->phone;
                        $maintenance->year = $request->year;
                        $maintenance->km = $request->km;
                        $maintenance->comment = $request->comment;
                        $maintenance->vin = $request->vin;
                        $maintenance->hour = $request->hour;
                        $maintenance->date = $request->date;
                        $maintenance->model = $request->model;
                        $maintenance->brand = $request->brand;
                        $maintenance->save();
                        
                        // Data for Zappier
                        $send = array(
                            "name" => $request->name,
                            "surname" => $request->surname,
                            "email" => $request->email,
                            "phone" => $request->phone,
                            "year" => $request->year,
                            "km" => $request->km,
                            "comment" => $request->comment,
                            "vin" => $request->vin,
                            "hour" => $request->hour,
                            "date" => $request->date,
                            "model" => $request->model,
                            "brand" => $request->brand
                        );

                        // Save to spreadsheet by zappier
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, "");
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($send));

                        $response = curl_exec($ch);
                        curl_close($ch);

                        // Data response
                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'Cita creada exitosamente',
                            'appointment' => $response
                        );
                    }
            } catch (Exception $e) {
                $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'message' => 'Error al enviar o guardar la cita.' . $e
                );
            }   
        }
        return response()->json($data);
    }

    /**
     * Maintenance Appointment update and send to spread sheet
    */
    public function update(Request $request, $id){
        $rules =[
            'name' => 'required|max:255|string',
            'surname' => 'required|max:255|string',
            'email' => 'required|max:255|string',
            'phone' => 'required|numeric',
            'year' => 'required|numeric',
            'km' => 'required|numeric',
            'comment' => 'required|max:1000|string',
            'hour' => 'required|string',
            'date' => 'required|string',
            'model' => 'required|string',
            'brand' => 'required|string'
        ];

        try {
            // Validator
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails() ) {
                 // Validator errors
                $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'errors'  => $validator->errors()->all()
                );
            }else{

                $maintenance = Maintenance::find( $id );

                if( is_object($maintenance) && !empty($maintenance) ){
                    $maintenance->name = $request->name;
                    $maintenance->surname = $request->surname;
                    $maintenance->email = $request->email;
                    $maintenance->phone = $request->phone;
                    $maintenance->year = $request->year;
                    $maintenance->km = $request->km;
                    $maintenance->comment = $request->comment;
                    $maintenance->vin = $request->vin;
                    $maintenance->hour = $request->hour;
                    $maintenance->date = $request->date;
                    $maintenance->model = $request->model;
                    $maintenance->brand = $request->brand;
                    $maintenance->save();
                    
                    // Data for Zappier
                    $send = array(
                        "name" => $request->name,
                        "surname" => $request->surname,
                        "email" => $request->email,
                        "phone" => $request->phone,
                        "year" => $request->year,
                        "km" => $request->km,
                        "comment" => $request->comment,
                        "vin" => $request->vin,
                        "hour" => $request->hour,
                        "date" => $request->date,
                        "model" => $request->model,
                        "brand" => $request->brand
                    );

                    // Save to spreadsheet by zappier
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, "");
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($send));

                    $response = curl_exec($ch);
                    curl_close($ch);

                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'Cita actualizada correctamente',
                        'appointment' => $response
                    );
                }else{
                    $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'message' => 'El id de la cita no existe'
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

        return response()->json($data);
    }

    /**
     * Maintenance Appointment delete
    */
    public function destroy(Request $request, $id){

        if ( is_array($request->all())) {
            $maintenance = Maintenance::find( $id );

            if( is_object($maintenance) && !is_null($maintenance) ){

                try{
                    $maintenance->delete();
                    
                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'La cita ha sido eliminada correctamente.'
                    );

                } catch (\Illuminate\Database\QueryException $e){
                    //throw $th;
                    $data = array(
                        'status' => 'error',
                        'code'   => '400',
                        'message' => $e->getMessage()
                    );
                }

            } else {
                $data = array(
                    'status' => 'error',
                    'code'   => '404',
                    'message' => 'El id de la cita no existe.'
                );
            }

        } else {
            $data = array(
            'status' => 'error',
            'code'   => '404',
            'message' => 'Los datos enviados no son correctos'
            );
        }

        return response()->json($data);
    }

}
