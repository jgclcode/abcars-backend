<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Form;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class Vehicle_ReviewController extends Controller
{   
    // Función que inicializar el formulario y las relaciones entre Form e Item.
    // Se debe mandar a llamar cuando se inicie el proceso de revisión del vehículo

    // Los id de los elementos Item van de 1 a 43.
    // Por cada formulario se adjuntan los 43 Items
    // El valor de cada respuesta se almacena en la tabla intermedia form_item, en el campo value de tipo enum con valores yes o no
    public function initializeForm($vehicleVin){
      
      // Se busca el vehículo con el vin proporcionado en $vehicleVin dentro del $request
      $vehicle =  Vehicle::where('vin', $vehicleVin)->first();

      // Validar que exista el vehículo en la base de datos
      if(is_object($vehicle) && !is_null($vehicle)){
        
        // Crear nuevo formulario y asignar el vin del vehículo
        $form = new Form();
        $form->description = "Formulario para la revisión vehicular inicial";
        $form->status = 'to review';
        $form->vehicle_vin = $vehicleVin;
        $form->vehicle_id = $vehicle->id;
        $form->save();
      
        // Se adjuntan los 43 items del formulario
        // Inicializa un vector con todos los ids correspondientes a los items que irán en el formulario
        $idsForm = array( 1,  2,  3,  4,  5,  6,  7,  8,  9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20,
                         21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40,
                         41, 42, 43);

        // Attach de los id
        $form->items()->attach($idsForm);
        
        // Retorno de toda la información del formulario para usarse en el frontend.
        // Se retorna:
        // Vin del vehículo guardado en el formulario, para despúes usarlo cuando se guarden los datos del formulario.
        // items del formulario para mostrarse en el frontend (Para asignar la descripción de cada item en los inptus checkbox en el frontend)
        $data = array(
          'status' => 'success',
          'code'   => '200',
          'message' => 'Formulario creado exitosamente',
          'vehicle_vin' => $form->vehicle_vin,
          'items' => $form->items()->get()
        );

      } else {
        // Código de error al no existir el vin del vehículo en la base de datos
        $data = array(
          'status' => 'error',
          'code'   => '404',
          'message' => 'El vin ingresado no existe en la base de datos'
        );
      }

      // Conversión en Json de la información
      return response()->json($data);
    }


    // Función para guardar los datos del formulario
    // Recibe el vin del vehículo para buscar el formulario asignado y un arreglo con los 43 campos de los checkboxes
    public function saveForm(Request $request){

      // Se busca el formulario mediante el vin del vehículo
      $form =  Form::firstWhere('vehicle_vin', $request->vehicle_vin);
      
      // Valida que exista el formulario
      if(is_object($form) && !is_null($form)){

        // Obtiene los items del formulario desde el campo checkboxes del request
        $formItems = $request->checkboxes;

        // Se itera para almacenar el cambio en la tabla pivote
        // Cada elemento se recibe de la forma ["1"=>"yes", "2"=>"no",...,"42"=>"yes","43"=>"yes"]

        foreach ($formItems as $key => $value) {
          // Se obtiene el elemento de la tabla intermedia mediante el método updateExistingPivot que recibe:
          // item_id y un vector con el valor "value" a actualizar.
          $form->items()->updateExistingPivot($key, [
            'value' => $value,
          ]);
        }

        // items del formulario guardados
        $data = array(
          'status' => 'success',
          'code'   => '200',
          'message' => 'Formulario actualizado exitosamente',
          'vehicle_vin' => $form->vehicle_vin,
          'items' => $form->items()->get()
        );

      } else {

        // Código de error al no existir el vin del vehículo en la base de datos
        $data = array(
          'status' => 'error',
          'code'   => '404',
          'message' => 'El vin ingresado no coincide con algún formulario.'
        );
      }

      // Conversión en Json de la información
      return response()->json($data);
    }

}