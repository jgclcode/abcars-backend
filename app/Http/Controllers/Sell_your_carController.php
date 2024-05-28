<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sell_your_car;
use App\Models\Client;
use App\Models\User;
use App\Models\Brand;
use App\Models\Valuator;
use App\Models\Carmodel;

class Sell_your_carController extends Controller
{
    public function index() {
        //        
        $from = date("Y-m-d 00:00:00",strtotime(date("Y-m-d 00:00:00")."- 15 days")); 
        $to = date("Y-m-d 23:59:59");      
        //
        $sell_your_car = Sell_your_car::where('status', '!=', 'inactive')
                                    ->whereBetween('created_at', [$from, $to])
                                    ->with(['spare_parts' => function($table){
                                        $table->where('priceOriginal', '>=', 0) // >= 0 && 
                                                ->where('priceGeneric', '>=', 0) // >= 0 && 
                                                ->where('priceUsed', '>=', 0) // >= 0 &&
                                                ->where('timeOriginal', null) // &&
                                                ->where('timeGeneric', null) // &&
                                                ->where('timeUsed', null); //, 
                                    }])
                                    // ->paginate(10)
                                    ->orderBy('id', 'desc')->paginate(10);
        $sell_your_car->load('brand', 'carmodel', 'check_list', 'client_sale');

        $data = array(
            'code' => 200,
            'status' => 'success',
            'sell_your_car' => $sell_your_car
        );

        return response()->json($data, $data['code']);
    }
    
