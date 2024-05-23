<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;

class MercadoLibreController extends Controller
{
    public function access_token(Request $request){
        $code = $request->query('code');  
        $mercadoLibreHelper = new \App\Helpers\MercadoLibreHelper(); 
        $response = $mercadoLibreHelper->access_token($code); 

        return response()->json( $response, 200 );        
    }

    public function refresh_access_token(Request $request){                     

        $mercadoLibreHelper = new \App\Helpers\MercadoLibreHelper();  
        $response = $mercadoLibreHelper->refresh_access_token();   
        
        return response()->json( $response, 200 );     
    }

    public function post_vehicle_ml( Request $request ){

        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if( !$checkToken ) {
            return response()->json(['code' => 200, 'status' => 'error', 'message' => 'El usuario no se encuentra logueado'], 200);
        }
                    
        $rules = [                                                    
            'vehicle_id' => 'required|exists:vehicles,id'                               
        ];               

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            // existio un error en los campos enviados 
            $data = array(
                'status' => 'error',
                'code' => '200',
                'errors' => $validator->errors()->all()
            );
            return response()->json($data, $data['code']);
        }


        $vehicle = Vehicle::find( $request->vehicle_id );        
        $pictures = [];   
        if(!is_object( $vehicle ) && is_null( $vehicle ) ){
            return response()->json(['code' => 200, 'status' => 'error', 'message' => "El vehículo con id $vehicle_id no existe"], 200);
        }else{
            $images = $vehicle->vehicle_images;

            foreach( $images as $index => $image ){
                $element = [                    
                    "source" => "https://abcars.mx/abcars-backend/api/image_vehicle/" . $image->path,
                    "position" =>  $index + 1              
                ];

                array_push($pictures, $element);
            }
        }            

        $mercadoLibreHelper = new \App\Helpers\MercadoLibreHelper();  
        $token = $mercadoLibreHelper->refresh_access_token();    

        if( !isset($token['response']) ){
            return response()->json([
                'code' => 200, 
                'status' => 'success',
                'message' => "No existe el refresh token"
            ], 200);
        }        

        // Construir el arreglo
        $data = array(
            "title" => $vehicle->name,
            "category_id" => "MLM1744",
            "price" => $vehicle->price,
            "currency_id" => "MXN",
            "available_quantity" => 1,
            "condition" => ($vehicle->type === "new" ? "new" : "used"),
            "listing_type_id" => "silver",
            "status" => "inactive",
            "location" => array(                
                "zip_code" => "72090",
                "neighborhood" => array(
                    "id" => "TUxNQlpPTjcwMTg",
                    "name" => "Zona Esmeralda"
                ),
                "city" => array(
                    "id" => "TUxNQ1BVRTEwOTA",
                    "name" => "Puebla"
                ),
                "state" => array(
                    "id" => "TUxNUFBVRTQ4ODM",
                    "name" => "Puebla"
                ),
                "country" => array(
                    "id" => "MX",
                    "name" => "Mexico",
                    "locale" => "es_MX",
                    "currency_id" => "MXN"
                )
            ),
            "attributes" => array(
                array(
                    "id" => "BRAND",
                    "value_id" => null,
                    "value_name" => $vehicle->carmodel->brand->name,
                    "value_struct" => null,
                    "values" => array(
                        array(
                            "id" => "378802",
                            "name" => $vehicle->carmodel->brand->name,
                            "struct" => null,
                            "source" => 1572
                        )
                    ),
                    "attribute_group_id" => "OTHERS",
                    "attribute_group_name" => "Otros",
                    "name" => "Marca",
                    "source" => 1572
                ),
                array(
                    "id" => "TRIM",
                    "value_id" => null,
                    "value_name" => "Versión específica del modelo"
                ),
                array(
                    "id" => "DOORS",
                    "value_name" => "4"
                ),
                array(
                    "id" => "VEHICLE_YEAR",
                    "value_name" => $vehicle->yearModel
                ),
                array(
                    "id" => "FUEL_TYPE",
                    "value_id" => null,
                    "value_name" => "Tipo de combustible"
                ),
                array(
                    "id" => "MODEL",
                    "value_id" => null,
                    "value_name" => $vehicle->carmodel->name
                ),
                array(
                    "id" => "KILOMETERS",
                    "value_id" => null,
                    "value_name" => $vehicle->km,
                    "value_struct" => null,
                    "values" => array(
                        array(
                            "id" => null,
                            "name" => $vehicle->km,
                            "struct" => null,
                            "source" => 1572
                        )
                    ),
                    "attribute_group_id" => "OTHERS",
                    "attribute_group_name" => "Otros",
                    "name" => "KILOMETERS",
                    "source" => 1572
                ),
                array(
                    "id" => "KILOMETERS",
                    "value_id" => null,
                    "value_name" => $vehicle->km . " km",
                    "value_struct" => null,
                    "values" => array(
                        array(
                            "id" => null,
                            "name" => $vehicle->km . " km",
                            "struct" => null,
                            "source" => 1572
                        )
                    ),
                    "attribute_group_id" => "OTHERS",
                    "attribute_group_name" => "Otros",
                    "name" => "KILOMETERS",
                    "source" => 1572
                )
            ),
            "pictures" => $pictures            
        );
        
