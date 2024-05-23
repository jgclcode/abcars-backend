<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Brand;
use App\Models\Carmodel;
use App\Models\State;
use App\Models\Vehicle;
use App\Models\Vehicle_image;

class IntelimotorController extends Controller
{
    // Array de datos de "business units" de intelimotors
    private $intelimotors = array(
        'abp' => array(
           'url_intelimotor' => "https://app.intelimotor.com", 
           'apiKey' => "9bf31b3263acf5341c26a55599efa43a253798b2407826360619d813ec855f40",
           'apiSecret' => "3320626983e92e64c01fa5a29c7584165740ffe6d014ba7664469d07f15e9a33",
           'business_unit_id' => '5fdd1fb7e6eda800137625a9'
        ),
        'pachuca' => array(
            'url_intelimotor' => "https://app.intelimotor.com", 
            'apiKey' => "12410593d8549bc46818098048f9500b24ff5dfa8a0e4e584e4eeadeb8aab607",
            'apiSecret' => "db307a004a3a55a748eb0d246335916067c86dd29105d7fb6aa55cb5d7b92820",
            'business_unit_id' => '60626124a5d98f00133987ad'
         ),
    );  
    private $pageSize = 6;  

    // LLenar base con vehiculos
    public function requestInventoryUnitsByBusinessUnitId( int $pageNumber = null ){ 
        $response = array(
            'code' => 200, 
            'status' => 'success',
            'vehicles_created' => array(
                'vins' => array(),
                'total' => 0
            ),
            'vehicles_updated' => array(
                'vins' => array(),
                'total' => 0
            ),
            'errors' => array()
        );
        foreach( $this->intelimotors as $value => $item){
            if( !is_null( $pageNumber ) ){                 
                $url = $item['url_intelimotor'] . '/api/business-units/' . $item['business_unit_id'] . '/inventory-units?apiKey=' . $item['apiKey'] . '&apiSecret=' . $item['apiSecret'] . '&pageSize=' . $this->pageSize . '&pageNumber=' . $pageNumber;                
                $data = $this->getRequest( $url );        
                $data = json_decode( $data );
                // Verificar que exista una respuesta
                if( isset($data->pagination ) ){
                    $saveData = $this->saveData( $url );

                    $response['vehicles_created']['vins'] = array_merge( $response['vehicles_created']['vins'], $saveData['vehicles_created']['vins'] );
                    $response['vehicles_created']['total'] = $response['vehicles_created']['total'] + $saveData['vehicles_created']['total'];
                    $response['errors'] = array_merge( $response['errors'], $saveData['errors'] );

                    $response['vehicles_updated']['vins'] = array_merge( $response['vehicles_updated']['vins'], $saveData['vehicles_updated']['vins'] );
                    $response['vehicles_updated']['total'] = $response['vehicles_updated']['total'] + $saveData['vehicles_updated']['total'];
                    $response['errors'] = array_merge( $response['errors'], $saveData['errors'] );
                    
                }else{
                    array_push( $response['errors'], 'Los datos no se pudieron obtener de intelimotors' );
                }
            }else{                              
                // Proceso si pageNumber esta en null            
                $url_abp = $item['url_intelimotor'] . '/api/business-units/' . $item['business_unit_id'] . '/inventory-units?apiKey=' . $item['apiKey'] . '&apiSecret=' . $item['apiSecret'] . '&pageSize=1';                                
                $data = $this->getRequest( $url_abp );        
                $data = json_decode( $data );
                // Verificar que exista una respuesta
                if( isset($data->pagination ) ){
                    $total = $data->pagination->total;                                    
                    $page_number = (round(($total / $this->pageSize))) - 1;
                    $url_last_three = $item['url_intelimotor'] . '/api/business-units/' . $item['business_unit_id'] . '/inventory-units?apiKey=' . $item['apiKey'] . '&apiSecret=' . $item['apiSecret'] . '&pageSize=' . $this->pageSize . '&pageNumber=' . $page_number;                
                    $saveData = $this->saveData( $url_last_three );

                    $response['vehicles_created']['vins'] = array_merge( $response['vehicles_created']['vins'], $saveData['vehicles_created']['vins'] );
                    $response['vehicles_created']['total'] = $response['vehicles_created']['total'] + $saveData['vehicles_created']['total'];
                    $response['errors'] = array_merge( $response['errors'], $saveData['errors'] );

                    $response['vehicles_updated']['vins'] = array_merge( $response['vehicles_updated']['vins'], $saveData['vehicles_updated']['vins'] );
                    $response['vehicles_updated']['total'] = $response['vehicles_updated']['total'] + $saveData['vehicles_updated']['total'];
                    $response['errors'] = array_merge( $response['errors'], $saveData['errors'] );
                }else{
                    array_push( $response['errors'], 'Los datos no se pudieron obtener de intelimotors' );
                }
                // Fin Proceso si pageNumber esta en null            
            }                       
        }                
        return response()->json($response, $response['code']);
    }

