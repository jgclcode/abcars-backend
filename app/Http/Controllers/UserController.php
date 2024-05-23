<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Client;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UserController extends Controller
{
    public function index( int $total = 10 )
    {        
        $users = User::paginate($total);
        $data = array(
          'code' => 200,
          'status' => 'success',
          'users' => $users
        );
        return response()->json($data, $data['code']);
    }

    public function register(Request $request){
      if (!is_array($request->all())) {
        $data = array(
          'status' => 'error',
          'code'   => '200',
          'message'  => "request must be an array"
        );
      }

      $rules = [
        'name'      => 'required|max:255|string',
        'surname'   => 'required|max:255|string',            
        'email'     => 'required|email|unique:users',
        'picture'   => 'File',            
        'gender'    => 'required|in:m,f',                        
      ];
      
      try {
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          // Esta condición se ejecuta si la validación de uno o más campos es incorrecta
          $data = array(
            'status' => 'error',
            'code'   => '200',
            'errors'  => $validator->errors()->all()
          );
        }else{
          // Validación pasada correctamente                                           

          // Cifrar la contraseña
          if( $request->password ){
            $real_password = $request->password;                              
          }else{
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';              
            $real_password = substr(str_shuffle($permitted_chars), 0, 10);
          }

          $pwd = hash('sha256', $real_password);
          
          // Crear el usuario
          $user = new User();
          $user->name = ucwords(strtolower($request->name));
          $user->surname = ucwords(strtolower($request->surname));           
          $user->email = $request->email;
          $user->gender = $request->gender;
          $user->password = $pwd;
          
          $user->assignRole('client');                

          //////////////////////////////////////////// 
          // Verificar si existe la carpeta vehicles
          $nombre_directorio = 'users';
          $directorio = storage_path() . '/app/' . $nombre_directorio;
          if (!file_exists($directorio)) {                
              mkdir($directorio, 0777, true);
          }
          // Fin verificar si existe la carpeta vehicles                             
          $image = $request->file('picture');
          if( is_object( $image ) && !empty( $image )){
              $nombre = \ImageHelper::upload($image, $directorio);
              $user->picture = $nombre;
          }                            
          ////////////////////////////////////////////
          $user->save();  
          //Enviar email
          // \EmailHelper::sendEmail($request->email, $real_password, $user->name, $user->surname);
          
          $data = array(
              'status' => 'success',
              'code'   => '200',
              'message' => 'El usuario se ha creado correctamente',
              'user' => $user
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
  
    public function login(Request $request){
        if (!is_array($request->all())) {
              $data = array(
                'status' => 'error',
                'code'   => '200',
                'message'  => "request must be an array"
              );
        }
        
        $rules = [
              'email'     => 'required|email',
              'password'  => 'required|max:255|string',
        ];

        try {
            $validator = \Validator::make($request->all(), $rules);
              if ($validator->fails()) {
                // Esta condición se ejecuta si la validación de uno o más campos es incorrecta
                $data = array(
                  'status' => 'error',
                  'code'   => '200',
                  'errors'  => $validator->errors()->all()
                );
                
              } else {

                // Validación pasada correctamente
                $jwtAuth = new \App\Helpers\JwtAuth();

                // Cifrar contraseña
                $pwd = hash('sha256', $request->password);

                // Devolver token o datos
                $data = $jwtAuth->signup($request->email, $pwd);
                
                if( !empty($request->gettoken) ){
                  $data = $jwtAuth->signup($request->email, $pwd, true);
                }
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

    public function newLogin(Request $request){

        if (!is_array($request->all())) {
            $data = array(
                'status' => 'error',
                'code'   => '200',
                'message'  => "request must be an array"
            );
        }

        $rules = [
            'email'     => 'required|email',
            'password'  => 'required|max:255|string',
        ];

        try {

            $validator = \Validator::make($request->all(), $rules);
            
            if ($validator->fails()) {
            
                $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'errors'  => $validator->errors()->all()
                );
            
            } else {
              
                // Validación pasada correctamente
                $jwtAuth = new \App\Helpers\JwtAuth();

                // Cifrar contraseña
                $pwd = hash('sha256', $request->password);

                // Devolver token y datos
                $data = $jwtAuth->newSignup($request->email, $pwd);
            }

        } catch (Exception $e) {
            $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'Los datos enviados no son correctos, ' . $e
            );
        }

        return response()->json($data);
  }     

    public function store(Request $request)
    {
        //
    }
   
    public function update(Request $request, $id)
    {
      // Comprobar si el usuario esta identificado
      $token = $request->header('Authorization');
      $jwtAuth = new \App\Helpers\JwtAuth();
      $checkToken = $jwtAuth->checkToken($token);
      if ( is_array($request->all()) && $checkToken ) {

        $rules = [          
            'name'      => 'required|max:255|string',
            'surname'   => 'required|max:255|string',            
            //'email'     => 'required|email|unique:users',
            'picture'   => 'File',            
            'gender'    => 'required|in:m,f',
            'password'  => 'required|max:255|string' 
        ];
        // Inicio Try catch
        try {
          // Obtener usuario
          $validator = \Validator::make($request->all(), $rules);
          if ($validator->fails() ) {
            // Esta condición se ejecuta si la validación de uno o más campos es incorrecta
            $data = array(
              'code'   => '200',
              'status' => 'error',
              'errors'  => $validator->errors()->all()
            );
          }else{            
            $pwd = hash('sha256', $request->password);

            $user = User::find( $id );
            if( is_object($user) && !empty($user) ){                
                $user->name = ucwords(strtolower($request->name));
                $user->surname = ucwords(strtolower($request->surname));               
                //$user->email = $request->email;
                $user->gender = $request->gender;
                $user->password = $pwd;                
                ////////////////////////////////////////////  
                // Verificar si existe la carpeta users
                $nombre_directorio = 'users';
                $directorio = storage_path() . '/app/' . $nombre_directorio;
                if (!file_exists($directorio)) {                
                    mkdir($directorio, 0777, true);
                }
                // Fin verificar si existe la carpeta vehicles                          
                $image = $request->file('picture');
                if( is_object( $image ) && !empty( $image )){
                    \ImageHelper::delete( $nombre_directorio, $user->picture );
                    $nombre = \ImageHelper::upload($image, $nombre_directorio);
                    $user->picture = $nombre;
                }                               
                ////////////////////////////////////////////
                $user->save();                                
                $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'message' => 'El usuario se ha actualizado correctamente',
                    'user' => $user
                );
            }else{
                $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'message' => 'El usuario no existe'
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
        // Fin Try catch
      }else{
        $data = array(
          'status' => 'error',
          'code'   => '200',
          'message' => 'El usuario no está identificado'
        );
      }

      return response()->json($data, $data['code']);
    }

    public function destroy(Request $request, $id)
    {
      // Comprobar si el usuario esta identificado
      $token = $request->header('Authorization');
      $jwtAuth = new \App\Helpers\JwtAuth();
      $checkToken = $jwtAuth->checkToken($token);
      if (is_array($request->all()) && $checkToken) {
        // Inicio Try catch
        try {
          // Obtener usuario
          $user = User::find( $request->id );

          if (is_object( $user ) && !is_null( $user ) ) {
            try {
              $user->delete();
              $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'El usuario ha sido eliminado correctamente'
              );
            }catch (\Illuminate\Database\QueryException $e){
                $data = array(
                    'status' => 'error',
                    'code'   => '404',
                    'message' => $e->getMessage()
                );
            }
          }else{
            $data = array(
              'status' => 'error',
              'code'   => '404',
              'message' => 'El usuario no existe'
            );
          }

        }catch (Exception $e) {
          $data = array(
            'status' => 'error',
            'code'   => '404',
            'message' => 'Los datos enviados no son correctos, ' . $e
          );
        }
        // Fin Try catch
      }else{
        $data = array(
          'status' => 'error',
          'code'   => '404',
          'message' => 'El usuario no está identificado'
        );
      }

      return response()->json($data, $data['code']);

    }
    
    // Get information user
    public function userById(int $user_id) {
      // Get user
      $user = User::find($user_id);
      $user->getRoleNames();

      if (is_object($user) && !is_null($user) ) {
        $data = array(
          'code'   => 200,
          'status' => 'success',
          'user' => $user->load('clients')
        );
      } else {
        $data = array(
          'code'   => 404,
          'status' => 'error',
          'message' => 'El usuario no existe'
        );
      }

      return response()->json($data, $data['code']);
    }

    // Get information user
    public function sellerById(int $user_id) {
      // Get user
      $user = User::find($user_id);

      if (is_object($user) && !is_null($user) ) {
        $user->getRoleNames();
        $data = array(
          'code'   => 200,
          'status' => 'success',
          'user' => $user
        );
      } else {
        $data = array(
          'code'   => 404,
          'status' => 'error',
          'message' => 'El usuario no existe'
        );
      }

      return response()->json($data, $data['code']);
    }

    // Get information user
    public function getUser(Request $request, int $user_id) {
      // Check if the user is logged in
      $token = $request->header('Authorization');
      $jwtAuth = new \App\Helpers\JwtAuth();
      $checkToken = $jwtAuth->checkToken($token);

      if (is_array($request->all()) && $checkToken) {
        // Start Try Catch
        try {
          // Get user
          $user = User::find($user_id);

          if (is_object($user) && !is_null($user) ) {
            $data = array(
              'code'   => 200,
              'status' => 'success',
              'user' => $user->load('clients')
            );
          } else {
            $data = array(
              'code'   => 404,
              'status' => 'error',
              'message' => 'El usuario no existe'
            );
          }
        } catch (Exception $e) {
          $data = array(
            'code'   => 400,
            'status' => 'error',
            'message' => 'Los datos enviados no son correctos, ' . $e
          );
        }
      } else {
        $data = array(
          'code'   => 401,
          'status' => 'error',
          'message' => 'El usuario no está identificado'
        );
      }

      return response()->json($data, $data['code']);
    }

    public function getUserByEmail( String $email ){
      $user = User::where('email', $email)->first();
      $data = array(
        'code'   => 200,
        'status' => 'success',
        'user' => is_object($user) ? $user->load('clients') : null
      );

      return response()->json($data, $data['code']);
    }

    public function getClient( int $user_id ){
      $user = User::find( $user_id );
      $client = null;
      if( is_object( $user ) && !is_null( $user ) ){
        $client = $user->clients()->first();
      }
      $data = array(
        'code' => 200,
        'status' => 'success',
        'client' => $client
      );
      return response()->json($data, $data['code']);
    }

    public function setImage( Request $request, int $user_id ){
      // Comprobar si el usuario esta identificado
      $token = $request->header('Authorization');
      $jwtAuth = new \App\Helpers\JwtAuth();
      $checkToken = $jwtAuth->checkToken($token);
      if ( is_array($request->all()) && $checkToken ) {
        $rules = [          
            'picture'   => 'required|File'
        ];
        // Inicio Try catch
        try {
          // Obtener usuario
          $validator = \Validator::make($request->all(), $rules);
          if ($validator->fails() ) {
            // Esta condición se ejecuta si la validación de uno o más campos es incorrecta
            $data = array(
              'code'   => '200',
              'status' => 'error',
              'errors'  => $validator->errors()->all()
            );
          }else{            
            $user = User::find( $user_id );
            if( is_object($user) && !empty($user) ){                                          
                ////////////////////////////////////////////  
                // Verificar si existe la carpeta users
                $nombre_directorio = 'users';
                $directorio = storage_path() . '/app/' . $nombre_directorio;
                if (!file_exists($directorio)) {                
                    mkdir($directorio, 0777, true);
                }
                // Fin verificar si existe la carpeta vehicles                          
                $image = $request->file('picture');
                if( is_object( $image ) && !empty( $image )){
                    \ImageHelper::delete( $nombre_directorio, $user->picture );
                    $nombre = \ImageHelper::upload($image, $nombre_directorio);
                    $user->picture = $nombre;
                }                               
                ////////////////////////////////////////////
                $user->save();                                
                $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'message' => 'La imagen del usuario se ha actualizado correctamente',
                    'user' => $user
                );
            }else{
                $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'message' => 'El usuario no existe'
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
        // Fin Try catch
      }else{
        $data = array(
          'status' => 'error',
          'code'   => '200',
          'message' => 'El usuario no está identificado'
        );
      }

      return response()->json($data, $data['code']);
    }

    public function getImage($filename){
      header('Access-Control-Allow-Origin: *');
      $file = '';
      try{
          $file = Storage::disk('users')->get($filename);
      }catch( \Exception $e ){
          $file = Storage::disk('users')->get('principal.png');
      }
      return new Response($file, 200);
    }

    public function recoverAccount( Request $request ){
      if (!is_array($request->all())) {
        $data = array(
          'status' => 'error',
          'code'   => '200',
          'message'  => "request must be an array"
        );
      }

      $rules = [          
          'email'     => 'required|email'
      ];

      try {
        $validator = \Validator::make($request->all(), $rules);
        if ($validator->fails()) {
          // Esta condición se ejecuta si la validación de uno o más campos es incorrecta
          $data = array(
            'status' => 'error',
            'code'   => '200',
            'errors'  => $validator->errors()->all()
          );
        }else{          
          $user = User::where("email", $request->email)->first();
          if( is_object($user) && !is_null($user) ){
            $jwtAuth = new \App\Helpers\JwtAuth();
            $token = $jwtAuth->signup($request->email, $user->password, true);
            if( is_array($token) && isset($token['token'])){
              //Enviar email
              \EmailHelper::resetPassword($request->email, $user->name, $user->surname, $token['token']);
              $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'El email ha sido enviado correctamente',
                'token' => $token['token']
              );  
            }                         
          }else{    
            $data = array(
              'status' => 'error',
              'code'   => '200',
              'message' => 'No existe ningun usuario con el email ingresado'
            );
          }                       
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

    public function resetPassword(Request $request)
    {
      if ( is_array($request->all()) ) {
        $rules = [          
            'token'      => 'required|string',
            'password'  => 'required|max:255|string' 
        ];
        // Inicio Try catch
        try {
          // Obtener usuario
          $validator = \Validator::make($request->all(), $rules);
          if ($validator->fails() ) {
            // Esta condición se ejecuta si la validación de uno o más campos es incorrecta
            $data = array(
              'code'   => '200',
              'status' => 'error',
              'errors'  => $validator->errors()->all()
            );
          }else{   
            $jwtAuth = new \App\Helpers\JwtAuth();
            $checkToken = $jwtAuth->checkToken(trim($request->token), true ); 

            $pwd = hash('sha256', $request->password);
            if( isset($checkToken->user) && is_object($checkToken->user) ){
              $user = User::find( $checkToken->user->id );
            }else{
              $user = null;
            }
            
            if( is_object($user) && !is_null($user) ){                
                $user->password = $pwd;                
                $user->save();                                
                $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'message' => 'La password ha actualizada correctamente',
                    'user' => $user
                );
            }else{
                $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'message' => 'El usuario no existe'
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
        // Fin Try catch
      }else{
        $data = array(
          'status' => 'error',
          'code'   => '200',
          'message' => 'Ocurrió un error'
        );
      }

      return response()->json($data, $data['code']);
    }

    public function updateUserAndClient(Request $request, int $id ){
      if ( is_array($request->all()) ) {

        $rules = [ 
            // Datos del usuario         
            'name'      => 'required|max:255|string',
            'surname'   => 'required|max:255|string',            
            'email'      => [
              'required',
              'email',
              Rule::unique('users')->ignore($id),
            ],
            'gender'    => 'required|in:m,f',
            'password'  => 'required|max:255|string', 
            // Datos del cliente
            'phoneOne'    => 'required|numeric',
            'phoneTwo'    => 'required|numeric',
            'curp'      => 'required|string',
            'role_name' => 'required|string'
        ];
        // Inicio Try catch
        try {
          // Obtener usuario
          $validator = \Validator::make($request->all(), $rules);
          if ($validator->fails() ) {
            // Esta condición se ejecuta si la validación de uno o más campos es incorrecta
            $data = array(
              'code'   => '200',
              'status' => 'error',
              'errors'  => $validator->errors()->all()
            );
          }else{            
            $pwd = hash('sha256', $request->password);

            $user = User::find( $id );
            if( is_object($user) && !empty($user) ){                
                $user->name = ucwords(strtolower($request->name));
                $user->surname = ucwords(strtolower($request->surname));               
                $user->email = $request->email;
                $user->gender = $request->gender;
                $user->password = $pwd;
                $user->roles()->detach();
                $user->assignRole($request->role_name);
                
                $total_clients = $user->clients()->count();
                
                if( $user->save() && $total_clients > 0 ){
                  $client = $user->clients()->first();
                  $client->phone1 = $request->phoneOne;
                  $client->phone2 = $request->phoneTwo;
                  $client->curp = $request->curp;
                  $client->save();
                }
                $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'message' => 'El usuario se ha actualizado correctamente',
                    'user' => $user->load(['clients'])
                );
            }else{
                $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'message' => 'El usuario no existe'
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
        // Fin Try catch
      }

      return response()->json($data, $data['code']);
    }

    public function createUserAndClient(Request $request ){
      if ( is_array($request->all()) ) {
        $rules = [ 
            // Datos del usuario         
            'name'      => 'required|max:255|string',
            'surname'   => 'required|max:255|string',            
            'email'     => 'required|email|unique:users',
            'gender'    => 'required|in:m,f',
            'password'  => 'required|max:255|string', 
            // Datos del cliente
            'phoneOne'    => 'required|numeric',
            'phoneTwo'    => 'required|numeric',
            'curp'      => 'required|string',
            'role_name' => 'required|string'
        ];
        // Inicio Try catch
        try {
          // Obtener usuario
          $validator = \Validator::make($request->all(), $rules);
          if ($validator->fails() ) {
            // Esta condición se ejecuta si la validación de uno o más campos es incorrecta
            $data = array(
              'code'   => '200',
              'status' => 'error',
              'errors'  => $validator->errors()->all()
            );
          }else{            
            $pwd = hash('sha256', $request->password);

            $user = new User;
                       
            $user->name = ucwords(strtolower($request->name));
            $user->surname = ucwords(strtolower($request->surname));               
            $user->email = $request->email;
            $user->gender = $request->gender;
            $user->password = $pwd;
            $user->assignRole($request->role_name);
            $total_clients = $user->clients()->count();
            
            if( $user->save() ){
              $client = new Client;
              $client->phone1 = $request->phoneOne;
              $client->phone2 = $request->phoneTwo;
              $client->curp = $request->curp;
              $client->points = 0;
              
              // Generate reference rewards of 7 characters dinamic            
              $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';                        
              $flag = true;
              
              // Checking reference rewards
              while ($flag) {
                $reference = substr(str_shuffle($permitted_chars), 0, 8);
                $rewardExists = Client::where('rewards', $reference)->first(); 

                if (!is_object($rewardExists)) {
                  $client->rewards = $reference;
                  $flag = false;
                }
              }
              
              $client->source_id = 1;
              $client->user_id = $user->id;
              $client->save();
            }
            $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'El usuario se ha creado correctamente',
                'user' => $user->load(['clients'])
            );
                       
          }
        }catch (Exception $e) {
              $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'Los datos enviados no son correctos, ' . $e
              );
        }
        // Fin Try catch
      }

      return response()->json($data, $data['code']);
    }

    public function deleteUserAndClient(Request $request, $id)
    {      
      if ( is_array($request->all()) ) {
        // Inicio Try catch
        try {
          // Obtener usuario
          $user = User::find( $request->id );
          $client = is_object( $user ) ? $user->clients()->first() : null;
          
          if( is_object($client) ){
            $client->delete();
            $client = null;
          }

          if (is_object( $user ) && !is_null( $user ) && is_null( $client ) ) {
            try {
              $user->delete();
              $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'El usuario ha sido eliminado correctamente'
              );
            }catch (\Illuminate\Database\QueryException $e){
                $data = array(
                    'status' => 'error',
                    'code'   => '404',
                    'message' => $e->getMessage()
                );
            }
          }else{
            $data = array(
              'status' => 'error',
              'code'   => '404',
              'message' => 'El usuario no existe'
            );
          }

        }catch (Exception $e) {
          $data = array(
            'status' => 'error',
            'code'   => '404',
            'message' => 'Los datos enviados no son correctos, ' . $e
          );
        }
        // Fin Try catch
      }

      return response()->json($data, $data['code']);

    }

    public function getUsersWithoutClient( $assigned_client_id = null ){ 
      $users = User::doesnthave('clients')->get();
      if( !is_null( $assigned_client_id ) ){
        $client = Client::find( $assigned_client_id );
        if( is_object( $client ) && !is_null( $client ) ){
          $client_users = $client->user()->get();
          $users = $users->merge($client_users);
        }                
      }     
            
      $data = array(
        'code' => 200,
        'status' => 'success',
        'users' => $users
      );
      return response()->json( $data, $data['code'] );
    }

    public function sendmails($id){
      $user = User::where('id', $id)->first();
       \EmailHelper::sendEmailuser($user->email, $user->name, $user->surname);
        echo ("EMAIL ENVIADO");
     }
}
