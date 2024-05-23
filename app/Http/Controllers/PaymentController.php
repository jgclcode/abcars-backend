<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\CustomerService;
use App\Models\Client;
use App\Models\Choice;

use Illuminate\Support\Facades\Response;

use Illuminate\Support\Facades\Http;

class PaymentController extends Controller
{
    //
    public function pay($vin, $user_id, $reward = false) {
        $vehicle = Vehicle::where('vin', $vin)->first();
        $customerService = CustomerService::where('name', 'Apartados')->first();
        $client = Client::where('user_id', $user_id)->first();

        return view('mercadoPago.payment', [
            'vehicle' => $vehicle,
            'customerService' => $customerService,
            'vin' => $vin,            
            'client' => $client,
            'reference' => $reward
        ]);
    }

    public function pagos($vehicle_id, $client_id,  Request $request) { /** $reward = null, */
        $payment_id = $request->get('payment_id');

        // $response = Http::get("https://api.mercadopago.com/v1/payments/$payment_id" . "?access_token=APP_USR-1775799022588516-100515-b18bf4e5726b0067ea1ae0226e4b24d4-835913117"); // Response de prueba anterior
        $response = Http::get("https://api.mercadopago.com/v1/payments/$payment_id" . "?access_token=APP_USR-8970674333411644-121915-ba266b5d946acfacbe647f5bf3d25f7f-1601836074"); // Response de producción

        // Decodificar el json devuelto
        $response = json_decode($response);
        
        $status = $response->status;
        $redirect_url = $response->transaction_details->external_resource_url;
        if ($status == 'approved') {
            $id = $response->id;
            $title = $response->additional_info->items[0]->title;
            $totalPaid = $response->transaction_details->total_paid_amount;
            $status = $response->status;
            $status_detail = $response->status_detail;

            return redirect( 'http://localhost:4200/success-payment/' . $id . '/' . $title . '/' . $totalPaid . '/' . $status . '/' . $status_detail);
            
        }

        if ($status == 'in_process') {
            $id = $response->id;
            $title = $response->additional_info->items[0]->title;
            $totalPaid = $response->transaction_details->total_paid_amount;
            $status = $response->status;
            $status_detail = $response->status_detail;

            // return redirect( 'http://localhost:4200/success-pending-payment/' . $id . '/' . $title . '/' . $totalPaid . '/' . $status . '/' . $status_detail . '/' . urlencode($redirect_url));
            return redirect( 'http://localhost:4200/success-payment/' . $id . '/' . $title . '/' . $totalPaid . '/' . $status . '/' . $status_detail);
            // Crear choice
            // $choice = new Choice();
            // $choice->amount = $response->transaction_amount;
            // $choice->namePayment = 'Mercado Pago';
            // $choice->status = 'progreso';
            // $choice->reference = $response->id;
            // $choice->amountDate = $response->date_created;
            // $choice->vehicle_id = $vehicle_id;
            // $choice->client_id = $client_id;
            // $choice->rewards = $reward;

            // if ($choice->save()) {
                // return redirect( env('MP_URL_SUCCESS') ) /* redirect('http://localhost:4200/saved-process') */;
                // $homeUrl = env('MP_URL_SUCCESS');
                // $escapedNewPageUrl = addslashes($redirect_url);
                // $script = " <script>
                //     let redirect_url = '$escapedNewPageUrl';
                //     window.open(redirect_url, '_blank');
                //     window.location.href = '$homeUrl';
                // </script>";
                // return Response::make( $script );
            // } else {
            //     return redirect( env('MP_URL_ERROR') ) /* redirect('http://localhost:4200/error-process') */;
            // }
        }
        
        if ($status == 'pending') {
            $id = $response->id;
            $title = $response->additional_info->items[0]->title;
            $totalPaid = $response->transaction_details->total_paid_amount;
            $status = $response->status;
            $status_detail = $response->status_detail;

            return redirect( 'http://localhost:4200/success-pending-payment/' . $id . '/' . $title . '/' . $totalPaid . '/' . $status . '/' . $status_detail . '/' . urlencode($redirect_url));
            // Crear choice
            // $choice = new Choice();
            // $choice->amount = $response->transaction_amount;
            // $choice->namePayment = 'Mercado Pago';
            // $choice->status = 'pendiente';
            // $choice->reference = $response->id;
            // $choice->amountDate = $response->date_created;
            // $choice->vehicle_id = $vehicle_id;
            // $choice->client_id = $client_id;
            // $choice->rewards = $reward;

            // if ($choice->save()) {
                // return redirect( env('MP_URL_SUCCESS') ) /* redirect('http://localhost:4200/saved-process') */;
                // $homeUrl = env('MP_URL_SUCCESS');
                // $script = " <script>
                //     window.open('$redirect_url', '_blank');
                //     window.location.href = '$homeUrl';
                // </script>";
                // return Response::make( $script );
            // } else {
            //     return redirect( env('MP_URL_ERROR') ) /* redirect('http://localhost:4200/error-process') */;
            // }
        }
    }

    public function approval(){
        // 
    }
    
    public function cancelled(){
        // 
    }
}