    public function store(Request $request)
    {
        if (!is_array($request->all())) {
            $data = array(
              'status' => 'error',
              'code'   => '200',
              'message'  => "request must be an array"
            );
        }

        $rules = [
            'version'   => 'required|max:255|string',
            'km'   => 'required|integer',            
            'year'   => 'required|integer', 
            'vin'   => 'required|max:17|min:17|string',           
            'date'   => 'required|string', 
            'hour'   => 'required|string', 
            'brand_id' => 'required|exists:brands,id',                                         
            'carmodel_id' => 'required|exists:carmodels,id',
            'client_id' => 'required|exists:clients,id'                                                                                  
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
                // $sell_your_car = Sell_your_car::where('vin', $request->vin )->first();
                // if( !is_object( $sell_your_car ) && is_null( $sell_your_car ) ){
                    $sell_your_car = new Sell_your_car;
                    $sell_your_car->version = $request->version;
                    $sell_your_car->km = $request->km;
                    $sell_your_car->year = $request->year;
                    $sell_your_car->vin = $request->vin;
                    $sell_your_car->date = $request->date;
                    $sell_your_car->hour = $request->hour;
                    $sell_your_car->brand_id = $request->brand_id;
                    $sell_your_car->carmodel_id = $request->carmodel_id;
                    $sell_your_car->subsidiary = $request->subsidiary;
                    $sell_your_car->client_id = $request->client_id;
                    if($sell_your_car->save()){
                        if ($request->valuador_id === null) {
                            // $this->dynamicAssignmentToValuator( $sell_your_car->id );
                            $this->getValuationQuote( $sell_your_car->id );
                        }else {
                            $this->assignmentToValuator( $request->valuador_id, $sell_your_car->id );
                        }
                    }
                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'La petición se ha generado correctamente',
                        'sell_your_car' => $sell_your_car
                    ); 
                // }else{
                //     $data = array(
                //         'status' => 'success',
                //         'code'   => '200',
                //         'message' => 'Ya existe una petición generada con anterioridad con este vin',
                //         'sell_your_car' => $sell_your_car
                //     );  
                // }                 
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
    
    public function update(Request $request, $id)
    {
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        \Log::info('Token recibido: ' . $token);

        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

       \Log::info('Token válido: ' . json_encode($checkToken)); 

        if (is_array($request->all()) && $checkToken) {
            
            $rules = [
                'version' => 'string',
                'km' => 'integer',
                'year' => 'integer',
                'vin' => 'string',
                'status' => 'string'
            ];

            try {
                $validator = \Validator::make($request->all(), $rules);
                if ($validator->fails()) {
                    // Error en los datos
                    $data = array(
                        'status' => 'error',
                        'code' => '200',
                        'errors' => $validator->errors()->all()
                    );
                }else {
                    if ($request->vin != '00000000000000000') {
                        $find_vin = Sell_your_car::where('vin', $request->vin)->get();
                        if ($find_vin != null) {
                            foreach ($find_vin as $find) {
                                $find->delete();
                            }
                        }
                    }
                    $sell_your_car = Sell_your_car::find( $id );
                    if (is_object($sell_your_car) && !empty($sell_your_car)) {
                        $sell_your_car->vin = $request->vin;
                        $sell_your_car->version = $request->version;
                        $sell_your_car->km = $request->km;
                        $sell_your_car->status = $request->status;
                        $sell_your_car->save();
                        $data = array(
                            'status' => 'success',
                            'code' => '200',
                            'message' => 'El campo km se ha actualiizado correctamente',
                            'sell_your_car' => $sell_your_car
                        );
                    }else {
                        $data = array(
                            'status' => 'error',
                            'code' => '200',
                            'message' => 'ID de financing no existe'
                        );
                    }
                }
            } catch (Exception $e) {
                $data = array(
                    'status' => 'error',
                    'code' => '200',
                    'message' => 'Los datos enviados no son correctos, ' . $e
                );
            }

        }else {
            $data = array(
                'status' => 'error',
                'code' => '200',
                'message' => 'El usuario no está identificado'
             );
        }

        return response()->json($data, $data['code']);
    }
    
    public function destroy($id)
    {
        //
    }

    // Llenado de tabla Vender tu auto
    public function sell_car(){
        $sell_your_car = Sell_your_car::paginate( 10 );

        $data = array(
            'code' => 200,
            'status' => 'success',
            'sell_your_car' => $sell_your_car->load('brand', 'carmodel', 'check_list', 'client_sale') 
        );

        return response()->json($data, $data['code']);
    }

    // Llenado de algunos campos necesarios en el formulario Checklist de valuación
    public function sell_car_valuation( $id ){
        // $sell_car_valuation_id = Sell_your_car::where('vin', $vin)->first();
        $sell_car_valuation_id = Sell_your_car::firstWhere('id', $id);

        $data = array(
            'code' => 200,
            'status' => 'success',
            'sell_car_valuation_id' => $sell_car_valuation_id->load('brand', 'carmodel', 'client_sale')
        );

        return response()->json($data, $data['code']);
    }

    public function getProspects(){
        $sale = Sell_your_car::orderBy('id')->get()->last();
        $sale->load('brand', 'carmodel', 'client_sale');
        return $sale;
    }

    public function dynamicAssignmentToValuator( int $sell_your_car_id ){
        $valuators = Valuator::where('status', 'active')->get();
        $elements = array( 
            'id' => null,
            'total' => 0,
            'valuator' => null
        );
        foreach($valuators as $valuator){
            if( is_null($elements['id']) ){
                $elements['id'] = $valuator->id;
                $elements['total'] = $valuator->sell_your_cars()->count();
                $elements['valuator'] = $valuator;

                $valuator->sell_your_cars()->detach($sell_your_car_id); 
            }else{
                $total_actual = $valuator->sell_your_cars()->count();
                if( $elements['total'] >= $total_actual ){
                    $elements['id'] = $valuator->id;
                    $elements['total'] = $total_actual;
                    $elements['valuator'] = $valuator;                    
                }
                $valuator->sell_your_cars()->detach($sell_your_car_id); 
            }            
        }
        $elements['valuator']->sell_your_cars()->attach( $sell_your_car_id );        
    }

    public function assignmentToValuator( int $userID, int $sell_your_car_id ){
        $valuator = Valuator::where('user_id', $userID)->get();
        foreach ($valuator as $valuador) {
            $valuatorRcd = $valuador;
        }

        $valuatorRcd->sell_your_cars()->attach($sell_your_car_id);
    }

    public function updatestandbyparts(Request $request, $id){
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if (is_array($request->all()) && $checkToken) {
            $rules = [
                'status' => 'in:stand_by,valued_standBy_parts,valued,buy_offer,full_documentation,ready_to_buy,reject,rejected,buy,preparation,edit_rep,pre_approved,readyForSale,done,inactive,to_valued,pre_preparation'
            ];

            try {
                $validator = \Validator::make($request->all(), $rules);
                if($validator->fails()){
                    $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'errors' => $validator->errors()->all()
                    );
                }else{
                    $sell_your_car = Sell_your_car::find($id);
                    if(is_object($sell_your_car) && !empty($sell_your_car)){
                        $sell_your_car->status = $request->status;
                        $sell_your_car->save();
                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'El status del Sell_your_car se ha actualizado correctamente',
                            'sellYourCar' => $sell_your_car
                        );
                    }else{
                        $data = array(
                            'status' => 'error',
                            'code'   => '200',
                            'message' => 'id del sell_your_car no existe'
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
                'message' => 'El usuario no está identificado'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function update_estimated_payment(Request $request, $id){
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if (is_array($request->all()) && $checkToken) {
            $sell_your_car = Sell_your_car::find($id);
            if (is_object($sell_your_car) && !empty($sell_your_car)) {
                $sell_your_car->estimated_payment_date = $request->estimated_payment_date;
                $sell_your_car->save();
                $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'message' => 'El estimated_payment_date del Sell_your_car se ha actualizado correctamente',
                    'sellYourCar' => $sell_your_car
                );
            }else {
                $data = array(
                    'status' => 'error',
                    'code' => '200',
                    'message' => 'id del sell_your_car no existe'
                );
            }
        }else {
            $data = array(
                'status' => 'error',
                'code' => '200',
                'message' => 'El usuario no está identificado'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function notificationvaluation($id,$email_val){
        $sell_your_car = Sell_your_car::find($id);
        $marca =Brand::find($sell_your_car->brand_id );
        $modelo =Carmodel::find($sell_your_car->carmodel_id );
        $clie =Client::find($sell_your_car->client_id );
        $usuario =User::find($clie->user_id );
 

        if(is_object($sell_your_car)){
 
            \EmailHelper::NewValuation($email_val, $marca->name, $modelo->name, $sell_your_car->year, $usuario->name, $usuario->surname, $clie->phone1, $usuario->email,$sell_your_car -> date, $sell_your_car ->hour); 
            $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'Correo enviado exitosamente'
            );
        }
        else{
            $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'id del sell_your_car no existe'
            );
        }
        return response()->json($data, $data['code']);
    }

    public function notificationvaluationA($id){
        $sell_your_car = Sell_your_car::find($id);

         if(is_object($sell_your_car)){
            $clie =Client::find($sell_your_car->client_id );
 
            $usuario =User::find($clie->user_id );
            \EmailHelper::vehicleAccepted( $usuario->email,  
            $usuario->name, $usuario->surname
            ,$sell_your_car -> date, $sell_your_car ->hour); 
            $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'Correo enviado exitosamente'
            );
        }
        else{
            $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'id del sell_your_car no existe'
            );
        }
        return response()->json($data, $data['code']);
    }


    public function notificationvaluationD($id){
        $sell_your_car = Sell_your_car::find($id);

         if(is_object($sell_your_car)){
            $clie =Client::find($sell_your_car->client_id );
 
            $usuario =User::find($clie->user_id );
            \EmailHelper::vehicleDenied( $usuario->email,$usuario->name, $usuario->surname ); 
            $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'Correo enviado exitosamente'
            );
        }
        else{
            $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'id del sell_your_car no existe'
            );
        }
        return response()->json($data, $data['code']);
    }

    public function getvaluatordates($idUser){
        $valuator = Valuator::where('user_id', $idUser)->get();
        foreach ($valuator as $valuador) {
            $valuatorID = $valuador->id;
        }
        if (!empty($valuatorID)) {
            $valuatorDates = Valuator::find($valuatorID)->sell_your_cars()->orderBy('created_at', 'desc')->paginate(10); /* get() */
    
            $valuatorDates->load('brand', 'carmodel', 'check_list', 'client_sale');
    
            $data = array(
                'code' => 200,
                'status' => 'success',
                'sell_your_car' => $valuatorDates
            );

            return response()->json($data, $data['code']);

        }else {
            $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'id del user no existe'
            );

            return response()->json($data, $data['code']);
        }


    }
    
