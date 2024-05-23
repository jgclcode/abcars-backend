<?php

namespace App\Imports;
use App\Models\Vehicle;

use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UpdatePromotionsImport implements WithHeadingRow, ToCollection
{    
    public function collection(Collection $rows)
    {        
        // Variables
        $updated_elements = array();

        if( Session::has('updated_elements') ){
            $updated_elements = Session::get('updated_elements');                             
        }else{
            $updated_elements = array(
                'total' => 0,
                'updated' => array(),                
                'errors' => array()
            );
        } 

        foreach ($rows as $key => $row ) 
        {                          
            // Vin del vehiculo
            $vehicle_vin = null;    
            if( isset( $row['vin'] ) && strlen(trim($row['vin'])) == 17 ){                
                $vehicle_vin = strtolower(trim($row['vin']));              
            }else{
                $updated_elements['errors']['fila: '. ($key+1) .'vin'] = 'La columna "vin" debe tener 17 caracteres';                
            }                    

            // Promoción    
            $vehicle_promocion = null;                                                 
            if( 
                isset( $row['promocion'] ) && strlen(trim($row['promocion'])) > 0
            ){                
                $vehicle_promocion = strtolower(trim($row['promocion']));                                      
            }else{
                $updated_elements['errors']['fila'. ($key+1) .'promocion'] = 'La columna "promocion" no tiene información';                
            }
            
            // Actualizar vehículo            
            if( !is_null( $vehicle_vin ) && !is_null( $vehicle_promocion ) ){
                $vehicle = Vehicle::where('vin', $vehicle_vin)->first();
                if( is_object( $vehicle ) ){                                                            
                    $vehicle->promotion = $vehicle_promocion;                                                  
                    if( $vehicle->save() ){
                        $updated_elements['updated']['fila'. ($key+1)] = 'La promoción ha sido agregada al vehículo con vin "' . $vehicle_vin;                
                        $updated_elements['total']++;
                    }                    
                }else{
                    $updated_elements['errors']['fila'. ($key+1)] = 'No existe ningun vehículo con el vin: ' . $vehicle_vin;                
                }
            }else{
                $updated_elements['errors']['fila'. ($key+1)] = 'La columna vin o la columna promoción están vacias';                
            }
            
        }
        //dd( $updated_elements );
        Session::flash('updated_elements', $updated_elements );        
    } 
}
