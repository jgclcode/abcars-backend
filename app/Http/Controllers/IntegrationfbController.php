<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;

use GuzzleHttp\Client;
use App\Models\Vehicle;
use App\Models\Vehicle_image;


class IntegrationfbController extends Controller
{
    public function publish(Request $request){
        //$token=\FbTokenHelper::getToken();  
        $id_page = "102437159099755";
        $url     = "https://graph.facebook.com/{$id_page}/photos";

 
        $rules =[
             'vehicle_id' => 'required|exists:vehicles,id'
        ];

        try{
            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                    $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'errors'  => $validator->errors()->all()
                    );
            }
            else{    

                $vehicle = Vehicle::find($request->vehicle_id);

                $vehicle_image = Vehicle_image::where('vehicle_id',$request->vehicle_id)->first();
                $image ="";
                if(is_object($vehicle_image)){
                    $image ="https://abcars.mx/abcars-backend/api/image_vehicle/".$vehicle_image->path;
                 }else{
                    $image ="https://bmwvecsahidalgo.com/assets/images/demo_image.png";
                }
 
                if($vehicle->fb_id){
                    $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'message' => 'Ya se encuentra publicado',
                    );  
                }
                else{
                    $data = [
                        'url' => $image,
                        'access_token' => "EAANhMIqtghABO5ku1LjOBGR3xb5wVfocfOJfUfX8eaSmc5ZAzOSSdtmAE9q5JQlFZCnkTbjF5JoPAbJUiWNs5JI234dUV8BjW1eJZBLZATKzZA71BJIDZAlJjATCx47UIg8akeMiDWNXO4qtSRZCRPkGZBLbjsIFssBZCDNTZAN8kK2m4H6ATVFs9b8R6ncPRSL4UZD",
                        'message' => $vehicle->name.",  ".$vehicle->description,
                    ];
    
                    $client = new Client();
                    $response = $client->post($url, [
                        'form_params' => $data,
                    ]);
                    $result = json_decode($response->getBody(), true);
                    $vehicle->fb_id = $result['id'];
                    $vehicle->save();
                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'Publicado exitosamente',
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

        return response()->json($data, $data['code']);
    }

    public function deletePublish(Request $request){

        //$token=\FbTokenHelper::getToken(); 
         
        $rules =[
            'vehicle_id' => 'required|exists:vehicles,id'
        ];     
        
        $vehicle = Vehicle::find($request->vehicle_id);
        try{          
            if($vehicle->fb_id==!null){
                $url   = "https://graph.facebook.com/v18.0/".$vehicle->fb_id."?access_token=EAANhMIqtghABO5ku1LjOBGR3xb5wVfocfOJfUfX8eaSmc5ZAzOSSdtmAE9q5JQlFZCnkTbjF5JoPAbJUiWNs5JI234dUV8BjW1eJZBLZATKzZA71BJIDZAlJjATCx47UIg8akeMiDWNXO4qtSRZCRPkGZBLbjsIFssBZCDNTZAN8kK2m4H6ATVFs9b8R6ncPRSL4UZD";
    
                $client = new Client();             
                $response = $client->delete($url, []);            
                $result = json_decode($response->getBody(), true);

                $vehicle->fb_id = null;
                $vehicle->save();   
    
                if($result['success']){
                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'La Publicacion ha sido Eliminada Correctamente'
                    );
                }
                else{
                    $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'message' => 'token vencido'
                    );
                }
            }
            else{
                $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'message' => 'No Existe Publicacion del Vehiculo'
                );        
            }
             
        }catch (Exception $e) {
            $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'Los datos enviados no son correctos, ' . $e
            );
        }    

        return response()->json($data, $data['code']);

    }



}