    public function getValuationQuotes(){
        //        
        $from = date("Y-m-d 00:00:00",strtotime(date("Y-m-d 00:00:00")."- 30 days")); 
        $to = date("Y-m-d 23:59:59");      
        //
        $valuation_quotes = Sell_your_car::select('sell_your_cars.id AS sycID', 'valuators.user_id AS id', 
                                                  'uv.name AS name', 'uv.surname AS surname', 'brands.name AS brandName',  /** 'uv.id AS userValuatorID', */
                                                  'carmodels.name AS carmodelName', 'users.name AS usernamec', 
                                                  'users.surname AS surnamec', 'users.email', 'clients.phone1',
                                                  'sell_your_cars.subsidiary','sell_your_cars.created_at', 'sell_your_cars.date', 'sell_your_cars.hour')
                        /** ////////////////// */
                        ->leftJoin('sell_your_car_valuator', 'sell_your_car_valuator.sell_your_car_id', 'sell_your_cars.id')
                        ->leftJoin('valuators', 'valuators.id', 'sell_your_car_valuator.valuator_id')
                        ->leftjoin('users AS uv', 'uv.id', 'valuators.user_id')
                        /** ////////////////// */
                        ->join('clients', 'clients.id', 'sell_your_cars.client_id')
                        ->join('users', 'users.id', 'clients.user_id')
                        ->join('brands', 'brands.id', 'sell_your_cars.brand_id')
                        ->join('carmodels', 'carmodels.id', 'sell_your_cars.carmodel_id')
                        ->whereNotNull('sell_your_cars.subsidiary')
                        ->where(function ($query) {
                            $query->where('valuators.status', 'active');
                            $query->orWhereNull('valuators.status');
                        })
                        ->whereBetween('sell_your_cars.created_at', [$from, $to])
                        ->orderBy('sell_your_cars.id', 'desc')->paginate(10);
        if (is_object($valuation_quotes) && !empty($valuation_quotes)) {
            $data = array(
                'code' => 200,
                'status' => 'success',
                'valuation_quotes' => $valuation_quotes
            );
        }else {
            $data = array(
                'code' => '404',
                'status' => 'error',
                'message' => 'No hay valuaciones externas'
            );
        }
        return response()->json($data, $data['code']);
    }

