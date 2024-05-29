<?php
namespace App\Helpers;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class JwtAuth{
  public $key;

  public function __construct(){
    $this->key = 'codecreativity-889998117227466';
  }

  public function signup($email, $password, $getToken = null){
    
    // Buscar si existe el usuario con sus credenciales
    $user = User::where([
      'email' => $email,
      'password' => $password
    ])->first();

    // Comprobar si son correctas
    $signup = false;
    if( is_object($user) ){
      $user = $user->load(['roles', 'permissions']);
      $signup = true;
    }

    // Generar el token del usuario
    if($signup){
      $token = array(
        'code' => 200,
        'status' => 'success',
        'user' => $user,
        'iat' => time(),
        'exp' => time() + (7 * 14 * 60 * 60)
      );
      
      $jwt = JWT::encode($token, $this->key, 'HS256');
      $decoded = JWT::decode($jwt, $this->key, ['HS256']);
      
      // Devolver los datos decodificados o el token en función de un parametro
      if(!is_null($getToken)){
        $data = array(
          'code' => 200,
          'status' => 'success',
          'token' => $jwt
        );
      } else {
        $data = (array) $decoded;
      }

    } else {
      $data = array(
        'code' => 200,
        'status' => 'error',
        'message' => 'Login incorrecto.'
      );
    }

    return $data;
  }


  public function newSignup($email, $password){
    
    // Buscar si existe el usuario con sus credenciales
    $user = User::where([
      'email' => $email,
      'password' => $password
    ])->first();

    if( is_object($user) ){
      $user = $user->load(['roles', 'permissions']);
      
      $token = array(
        'code' => 200,
        'status' => 'success',
        'user' => $user,
        'iat' => time(),
        'exp' => time() + (7 * 14 * 60 * 60)
      );
      
      $jwt = JWT::encode($token, $this->key, 'HS256');
      
      // Devolver los datos decodificados y el token
      $data = array(
        'code' => 200,
        'status' => 'success',
        'message' => 'Login correcto.',
        'token' => $jwt,
        'user'=> $user
      );

    } else {
      $data = array(
        'code' => 200,
        'status' => 'error',
        'message' => 'Login incorrecto.'
      );
    }

    return $data;
  }

  public function checkToken( $jwt, $getIdentity = false ){
    $auth = false;
    try{
      // \Log::info('Token antes de limpiar: ' . $jwt);
      $jwt = str_replace('"', '', $jwt);
      \Log::info('Token después de limpiar comillas: ' . $jwt);
      $decoded = JWT::decode($jwt, $this->key, ['HS256']);
      // \Log::info('Token decodificado: ' . json_encode($decoded));
    }catch(\UnexpectedValueException $e){
      \Log::error('UnexpectedValueException: ' . $e->getMessage());
      $auth = false;
    }catch( \DomainException $e ){
      \Log::error('DomainException: ' . $e->getMessage());
      $auth = false;
    }

    if( !empty($decoded) && is_object($decoded) && isset( $decoded ) ){
      $auth = true;
    }else{
      $auth = false;
    }

    if($getIdentity){
      return $decoded;
    }

    return $auth;
  }  

}
