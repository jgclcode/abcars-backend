<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quote;


class QuoteController extends Controller
{
  public function index(){       
    $quote = Quote::paginate( 10 );
    $data = array(
      'code' => 200,
      'status' => 'success',
      'quote' => $quote
    );

    return response()->json($data, $data['code']);
  }

  public function store(Request $request){
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);

    //verificacion de datos que este lleno 
    if(is_array($request->all()) ) {
      //especificacion de tipado y campos requeridos 
      $rules = [
          'type' => 'required|max:255|string',
          'vin' => 'required|max:255|string',                    
          'status' => 'required|max:255|string',                    
          'quoteDate' => 'required|max:255|string',
          'client_id' => 'required|exists:clients,id',                    
          'brand_id' => 'required|exists:brands,id',                     
          'carmodel_id' => 'required|exists:carmodels,id'     
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
          // Crear el quote
          $quote = new Quote();
          $quote->type = $request->type;
          $quote->vin = strtoupper($request->vin);                           
          $quote->status = $request->status;
          $quote->quoteDate = $request->quoteDate;

          // Generate reference rewards of 7 characters dinamic            
          $permitted_chars = '0123456789';                        
          $flag = true;
          
          // Checking reference rewards
          while ($flag) {
            $reference = substr(str_shuffle($permitted_chars), 0, 5);
            $orderExists = Quote::where('order', $reference)->first(); 

            if (!is_object($orderExists)) {
              $quote->order = $reference;
              $flag = false;
            }
          }

          $quote->client_id = $request->client_id;
          $quote->brand_id = $request->brand_id;
          $quote->carmodel_id = $request->carmodel_id;
          $quote->save();  

          $data = array(
            'status' => 'success',
            'code'   => '200',
            'message' => 'quote creado exitosamente',
            'quote' => $quote
          );                
        }

      }catch (Exception $e){
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

    if( is_array($request->all()) && $checkToken){

      $rules =[
        'type' => 'required|max:255|string',
        'vin' => 'required|max:255|string',                    
        'status' => 'required|max:255|string',                    
        'quoteDate' => 'required|max:255|string',
        'incomeService' => 'string',
        'admissionDate' => 'string',
        'incomeKM' => 'string',                    
        'client_id' => 'required|exists:clients,id',                    
        'brand_id' => 'required|exists:brands,id',                     
        'carmodel_id' => 'required|exists:carmodels,id'                         
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
          $quote = Quote::find( $id );

          if( is_object($quote) && !empty($quote) ){                
            $quote->type = $request->type;
            $quote->vin = strtoupper($request->vin);                           
            $quote->status = $request->status;
            $quote->quoteDate = $request->quoteDate;
            $quote->incomeService = $request->incomeService;
            $quote->admissionDate = $request->admissionDate;
            $quote->incomeKM = $request->incomeKM;
            $quote->client_id = $request->client_id;
            $quote->brand_id = $request->brand_id;
            $quote->carmodel_id = $request->carmodel_id;               
            $quote->save();  

            $data = array(
              'status' => 'success',
              'code'   => '200',
              'message' => 'quote se ha actualizado correctamente'               
            );
          }else{
              $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'id de quote no existe'
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

  public function destroy(Request $request,$id){
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);

    if( is_array($request->all()) && $checkToken ){
      // Inicio Try catch
      try{
        $Quote = Quote::find( $id );

        if( is_object($Quote) && !is_null($Quote) ){

          try{
              $Quote->delete();

              $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'Quote ha sido eliminado correctamente'
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
              'message' => 'El id del Quote no existe'
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

  public function setServiceToQuote( Request $request ){
    if (is_array($request->all()) ) {
      $rules =[                              
        'quote_id' => 'required|exists:quotes,id',                    
        'service_id' => 'required|exists:services,id'                         
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
          $quote = Quote::find( $request->quote_id );
          $carbon = new \Carbon\Carbon();          
          $quote->services()->attach( $request->service_id, ['created_at' => $carbon->now(), 'updated_at' => $carbon->now() ] );
          $data = array(
            'status' => 'success',
            'code'   => '200',
            'message' => 'El servicio se ha agregado correctamente a la cita',
            'quote' => $quote->load('services')
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
            'status' => 'error',
            'code'   => '200',
            'message'  => "El usuario no esta identificado"
        );
    }
    
    return response()->json($data, $data['code']); 
  }

  public function getDataQuotes( $word = '' ){
    $quotes = Quote::select('quotes.*')
                    ->join('quote_service', 'quote_service.quote_id', 'quotes.id')
                    ->join('services', 'services.id', 'quote_service.service_id')
                    ->join('brands', 'brands.id', 'quotes.brand_id')
                    ->join('carmodels', 'carmodels.id', 'quotes.carmodel_id')
                    ->join('clients', 'clients.id', 'quotes.client_id')
                    ->join('users', 'users.id', 'clients.user_id')
                    ->whereRaw(
                      "
                        users.name LIKE '%$word%' OR 
                        users.surname LIKE '%$word%' OR
                        users.email LIKE '%$word%' OR 
                        clients.phone1 LIKE '%$word%' OR
                        services.name LIKE '%$word%' OR
                        quotes.created_at LIKE '%$word%' OR
                        brands.name LIKE '%$word%' OR
                        carmodels.name LIKE '%$word%' OR
                        quotes.status LIKE '%$word%'
                      "
                    )
                    ->paginate( 10 );
    $quotes->load(['client','services', 'brand', 'carmodel']);

    $data = array(
      'code' => 200,
      'status' => 'success',
      'quotes' => $quotes
    );

    return response()->json($data, $data['code']);
  }

  public function newStatus(Request $request, $id){       
    if( is_array($request->all())){

      $rules =[                           
        'status' => 'required|max:255|string',
        'incomeService' => 'string',
        'admissionDate' => 'string',
        'incomeKM' => 'string',                          
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
          $quote = Quote::find( $id );

          if( is_object($quote) && !empty($quote) ){                                                     
            $quote->status = $request->status;    
            
            if ($request->incomeService) {
              $quote->incomeService = $request->incomeService;
            }

            if ($request->admissionDate) {
              $quote->admissionDate = $request->admissionDate;
            }

            if ($request->incomeKM) {
              $quote->incomeKM = $request->incomeKM;
            }
            
            $quote->save();  

            $data = array(
              'status' => 'success',
              'code'   => '200',
              'message' => 'Quote se ha actualizado correctamente'               
            );
          }else{
              $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'ID de quote no existe'
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
  
}
