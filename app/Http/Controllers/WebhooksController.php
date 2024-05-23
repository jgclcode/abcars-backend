<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Choice;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Http;

class WebhooksController extends Controller
{
    //
    public function webhook(Request $request){
        // Obtiene todos los datos de la solicitud
        $data = $request->all();
        // Convierte el array a una cadena JSON
        $dataString = json_encode($data);
        $dataDecoded = json_decode($dataString, true);
        // dd($dataDecoded);
        if ($dataDecoded['type'] === 'payment') {
            $payment_id = $dataDecoded['data']['id'];
            $response = Http::get("https://api.mercadopago.com/v1/payments/$payment_id" . "?access_token=APP_USR-8970674333411644-121915-ba266b5d946acfacbe647f5bf3d25f7f-1601836074"); // Response de producción
    
            // Decodificar el json devuelto
            $response = json_decode($response);
    
            $status = $response->status;
            $status_detail = $response->status_detail;
            if($status == 'approved') {
                if (Choice::where('reference', $payment_id)->exists()) {
                    $choice = Choice::where('reference', $payment_id)->first();
                    $choice->status = 'apartado';
                    $choice->save();
                    // return response()->json(['status' => 'success'], 200);
                }else{
    
                    $vehicle_vin = $response->additional_info->items[0]->id;
                    $vehicle = Vehicle::where('vin', $vehicle_vin)->first();
                    $client_id = $response->additional_info->items[0]->category_id;
        
                    // Crea registro en choices
                    $choice = new Choice();
                    $choice->amount = $response->transaction_amount;
                    $choice->namePayment = 'Mercado Pago';
                    $choice->status = 'apartado';
                    $choice->reference = $response->id;
                    $choice->amountDate = $response->date_approved;
                    $choice->vehicle_id = $vehicle->id;
                    $choice->client_id = $client_id;
        
                    $choice->save();
        
                    // return response()->json(['status' => 'success'], 200);
                }
            }

            if ($status == 'in_process') {
                $vehicle_vin = $response->additional_info->items[0]->id;
                $vehicle = Vehicle::where('vin', $vehicle_vin)->first();
                $client_id = $response->additional_info->items[0]->category_id;

                // Crea registro en choices
                $choice = new Choice();
                $choice->amount = $response->transaction_amount;
                $choice->namePayment = 'Mercado Pago';
                $choice->status = 'progreso';
                $choice->reference = $response->id;
                $choice->amountDate = $response->date_created;
                $choice->vehicle_id = $vehicle->id;
                $choice->client_id = $client_id;

                $choice->save();
            }

            if ($status == 'pending') {
                $vehicle_vin = $response->additional_info->items[0]->id;
                $vehicle = Vehicle::where('vin', $vehicle_vin)->first();
                $client_id = $response->additional_info->items[0]->category_id;

                // Crea registro en choices
                $choice = new Choice();
                $choice->amount = $response->transaction_amount;
                $choice->namePayment = 'Mercado Pago';
                $choice->status = 'pendiente';
                $choice->reference = $response->id;
                $choice->amountDate = $response->date_created;
                $choice->vehicle_id = $vehicle->id;
                $choice->client_id = $client_id;

                $choice->save();
            }

            if ($status == 'cancelled' && $status_detail == 'expired') {
                if (Choice::where('reference', $payment_id)->exists()) {
                    $choice = Choice::where('reference', $payment_id)->first();
                    // dd($choice);
                    $choice->delete();
                    return response()->json(['status' => 'success'], 200);
                }
            }
            return response()->json(['status' => 'success'], 200);
        }
        
    }
}
