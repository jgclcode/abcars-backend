<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversion_Form;

class ConversionFormController extends Controller
{

    /**
     * Appointment to analize conversion of clients and send it to spread sheet
    */
    public function saveConversionForm (Request $body) {
        
        date_default_timezone_set('America/Mexico_City');
        // Verificar si el horario de verano está activado
        $horarioVerano = date('I'); // Retorna 1 si el horario de verano está activado, 0 si no

        // Restar una hora a la hora actual si el horario de verano está activado

        $date = date('Y-m-d H:i:s');

        if ($horarioVerano) {
            $date = date('Y-m-d H:i:s', strtotime('-1 hour'));
        }

        if (!is_array($body->all())) {
            $data = array(
              'status' => 'error',
              'code'   => '200',
              'message'  => "request must be an array"
            );
        }

        $rules = [
            'name'         => 'required|string|max:255', 
            'lastName'     => 'required|string|max:255',
            'email'        => 'required|string|max:255', 
            'phone'        => 'required|numeric',
            'time'         => 'required|string|max:255',
            'appointment'  => 'required|string|max:255',
            'filial'       => 'required|string|max:255',
        ];

        try {
            $validator = \Validator::make($body->all(), $rules);
            if ($validator->fails()) {
                // Esta condición se ejecuta si la validación de uno o más campos es incorrecta
                $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'errors'  => $validator->errors()->all()
                );
            }else{                        
                              
                $conversion_form = new Conversion_Form();
        
                $conversion_form->name        = $body->name;
                $conversion_form->lastName    = $body->lastName;
                $conversion_form->phone       = $body->phone;
                $conversion_form->email       = $body->email;
                $conversion_form->appointment = ($body->appointment.' '.$body->time);
                $conversion_form->filial      = $body->filial;
                $conversion_form->save();


                // Enviar información a Zappier
                $request = array(
                    "date" => $date,
                    "name" => $body->name,
                    "lastName" => $body->lastName,
                    "phone" => $body->phone,
                    "email" => $body->email,
                    "appointmentDate" => $body->appointment,
                    "appointmentHour" => $body->time,
                    "filial" => $body->filial
                );
                
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://hooks.zapier.com/hooks/catch/8825119/3mimti7/");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));

                $data = curl_exec($ch);
                curl_close($ch);                
                
                // Retorno del mensaje de éxito
                $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'message' => 'La cita se ha generado correctamente',
                    'damage' => $conversion_form
                );                

            }
        } catch (Exception $e) {
                $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'message' => 'Los datos enviados no son correctos, ' . $e
                );
        }

        return response()->json($data, $data['code']);
    }
}
