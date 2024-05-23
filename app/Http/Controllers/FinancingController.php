<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Models\Financing;
use App\Models\Carmodel;
use App\Models\Brand;
use App\Models\Client;
use App\Models\User;

class FinancingController extends Controller
{

  public function index() {       
    $financing = Financing::orderBy('created_at', 'desc')->paginate( 10 );
    $financing->load(['client', 'carmodel', 'brand', 'state', 'references']);

    $data = array(
      'code' => 200,
      'status' => 'success',
      'financing' => $financing
    );

    return response()->json($data, $data['code']);
  }

  public function store(Request $request) {
    //verificacion de datos que este lleno 
    if(is_array($request->all())) {
      //especificacion de tipado y campos requeridos 
      $rules = [
        'lastname' => 'max:255',
        'mothername' => 'required|string',
        'status' => 'required|in:approved,qualified,denied,active',
        'price' => 'required|numeric',
        'hitch' => 'required|numeric',
        'year' => 'required|string',
        'brand_name' => 'required|string',
        'carmodel_name' => 'required|string',
        'monthly_fees' => 'required|numeric',
        'rfc' => 'required|string',
        'civil_status' => 'required|in:Soltero(a),Casado(a) - Bienes Mancomunados,Casado(a) - Bienes Separados,Viudo(a),Divorciado(a),Separado(a),UnionLibre',
        'studies_level' => 'required|in:Primaria,Secundaria,Bachillerato,Licenciatura,Maestría,Doctorado,NoTiene', 
        'economic_dependents' => 'required|numeric',
        'has_vehicle' => 'required|in:yes,no',

        'street_name' => 'required|string',
        'suburb' => 'required|string',
        'number' => 'required',
        'postal_code' => 'required', 
        'state_name' => 'required|string',               
        'municipality' => 'required|string',

        'company' => 'required|string', 
        'employment_situation' => 'required|string', 
        'salary' => 'required|numeric',
        'role' => 'required|string',
        'date_start' => 'required|max:255|string',
        'date_end' => 'max:255',
        'number_phone_company' => 'required|string',
        
        // Addres Company
        'street_name_company' => 'max:255',
        'suburb_company' => 'max:255',
        'number_home_company' => 'max:255',
        'postal_code_company' => 'max:255', 
        'state_company' => 'max:255',                       
        'municipality_company' => 'max:255',

        // Addres Reference
        // 'street_name_reference' => 'required|string',
        // 'suburb_reference' => 'required|string',
        // 'number_reference' => 'required',
        // 'postal_code_reference' => 'required',
        // 'state_reference' => 'required|string',
        // 'municipality_reference' => 'required|string',

        // Questions
        // 'credit_card' => 'required|in:yes,no',
        // 'numbers_card' => 'max:255',
        // 'mortgage_credit' => 'required|in:yes,no',
        // 'automotive_credit' => 'required|in:yes,no',
        // 'third_person' => 'required|in:yes,no',

        // User Agent
        'useragent' => 'required',

        'client_id' => 'required|exists:clients,id'
      ];

      try {
        //validacion de tipado y campos requeridos 
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
          //existio un error en los campos enviados 
          $data = array(
            'status' => 'error',
            'code'   => 400,
            'errors'  => $validator->errors()->all()
          );
        } else {              
          // Reference to Client and User
          $client = Client::select("user_id", "phone1", "phone2")->where('id',  $request->client_id)->get()->first();
          $user = User::where('id',  $client->user_id)->get()->first();
          // Crear el Financing
          $financing = new Financing();
          $financing->lastname = $request->lastname;            
          $financing->mothername = $request->mothername;            
          $financing->status = $request->status;            
          $financing->price = $request->price;            
          $financing->hitch = $request->hitch;  
          $financing->year = $request->year;  
          $financing->brand_name = $request->brand_name;  
          $financing->carmodel_name = $request->carmodel_name;
          $financing->monthly_fees = $request->monthly_fees;
          $financing->rfc = $request->rfc;
          $financing->civil_status = $request->civil_status;
          $financing->studies_level = $request->studies_level;
          $financing->economic_dependents = $request->economic_dependents;
          $financing->has_vehicle = $request->has_vehicle;
          $financing->street_name = $request->street_name;
          $financing->suburb = $request->suburb;
          $financing->number = $request->number;
          $financing->postal_code = $request->postal_code;
          $financing->state_name = $request->state_name;
          $financing->municipality = $request->municipality;
          $financing->company = $request->company;
          $financing->employment_situation = $request->employment_situation;
          $financing->salary = $request->salary;
          $financing->role = $request->role;
          $financing->date_start = $request->date_start;
          $financing->date_end = $request->date_end;
          $financing->number_phone_company = $request->number_phone_company;

          // Addres Company
          $financing->street_name_company = $request->street_name_company;
          $financing->suburb_company = $request->suburb_company;
          $financing->number_home_company = $request->number_home_company;
          $financing->postal_code_company = $request->postal_code_company;
          $financing->state_company = $request->state_company;
          $financing->municipality_company = $request->municipality_company;

          // Addres Reference
          // $financing->street_name_reference = $request->street_name_reference;
          // $financing->suburb_reference = $request->suburb_reference;
          // $financing->number_reference = $request->number_reference;
          // $financing->postal_code_reference = $request->postal_code_reference;
          // $financing->state_reference = $request->state_reference;
          // $financing->municipality_reference = $request->municipality_reference;

          // Questions
          // $financing->credit_card = $request->credit_card;
          // $financing->numbers_card = $request->numbers_card;
          // $financing->mortgage_credit = $request->mortgage_credit;
          // $financing->automotive_credit = $request->automotive_credit;
          // $financing->third_person = $request->third_person;

          // User Agent
          $financing->useragent = $request->useragent;

          $financing->client_id = $request->client_id;

          // Create zappier object
          $zappier = array(
            'fecha'      => date_format(date_create(), "d-m-y H:i:s"),
            'tipo'       => 'Seminuevo',
            'campaña'    => 'Solicitud de financiamiento ABcars',
            'nombre'     => $financing->lastname,
            'apellido'   => $financing->mothername,
            'telefono'   => $client->phone1,
            'correo'     => $user->email,
            'auto'       => $request->brand_name.' '.$request->carmodel_name,
            'comentario' => $financing->monthly_fees.' meses',
            'inversion'  => $financing->hitch
          );

          if($request->seller_id != NULL){
            $financing->seller_id = $request->seller_id;
            $seller = User::where('id',  $request->seller_id)->get()->first();
            $zappier['consultor'] = $seller->name.' '.$seller->surname;
          }

          $financing->save();

          // send content to webhook
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, "https://hooks.zapier.com/hooks/catch/8825119/3tw3rhj");
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
          curl_setopt($ch, CURLOPT_POST, true);
          curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($zappier));

          $data = curl_exec($ch);
          curl_close($ch);


          // Enviar mail icholula@chevroletbalderrama.com
          // \EmailHelper::sendMailCholula('icholula@chevroletbalderrama.com', $user->name, $request->lastname, $request->mothername, $client->phone1, $request->brand_name, $request->carmodel_name, $request->year, $request->price, $request->hitch, $request->monthly_fees);

          // Delete zappier array
          unset($zappier);

          $data = array(
            'status' => 'success',
            'code'   => 200,
            'message' => 'Financing creado exitosamente',
            'financing' => $financing
          );                
        }
      } catch (Exception $e) {
        $data = array(
          'status' => 'error',
          'code'   => 400,
          'message' => 'Los datos enviados no son correctos, ' . $e
        );
      }
    } else {
      $data = array(
        'status' => 'error',
        'code'   => 401,
        'message'  => "El usuario no esta identificado"
      );
    }
    
    return response()->json($data, $data['code']);
  }

  public function update(Request $request, $id) {
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);

    if(is_array($request->all()) && $checkToken) {
      $rules =[
        'lastname' => 'max:255',
        'mothername' => 'required|string',
        'status' => 'required|in:approved,qualified,denied,active',
        'price' => 'required|numeric',
        'hitch' => 'required|numeric',
        'year' => 'required|string',
        'brand_id' => 'required|exists:brands,id',
        'carmodel_id' => 'required|exists:carmodels,id',
        'monthly_fees' => 'required|numeric',
        'rfc' => 'required|string',
        'civil_status' => 'required|in:Soltero(a),Casado(a) - Bienes Mancomunados,Casado(a) - Bienes Separados,Viudo(a),Divorciado(a),Separado(a),UnionLibre',
        'studies_level' => 'required|in:Primaria,Secundaria,Bachillerato,Licenciatura,Maestría,Doctorado,NoTiene', 
        'economic_dependents' => 'required|numeric',
        'has_vehicle' => 'required|in:yes,no',
        'street_name' => 'required|string',
        'suburb' => 'required|string',
        'number' => 'required|string',
        'postal_code' => 'required|string', 
        'state_id' => 'required|exists:states,id',                       
        'municipality' => 'required|string',
        'company' => 'required|string', 
        'employment_situation' => 'required|string', 
        'salary' => 'required|numeric',
        'role' => 'required|string',
        'date_start' => 'required|max:255|string',
        'date_end' => 'max:255',
        'number_phone_company' => 'required|string',
        
        // Addres Company
        'street_name_company' => 'max:255',
        'suburb_company' => 'max:255',
        'number_home_company' => 'max:255',
        'postal_code_company' => 'max:255', 
        'state_company' => 'max:255',                       
        'municipality_company' => 'max:255',

        // Addres Reference
        'street_name_reference' => 'required|string',
        'suburb_reference' => 'required|string',
        'number_reference' => 'required|string',
        'postal_code_reference' => 'required|string',
        'state_reference' => 'required|string',
        'municipality_reference' => 'required|string',

        // Questions
        'credit_card' => 'required|in:yes,no',
        'numbers_card' => 'max:255',
        'mortgage_credit' => 'required|in:yes,no',
        'automotive_credit' => 'required|in:yes,no',
        'third_person' => 'required|in:yes,no',

        // User Agent
        'useragent' => 'required',

        'client_id' => 'required|exists:clients,id'                        
      ];

      try {
        // Obtener package
        $validator = \Validator::make($request->all(), $rules);
        
        if ($validator->fails()) {
          // error en los datos ingresados
          $data = array(
            'status' => 'error',
            'code'   => 400,
            'errors'  => $validator->errors()->all()
          );
        } else {            
          // Reference to Client and User
          $modelo = Carmodel::select("name")->where('id', $request->carmodel_id)->get()->first();
          $brand = Brand::select("name")->where('id',  $request->brand_id)->get()->first();
          $client = Client::select("user_id")->where('id', $request->client_id)->get()->first();
          $user   = User::where('id', $client->user_id)->get()->first();
          
          // Update Financing
          $financing = Financing::find( $id );

          if (is_object($financing) && !empty($financing)) {
            $financing->lastname = $request->lastname;            
            $financing->mothername = $request->mothername;            
            $financing->status = $request->status;            
            $financing->price = $request->price;            
            $financing->hitch = $request->hitch;  
            $financing->year = $request->year;  
            $financing->brand_id = $request->brand_id;  
            $financing->carmodel_id = $request->carmodel_id;
            $financing->monthly_fees = $request->monthly_fees;
            $financing->rfc = $request->rfc;
            $financing->civil_status = $request->civil_status;
            $financing->studies_level = $request->studies_level;
            $financing->economic_dependents = $request->economic_dependents;
            $financing->has_vehicle = $request->has_vehicle;
            $financing->street_name = $request->street_name;
            $financing->suburb = $request->suburb;
            $financing->number = $request->number;
            $financing->postal_code = $request->postal_code;
            $financing->state_id = $request->state_id;
            $financing->municipality = $request->municipality;
            $financing->company = $request->company;
            $financing->employment_situation = $request->employment_situation;
            $financing->salary = $request->salary;
            $financing->role = $request->role;
            $financing->date_start = $request->date_start;
            $financing->date_end = $request->date_end;
            $financing->number_phone_company = $request->number_phone_company;

            // Addres Company
            $financing->street_name_company = $request->street_name_company;
            $financing->suburb_company = $request->suburb_company;
            $financing->number_home_company = $request->number_home_company;
            $financing->postal_code_company = $request->postal_code_company;
            $financing->state_company = $request->state_company;
            $financing->municipality_company = $request->municipality_company;

            // Addres Reference
            $financing->street_name_reference = $request->street_name_reference;
            $financing->suburb_reference = $request->suburb_reference;
            $financing->number_reference = $request->number_reference;
            $financing->postal_code_reference = $request->postal_code_reference;
            $financing->state_reference = $request->state_reference;
            $financing->municipality_reference = $request->municipality_reference;

            // Questions
            $financing->credit_card = $request->credit_card;
            $financing->numbers_card = $request->numbers_card;
            $financing->mortgage_credit = $request->mortgage_credit;
            $financing->automotive_credit = $request->automotive_credit;
            $financing->third_person = $request->third_person;

            // User Agent
            $financing->useragent = $request->useragent;
            
            $financing->client_id = $request->client_id;
            $financing->save();  

            // Enviar mail al cliente
            \EmailHelper::sendMailClient($user->email, $user->name, $user->surname, $brand->name, $modelo->name, $request->year, $request->price, $request->hitch, $request->monthly_fees, $request->status);

            $data = array(
              'status' => 'success',
              'code'   => 200,
              'message' => 'Financing se ha actualizado correctamente',
              'financing' => $financing          
            );
          } else {
            $data = array(
              'status' => 'error',
              'code'   => 404,
              'message' => 'ID de financing no existe'
            );
          }   
        }
      } catch (Exception $e) {
        $data = array(
          'status' => 'error',
          'code'   => 400,
          'message' => 'Los datos enviados no son correctos, ' . $e
        );
      }
    } else {
      $data = array(
        'status' => 'error',
        'code'   => 401,
        'message' => 'El usuario no está identificado'
      );
    }

    return response()->json($data, $data['code']); 
  }

  public function destroy(Request $request,$id) {
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);

    if (is_array($request->all()) && $checkToken) {
      // Inicio Try catch
      try {
        $financing = Financing::find( $id );

        if (is_object($financing) && !is_null($financing)) {
          try {
            $financing->delete();

            $data = array(
              'status' => 'success',
              'code'   => 200,
              'message' => 'Financing ha sido eliminado correctamente'
            );
          } catch (\Illuminate\Database\QueryException $e) {
            //throw $th;
            $data = array(
              'status' => 'error',
              'code'   => 400,
              'message' => $e->getMessage()
            );
          }
        } else {
          $data = array(
            'status' => 'error',
            'code'   => 404,
            'message' => 'El id del Financing no existe'
          );
        }
      } catch (Exception $e) {
        $data = array(
          'status' => 'error',
          'code'   => 400,
          'message' => 'Los datos enviados no son correctos, ' . $e
        );
      }        
    } else {
      $data = array(
        'status' => 'error',
        'code'   => 401,
        'message' => 'El usuario no está identificado'
      );
    }

    return response()->json($data, $data['code']);
  }

  public function financingsbyUser(int $user_id) {
    // Find user
    $user = User::find($user_id);
    
    // Checking if exist her user
    if (is_object($user)) {
      // Checking if exists her client
      if (is_object($user->clients()->first())) {
        
        $client_id = $user->clients()->first();
        $financings = Financing::where('client_id', $client_id->id)->get();

        $data = array(
          'code'   => 200,
          'status' => 'success',
          'message' => 'Client encontrado.',
          'financings' => $financings->load(['brand', 'carmodel']),
          'user' => $user
        );

      } else {
        $data = array(
          'code'   => 404,
          'status' => 'error',
          'message' => 'El usuario no fue encontrado con su client_id.'
        );
      }     
    } else {
      $data = array(
        'code'   => 404,
        'status' => 'error',
        'message' => 'El usuario ingresado no fue encontrado.'
      );
    }
    
    return response()->json($data, $data['code']);
  }

  public function uploadFilesFinancing(Request $request, $financing_id) {
    if (is_array($request->all())) {
      $rules = [
        'ine_front' => 'required|File',
        'ine_back' => 'required|File',
      ];
      
      try {        
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {          
          $data = array(
            'code'   => '400',
            'status' => 'error',
            'errors'  => $validator->errors()->all()
          );
        } else {            
          $financing = Financing::find($financing_id);

          if (is_object($financing) && !empty($financing)) {                                      
            // Pictures - Checking exist the folder 'financing_ines'
            $name_directory = 'financings';
            $directory = storage_path() . '/app/' . $name_directory;

            if (!file_exists($directory)) {                
              mkdir($directory, 0777, true);
            }

            // Add picture INE FRONT
            $ine_front = $request->file('ine_front');
            if (is_object($ine_front) && !empty($ine_front)) {
              $name = \ImageHelper::upload($ine_front, $name_directory);            
              $financing->ine_front = str_replace(' ', '', $name);
            }

            // Add picture INE BACK
            $ine_back = $request->file('ine_back');
            if (is_object($ine_back) && !empty($ine_back)) {
              $name = \ImageHelper::upload($ine_back, $name_directory);            
              $financing->ine_back = str_replace(' ', '', $name);              
            }       
            
            // Add picture ADDRESS PROOF
            $address_proof = $request->file('address_proof');
            if (is_object($address_proof) && !empty($address_proof)) {
              $name = \ImageHelper::upload($address_proof, $name_directory);            
              $financing->address_proof = str_replace(' ', '', $name);              
            }       

            if ($financing->save()) {
              $data = array(
                'code'   => '200',
                'status' => 'success',
                'message' => 'Los archivos del financiamiento han sido subido exitosamente.',                
              );
            }
          } else {
            $data = array(
              'status' => 'error',
              'code'   => '404',
              'message' => 'El financiamiento no existe.'
            );
          }            
        }
      } catch (Exception $e) {
        $data = array(
          'code'   => '401',
          'status' => 'error',
          'message' => 'Los datos enviados no son correctos, ' . $e
        );
      }      
    } else {
      $data = array(
        'code'   => '401',
        'status' => 'error',
        'message' => 'El usuario no está identificado.'
      );
    }

    return response()->json($data, $data['code']);
  }

  public function previewFilesFinancing(Request $request, $id) {
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);

    if(is_array($request->all()) && $checkToken) {
      // Get financing_id
      $financing = Financing::find($id);

      if (is_object($financing)) {
        if ($request->type === 'front') {
          $data = $this->returnFile($financing->ine_front);
        } else {  
          $data = $this->returnFile($financing->ine_back);
        }
      } else {
        $data = array(
          'status' => 'error',
          'code'   => 404,
          'message' => 'El financiamiento no ha sido encontrado'
        );  
      }
    } else {
      $data = array(
        'status' => 'error',
        'code'   => 401,
        'message' => 'El usuario no está identificado'
      );
    }

    return response()->json($data, $data['code']); 
  }

  private function returnFile($filename) {
    $file = Storage::disk('financings')->exists($filename);
          
    if($file) {
      $data = array(
        'status' => 'success',
        'code'   => 200,
        'encode_picture' => base64_encode(Storage::disk('financings')->get($filename))
      );  
    } else {
      $data = array(
        'status' => 'not-found',
        'code'   => 404,
        'encode_picture' => null
      );
    }

    return $data;
  }

  public function financing_ine_front($financing_id)
  {
    header('Access-Control-Allow-Origin: *');
    $financing = Financing::find($financing_id);
    try {
      $file_front = Storage::disk('financings')->download($financing->ine_front);
    } catch (\Exception $e) {
      $file_front = Storage::disk('vehicles')->download('principal.png');
    }
    
    return $file_front;
  }
  
  public function financing_ine_back($financing_id)
  {
    header('Access-Control-Allow-Origin: *');
    $financing = Financing::find($financing_id);
    try {
      $file_back = Storage::disk('financings')->download($financing->ine_back);
    } catch (\Exception $e) {
      $file_back = Storage::disk('vehicles')->download('principal.png');
    }
    return $file_back;
  }

  public function search_financing(String $word = '', int $cantidad)
  {
    // dd($word);
    // Word
    if ($word === 'a') {
      $word_condition = "carmodels.name LIKE NOT NULL";
    }else{
      $word_condition = "carmodels.name LIKE '%$word%' OR brands.name LIKE '%$word%' OR financings.year LIKE '%$word%' OR financings.price LIKE '%$word%' OR financings.hitch LIKE '%$word%' OR financings.monthly_fees LIKE '%$word%' OR financings.created_at LIKE '%$word%' OR users.name LIKE '%$word%' OR financings.lastname LIKE '%$word%' OR users.surname LIKE '%$word%' OR financings.mothername LIKE '%$word%' ";
    }
    $financings = Financing::select('financings.*')
            ->join('carmodels', 'carmodels.id', 'financings.carmodel_id')
            ->join('brands', 'brands.id', 'financings.brand_id')
            ->join('clients', 'clients.id', 'financings.client_id')
            ->join('users', 'users.id', 'clients.user_id')
            ->whereRaw(
                "
                  (
                    $word_condition
                  )
                "
              )
              ->orderBy('created_at', 'desc') //get();
              ->paginate($cantidad);
    $financings->load('client', 'carmodel', 'brand', 'state', 'references');
    $data = array(
      'code' => 200,
      'status' => 'success',
      'financings' => $financings
    );

    return response()->json($data, $data['code']);
    
  }
}