    // Obtener vehiculo por vin, para insertar o actualizar
    public function requestUnitByVin( String $vin ){
        $response = array(
            'code' => 200, 
            'status' => 'success',
            'vehicles_created' => false,
            'message' => '',
            'errors' => array()
        );
        foreach( $this->intelimotors as $item ){
            $url = $item['url_intelimotor'] . '/api/units/vin/' . $vin . '?apiKey=' . $item['apiKey'] . '&apiSecret=' . $item['apiSecret'];
            $data = $this->getRequest( $url );        
            $data = json_decode( $data );
            if( isset($data->data ) ){
                $saveData = $this->saveData( $url, true );
                if( is_array($saveData) ){
                    if( $saveData['vehicles_created']['total'] > 0 ){
                        $response['vehicles_created'] = true;
                        $response['message'] = 'Vehiculo insertado correctamente';
                    }else{
                        $response['message'] = 'Vehiculo actualizado correctamente';
                    }
                }else{
                    $response['errors'] = 'Ocurrio un problema';
                }                
            }else{
                $response['errors'] = 'Ocurrio un problema con la url de intelimotors';
            }
        }
        return response()->json($response, $response['code']);
    }

    private function getRequest( String $url ){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url ); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_HEADER, 0); 
        $data = curl_exec($ch); 
        curl_close($ch); 
        return $data;
    }

    private function saveData( String $url, $actualizar_imagenes = false){
        $response = array(
            'vehicles_created' => array(
                'vins' => array(),
                'total' => 0
            ),
            'vehicles_updated' => array(
                'vins' => array(),
                'total' => 0
            ),
            'errors' => array()
        );

        $intelimotors_data = $this->getRequest( $url );        
        $intelimotors_data = json_decode( $intelimotors_data );

        if( is_object($intelimotors_data) && isset( $intelimotors_data->data ) ){
            $datos = $intelimotors_data->data;
            if( is_array( $datos ) ){
                foreach( $datos as $dato ){
                    $brand_id = $this->getBrandName( $dato->brands[0]->name ); 
                    $vehicle = Vehicle::where('vin', trim( $dato->vin ) )->first();                 
                    if( !is_object( $vehicle ) && is_null( $vehicle ) ){
                        $vehicle = new Vehicle();
                        $crear = $this->assignDataToVehicle( $vehicle, $dato, $brand_id, false );
                        if( isset($crear['vin']) ){
                            array_push( $response['vehicles_created']['vins'], $crear['vin'] );
                            $response['vehicles_created']['total'] = $response['vehicles_created']['total'] + 1;
                        }else{
                            array_push($response['errors'], $crear['error']);
                        }
                    }else{
                        $actualizar = $this->assignDataToVehicle( $vehicle, $dato, $brand_id, true );
                        if( isset($actualizar['vin']) ){
                            array_push( $response['vehicles_updated']['vins'], $actualizar['vin'] );
                            $response['vehicles_updated']['total'] = $response['vehicles_updated']['total'] + 1;
                        }else{
                            array_push($response['errors'], $actualizar['error']);
                        }
                    }                                            
                }
            }else{
                $brand_id = $this->getBrandName( $datos->brands[0]->name ); 
                $vehicle = Vehicle::where('vin', trim( $datos->vin ) )->first();                 
                if( !is_object( $vehicle ) && is_null( $vehicle ) ){
                    $vehicle = new Vehicle();
                    $crear = $this->assignDataToVehicle( $vehicle, $datos, $brand_id, false );
                    if( isset($crear['vin']) ){
                        array_push( $response['vehicles_created']['vins'], $crear['vin'] );
                        $response['vehicles_created']['total'] = $response['vehicles_created']['total'] + 1;
                    }else{
                        array_push($response['errors'], $crear['error']);
                    }
                }else{
                    $actualizar = $this->assignDataToVehicle( $vehicle, $datos, $brand_id, true, $actualizar_imagenes );
                    if( isset($actualizar['vin']) ){
                        array_push( $response['vehicles_updated']['vins'], $actualizar['vin'] );
                        $response['vehicles_updated']['total'] = $response['vehicles_updated']['total'] + 1;
                    }else{
                        array_push($response['errors'], $actualizar['error']);
                    }
                }                                            
            }            
                        
        }
        return $response;
    }

    public function assignDataToVehicle( $vehicle, $dato, int $brand_id, $actualizar, $actualizar_imagenes = false ){
        $vehicle->name = $dato->brands[0]->name . ' ' . $dato->models[0]->name . ', ' . ( count($dato->trims) > 0 ? $dato->trims[0]->name : $dato->customTrim );                    
        $vehicle->description = is_null($dato->listingInfo) ? "" : $dato->listingInfo->descriptions->carcentral;
        $vehicle->vin = trim( $dato->vin );
        $vehicle->location = "no tiene"; // preguntar en donde esta
        $vehicle->yearModel = $dato->years[0]->name;
        $vehicle->purchaseDate = date('Y-m-d', ($dato->buyDate/1000));
        $vehicle->price = $dato->buyPrice;
        $vehicle->priceList = $dato->listPrice;
        $vehicle->salePrice = $dato->listPrice; //$dato->sellPrice, preguntar
        $vehicle->type = "pre_owned";
        $vehicle->carline = ( count($dato->trims) > 0 ? $dato->trims[0]->name : $dato->customTrim );
        $vehicle->cylinders = 1; // preguntar
        $vehicle->colorInt = is_null($dato->listingInfo) ? "no tiene" : $dato->listingInfo->interiorColor;
        $vehicle->colorExt = is_null($dato->listingInfo) ? "no tiene" : $dato->listingInfo->exteriorColor;
        $vehicle->status = "active";
        $vehicle->plates = is_null($dato->listingInfo) ? "no tiene" : $dato->listingInfo->licensePlate;
        $vehicle->transmission = is_null($dato->listingInfo) ? "manual" : $this->getTransmision( $dato->listingInfo->transmission );
        $vehicle->inventoryDays = 1;
        $vehicle->km = $dato->kms;                    
        $vehicle->numKeys = 1;
        $vehicle->studs = "no";
        $vehicle->spareTire = "no";
        $vehicle->hydraulicJack = "no";
        $vehicle->extinguiser = "no";
        $vehicle->reflectives = "no";
        $vehicle->handbook = "no";
        $vehicle->insurancePolicy = "no";
        $vehicle->powerCables = "no";
        $vehicle->promotion = null;
        $vehicle->carmodel_id = $this->getCarmodelId($dato->models[0]->name, $brand_id);
        $vehicle->vehiclebody_id = 13;
        $vehicle->branch_id = $this->getBranch( $dato->businessUnit->name, $dato->businessUnit->state);
        $vehicle->client_id = 1;                    
        if( $vehicle->save() ){  
            $imagenes = $vehicle->vehicle_images()->count();   
            $pictures = $dato->pictures;                                    
            if( is_array($pictures) && count($pictures) > 1 && $imagenes == 0 ){
                for( $i = 0; $i < count($pictures); $i++ ){
                    $vehicle_image = new Vehicle_image;                         
                    $vehicle_image->vehicle_id = $vehicle->id;
                    $vehicle_image->path = "https://intelimotor.s3.amazonaws.com/" . $pictures[$i];                        
                    $vehicle_image->external_website = "yes";
                    $vehicle_image->save();
                }
            }elseif($actualizar_imagenes) {         
                $images = $vehicle->vehicle_images()->get();
                foreach( $images as $image ){
                    $image->delete();
                }  
                for( $i = 0; $i < count($pictures); $i++ ){
                    $vehicle_image = new Vehicle_image;                         
                    $vehicle_image->vehicle_id = $vehicle->id;
                    $vehicle_image->path = "https://intelimotor.s3.amazonaws.com/" . $pictures[$i];                        
                    $vehicle_image->external_website = "yes";
                    $vehicle_image->save();
                }             
            }
            return array( "vin" => $vehicle->vin );            
        }
        return array( "error" => "ocurrio un error en la subida del vehiculo con vin: " . $dato->vin );            
    }

    public function getTransmision( String $cadena ){
        $transmision;
        if($cadena=="Automática"){
          $transmision ="automatico";
        }
        else{
          $transmision="manual";
        }
        return $transmision;
    }


    public function getBrandName( String $name ){    
        $brand = Brand::where( 'name', $name )->first();
        if( !is_object( $brand ) ){
          $brand = new Brand;
          $brand->name = strtolower(trim($name));
          $brand->save();
        }
        return is_object($brand) ? $brand->id : null;
    }

    public function getCarmodelId( String $name, int $brand_id ){
        $name = str_replace('&', ' ', $name);
        $carmodel = Carmodel::where('name', strtolower(trim($name)) )->where('brand_id', $brand_id)->first();
        if( !is_object( $carmodel ) ){
          $brand = Brand::find($brand_id);
          if( is_object($brand) ) {
            $carmodel = new Carmodel;
            $carmodel->name = strtolower(trim($name));
            $carmodel->brand_id = $brand->id;
            $carmodel->save();
          }
        }
        return is_object($carmodel) ? $carmodel->id : null;
    }

    public function getBranch( String $name, String $state){
        $branch = Branch::where('name', $name)->first();
        if( !is_object( $branch ) ){
            $branch = new Branch;
            $branch->name = strtolower(trim($name));
            $branch->state_id = $this->getState( $state );
            $branch->save();
        }
        return is_object($branch) ? $branch->id : null;
    }

    public function getState( String $state){
        $state = State::whereRaw(
            " states.name LIKE '%$state%'"
        )->first();

        return is_object($state) ? $state->id : null;
    }
    
    public function allUnitsWithoutImages(){
        $data = array(
            'code' => 200, 
            'status' => 'success',
            'actualizados' => 0,
            'sin_imagenes' => 0
        );
        $vehicles = Vehicle::leftJoin('vehicle_images', 'vehicles.id', 'vehicle_images.vehicle_id')
                            ->whereNull('vehicle_images.id')
                            ->get();        
        foreach( $vehicles as $vehicle ){            
            $respuesta = $this->requestUnitByVin($vehicle->vin);            
            $respuesta = json_encode( $respuesta );
            $respuesta = json_decode( $respuesta );
            if( $respuesta->original->message  === "Vehiculo actualizado correctamente" ){
                $data['actualizados'] = $data['actualizados'] + 1;
            }            
        }  
        $data['sin_imagenes'] = $vehicles->count();                 
        
        return response()->json($data, $data['code']);                            
    }
}
