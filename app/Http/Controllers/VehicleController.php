<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Carmodel;
use App\Models\Vehicle;
use App\Models\Choice;
use App\Models\Client;
use App\Models\Bill;
use App\Models\Purchase;
use App\Models\Expense;
use App\Models\Set;
use App\Models\State;
use App\Models\Log;
use App\Models\Shield;
use Illuminate\Support\Facades\DB;
use App\Models\SetImage;
use App\Models\Check_vehicle;
use Illuminate\Support\Facades\Storage;



// Excel
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\VehiclesImport;
use App\Imports\UpdatePromotionsImport;

class VehicleController extends Controller
{
    public function index(Request $request){

            $vehicle = Vehicle::paginate( 10 );
            $data = array(
                'code' => 200,
                'status' => 'success',
                'vehicle' => $vehicle
            );

        return response()->json($data, $data['code']);
    }

    public function store(Request $request){
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        //verificacion de datos que este lleno
        if (is_array($request->all())  ) {
            //especificacion de tipado y campos requeridos
            $rules =[
                'name' => 'required|max:255|string',
                'description' => 'required|max:1000|string',
                'vin' => 'required|max:255|string',
                'location' => 'max:255|string',
                'yearModel' => 'required|integer',
                'purchaseDate' => 'required|max:255|string',
                'price' => 'required|numeric',
                'priceList' => 'required|numeric',
                'salePrice' => 'required|numeric',
                'type' => 'required|in:new,pre_owned,demo',
                'carline' => 'required|max:255|string',
                'cylinders' => 'required|integer',
                'colorInt' => 'required|max:255|string',
                'colorExt' => 'required|max:255|string',
                'status' => 'required|in:active,inactive,sale',
                'plates' => 'string',
                'transmission' => 'required|max:255|string',
                'inventoryDays' => 'required|numeric',
                'km' => 'required|integer',
                'numKeys' => 'required|integer',
                'studs' => 'required|in:yes,no',
                'spareTire' => 'required|in:yes,no',
                'hydraulicJack' => 'required|in:yes,no',
                'extinguiser' => 'required|in:yes,no',
                'reflectives' => 'required|in:yes,no',
                'handbook' => 'required|in:yes,no',
                'insurancePolicy' => 'required|in:yes,no',
                'powerCables' => 'required|in:yes,no',
                'promotion' => 'max:255|string',
                ## 'priceOffer' => 'numeric',
                'carmodel_id' => 'required|exists:carmodels,id',
                'vehiclebody_id' => 'required|exists:vehiclebodies,id',
                'branch_id' => 'required|exists:branches,id',
                'client_id' => 'required|exists:clients,id'
            ];

            try {
                //validacion de tipado y campos requeridos
                $validator = \Validator::make($request->all(), $rules);
                    if ($validator->fails()) {
                    //existio un error en los campos enviados
                    $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'errors'  => $validator->errors()->all()
                    );
                    }else{
                        $vehicleExist = Vehicle::where('vin', $request->vin)->first();
                        if(!is_object($vehicleExist)){
                            // Crear el vehicle
                            $vehicle = new Vehicle();
                            $vehicle->name = $request->name;
                            $vehicle->description = $request->description;
                            $vehicle->vin = $request->vin;
                            $vehicle->location = $request->location;
                            $vehicle->yearModel = $request->yearModel;
                            $vehicle->purchaseDate = $request->purchaseDate;
                            $vehicle->price = $request->price;
                            $vehicle->priceList = $request->priceList;
                            $vehicle->salePrice = $request->salePrice;
                            $vehicle->type = $request->type;
                            $vehicle->carline = $request->carline;
                            $vehicle->cylinders = $request->cylinders;
                            $vehicle->colorInt = $request->colorInt;
                            $vehicle->colorExt = $request->colorExt;
                            $vehicle->status = $request->status;
                            $vehicle->plates = $request->plates;
                            $vehicle->transmission = $request->transmission;
                            $vehicle->inventoryDays = $request->inventoryDays;
                            $vehicle->km = $request->km;
                            $vehicle->numKeys = $request->numKeys;
                            $vehicle->studs = $request->studs;
                            $vehicle->spareTire = $request->spareTire;
                            $vehicle->hydraulicJack = $request->hydraulicJack;
                            $vehicle->extinguiser = $request->extinguiser;
                            $vehicle->reflectives = $request->reflectives;
                            $vehicle->handbook = $request->handbook;
                            $vehicle->insurancePolicy = $request->insurancePolicy;
                            $vehicle->powerCables = $request->powerCables;
                            $vehicle->promotion = $request->promotion;
                            $vehicle->priceOffer = NULL;
                            $vehicle->carmodel_id = $request->carmodel_id;
                            $vehicle->vehiclebody_id = $request->vehiclebody_id;
                            $vehicle->branch_id = $request->branch_id;
                            $vehicle->client_id = 1;
                            $vehicle->description = $request->description;                                                        
                            if($vehicle->save()){
                               // actualizar el registro de las tablas
                                $v_to_d = Vehicle::where('vin', $vehicle->vin)->withTrashed()->first();
                                
                                if( is_object($v_to_d) ){

                                    $Set_Image = SetImage::where('vehicle_id' , $v_to_d->id)->get();
                                    
                                    if(is_object($Set_Image)){

                                        $Set_Image = SetImage::where('vehicle_id' , $v_to_d->id)->delete();
                                        
                                        Storage::deleteDirectory('check/'.$vehicle->vin.'');
                                    }

                                    $checks = Check_vehicle::where('vehicle_id' , $v_to_d->id)->get();
                                     
                                    if(!empty($checks) ){
                                        foreach($checks as $check){
                                            $check->vehicle_id = $vehicle->id;
                                            $check->save(); 
                                        }                                    
                                    }

                                }   

                                $carmodel = Carmodel::find($vehicle->carmodel_id);
                                if( $vehicle->location === 'hidalgo' && $carmodel->brand_id === 10 ){
                                    $vecsa_response = $this->addVecsaVehicle( $vehicle );                                
                                }                                
                            }

                            $data = array(
                                'status' => 'success',
                                'code'   => '200',
                                'message' => 'vehicle creado exitosamente',
                                'vehicle' => $vehicle                                
                            );
                        }else{
                            $data = array(
                                'status' => 'error',
                                'code'   => '200',
                                'message' => 'vin duplicado'
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

        }else{
            $data = array(
                'status' => 'error',
                'code'   => '200',
                'message'  => "El usuario no esta identificado"
            );
        }

        return response()->json($data, $data['code']);
    }

    public function update(Request $request, $id){

        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if($checkToken){
            $rules =[
                'name' => 'required|max:255|string',
                'description' => 'required|max:1000|string',
                'location' => 'max:255|string',
                'yearModel' => 'required|integer',
                'purchaseDate' => 'required|max:255|string',
                'price' => 'required|numeric',
                'priceList' => 'required|numeric',
                'salePrice' => 'required|numeric',
                'type' => 'required|in:new,pre_owned,demo',
                'carline' => 'required|max:255|string',
                'cylinders' => 'required|integer',
                'colorInt' => 'required|max:255|string',
                'colorExt' => 'required|max:255|string',
                'status' => 'required|in:active,inactive,sale',
                'plates' => 'string',
                'transmission' => 'required|in:automatico,manual,cvt,triptronic',
                'inventoryDays' => 'required|numeric',
                'km' => 'required|integer',
                'numKeys' => 'required|integer',
                'studs' => 'required|in:yes,no',
                'spareTire' => 'required|in:yes,no',
                'hydraulicJack' => 'required|in:yes,no',
                'extinguiser' => 'required|in:yes,no',
                'reflectives' => 'required|in:yes,no',
                'handbook' => 'required|in:yes,no',
                'insurancePolicy' => 'required|in:yes,no',
                'powerCables' => 'required||in:yes,no',
                'promotion' => 'max:255|string',
                'priceBonus' => 'max:255|string',
                ## 'priceOffer' => 'numeric',
                'carmodel_id' => 'required|exists:carmodels,id',
                'vehiclebody_id' => 'required|exists:vehiclebodies,id',
                'branch_id' => 'required|exists:branches,id',
                'client_id' => 'required|exists:clients,id'
            ];

            try {
                // Obtener package
                $validator = \Validator::make($request->all(), $rules);
                    if ($validator->fails() ) {
                        // error en los datos ingresados
                        $data = array(
                            'status' => 'error',
                            'code'   => '200',
                            'errors'  => $validator->errors()->all()
                        );
                    }else{
                        $vehicle = Vehicle::find( $id );

                        if( is_object($vehicle) && !empty($vehicle) ){
                            $vehicle->name = $request->name;
                            $vehicle->description = $request->description;
                            $vehicle->location = $request->location;
                            $vehicle->yearModel = $request->yearModel;
                            $vehicle->purchaseDate = $request->purchaseDate;

                            // Validates if new price is less than saved price to detect an offer, if not then eliminates offer price and saves new price

                            // Valida si el nuevo precio es menor que el precio guardado para detectar una promoción, sino, elimina el precio oferta y
                            // guarda nuevo precio
                            if (!is_null($request->priceOffer)) {
                                if ($request->price < $vehicle->price) {
                                    $vehicle->price = $vehicle->price;
                                    $vehicle->priceOffer = $request->price;
                                }else if($request->price > $vehicle->price) {
                                    $vehicle->price = $request->price;
                                    $vehicle->priceOffer = NULL;
                                }else {
                                    $vehicle->priceOffer = $request->priceOffer;
                                }
                            }else {
                                if($request->price < $vehicle->price){
                                    $vehicle->price = $vehicle->price;
                                    $vehicle->priceOffer = $request->price;
                                } else {
                                    $vehicle->price = $request->price;
                                    $vehicle->priceOffer = NULL;
                                }
                            }

                            $vehicle->priceList = $request->priceList;
                            $vehicle->salePrice = $request->salePrice;
                            $vehicle->type = $request->type;
                            $vehicle->carline = $request->carline;
                            $vehicle->cylinders = $request->cylinders;
                            $vehicle->colorInt = $request->colorInt;
                            $vehicle->colorExt = $request->colorExt;
                            $vehicle->status = $request->status;
                            $vehicle->plates = $request->plates;
                            $vehicle->transmission = $request->transmission;
                            $vehicle->inventoryDays = $request->inventoryDays;
                            $vehicle->km = $request->km;
                            $vehicle->numKeys = $request->numKeys;
                            $vehicle->studs = $request->studs;
                            $vehicle->spareTire = $request->spareTire;
                            $vehicle->hydraulicJack = $request->hydraulicJack;
                            $vehicle->extinguiser = $request->extinguiser;
                            $vehicle->reflectives = $request->reflectives;
                            $vehicle->handbook = $request->handbook;
                            $vehicle->insurancePolicy = $request->insurancePolicy;
                            $vehicle->powerCables = $request->powerCables;
                            $vehicle->promotion = $request->promotion;
                            $vehicle->carmodel_id = $request->carmodel_id;
                            $vehicle->priceBonus = $request->priceBonus;
                            $vehicle->vehiclebody_id = $request->vehiclebody_id;
                            $vehicle->branch_id = $request->branch_id;
                            $vehicle->client_id = $request->client_id;
                            $vehicle->description = $request->description;
                            $vehicle->save();

                            $data = array(
                                'status' => 'success',
                                'code'   => '200',
                                'message' => 'vehicle actualizado correctamente'
                            );
                        }else{
                            $data = array(
                                'status' => 'error',
                                'code'   => '200',
                                'message' => 'id de vehicle no existe'
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
                'message'  => "El usuario no esta identificado"
            );
        }

        return response()->json($data, $data['code']);
    }

    public function destroy(Request $request, $id){
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if ( is_array($request->all()) && $checkToken ) {
            // Inicio Try catch
            try {
                $vehicle = Vehicle::find( $id );

                if( is_object($vehicle) && !is_null($vehicle) ){

                    try{
                        $vin = $vehicle->vin;
                        $vehicle->delete();
                        Vehicle::where('vin', $vin)->onlyTrashed()->update(['status' => 'sale']);
                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'vehicle ha sido eliminado correctamente'
                        );

                    }catch (\Illuminate\Database\QueryException $e){
                        //throw $th;
                        $data = array(
                            'status' => 'error',
                            'code'   => '400',
                            'message' => $e->getMessage()
                        );
                    }

                }else{
                    $data = array(
                        'status' => 'error',
                        'code'   => '404',
                        'message' => 'El id del Assist no existe'
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

    public function addVecsaVehicle( Vehicle $vehicle ){  
        $url = 'https://bmwvecsahidalgo.com/vecsa-backend/api';
        // $url = 'http://vecsa-backend.test/api';

        $apiUrl = "$url/vehicleByVin/$vehicle->vin"; 

        $add = true;

        $ch = curl_init($apiUrl);        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);        
        if ($response === FALSE) {
            // Manejar el error
            echo 'Error al realizar la solicitud: ' . curl_error($ch);
        } else {
            $response_data = json_decode($response);

            if( $response_data->status === "success" ){
                $exists_vehicle = $response_data->vehicle;
                $add = false;
            }  
        }        
        // Login 
        $apiUrl = "$url/login";        
        $data = array(
            'email' => 'manager@vecsa.com',
            'password' => 'Manager%2024%%',
            'gettoken' => true
        );
        // Inicializar cURL
        $ch = curl_init($apiUrl);

        // Configurar opciones de la solicitud
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Realizar la solicitud POST
        $response = curl_exec($ch);  
        $token = '';      

        // Manejar la respuesta
        if ($response === FALSE) {
            // Manejar el error
            echo 'Error al realizar la solicitud: ' . curl_error($ch);
        } else {
            $response_data = json_decode($response);

            if( $response_data->status === "success" ){
                $token = $response_data->token;
            }            
        }

        // Cerrar la sesión cURL
        curl_close($ch);        

                
        if( $add === true ){
            $apiUrl = "$url/vehicle";
        }else{
            $apiUrl = "$url/vehicle/$exists_vehicle->id";
        } 

        // Datos a enviar (pueden ser un array o una cadena en formato JSON, dependiendo de la API)
        $data = array(
            'name' => $vehicle->name,
            'description' => $vehicle->description,
            'vin' => $vehicle->vin,
            'location' => $vehicle->location,
            'yearModel' => $vehicle->yearModel,
            'purchaseDate' => $vehicle->purchaseDate,
            'price' => $vehicle->price,
            'priceList' => $vehicle->priceList,
            'salePrice' => $vehicle->salePrice,
            'type' => $vehicle->type,
            'carline' => $vehicle->carline,
            'cylinders' => $vehicle->cylinders,
            'colorInt' => $vehicle->colorInt,
            'colorExt' => $vehicle->colorExt,
            'status' => 'inactive',            
            'transmission' => $vehicle->transmission,
            'inventoryDays' => $vehicle->inventoryDays,
            'km' => $vehicle->km,
            'numKeys' => $vehicle->numKeys,
            'studs' => $vehicle->studs,
            'spareTire' => $vehicle->spareTire,
            'hydraulicJack' => $vehicle->hydraulicJack,
            'extinguiser' => $vehicle->extinguiser,
            'reflectives' => $vehicle->reflectives,
            'handbook' => $vehicle->handbook,
            'insurancePolicy' => $vehicle->insurancePolicy,
            'powerCables' => $vehicle->powerCables,
            'promotion' => $vehicle->promotion,
            'carmodel_id' => $vehicle->carmodel_id,
            'vehiclebody_id' => $vehicle->vehiclebody_id,
            'branch_id' => $vehicle->branch_id,
            'client_id' => $vehicle->client_id
        );

        // Inicializar cURL
        $ch = curl_init($apiUrl);

        // Configurar opciones de la solicitud
        if( $add === true ){
            curl_setopt($ch, CURLOPT_POST, 1);
        }else{
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); 
        }        
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(            
            'Authorization: ' . $token
        ));

        // Realizar la solicitud POST
        $response = curl_exec($ch);

        // Manejar la respuesta
        if ($response === FALSE) {
            // Manejar el error
            return 'Error al realizar la solicitud: ' . curl_error($ch);
        } else {
            // Procesar la respuesta
            return $response;
        }

        // Cerrar la sesión cURL
        curl_close($ch);
    }

    public function load( Request $request ){
        $file = $request->file('file');
        Excel::import(new VehiclesImport, $file);
        $response = $added_elements = Session::get('added_elements');
        $data = array(
            'code' => 200,
            'status' => 'success',
            'respuesta' => $response
        );
        return response()->json($data, $data['code']);
    }

    public function addPromotions( Request $request ){
        $file = $request->file('file');
        Excel::import(new UpdatePromotionsImport, $file);
        $updated_elements = Session::get('updated_elements');
        $data = array(
            'code' => 200,
            'status' => 'success',
            'respuesta' => $updated_elements
        );
        return response()->json($data, $data['code']);
    }

    public function vehiclesSearch( int $cantidad, String $brandNames = '', String $modelNames = '', String $years = '', String $carrocerias = '', float $price = 0, String $word = '', String $orden = '', String $states = '' , String $transmissions = ''){
        // Condiciones dinamicas
        // Brands
        $brandNames = explode(",", $brandNames);
        $brand_condition = is_array($brandNames) && !is_null($brandNames) ? $this->conditionBrand($brandNames) : $this->conditionBrand([]);
        
        // Models
        $modelNames = explode(",", $modelNames);
        $model_condition = is_array($modelNames) && !is_null($modelNames) ? $this->conditionModel($modelNames) : $this->conditionModel([]);
        
        // Years
        $years = explode(",", $years);
        $year_condition = is_array($years) && !is_null($years) ? $this->conditionYear($years) : $this->conditionYear([]);
        
        // Carroceria
        $carrocerias = explode(",", $carrocerias);
        $carroceria_condition = is_array($carrocerias) && !is_null($carrocerias) ? $this->conditionCarroceria($carrocerias) : $this->conditionCarroceria([]);
        
        // Price
        $price_condition = !is_null($price) ? $this->conditionPrice($price) : $this->conditionPrice(0);

        // States
        $states = explode(",", $states);
        $state_condition = is_array($states) && !is_null($states) ? $this->conditionState($states) : $this->conditionState([]);

        // Transmissions
        $transmissions = explode(",", $transmissions);
        $transmission_condition = is_array($transmissions) && !is_null($transmissions) ? $this->conditionTransmission($transmissions) : $this->conditionTransmission([]);

        // Condición que dinamica para orden de precios

        switch ($orden) {
            case "precioMas":
                $campo = "vehicles.salePrice";
                $orden_var = "DESC";
                break;
            case "precioMenos":
                $campo = "vehicles.salePrice";
                $orden_var = "ASC";
                break;
            case "vacio":
                $campo = "vehicles.priceOffer";
                $orden_var = "DESC";
                break;
            default:
                $campo = "vehicles.id";
                $orden_var = "DESC";
                break;
        }

        // Word
        if( $word === 'a' ){
            $word_condition = "vehicles.name IS NOT NULL";
        }else{
            $word_condition = "vehicles.name LIKE '%$word%' OR vehicles.vin LIKE '%$word%' OR vehicles.yearModel LIKE '%$word%' OR brands.name LIKE '%$word%' OR carmodels.name LIKE '%$word%' ";
        }

        $vehicles = Vehicle::select('vehicles.*')
                ->join('carmodels', 'carmodels.id', 'vehicles.carmodel_id')
                ->join('brands', 'carmodels.brand_id', 'brands.id')
                ->join('vehiclebodies', 'vehiclebodies.id', 'vehicles.vehiclebody_id')
                ->join('branches', 'branches.id', 'vehicles.branch_id')
                ->where('status', 'active')
                ->has('vehicle_images')
                ->whereRaw(
                    "
                        $brand_condition AND
                        $model_condition AND
                        $year_condition AND
                        $carroceria_condition AND
                        $price_condition AND
                        $state_condition AND
                        $transmission_condition
                    "
                )
                ->whereRaw(
                    "
                        (
                            $word_condition
                        )
                    "
                )
                ->with('vehicle_images', function($query){
                    $query->orderBy('id', 'asc');
                })
                ->with('vehicle_360_images', function($query){
                    $query->orderBy('id', 'asc');
                })
                ->orderBy($campo, $orden_var)
                ->paginate($cantidad);

        $vehicles->load(['carmodel', 'vehiclebody', 'branch', 'shields', 'choices']);
        $data = array(
            'code' => 200,
            'status' => 'success',
            'vehicles' => $vehicles
        );
        //
        return response()->json($data, $data['code']);
    }

    public function vehiclesSearchAll( int $cantidad, String $brandNames = '', String $modelNames = '', String $years = '', String $carrocerias = '', float $price = 0, String $word = '', String $orden = '', String $states = '' ){
        // Condiciones dinamicas
        // Brands
        $brandNames = explode(",", $brandNames);
        $brand_condition = is_array($brandNames) && !is_null($brandNames) ? $this->conditionBrand($brandNames) : $this->conditionBrand([]);
        
        // Models
        $modelNames = explode(",", $modelNames);
        $model_condition = is_array($modelNames) && !is_null($modelNames) ? $this->conditionModel($modelNames) : $this->conditionModel([]);
        
        // Years
        $years = explode(",", $years);
        $year_condition = is_array($years) && !is_null($years) ? $this->conditionYear($years) : $this->conditionYear([]);
        
        // Carroceria
        $carrocerias = explode(",", $carrocerias);
        $carroceria_condition = is_array($carrocerias) && !is_null($carrocerias) ? $this->conditionCarroceria($carrocerias) : $this->conditionCarroceria([]);
        
        // Price
        $price_condition = !is_null($price) ? $this->conditionPrice($price) : $this->conditionPrice(0);

        // States
        $states = explode(",", $states);
        $state_condition = is_array($states) && !is_null($states) ? $this->conditionState($states) : $this->conditionState([]);

        // Condición que dinamica para orden de precios

        switch ($orden) {
            case "precioMas":
                $campo = "vehicles.salePrice";
                $orden_var = "DESC";
                break;
            case "precioMenos":
                $campo = "vehicles.salePrice";
                $orden_var = "ASC";
                break;
            default:
                $campo = "vehicles.id";
                $orden_var = "DESC";
                break;
        }

        // Word
        if( $word === 'a' ){
            $word_condition = "vehicles.name IS NOT NULL";
        }else{
            $word_condition = "vehicles.name LIKE '%$word%' OR vehicles.vin LIKE '%$word%' OR vehicles.yearModel LIKE '%$word%' OR brands.name LIKE '%$word%' OR carmodels.name LIKE '%$word%' ";
        }

        $vehicles = Vehicle::select('vehicles.*')
                ->join('carmodels', 'carmodels.id', 'vehicles.carmodel_id')
                ->join('brands', 'carmodels.brand_id', 'brands.id')
                ->join('vehiclebodies', 'vehiclebodies.id', 'vehicles.vehiclebody_id')
                ->join('branches', 'branches.id', 'vehicles.branch_id')
                ->where('status', 'active')
                ->whereRaw(
                    "
                        $brand_condition AND
                        $model_condition AND
                        $year_condition AND
                        $carroceria_condition AND
                        $price_condition AND
                        $state_condition
                    "
                )
                ->whereRaw(
                    "
                        (
                            $word_condition
                        )
                    "
                )
                ->with('vehicle_images', function($query){
                    $query->orderBy('id', 'asc');
                })
                ->with('vehicle_360_images', function($query){
                    $query->orderBy('id', 'asc');
                })
                ->orderBy($campo, $orden_var)
                ->paginate($cantidad);
        $vehicles->load(['carmodel', 'vehiclebody', 'branch', 'shields', 'choices']);
        $data = array(
            'code' => 200,
            'status' => 'success',
            'vehicles' => $vehicles
        );

        return response()->json($data, $data['code']);
    }

    public function getRandomVehicles(){
        $vehicles = Vehicle::where('status', 'active')
                            ->with('vehicle_images', function($query){
                                $query->orderBy('id', 'asc');
                            })
                            ->where('priceOffer', '>', 0)
                            ->has('vehicle_images')
                            ->get();
        if ($vehicles->count() >= 10) {
            $vehicles = $vehicles->random(10);
        } else {
            $vehicles = $vehicles->random($vehicles->count());
        }
        $vehicles->load(['carmodel', 'vehiclebody', 'branch', 'vehiclebody']);
        $data = array(
            'code' => 200,
            'status' => 'success',
            'vehicles' => $vehicles
        );

        return response()->json($data, $data['code']);
    }

    public function vehiclesById( int $vehicle_id,Request $request ){

            $vehicle = Vehicle::find($vehicle_id);
            $data = array(
                'code' => 200,
                'status' => 'success',
                'vehicle' => is_object($vehicle) && !is_null($vehicle) ? $vehicle->load(['carmodel', 'client', 'vehiclebody', 'branch', 'vehicle_images']) : null
            );


        return response()->json($data, $data['code']);
    }

    public function vehiclesByVin( string $vin ,Request $request){

        $vehicle = Vehicle::where('vin', $vin)
            ->with('vehicle_images', function($query){
            $query->orderBy('id', 'asc');
        })->first();


        if( is_object($vehicle) && !is_null($vehicle) ){

            $data = array(
                'code' => 200,
                'status' => 'success',
                'vehicle' => is_object($vehicle) && !is_null($vehicle) ? $vehicle->load(['carmodel', 'client', 'vehiclebody', 'branch', 'shields', 'choices', 'vehiclebody']) : null
            );

        }else{
            $data = array(
                'code'   => '404',
                'status' => 'error',
                'message' => 'El vin no existe'
                );
        }
            
        return response()->json($data, $data['code']);
    }

    public function brandsByActiveVehicles( String $modelNames = '', String $years = '', String $carrocerias = '', float $price = 0, String $states = '', String $transmissions = ''  ){
        // Models
        $modelNames = explode(",", $modelNames);
        $model_condition = is_array($modelNames) && !is_null($modelNames) ? $this->conditionModel($modelNames) : $this->conditionModel([]);
        
        // Years
        $years = explode(",", $years);
        $year_condition = is_array($years) && !is_null($years) ? $this->conditionYear($years) : $this->conditionYear([]);
        
        // Carroceria
        $carrocerias = explode(",", $carrocerias);
        $carroceria_condition = is_array($carrocerias) && !is_null($carrocerias) ? $this->conditionCarroceria($carrocerias) : $this->conditionCarroceria([]);
        
        // Price
        $price_condition = !is_null($price) ? $this->conditionPrice($price) : $this->conditionPrice(0);

        // States
        $states = explode(",", $states);
        $state_condition = is_array($states) && !is_null($states) ? $this->conditionState($states) : $this->conditionState([]);

        // Transmissions
        $transmissions = explode(",", $transmissions);
        $transmission_condition = is_array($transmissions) && !is_null($transmissions) ? $this->conditionTransmission($transmissions) : $this->conditionTransmission([]);

        $brands = Vehicle::select('brands.*')
                        ->join('carmodels', 'carmodels.id', 'vehicles.carmodel_id')
                        ->join('brands', 'carmodels.brand_id', 'brands.id')
                        ->join('branches', 'branches.id', 'vehicles.branch_id')
                        ->where('status', 'active')
                        ->has('vehicle_images')
                        ->whereRaw(
                            "
                            $model_condition AND
                            $year_condition AND
                            $carroceria_condition AND
                            $price_condition AND
                            $state_condition AND
                            $transmission_condition
                            "
                        )
                        ->distinct('brands.id')
                        ->orderBy('brands.id', 'asc')
                        ->get();
        $data = array(
            'code' => 200,
            'status' => 'success',
            'brands' => $brands,
            'total' => $brands->count()
        );
        return response()->json($data, $data['code']);
    }

    public function modelsByActiveVehicles( String $brandNames = '', String $years = '', String $carrocerias = '', float $price = 0, String $states = '', String $transmissions = ''  ){
        // Brands
        $brandNames = explode(",", $brandNames);
        $brand_condition = is_array($brandNames) && !is_null($brandNames) ? $this->conditionBrand($brandNames) : $this->conditionBrand([]);
        
        // Years
        $years = explode(",", $years);
        $year_condition = is_array($years) && !is_null($years) ? $this->conditionYear($years) : $this->conditionYear([]);
        
        // Carroceria
        $carrocerias = explode(",", $carrocerias);
        $carroceria_condition = is_array($carrocerias) && !is_null($carrocerias) ? $this->conditionCarroceria($carrocerias) : $this->conditionCarroceria([]);
        
        // Price
        $price_condition = !is_null($price) ? $this->conditionPrice($price) : $this->conditionPrice(0);

        // States
        $states = explode(",", $states);
        $state_condition = is_array($states) && !is_null($states) ? $this->conditionState($states) : $this->conditionState([]);

        // Transmissions
        $transmissions = explode(",", $transmissions);
        $transmission_condition = is_array($transmissions) && !is_null($transmissions) ? $this->conditionTransmission($transmissions) : $this->conditionTransmission([]);

        $models = Vehicle::select('carmodels.*')
                        ->join('carmodels', 'carmodels.id', 'vehicles.carmodel_id')
                        ->join('branches', 'branches.id', 'vehicles.branch_id')
                        ->where('status', 'active')
                        ->has('vehicle_images')
                        ->whereRaw(
                            "
                            $brand_condition AND
                            $year_condition AND
                            $carroceria_condition AND
                            $price_condition AND
                            $state_condition AND
                            $transmission_condition
                            "
                        )
                        ->distinct('carmodels.id')
                        ->orderBy('carmodels.id', 'asc')
                        ->get();
        $data = array(
            'code' => 200,
            'status' => 'success',
            'models' => $models,
            'total' => $models->count()
        );
        return response()->json($data, $data['code']);
    }

    public function yearsByActiveVehicles( String $brandNames = '', String $modelNames = '', String $carrocerias = '', float $price = 0, String $states = '', String $transmissions = '' ){
        // Brands
        $brandNames = explode(",", $brandNames);
        $brand_condition = is_array($brandNames) && !is_null($brandNames) ? $this->conditionBrand($brandNames) : $this->conditionBrand([]);
        
        // Models
        $modelNames = explode(",", $modelNames);
        $model_condition = is_array($modelNames) && !is_null($modelNames) ? $this->conditionModel($modelNames) : $this->conditionModel([]);
        
        // Carroceria
        $carrocerias = explode(",", $carrocerias);
        $carroceria_condition = is_array($carrocerias) && !is_null($carrocerias) ? $this->conditionCarroceria($carrocerias) : $this->conditionCarroceria([]);
        
        // Price
        $price_condition = !is_null($price) ? $this->conditionPrice($price) : $this->conditionPrice(0);

        // States
        $states = explode(",", $states);
        $state_condition = is_array($states) && !is_null($states) ? $this->conditionState($states) : $this->conditionState([]);

        // Transmissions
        $transmissions = explode(",", $transmissions);
        $transmission_condition = is_array($transmissions) && !is_null($transmissions) ? $this->conditionTransmission($transmissions) : $this->conditionTransmission([]);

        $years = Vehicle::select('yearModel')
                        ->join('carmodels', 'carmodels.id', 'vehicles.carmodel_id')
                        ->join('branches', 'branches.id', 'vehicles.branch_id')
                        ->where('status', 'active')
                        ->has('vehicle_images')
                        ->whereRaw(
                            "
                            $brand_condition AND
                            $model_condition AND
                            $carroceria_condition AND
                            $price_condition AND
                            $state_condition AND
                            $transmission_condition
                            "
                        )
                        ->distinct('yearModel')
                        ->orderBy('yearModel', 'asc')
                        ->get();
        $data = array(
            'code' => 200,
            'status' => 'success',
            'years' => $years,
            'total' => $years->count()
        );
        return response()->json($data, $data['code']);
    }

    public function vehiclebodiesByActiveVehicles( String $brandNames = '', String $modelNames = '', String $years = '', float $price = 0, String $states = '', String $transmissions = '' ){
        // Brands
        $brandNames = explode(",", $brandNames);
        $brand_condition = is_array($brandNames) && !is_null($brandNames) ? $this->conditionBrand($brandNames) : $this->conditionBrand([]);
        
        // Models
        $modelNames = explode(",", $modelNames);
        $model_condition = is_array($modelNames) && !is_null($modelNames) ? $this->conditionModel($modelNames) : $this->conditionModel([]);
        
        // Years
        $years = explode(",", $years);
        $year_condition = is_array($years) && !is_null($years) ? $this->conditionYear($years) : $this->conditionYear([]);
        
        // Price
        $price_condition = !is_null($price) ? $this->conditionPrice($price) : $this->conditionPrice(0);

        // States
        $states = explode(",", $states);
        $state_condition = is_array($states) && !is_null($states) ? $this->conditionState($states) : $this->conditionState([]);

        // Transmissions
        $transmissions = explode(",", $transmissions);
        $transmission_condition = is_array($transmissions) && !is_null($transmissions) ? $this->conditionTransmission($transmissions) : $this->conditionTransmission([]);

        $vehiclebodies = Vehicle::select('vehiclebodies.*')
                        ->join('vehiclebodies', 'vehiclebodies.id', 'vehicles.vehiclebody_id')
                        ->join('carmodels', 'carmodels.id', 'vehicles.carmodel_id')
                        ->join('branches', 'branches.id', 'vehicles.branch_id')
                        ->where('vehicles.status', 'active')
                        ->has('vehicle_images')
                        ->whereRaw(
                            "
                            $brand_condition AND
                            $model_condition AND
                            $year_condition AND
                            $price_condition AND
                            $state_condition AND
                            $transmission_condition
                            "
                        )
                        ->distinct('vehiclebodies.id')
                        ->orderBy('vehiclebodies.id', 'asc')
                        ->get();
        $data = array(
            'code' => 200,
            'status' => 'success',
            'vehiclebodies' => $vehiclebodies,
            'total' => $vehiclebodies->count()
        );
        return response()->json($data, $data['code']);
    }

    public function statesByActiveVehicles( String $brandNames = '', String $modelNames = '', String $carrocerias = '', String $years = '', float $price = 0, String $transmissions = '' ){
        // Brands
        $brandNames = explode(",", $brandNames);
        $brand_condition = is_array($brandNames) && !is_null($brandNames) ? $this->conditionBrand($brandNames) : $this->conditionBrand([]);
        
        // Models
        $modelNames = explode(",", $modelNames);
        $model_condition = is_array($modelNames) && !is_null($modelNames) ? $this->conditionModel($modelNames) : $this->conditionModel([]);
        
        // Years
        $years = explode(",", $years);
        $year_condition = is_array($years) && !is_null($years) ? $this->conditionYear($years) : $this->conditionYear([]);
        
        // Price
        $price_condition = !is_null($price) ? $this->conditionPrice($price) : $this->conditionPrice(0);

        // Carroceria
        $carrocerias = explode(",", $carrocerias);
        $carroceria_condition = is_array($carrocerias) && !is_null($carrocerias) ? $this->conditionCarroceria($carrocerias) : $this->conditionCarroceria([]);

        // Transmissions
        $transmissions = explode(",", $transmissions);
        $transmission_condition = is_array($transmissions) && !is_null($transmissions) ? $this->conditionTransmission($transmissions) : $this->conditionTransmission([]);

        $states = Vehicle::select('states.*')
                        ->join('branches', 'branches.id', 'vehicles.branch_id')
                        ->join('states', 'states.id', 'branches.state_id')
                        ->where('vehicles.status', 'active')
                        ->has('vehicle_images')->join('carmodels', 'carmodels.id', 'vehicles.carmodel_id')->where('vehicles.status', 'active')
                        ->whereRaw(
                            "
                            $brand_condition AND
                            $model_condition AND
                            $carroceria_condition AND
                            $year_condition AND
                            $price_condition AND
                            $transmission_condition
                            "
                        )
                        ->distinct('states.id')
                        ->orderBy('states.id', 'asc')
                        ->get();
        $data = array(
            'code' => 200,
            'status' => 'success',
            'states' => $states,
            'total' => $states->count()
        );
        return response()->json($data, $data['code']);
    }

    public function transmissionsByActiveVehicles( String $brandNames = '', String $modelNames = '', String $carrocerias = '',  String $years = '', float $price = 0, String $states = '' ){
        // Brands
        $brandNames = explode(",", $brandNames);
        $brand_condition = is_array($brandNames) && !is_null($brandNames) ? $this->conditionBrand($brandNames) : $this->conditionBrand([]);
        
        // Models
        $modelNames = explode(",", $modelNames);
        $model_condition = is_array($modelNames) && !is_null($modelNames) ? $this->conditionModel($modelNames) : $this->conditionModel([]);
        
        // Carroceria
        $carrocerias = explode(",", $carrocerias);
        $carroceria_condition = is_array($carrocerias) && !is_null($carrocerias) ? $this->conditionCarroceria($carrocerias) : $this->conditionCarroceria([]);
        
        // Years
        $years = explode(",", $years);
        $year_condition = is_array($years) && !is_null($years) ? $this->conditionYear($years) : $this->conditionYear([]);
        
        // Price
        $price_condition = !is_null($price) ? $this->conditionPrice($price) : $this->conditionPrice(0);

        // States
        $states = explode(",", $states);
        $state_condition = is_array($states) && !is_null($states) ? $this->conditionState($states) : $this->conditionState([]);

        $transmissions = Vehicle::select('transmission')
                        ->join('carmodels', 'carmodels.id', 'vehicles.carmodel_id')
                        ->join('branches', 'branches.id', 'vehicles.branch_id')
                        ->where('status', 'active')
                        ->has('vehicle_images')
                        ->whereRaw(
                            "
                            $brand_condition AND
                            $model_condition AND
                            $carroceria_condition AND
                            $year_condition AND
                            $price_condition AND
                            $state_condition
                            "
                        )
                        ->distinct('transmission')
                        ->orderBy('transmission', 'asc')
                        ->get();
        $data = array(
            'code' => 200,
            'status' => 'success',
            'transmissions' => $transmissions,
            'total' => $transmissions->count()
        );
        return response()->json($data, $data['code']);
    }

    private function conditionBrand( Array $brandNames ){
        $brand_ids = Brand::whereIn('name', $brandNames)->get()->pluck('id');

        if( count($brand_ids) > 0  ){
            $brand_condition = "carmodels.brand_id IN ( ";
            for( $i = 0; $i < count($brand_ids); $i++ ){
                $brand_condition .= $i != 0 ? ", " . (string) $brand_ids[$i] : (string) $brand_ids[$i];
            }
            $brand_condition .= " ) ";
        }else{
            $brand_condition = "carmodels.brand_id != 0";
        }

        return $brand_condition;
    }

    private function conditionModel( Array $modelNames ){
        $model_ids = Carmodel::whereIn('name', $modelNames)->get()->pluck('id');

        if( count($model_ids) > 0  ){
            $model_condition = "vehicles.carmodel_id IN ( ";
            for( $i = 0; $i < count($model_ids); $i++ ){
                $model_condition .= $i != 0 ? ", " . (string) $model_ids[$i] : (string) $model_ids[$i];
            }
            $model_condition .= " ) ";
        }else{
            $model_condition = "vehicles.carmodel_id != 0";
        }

        return $model_condition;
    }

    private function conditionYear( Array $stringYears ){
        $years = Vehicle::whereIn('yearModel', $stringYears)->get()->pluck('id');
        if( count($years) > 0  ){
            $year_condition = "vehicles.id IN ( ";
            for( $i = 0; $i < count($years); $i++ ){
                $year_condition .= $i != 0 ? ", " . (string) $years[$i] : (string) $years[$i];
            }
            $year_condition .= " ) ";
        }else{
            $year_condition = "vehicles.id != 0";
        }
        return $year_condition;
    }

    private function conditionCarroceria( Array $carrocerias ){
        $carrocerias = Vehicle::whereIn('vehiclebody_id', $carrocerias)->get()->pluck('id');
        //dd( $carrocerias );
        if( count($carrocerias) > 0  ){
            $carroceria_condition = "vehicles.id IN ( ";
            for( $i = 0; $i < count($carrocerias); $i++ ){
                $carroceria_condition .= $i != 0 ? ", " . (string) $carrocerias[$i] : (string) $carrocerias[$i];
            }
            $carroceria_condition .= " ) ";
        }else{
            $carroceria_condition = "vehicles.id != 0";
        }

        return $carroceria_condition;
    }

    private function conditionPrice( float $price ){
        if( $price > 0 ){
            $price_condition = "vehicles.salePrice BETWEEN 0 AND $price";
        }else{
            $price_condition = "vehicles.salePrice != 0";
        }

        return $price_condition;
    }

    private function conditionState( Array $stateNames ){
        $state_ids = State::whereIn('name', $stateNames)->get()->pluck('id');

        if( count($state_ids) > 0  ){
            $state_condition = "branches.state_id IN ( ";
            for( $i = 0; $i < count($state_ids); $i++ ){
                $state_condition .= $i != 0 ? ", " . (string) $state_ids[$i] : (string) $state_ids[$i];
            }
            $state_condition .= " ) ";
        }else{
            $state_condition = "branches.state_id != 0";
        }

        return $state_condition;
    }

    private function conditionTransmission( Array $transmissionNames ){
        $transmissions = Vehicle::whereIn('transmission', $transmissionNames)->get()->pluck('id');
        if( count($transmissions) > 0  ){
            $transmission_condition = "vehicles.id IN ( ";
            for( $i = 0; $i < count($transmissions); $i++ ){
                $transmission_condition .= $i != 0 ? ", " . (string) $transmissions[$i] : (string) $transmissions[$i];
            }
            $transmission_condition .= " ) ";
        }else{
            $transmission_condition = "vehicles.id != 0";
        }
        return $transmission_condition;
    }

    public function modelsVehicle(String $brand_id) {
        $models = Carmodel::where('brand_id', $brand_id)->get();

        if (is_object($models)) {
            $data = array(
                'code' => 200,
                'status' => 'success',
                'models' => $models
            );
        } else {
            $data = array(
                'code' => 404,
                'status' => 'error',
                'models' => $models,
            );
        }

        return response()->json($data, $data['code']);
    }

    public function getVehiclesPurchasedByClient( int $client_id ){
        $vehicles = Vehicle::select('vehicles.*')
                            ->join('choices', 'choices.vehicle_id', 'vehicles.id')
                            ->where('choices.client_id', $client_id)
                            ->where('choices.status', 'apartado')
                            ->where('vehicles.status', 'sale')
                            ->get();

        $data = array(
            'code' => 200,
            'status' => 'success',
            'vehicles' => $vehicles->load(['carmodel', 'branch', 'vehicle_images'])
        );

        return response()->json( $data, $data['code'] );
    }

    public function get_set_vehicle($vehicle_id){
        //Busqueda dentro de tabla se dets por id de vehiculo
        $set=Set::where('vehicle_id', $vehicle_id)->first();

        if (is_object($set)){
            $vehicle=Vehicle::where('id', $set->vehicle_id)->first();
            $bill=Bill::where('id', $set->bill_id)->first();
            $purchase=Purchase::where('id', $set->purchase_id)->first();
            $expense=Expense::where('id', $set->expense_id)->first();

            $data = array(
                'code' => 200,
                'status' => 'success',
                'vehicle' => $vehicle,
                'bill' => $bill,
                'purchase' => $purchase,
                'expense' => $expense,
            );
        }
        else{
            $data = array(
                'code' => 404,
                'status' => 'error'
            );
        }
        return response()->json($data, $data['code']);

    }

    public function getRecommendedCarsByVin( String $vin ){
        $price = 100000;
        $vehicle = Vehicle::where('vin', $vin)->first();
        if( is_object($vehicle) ){
            $price = $vehicle->salePrice;
        }
        $max = $price + 100000;
        $min = $price - 100000;

        $vehicles = Vehicle::has('vehicle_images')->whereBetween('salePrice', [$min, $max])->where('vin', '!=', $vin)->where('status', 'active')
                                                    ->with('vehicle_images', function($query){
                                                        $query->orderBy('id', 'asc');
                                                    })->get();
        if ($vehicles->count() >= 10) {
            $vehicles = $vehicles->random(10);
        } else {
            $carmodel = $vehicle->carmodel()->first();
            $vehicles = Vehicle::select('vehicles.*')
                            ->join('carmodels', 'carmodels.id', 'vehicles.carmodel_id')
                            ->join('brands', 'brands.id', 'carmodels.brand_id')
                            ->where('vehicles.vin', '!=', $vin)
                            ->where('brands.id', $carmodel->brand_id)
                            ->where('status', 'active')
                            ->with('vehicle_images', function($query){
                                    $query->orderBy('id', 'asc');
                                })
                            ->has('vehicle_images')
                            ->get();
            if ($vehicles->count() >= 10) {
                $vehicles = $vehicles->random(10);
            }else{
                $vehicles = Vehicle::where('vin', '!=', $vin)->where('status', 'active')->has('vehicle_images')
                                                            ->with('vehicle_images', function($query){
                                                                $query->orderBy('id', 'asc');
                                                            })->get();
                if ($vehicles->count() >= 10) {
                    $vehicles = $vehicles->random(10);
                }else{
                    $vehicles = $vehicles->random($vehicles->count());
                }
            }
        }
        $vehicles->load(['carmodel', 'client', 'vehiclebody', 'branch', 'shields', 'choices']);
        $data = array(
            "code" => 200,
            "status" => "success",
            "vehicles" => $vehicles
        );
        return response()->json($data, $data['code']);
    }

    public function vehicleSold(String $vin) {
        $data = array(
            'code' => 200,
            'status' => 'success',
            'vehicle' => null,
            'sold' => false
        );

        // Find Vehicle
        $vehicle = Vehicle::where('vin', trim($vin))->where('status', 'active')->first();

        if (is_object($vehicle) && !is_null($vehicle)) {

            // Change status vehicle to sale
            $vehicle->status = 'sale';

            // Save vehicle & save rewards
            if ($vehicle->save()) {
                $data['vehicle'] = $vehicle;
                $data['sold'] = true;

                // Search vehicle in choices
                $choice_vehicle = Choice::where('vehicle_id', $vehicle->id)->first();

                if( is_object($choice_vehicle) && !is_null( $choice_vehicle ) ){
                    // Find Customer Recommends Vehicle (Recomienda)
                    $client_recommends_vehicle = Client::where('rewards', $choice_vehicle->rewards)->first();

                    // Find Recommended Client (Recomendado)
                    $client_recommended = Client::find($choice_vehicle->client_id);

                    if (is_object($client_recommended) && is_object($client_recommends_vehicle) && ($client_recommended->rewards !== $client_recommends_vehicle->rewards)) {                                        
                        $client_recommends_vehicle->points = ($client_recommends_vehicle->points === 3000) ? $client_recommends_vehicle->points : $client_recommends_vehicle->points + 100;
                        $client_recommends_vehicle->save();

                        $client_recommended->points = ($client_recommended->points === 3000) ? $client_recommended->points : $client_recommended->points + 100;
                        $client_recommended->save();
                    }

                    // Create new log
                    $log = new Log;
                    $log->process = "unit sold: " . $vin;
                    $log->error_description = "Without errors";
                    $log->url = "vehicleSold";
                    $log->save();
                }
            }
        } else {
            // Create new log
            $log = new Log;
            $log->process = "unsold unit: " . $vin;
            $log->error_description = "Without errors";
            $log->url = "vehicleSold";
            $log->save();
        }

        return response()->json($data, $data['code']);
    }

    public function updatePromotion( Request $request ){
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if( is_array($request->all()) && $checkToken ){
            $rules =[
                'vin' => 'required|max:255|string',
                'promotion' => 'required|string'
            ];
            try {
                //validacion de tipado y campos requeridos
                $validator = \Validator::make($request->all(), $rules);
                    if ($validator->fails()) {
                        //existio un error en los campos enviados
                        $data = array(
                            'status' => 'error',
                            'code'   => '200',
                            'errors'  => $validator->errors()->all()
                        );
                    }else{
                        // Crear el vehicle
                        $vehicle = Vehicle::where('vin', $request->vin)->first();
                        $vehicle->promotion = $request->promotion;
                        $vehicle->save();

                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'promoción actualizada correctamente',
                            'vehicle' => $vehicle
                        );
                    }
            } catch (Exception $e) {
                $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'message' => 'Los datos enviados no son correctos, ' . $e
                );
            }
        }else{
            $data = array(
                'code' => 200,
                'status' => 'success',
                'message' => 'El usuario no esta identificado'
            );
        }

        return response()->json( $data, $data['code']);
    }

    public function getActiveVehicles( string $word = null, int $total = 10 ){
        if( is_null($word) ){
            $vehicles = Vehicle::where('status', 'active')->paginate( $total );
        }else{
            $vehicles = Vehicle::where('status', 'active')
                                ->whereRaw("
                                    name LIKE '%$word%' OR
                                    vin LIKE '%$word%'
                                ")
                                ->paginate( $total );
        }

        $vehicles->load(['vehicle_images', 'choices']);
        $data = array(
            'code' => 200,
            'status' => 'success',
            'vehicles' => $vehicles
        );
        return response()->json($data, $data['code']);
    }

    public function getActiveVehiclesLocation( string $word = null, int $total = 10 ){
        if( is_null($word) ){
            $vehicles = Vehicle::where('status', 'active')->paginate( $total );
        }else{
            $vehicles = Vehicle::where('status', 'active')
                                ->whereRaw("
                                    name LIKE '%$word%' OR
                                    vin LIKE '%$word%' OR
                                    location LIKE '%$word%'
                                ")
                                ->paginate( $total );
        }

        $vehicles->load(['vehicle_images', 'choices']);
        $data = array(
            'code' => 200,
            'status' => 'success',
            'vehicles' => $vehicles
        );
        return response()->json($data, $data['code']);
    }

    public function getVehiclesAbcars(){
        $vehicles = Vehicle::select(
            'vehicles.*',
            DB::raw( "(SELECT path FROM vehicle_images WHERE vehicle_images.vehicle_id = vehicles.id LIMIT 1) as 'image'" )
        )->where('status', 'active')->get();

        $vehicles->load(['carmodel']);
        return $vehicles;

    }

    /**
     * Ask information vehicle send to spread sheet
    */
    public function askInformationVehicle(Request $body) {
        $request = array(
            "name" => $body->name,
            "surname" => $body->surname,
            "email" => $body->email,
            "phone" => $body->phone,
            "auto" => $body->auto,
            "datetime" => $body->datetime
        );
        // Intelimotors
        $intel = array(
            "adId" => $body->vehicle_id,
            "email" => $body->email,
            "name" => $body->name . ' ' . $body->surname,
            "phone" => $body->phone,
            "message" => "Hola. ¿Sigue disponible?",
            "origin" => "ABP" //$body->branch_name
        );

        $this->intelimotors( $intel );
        // Fin Intelimotors

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://hooks.zapier.com/hooks/catch/8825119/bz4is8c");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request));

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    public function getVehicleSale(){
        $vehicles = Vehicle::where('status', 'inactive')->get();

        return $vehicles;

    }

    public function intelimotors( Array $datos ){
        $conexion = curl_init();

        $data = array(
            "adId" => $datos['adId'],
            "customer" => array(
                "email" => $datos['email'],
                "name" => $datos['name'],
                "phone" => $datos['phone'],
                "message" => "Hola. ¿Sigue disponible?"
            ),
            "vendor" => array(
                "origin" => $datos['origin']
            )
        );

        $data_string = json_encode($data);
        //dd($data_string);

        $ch = curl_init("https://app.intelimotor.com/api/webhooks/carcentral?apiKey=776F486E92E4262F5125EB7DA7EDB7DB67F58C71C31F2AF135BE5595F9");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function getVehiclesAbcarsxml(){
        $vehicles = Vehicle::select(
            "vehicles.vin as id",
            "vehicles.name as title",
            DB::raw('CONCAT(vehicles.name, vehicles.carline) AS description'),
            DB::raw('CONCAT("in stock","") AS availability'),
            DB::raw('CONCAT(" "," ") AS inventory'),
            DB::raw('CONCAT("used"," ") AS condicion'),
            "vehicles.salePrice as price",
            DB::raw('CONCAT("https://abcars.mx/compra-tu-auto/detail/", vehicles.vin) AS description'), 
            DB::raw('CONCAT("https://automotrizbalderrama.com/inventory/api/getImagesAb/", vehicles.vin) AS image_link'), 
            //marca
            "brands.name as brand",

            DB::raw('CONCAT(""," ") AS additional_image_link'),
            "vehicles.colorExt as color",
            DB::raw('CONCAT(""," ") AS google_product_category'),
            DB::raw('CONCAT(""," ") AS custom_label_0'),
            DB::raw('CONCAT(vehicles.name, vehicles.yearModel) AS custom_label_1'),
            "vehicles.km as custom_label_2",
            DB::raw('CONCAT(""," ") AS custom_label_4'),
            DB::raw('CONCAT("active"," ") AS status'),
        )
        ->join("carmodels","vehicles.carmodel_id", "=", "carmodels.id")
        ->join("brands","carmodels.brand_id", "=", "brands.id")
        ->where('status', 'active')
        ->get();

        return response()->xml(['inventario_ABcars'=>$vehicles ]);

    }

    public function updateStatus( Request $request, $id ){
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if ( is_array($request->all()) && $checkToken ) {
            $rules =[
                'status' => 'required|in:active,inactive,sale'
            ];

            try {
                // Obtener package
                $validator = \Validator::make($request->all(), $rules);
                    if ($validator->fails() ) {
                        // error en los datos ingresados
                        $data = array(
                            'status' => 'error',
                            'code'   => '200',
                            'errors'  => $validator->errors()->all()
                        );
                    }else{
                    $vehicle = Vehicle::find( $id );
                        if( is_object($vehicle) && !empty($vehicle) ){
                            $vehicle->status = $request->status;
                            if($vehicle->save()){
                                if($vehicle->status == 'sale'){

                                // Search vehicle in choices
                                $choice_vehicle = Choice::where('vehicle_id', $vehicle->id)->first();

                                    if( is_object($choice_vehicle) && !is_null( $choice_vehicle ) ){
                                        // Find Customer Recommends Vehicle (Recomienda)
                                        $client_recommends_vehicle = Client::where('rewards', $choice_vehicle->rewards)->first();

                                        // Find Recommended Client (Recomendado)
                                        $client_recommended = Client::find($choice_vehicle->client_id);

                                        if (is_object($client_recommended) && is_object($client_recommends_vehicle) && ($client_recommended->rewards !== $client_recommends_vehicle->rewards)) {                                        
                                            $client_recommends_vehicle->points = ($client_recommends_vehicle->points === 3000) ? $client_recommends_vehicle->points : $client_recommends_vehicle->points + 100;
                                            $client_recommends_vehicle->save();

                                            $client_recommended->points = ($client_recommended->points === 3000) ? $client_recommended->points : $client_recommended->points + 100;
                                            $client_recommended->save();
                                        }

                                        // Create new log
                                        $log = new Log;
                                        $log->process = "unit sold: " . $vin;
                                        $log->error_description = "Without errors";
                                        $log->url = "vehicleSold";
                                        $log->save();
                                    }
                                }
                            }

                            $data = array(
                                'status' => 'success',
                                'code'   => '200',
                                'message' => 'vehicle actualizado correctamente',
                                'vehicle' => $vehicle
                            );
                        }else{
                            $data = array(
                                'status' => 'error',
                                'code'   => '200',
                                'message' => 'id de vehicle no existe'
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
            'code'   => '404',
            'message' => 'El usuario no está identificado'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function getSaleVehiclesWithoutChoice(){
        $vehicles = Vehicle::where('status', 'sale')
                                ->doesnthave('choices')
                                ->get(['id', 'name','vin']);
        $data = array(
            'code' => 200,
            'status' => 'success',
            'vehicles' => $vehicles
        );
        return response()->json($data, $data['code']);
    }

    public function getVehiclebyStatus($status){
        if($status =="active"|| $status =="inactive"|| $status =="sale" ){
            $vehicles = Vehicle::where('status', $status)->get();
            $data = array(
                'code' => 200,
                'status' => 'success',
                'vehicles' => $vehicles
            );
        }
        else{
            $data = array(
                'code' => 200,
                'status' => 'error',
                'message' => 'el estatus consultado no es valido'
            );
        }
        return response()->json($data, $data['code']);
    }

    public function getVehiclebyBranch($brach){
            $vehicles = Vehicle::where('branch_id', $brach)->get();
            $data = array(
                'code' => 200,
                'status' => 'success',
                'vehicles' => $vehicles
            );
        return response()->json($data, $data['code']);
    }

    public function vehicles_sales(int $cantidad, String $brandNames = '', String $modelNames = '', String $years = '', String $carrocerias = '', float $price = 0, String $word = '', String $orden = '', String $states = ''){
        /****************************************************************** */
        // Condiciones dinamicas
        // Brands
        $brandNames = explode(",", $brandNames);
        $brand_condition = is_array($brandNames) && !is_null($brandNames) ? $this->conditionBrand($brandNames) : $this->conditionBrand([]);
        // Models
        $modelNames = explode(",", $modelNames);
        $model_condition = is_array($modelNames) && !is_null($modelNames) ? $this->conditionModel($modelNames) : $this->conditionModel([]);
        // Years
        $years = explode(",", $years);
        $year_condition = is_array($years) && !is_null($years) ? $this->conditionYear($years) : $this->conditionYear([]);
        // Carroceria
        $carrocerias = explode(",", $carrocerias);
        $carroceria_condition = is_array($carrocerias) && !is_null($carrocerias) ? $this->conditionCarroceria($carrocerias) : $this->conditionCarroceria([]);
        // Price
        $price_condition = !is_null($price) ? $this->conditionPrice($price) : $this->conditionPrice(0);
        // States
        $states = explode(",", $states);
        $state_condition = is_array($states) && !is_null($states) ? $this->conditionState($states) : $this->conditionState([]);

        $vehicles = Vehicle::select('vehicles.*')
                ->join('carmodels', 'carmodels.id', 'vehicles.carmodel_id')
                ->join('brands', 'carmodels.brand_id', 'brands.id')
                ->join('vehiclebodies', 'vehiclebodies.id', 'vehicles.vehiclebody_id')
                ->join('branches', 'branches.id', 'vehicles.branch_id')
                ->join('shield_vehicle', 'shield_vehicle.vehicle_id', 'vehicles.id')
                ->join('shields', 'shields.id', 'shield_vehicle.shield_id')
                ->where('status', 'active')
                // ->where('priceOffer', '>', 0)
                ->distinct('vehicles.vin')
                ->has('vehicle_images')
                ->whereRaw(
                    "
                        $brand_condition AND
                        $model_condition AND
                        $year_condition AND
                        $carroceria_condition AND
                        $price_condition AND
                        $state_condition
                    "
                )
                ->with('vehicle_images', function($query){
                    $query->orderBy('id', 'asc');
                })
                ->paginate($cantidad);
        $vehicles->load(['carmodel', 'vehiclebody', 'branch', 'shields', 'choices']);
        $data = array(
            'code' => 200,
            'status' => 'success',
            'vehicles' => $vehicles
        );
        //
        return response()->json($data, $data['code']);
        /****************************************************************** */
    }


    public function minMaxPrices(){

        $minPrice = Vehicle::where('status', '=', 'active')->whereNotNull('price')->with('vehicle_images')->has('vehicle_images')->min('price');
        $maxPrice = Vehicle::where('status', '=', 'active')->with('vehicle_images')->has('vehicle_images')->max('price');

        $data = array(
            'code'=> 200,
            'min'=> $minPrice,
            'max'=> $maxPrice,
            'status'=> 'success'
        );

        return response()->json($data, $data['code']);
    }

    public function getPreownedVehiclesXML(){
        $data = array(                        
            "channel" => array(
                "title" => "Inventario de Abcars",
                "description" => "Inventario de Abcars para Facebook",         
                /*
                'elemento1' => [
                    '__attributes' => ['atributo1' => 'valor1', 'jaja' => 'kakaka'],
                    'value' => 'contenido1',
                ]
                */
            )        
        );

        $vehicles = Vehicle::where('status', 'active')
                        ->where('type', 'pre_owned')
                        //->has('vehicle_images')
                        ->with('vehicle_images', function($query){
                            $query->orderBy('id', 'asc');
                        })
                        ->with('carmodel')                      
                        ->get();

        foreach ($vehicles as $key => $vehicle){                 
            $product = array(
                'id' => $vehicle->id,
                'title' => $vehicle->name,
                'description' => $vehicle->description,
                'availability' => 'in stock',
                'inventory' => '1',
                'condition' => 'used',
                'price' => $vehicle->price,
                'link' => "https://abcars.mx/compra-tu-auto/detail/$vehicle->vin",                
                'brand' => $vehicle->carmodel->brand->name,                        
                'color' => $vehicle->colorExt,
                'google_product_category' => '916',
                'custom_label_0' => $vehicle->carmodel->name,
                'custom_label_1' => $vehicle->yearModel,                
                'custom_label_2' => $vehicle->km . ' kms',                               
            );

            foreach( $vehicle->vehicle_images as $index => $image ){
                if( $index === 0 ){
                    $product['image_link'] = "https://abcars.mx/abcars-backend/api/image_vehicle/" . $vehicle->vehicle_images[0]->path;
                }else {
                    $product["additional_image_link$index"] = "https://abcars.mx/abcars-backend/api/image_vehicle/" . $vehicle->vehicle_images[$index]->path;
                }
            }

            $data['channel']['product'.$key] = $product;
        }     
            
        $xml = $this->arrayToXml($data); // Necesitarás implementar esta función
    
        return response($xml, 200)->header('Content-Type', 'text/xml');
    }

    private function arrayToXml($array, $xml = null)
    {
        if ($xml === null) {
            $xml = new \SimpleXMLElement('<rss/>');            
        }

        foreach ($array as $key => $value) {
            // Si $value es un array y tiene una clave '__attributes', consideramos que son atributos
            if (is_array($value) && isset($value['__attributes'])) {
                $element = $xml->addChild($key, $value['value']);
                foreach ($value['__attributes'] as $attrKey => $attrValue) {
                    $element->addAttribute($attrKey, $attrValue);
                }
                unset($value['__attributes']);                
            } elseif (is_array($value)) {                
                if(str_contains($key, 'product')){
                    $key = 'item';
                }                                                                           
                $this->arrayToXml($value, $xml->addChild($key));
            } else { 
                if(str_contains($key, 'additional_image_link')){
                    $key = 'additional_image_link';
                }                                      
                $xml->addChild($key, htmlspecialchars($value));
            }
        }

        return $xml->asXML();
    }
}