    public function getValuationQuote($sell_your_car_id) {
        $valuation_quote = Sell_your_car::select('sell_your_cars.id', 'brands.name AS brandName', 
                                                  'carmodels.name AS carmodelName', 'sell_your_cars.km', 'sell_your_cars.year', 'users.name AS userName', 
                                                  'users.surname', 'users.email', 'clients.phone1',
                                                  'sell_your_cars.subsidiary', 'sell_your_cars.date', 'sell_your_cars.hour')
                        ->join('clients', 'clients.id', 'sell_your_cars.client_id')
                        ->join('users', 'users.id', 'clients.user_id')
                        ->join('brands', 'brands.id', 'sell_your_cars.brand_id')
                        ->join('carmodels', 'carmodels.id', 'sell_your_cars.carmodel_id')
                        ->whereIn('sell_your_cars.subsidiary', ['puebla', 'tlaxcala', 'pachuca'])
                        ->where('sell_your_cars.id', $sell_your_car_id)
                        ->get()->first();
        // $data = array(
        //     'code' => 200,
        //     'status' => 'success',
        //     'valuation_quote' => $valuation_quote
        // );
        // return response()->json($data, $data['code']);

        // Create zappier object
        $zappier = array(
            'fecha' => date_format(date_create(), "d-m-y H:i:s"),
            'tipo'  => 'Seminuevo',
            'campaña'   => 'Cita valuación externa',
            'nombre'    => $valuation_quote->userName,
            'apellidos' => $valuation_quote->surname,
            'telefono'  => $valuation_quote->phone1,
            'email'     => $valuation_quote->email,
            'marca'     => $valuation_quote->brandName, 
            'modelo'    => $valuation_quote->carmodelName,
            'km'        => $valuation_quote->km,
            'año'       => $valuation_quote->year,
            'cita'      => $valuation_quote->date,
            'Hora'      => $valuation_quote->hour,
            'sucursal'  => $valuation_quote->subsidiary
        );

        // Send content to webhook
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://hooks.zapier.com/hooks/catch/8825119/3pa3d1g");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($zappier));

        $data = curl_exec($ch);
        curl_close($ch);

        // Enviar mail a lesamaoyabi@gmail.com
        if ($valuation_quote->subsidiary == 'puebla' || $valuation_quote->subsidiary == 'tlaxcala') {
            \EmailHelper::sendMailExternalValuation('admon-seminuevos@chevroletbalderrama.com', $valuation_quote->userName, $valuation_quote->surname,
                                            $valuation_quote->phone1, $valuation_quote->email,
                                            $valuation_quote->brandName, $valuation_quote->carmodelName,
                                            $valuation_quote->subsidiary, $valuation_quote->date, $valuation_quote->hour, $sell_your_car_id);
        }

        if ($valuation_quote->subsidiary == 'pachuca') {
            \EmailHelper::sendMailExternalValuation('admvecsaseminuevos@bmwvecsa.com', $valuation_quote->userName, $valuation_quote->surname,
                                            $valuation_quote->phone1, $valuation_quote->email,
                                            $valuation_quote->brandName, $valuation_quote->carmodelName,
                                            $valuation_quote->subsidiary, $valuation_quote->date, $valuation_quote->hour, $sell_your_car_id);
        }
        // Delete zappier array
        unset($zappier);
    }

