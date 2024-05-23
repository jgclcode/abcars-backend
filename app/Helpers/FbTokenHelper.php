<?php
namespace App\Helpers;
use GuzzleHttp\Client;


class FbTokenHelper {

    
    public static function getToken(){

        $id_page ="102437159099755";
        $token="EAAF3UjYMQZCMBO7IJsf3piIFBCBshGZALmdITyeyHROtf2dwhRyfmZAcHaL1nGnPgSZAlPZCpvxyTsD0HVF2iMKBW2y7D23vibASEHaTFKlZBxBpjjxcjZBMl4eyvp1adHZAx1vYXpu4eZAyNIyaKtnthZAhDSQxwrPVuCTujQQ8L1wZCxvcXT0UahjopZCKPDkAfJ4ZD";

        $url="https://graph.facebook.com/".$id_page."/accounts?access_token=".$token;

        $client = new Client();
        $response = $client->get($url, [
            
        ]);

        $result = json_decode($response->getBody(), true);

        return $accessToken = $result['data'][0]['access_token'];
 
    }
}
