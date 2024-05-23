<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Valuation;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Sell_your_car;
use App\Models\Client;
use App\Models\User;
use App\Models\Brand;
use App\Models\Valuator;
use App\Models\Carmodel;
use App\Models\Painting_work;
use App\Models\Spare_part;
use App\Models\Check_List;




class ValuationController extends Controller
{
    //index es para mostrar datos
  public function index(){
    $valuations = Valuation::paginate( 10 );
    $data = array(
      'code' => 200,
      'status' => 'success',
      'valuations' => $valuations
    );
      
    return response()->json($data, $data['code']);
  }
    //store se utiliza para insertar datos
  public function store(Request $request){
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);

    //verificacion de datos que este lleno 
    if (is_array($request->all()) && $checkToken ) {
      //especificacion de tipado y campos requeridos 
      $rules = [
          'referrer' => 'required|max:255|string',
          'seller' => 'required|max:255|string',
          'location' => 'required|max:255|string',
          'customerPrice' => 'required|max:255|string',
          'evaluationOffer' => 'required|max:255|string',
          'observations' => 'required|max:255|string',
          'takeCarAccount' => 'required|max:255|string',
          'takeCarBuy' => 'required|max:255|string',
          'branch_id' => 'required|exists:branches,id',  
          'valuator_id' => 'required|exists:valuators,id',                    
          'adviser_id' => 'required|exists:advisers,id'                    
      ];
    
      try{
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
          // Crear el valuator
          $vakuation = new Valuation();
          $vakuation->referrer = $request->referrer;
          $vakuation->seller = $request->seller;
          $vakuation->location = $request->location;
          $vakuation->customerPrice = $request->customerPrice;
          $vakuation->evaluationOffer = $request->evaluationOffer;
          $vakuation->observations = $request->observations;
          $vakuation->takeCarAccount = $request->takeCarAccount;
          $vakuation->takeCarBuy = $request->takeCarBuy;
          $vakuation->branch_id = $request->branch_id;
          $vakuation->valuator_id = $request->valuator_id;
          $vakuation->adviser_id = $request->adviser_id;
          $vakuation->save();  

          $data = array(
              'status' => 'success',
              'code'   => '200',
              'message' => 'La vakuation se creo exitosamente'
            );                
        }
      }catch(Exception $e){
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

  //update se utiliza para actualizar datos
  public function update(Request $request, $id){

    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);

    if ( is_array($request->all()) && $checkToken ){
      $rules = [
          'referrer' => 'required|max:255|string',
          'seller' => 'required|max:255|string',
          'location' => 'required|max:255|string',
          'customerPrice' => 'required|max:255|string',
          'evaluationOffer' => 'required|max:255|string',
          'observations' => 'required|max:255|string',
          'takeCarAccount' => 'required|max:255|string',
          'takeCarBuy' => 'required|max:255|string',
          'branch_id' => 'required|exists:branches,id',  
          'valuator_id' => 'required|exists:valuators,id',                    
          'adviser_id' => 'required|exists:advisers,id'                     
      ];
      try{
        // Obtener valuator
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails() ){
          // error en los datos ingresados
          $data = array(
            'status' => 'error',
            'code'   => '200',
            'errors'  => $validator->errors()->all()
          );
        }else{            
          $vakuation = Valuation::find( $id );

          if( is_object($vakuation) && !empty($vakuation)){                
              $vakuation->referrer = $request->referrer;
              $vakuation->seller = $request->seller;
              $vakuation->location = $request->location;
              $vakuation->customerPrice = $request->customerPrice;
              $vakuation->evaluationOffer = $request->evaluationOffer;
              $vakuation->observations = $request->observations;
              $vakuation->takeCarAccount = $request->takeCarAccount;
              $vakuation->takeCarBuy = $request->takeCarBuy;
              $vakuation->branch_id = $request->branch_id;
              $vakuation->valuator_id = $request->valuator_id;
              $vakuation->adviser_id = $request->adviser_id;               
              $vakuation->save();    

              $data = array(
                  'status' => 'success',
                  'code'   => '200',
                  'message' => 'vakuation se ha actualizado correctamente'                
              );
          }else{
              $data = array(
                  'status' => 'error',
                  'code'   => '200',
                  'message' => 'id de vakuation no existe'
              );
          }            
        }
      }catch (Exception $e){
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

     //destroy se utiliza para eliminar datos 
  public function destroy(Request $request, $id){
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);

    if( is_array($request->all()) && $checkToken ){
      // Inicio Try catch
      try{
        $Valuation = Valuation::find( $id );

        if( is_object($Valuation) && !is_null($Valuation)){

          try{
            $Valuation->delete();

            $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'Valuation ha sido eliminada correctamente'
            );
          }catch (\Illuminate\Database\QueryException $e){
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
            'message' => 'El id del la Valuation no existe'
          );
        }

      }catch (Exception $e){
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

  public function authorization($id ){
    $sell_your_car = Sell_your_car::find( $id );

    if( is_object($sell_your_car) && !is_null($sell_your_car)){   
      $marca =Brand::find($sell_your_car->brand_id );
      $modelo =Carmodel::find($sell_your_car->carmodel_id );
      $hyp = Painting_work::where('sell_your_car_id', $id)
      ->where('status', 'approved')->get();

      $cys = Spare_part::where('sell_your_car_id', $id)
      ->where('status', 'approved')->get();

      $checklist = Check_List::where('sell_your_car_id', $id)->first();
      
      $valuador =User::where('id', $checklist->user_id)->first();

      $report = PDF::loadView('valoracion.authorization' ,compact(['sell_your_car','marca','modelo','hyp','cys','checklist','valuador']));

      return ($report->download('Autorizacion de acondicionamiento autos seminuevos.pdf'));
    }
    else{
      $data = array(
        'status' => 'error',
        'code'   => '404',
        'message' => 'El id del Sell your car no existe'
      );
      return response()->json($data, $data['code']);

    }
   
  }
}
