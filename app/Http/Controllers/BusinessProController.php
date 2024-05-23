<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Brand;
use App\Models\Carmodel;

class BusinessProController extends Controller
{
    public function getVehiclesToBusinessPro(){
        $url = "https://bp.strega-gestion-leads.com/api/getPreownedVehicles";        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url ); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        curl_setopt($ch, CURLOPT_HEADER, 0); 
        $response = curl_exec($ch); 
        curl_close($ch); 
        $json = json_decode($response);

        unset($response);

        $data = array(
            "code" => 200,
            "status" => "success",
            "add" => array(),
            "total" => 0
        );

        if( !is_null($json) ){
            $vehicles = $json->data; 
            for($i = 0; $i < count($vehicles); $i++){
                $vehicle = Vehicle::where('vin', $vehicles[$i]->vin)->onlyTrashed()->first();                
                if( !is_object($vehicle) && is_null($vehicle) ){     
                    $brand = Brand::where('name', $vehicles[$i]->brand)->first();
                    if( !is_object($brand) && is_null($brand) ){
                        $brand = new Brand;
                        $brand->name = $vehicles[$i]->brand;
                        $brand->save();
                    }

                    $model = Carmodel::where('name', $vehicles[$i]->model)->where('brand_id', $brand->id)->first();
                    if( !is_object($model) && is_null($model) ){
                        $model = new Carmodel;
                        $model->name = $vehicles[$i]->model;
                        $model->brand_id = $brand->id;
                        $model->save();
                    }
                    $carbon = new \Carbon\Carbon(); 
                    
                    $vehicle = new Vehicle;
                    $vehicle->name = $vehicles[$i]->name;
                    $vehicle->description = "no tiene";
                    $vehicle->vin = $vehicles[$i]->vin;
                    $vehicle->location = $vehicles[$i]->location;
                    $vehicle->yearModel = $vehicles[$i]->year;
                    $vehicle->purchaseDate = date('Y-m-d', strtotime(str_replace("/", "-", $vehicles[$i]->acquisition_date)));
                    $vehicle->price = $vehicles[$i]->full_purchase_amount;
                    $vehicle->priceList = $vehicles[$i]->cost;
                    $vehicle->salePrice = $vehicles[$i]->full_purchase_amount;
                    $vehicle->type = "pre_owned";
                    $vehicle->carline = $vehicles[$i]->carline;
                    $vehicle->cylinders = $vehicles[$i]->cylinders;
                    $vehicle->colorInt = $vehicles[$i]->interior_color;
                    $vehicle->colorExt = $vehicles[$i]->exterior_color;
                    $vehicle->status = "active";
                    $vehicle->plates = $vehicles[$i]->plates;
                    $vehicle->transmission = $vehicles[$i]->transmission;
                    $vehicle->inventoryDays = $vehicles[$i]->antiquity;
                    $vehicle->km = $vehicles[$i]->km;
                    $vehicle->numKeys = (int) trim($vehicles[$i]->numKeys);
                    $vehicle->studs = "no";
                    $vehicle->spareTire = "no";
                    $vehicle->hydraulicJack = "no";
                    $vehicle->extinguiser = "no";
                    $vehicle->reflectives = "no";
                    $vehicle->handbook = "no";
                    $vehicle->insurancePolicy = "no";
                    $vehicle->powerCables = "no";
                    $vehicle->promotion = NULL;
                    $vehicle->priceOffer = NULL;
                    $vehicle->carmodel_id = $model->id;
                    $vehicle->vehiclebody_id = 12;
                    $vehicle->branch_id = $vehicles[$i]->brand === "BMW" ? 9 : 8;
                    $vehicle->client_id = 1; 
                    $vehicle->deleted_at = $carbon->now();                    
                    if($vehicle->save()){
                        $data['total']++;
                        array_push($data['add'], $vehicle->vin);
                    }
                }              
            }            
        }else{
            $data = array(
                "code" => 200,
                "status" => "error",
                "message" => "Ocurrió un error al obtener la información"
            );
        }       
                
        return $data;
    }
}
