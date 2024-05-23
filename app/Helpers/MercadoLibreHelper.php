<?php

namespace App\Helpers;
  
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\Temporary_token;
  
class MercadoLibreHelper { 
    private $client_id = '8179146459529409';
    private $client_secret = 'tqkcHpMaskfswiAacgeZ8WMl7AdEM6IG';

    // private $client_id = '234666534415693';
    // private $client_secret = 'JJdjVhdj7piYnAIwQTLh9V5j8k73rQDK';
    private $refresh_token = null;

    public function __construct()
    {        
        $temporary_token = Temporary_token::where('platform', 'mercado libre')->first();
        if( is_object($temporary_token) && !is_null($temporary_token) ){
            $this->refresh_token = $temporary_token->refresh_token;
        }         
    }

    public function access_token(String $code){               
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.mercadolibre.com/oauth/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "grant_type=authorization_code&client_id=".$this->client_id."&client_secret=".$this->client_secret."&code=".$code."&redirect_uri=https%3A%2F%2Fabcars.mx%2Fabcars-backend",
            CURLOPT_HTTPHEADER => array(
                'accept: application/json',
                'content-type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $data = json_decode($response);  
        
        if( isset($data->refresh_token) ){
            $temporary_token = new Temporary_token;
            $temporary_token->platform = 'mercado libre';
            $temporary_token->refresh_token = $data->refresh_token;
            $temporary_token->save();   
        }else{
            return [            
                'response' => $data, 
                'temporary_token' => null
            ]; 
        }            

        return [
            'response' => $data, 
            'temporary_token' => $temporary_token                  
        ];        
    }

    public function refresh_access_token(){                     

        if( is_null($this->refresh_token) ){
            return response()->json([
                'code' => 200,
                'status' => 'error',
                'message' => 'actualmente no hay un refresh token',                  
            ], 200);   
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.mercadolibre.com/oauth/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => "grant_type=refresh_token&client_id=".$this->client_id."&client_secret=".$this->client_secret."&refresh_token=".$this->refresh_token,
            CURLOPT_HTTPHEADER => array(
                'accept: application/json',
                'content-type: application/x-www-form-urlencoded'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);        
        $data = json_decode($response);   
        
        $temporary_token = Temporary_token::where('platform', 'mercado libre')->first();
        if( is_object($temporary_token) && !is_null($temporary_token) ){
            $temporary_token->refresh_token = $data->refresh_token;
            $temporary_token->save();
        }   

        return [            
            'response' => $data, 
            'temporary_token' => $temporary_token                 
        ];     
    }
    
    public function add_description_item( String $ACCESS_TOKEN, String $ITEM_ID, String $description){
        $data = array(            
            "plain_text" => $description
        );
        
        $jsonData = json_encode($data);        

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.mercadolibre.com/items/$ITEM_ID/description",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $ACCESS_TOKEN,
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);        
        $data = json_decode($response);

        return $data;
    }
}