        $jsonData = json_encode($data);        

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.mercadolibre.com/items',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $token['response']->access_token,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);        

        curl_close($curl);        
        $data = json_decode($response); 

        if( isset($data->id) ){
            $description = $mercadoLibreHelper->add_description_item($token['response']->access_token, $data->id, $vehicle->description);

            $vehicle->mercado_id = $data->id;
            $vehicle->save();

            return response()->json([
                'code' => 200, 
                'status' => 'success',
                'response' => $data, 
                'description' => $description
            ], 200);
        }else {
            return response()->json([
                'code' => 200, 
                'status' => 'error',
                'response' => $data                
            ], 200);
        }  
    }

    public function existsVehicle( String $mercado_item_id, String $access_token ){        
        $url = "https://api.mercadolibre.com/items/$mercado_item_id";

        $ch = curl_init($url);

        // Establecer opciones de cURL
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $access_token));

        // Realizar la solicitud GET
        $response = curl_exec($ch);

        $data = json_decode($response);                 

        if( !is_null($data) && is_object($data) ){
            if( isset( $data->error) ){
                return null;
            }
            return $data;
        }else{
            return null;
        }
    }

    public function update_vehicle_ml(Request $request){
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if( !$checkToken ) {
            return response()->json(['code' => 200, 'status' => 'error', 'message' => 'El usuario no se encuentra logueado'], 200);
        }
                    
        $rules = [                                                    
            'vehicle_id' => 'required|exists:vehicles,id'                               
        ];               

        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            // existio un error en los campos enviados 
            $data = array(
                'status' => 'error',
                'code' => '200',
                'errors' => $validator->errors()->all()
            );
            return response()->json($data, $data['code']);
        }


        $vehicle = Vehicle::find( $request->vehicle_id );        
        $pictures = [];   
        if(!is_object( $vehicle ) && is_null( $vehicle ) ){
            return response()->json(['code' => 200, 'status' => 'error', 'message' => "El vehículo con id $vehicle_id no existe"], 200);
        }

        $mercadoLibreHelper = new \App\Helpers\MercadoLibreHelper();  
        $token = $mercadoLibreHelper->refresh_access_token();

        $mercado_item_id = $vehicle->mercado_id;

        $vehicle_ml = $this->existsVehicle( $mercado_item_id, $token['response']->access_token );

        if( !is_null( $vehicle_ml ) ){                    
            $pictures = [];   
            if(!is_object($vehicle)){
                return response()->json(["message" => "no existe"], 200);
            }else{
                $images = $vehicle->vehicle_images;

                foreach( $images as $index => $image ){
                    $element = [                    
                        "source" => "https://abcars.mx/abcars-backend/api/image_vehicle/" . $image->path,
                        "position" =>  $index + 1              
                    ];
                    array_push($pictures, $element);
                }

                $data = array(
                    "title" => $vehicle->name . " update",
                    "category_id" => "MLM1744",
                    "price" => $vehicle->price,
                    "currency_id" => "MXN",
                    "available_quantity" => 1,
                    "condition" => ($vehicle->type === "new" ? "new" : "used"),                                    
                    "location" => array(
                        "city" => array(
                            "id" => "TUxBQlNBQTM3Mzda",
                            "name" => "Ciudad de México"
                        ),
                        "state" => array(
                            "id" => "MX-DIF",
                            "name" => "Ciudad de México"
                        ),
                        "country" => array(                
                            "id" => "MX",
                            "name" => "Mexico",
                            "locale" => "es_MX",
                            "currency_id" => "MXN"                    
                        )
                    ),
                    "attributes" => array(
                        array(
                            "id" => "BRAND",
                            "value_id" => null,
                            "value_name" => $vehicle->carmodel->brand->name,
                            "value_struct" => null,
                            "values" => array(
                                array(
                                    "id" => "378802",
                                    "name" => $vehicle->carmodel->brand->name,
                                    "struct" => null,
                                    "source" => 1572
                                )
                            ),
                            "attribute_group_id" => "OTHERS",
                            "attribute_group_name" => "Otros",
                            "name" => "Marca",
                            "source" => 1572
                        ),
                        array(
                            "id" => "TRIM",
                            "value_id" => null,
                            "value_name" => "Versión específica del modelo"
                        ),
                        array(
                            "id" => "DOORS",
                            "value_name" => "4"
                        ),
                        array(
                            "id" => "VEHICLE_YEAR",
                            "value_name" => $vehicle->yearModel
                        ),
                        array(
                            "id" => "FUEL_TYPE",
                            "value_id" => null,
                            "value_name" => "Tipo de combustible"
                        ),
                        array(
                            "id" => "MODEL",
                            "value_id" => null,
                            "value_name" => $vehicle->carmodel->name
                        ),
                        array(
                            "id" => "KILOMETERS",
                            "value_id" => null,
                            "value_name" => $vehicle->km,
                            "value_struct" => null,
                            "values" => array(
                                array(
                                    "id" => null,
                                    "name" => $vehicle->km,
                                    "struct" => null,
                                    "source" => 1572
                                )
                            ),
                            "attribute_group_id" => "OTHERS",
                            "attribute_group_name" => "Otros",
                            "name" => "KILOMETERS",
                            "source" => 1572
                        ),
                        array(
                            "id" => "KILOMETERS",
                            "value_id" => null,
                            "value_name" => $vehicle->km . " km",
                            "value_struct" => null,
                            "values" => array(
                                array(
                                    "id" => null,
                                    "name" => $vehicle->km . " km",
                                    "struct" => null,
                                    "source" => 1572
                                )
                            ),
                            "attribute_group_id" => "OTHERS",
                            "attribute_group_name" => "Otros",
                            "name" => "KILOMETERS",
                            "source" => 1572
                        )
                    ),
                    "pictures" => $pictures            
                );
                
                $jsonData = json_encode($data);        
        
                $curl = curl_init();
        
                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.mercadolibre.com/items/$mercado_item_id",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'PUT',
                    CURLOPT_POSTFIELDS => $jsonData,
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer ' . $token['response']->access_token,
                        'Content-Type: application/json'
                    ),
                ));
        
                $response = curl_exec($curl);        
        
                curl_close($curl);        
                $data = json_decode($response); 
            }   
        }        

        return response()->json([
            'code' => 200, 
            'status' => 'success',
            'response' => $data,             
        ], 200);
    }

    public function delete_vehicle_ml(){
        $mercadoLibreHelper = new \App\Helpers\MercadoLibreHelper();  
        $token = $mercadoLibreHelper->refresh_access_token();

        $mercado_item_id = 'MLM2011260241';

        $vehicle_ml = $this->existsVehicle( $mercado_item_id, $token['response']->access_token );

        $data = array(
            "status" => "closed"                      
        );

        $jsonData = json_encode($data);

        if( !is_null( $vehicle_ml ) ){
            $vehicle = Vehicle::find( 499 );
        
            $pictures = [];   
            if(!is_object($vehicle)){
                return response()->json(["message" => "no existe"], 200);
            }else{                                

                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => "https://api.mercadolibre.com/items/{$mercado_item_id}",
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_CUSTOMREQUEST => "PUT",
                    CURLOPT_POSTFIELDS => $jsonData,
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer ' . $token['response']->access_token,
                    ),
                ));

                $response = curl_exec($curl);
                curl_close($curl);        
                $data = json_decode($response); 

                return response()->json([            
                    "eliminado" => $data
                ], 200);
            }
        }
    }
}