    public function getActiveValuator() {
        $active_valuator = Valuator::select('users.id', 'users.name', 'users.surname')
                                    ->join('users', 'users.id', 'valuators.user_id')
                                    ->where('valuators.status', 'active')
                                    ->get();
        $data = array(
            'code' => 200,
            'status' => 'success',
            'active_valuator' => $active_valuator
        );
        return response()->json($data, $data['code']);
    }

    public function existsAssignValuator( int $userID, int $sell_your_car_id ) {
        $valuator = Valuator::where('user_id', $userID)->first();
        $valuation_quote = Sell_your_car::select('sell_your_cars.id', 'brands.name AS brandName', 
                                                  'carmodels.name AS carmodelName', 'users.name AS userName', 
                                                  'users.surname', 'users.email', 'clients.phone1',
                                                  'sell_your_cars.subsidiary', 'sell_your_cars.date', 'sell_your_cars.hour')
                        ->join('clients', 'clients.id', 'sell_your_cars.client_id')
                        ->join('users', 'users.id', 'clients.user_id')
                        ->join('brands', 'brands.id', 'sell_your_cars.brand_id')
                        ->join('carmodels', 'carmodels.id', 'sell_your_cars.carmodel_id')
                        ->whereIn('sell_your_cars.subsidiary', ['puebla', 'tlaxcala', 'pachuca'])
                        ->where('sell_your_cars.id', $sell_your_car_id)
                        ->get()->first();
        $exists_syc = Sell_your_car::join('sell_your_car_valuator', 'sell_your_car_valuator.sell_your_car_id', 'sell_your_cars.id')
                                    ->where('sell_your_cars.id', $sell_your_car_id)
                                    ->get()->first();
        if (!is_null($exists_syc)) { /** $exists_syc->count() > 0 &&  */
            $valuators = Valuator::where('status', 'active')->get();
            foreach ($valuators as $valuator_detach) {
                $valuator_detach->sell_your_cars()->detach($sell_your_car_id);
            }
            $valuator->sell_your_cars()->attach($sell_your_car_id);
            $data = array(
                'code' => 200,
                'status' => 'success',
                'message' => 'Se ha re-asignado el valuador exitosamente.',
                // 'exists_syc' => $exists_syc
            );
            switch ($valuator->user_id) {
                case '14':
                    \EmailHelper::sendMailExternalValuation('ftapia@chevroletbalderrama.com', $valuation_quote->userName, $valuation_quote->surname,
                                            $valuation_quote->phone1, $valuation_quote->email,
                                            $valuation_quote->brandName, $valuation_quote->carmodelName,
                                            $valuation_quote->subsidiary, $valuation_quote->date, $valuation_quote->hour, $sell_your_car_id);
                    break;
                case '20':
                    \EmailHelper::sendMailExternalValuation('cfuentes@chevroletbalderrama.com', $valuation_quote->userName, $valuation_quote->surname,
                                            $valuation_quote->phone1, $valuation_quote->email,
                                            $valuation_quote->brandName, $valuation_quote->carmodelName,
                                            $valuation_quote->subsidiary, $valuation_quote->date, $valuation_quote->hour, $sell_your_car_id);
                    break;
                case '865':
                    \EmailHelper::sendMailExternalValuation('nain.casanas@bmwvecsa.com', $valuation_quote->userName, $valuation_quote->surname,
                                            $valuation_quote->phone1, $valuation_quote->email,
                                            $valuation_quote->brandName, $valuation_quote->carmodelName,
                                            $valuation_quote->subsidiary, $valuation_quote->date, $valuation_quote->hour, $sell_your_car_id);
                    break;
                case '1410':
                    \EmailHelper::sendMailExternalValuation('abigail.guzman@bmwvecsa.com', $valuation_quote->userName, $valuation_quote->surname,
                                            $valuation_quote->phone1, $valuation_quote->email,
                                            $valuation_quote->brandName, $valuation_quote->carmodelName,
                                            $valuation_quote->subsidiary, $valuation_quote->date, $valuation_quote->hour, $sell_your_car_id);
                    break;
                
                default:
                    echo 'No existe el valuador';
                    break;
            }
        }else {
            $valuator->sell_your_cars()->attach($sell_your_car_id);
            $data = array(
                'code' => 200,
                'status' => 'success',
                'message' => 'El valuador se ha asignado exitosamente.',
                // 'exists_syc' => $exists_syc
            );
            switch ($valuator->user_id) {
                case '14':
                    \EmailHelper::sendMailExternalValuation('ftapia@chevroletbalderrama.com', $valuation_quote->userName, $valuation_quote->surname,
                                            $valuation_quote->phone1, $valuation_quote->email,
                                            $valuation_quote->brandName, $valuation_quote->carmodelName,
                                            $valuation_quote->subsidiary, $valuation_quote->date, $valuation_quote->hour, $sell_your_car_id);
                    break;
                case '20':
                    \EmailHelper::sendMailExternalValuation('cfuentes@chevroletbalderrama.com', $valuation_quote->userName, $valuation_quote->surname,
                                            $valuation_quote->phone1, $valuation_quote->email,
                                            $valuation_quote->brandName, $valuation_quote->carmodelName,
                                            $valuation_quote->subsidiary, $valuation_quote->date, $valuation_quote->hour, $sell_your_car_id);
                    break;
                case '865':
                    \EmailHelper::sendMailExternalValuation('nain.casanas@bmwvecsa.com', $valuation_quote->userName, $valuation_quote->surname,
                                            $valuation_quote->phone1, $valuation_quote->email,
                                            $valuation_quote->brandName, $valuation_quote->carmodelName,
                                            $valuation_quote->subsidiary, $valuation_quote->date, $valuation_quote->hour, $sell_your_car_id);
                    break;
                case '1410':
                    \EmailHelper::sendMailExternalValuation('abigail.guzman@bmwvecsa.com', $valuation_quote->userName, $valuation_quote->surname,
                                            $valuation_quote->phone1, $valuation_quote->email,
                                            $valuation_quote->brandName, $valuation_quote->carmodelName,
                                            $valuation_quote->subsidiary, $valuation_quote->date, $valuation_quote->hour, $sell_your_car_id);
                    break;
                
                default:
                    echo 'No existe el valuador';
                    break;
            }
            
        }
        return response()->json($data, $data['code']);
    }
}
