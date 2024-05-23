<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sell_your_car;
use App\Models\Certification;

class CertificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Certification = Certification::paginate( 10 );
        $data = array(
          'code' => 200,
          'status' => 'success',
          'Certification' => $Certification
        );
    
        return response()->json($data, $data['code']);
    }

    public function store(Request $request){
        //verificacion de datos que este lleno 
        if(is_array($request->all()) ) {
          //especificacion de tipado y campos requeridos 
          $rules = [    
            'cvq1'=> 'in:a1,a2,a3,a4',     
            'cvq2'=> 'in:a1,a2,a3,a4',     
            'cvq3'=> 'in:a1,a2,a3,a4',     
            // 'cvq4'=> 'in:a1,a2,a3,a4',     
            // 'dateLastMaintenance' => 'date',
            'cvq5'=> 'in:a1,a2,a3,a4',     
            'cvq6'=> 'in:a1,a2,a3,a4',     
            'cvq7'=> 'in:a1,a2,a3,a4',     
            'cvq8'=> 'in:a1,a2,a3,a4',     
            'cvq9'=> 'in:a1,a2,a3,a4',     
            'cvq11'=> 'in:a1,a2,a3,a4',     
            'cvq12'=> 'in:a1,a2,a3,a4',  
            'sell_your_car_id' => 'required|exists:sell_your_cars,id'                                 
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
              // Crear el Certification
              $Certification = new Certification();  
              $Certification->cvq1 = $request-> cvq1; 
              $Certification->cvq2 = $request-> cvq2; 
              $Certification->cvq3 = $request-> cvq3; 
              // $Certification->cvq4 = $request-> cvq4;     
              $Certification->dateLastMaintenance = $request->dateLastMaintenance;
              $Certification->cvq5 = $request-> cvq5; 
              $Certification->cvq6 = $request-> cvq6; 
              $Certification->cvq7 = $request-> cvq7; 
              $Certification->cvq8 = $request-> cvq8; 
              $Certification->cvq9 = $request-> cvq9; 
              $Certification->cvq11 = $request-> cvq11; //cambiar a 10
              $Certification->cvq12 = $request-> cvq12; 
              $Certification->sell_your_car_id = $request-> sell_your_car_id;   
              $Certification->save();  
    
              $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'Certification creado exitosamente',
                'Certification' => $Certification
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
        if ( is_array($request->all()) && $checkToken ) {
    
          $rules =[
            'cvq1'=> 'in:a1,a2,a3,a4',     
            'cvq2'=> 'in:a1,a2,a3,a4',     
            'cvq3'=> 'in:a1,a2,a3,a4',     
            // 'cvq4'=> 'in:a1,a2,a3,a4',     
            // 'dateLastMaintenance' => 'date',
            'cvq5'=> 'in:a1,a2,a3,a4',     
            'cvq6'=> 'in:a1,a2,a3,a4',     
            'cvq7'=> 'in:a1,a2,a3,a4',     
            'cvq8'=> 'in:a1,a2,a3,a4',     
            'cvq9'=> 'in:a1,a2,a3,a4',     
            'cvq11'=> 'in:a1,a2,a3,a4',     
            'cvq12'=> 'in:a1,a2,a3,a4',  
            'sell_your_car_id' => 'required|exists:sell_your_cars,id'                    
          ];
          try {
              // Obtener Certificatione
              $validator = \Validator::make($request->all(), $rules);
              if ($validator->fails() ) {
                // error en los datos ingresados
                $data = array(
                  'status' => 'error',
                  'code'   => '200',
                  'errors'  => $validator->errors()->all()
                );
              }else{            
                $Certification = Certification::find( $id );
                if( is_object($Certification) && !empty($Certification) ){                
                    $Certification->cvq1 = $request-> cvq1; 
                    $Certification->cvq2 = $request-> cvq2; 
                    $Certification->cvq3 = $request-> cvq3; 
                    // $Certification->cvq4 = $request-> cvq4;     
                    $Certification->dateLastMaintenance = $request->dateLastMaintenance;
                    $Certification->cvq5 = $request-> cvq5; 
                    $Certification->cvq6 = $request-> cvq6; 
                    $Certification->cvq7 = $request-> cvq7; 
                    $Certification->cvq8 = $request-> cvq8; 
                    $Certification->cvq9 = $request-> cvq9; 
                    $Certification->cvq11 = $request-> cvq11; //cambiar a 10
                    $Certification->cvq12 = $request-> cvq12; 
                    $Certification->sell_your_car_id = $request-> sell_your_car_id;   
                    $Certification->save();                          
                    $data = array(
                      'status' => 'success',
                      'code'   => '200',
                      'message' => 'Certificatione actualizado correctamente'                
                    );
                }else{
                    $data = array(
                      'status' => 'error',
                      'code'   => '200',
                      'message' => 'El Certificatione no existe'
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
    
        if ( is_array($request->all()) && $checkToken ) {
            // Inicio Try catch
            try {
              $Certification = Certification::find( $id );
              if( is_object($Certification) && !is_null($Certification) ){
    
                  try {
                      $Certification->delete();
    
                      $data = array(
                          'status' => 'success',
                          'code'   => '200',
                          'message' => 'El Certificatione ha sido eliminado correctamente'
                      );
                    } catch (\Illuminate\Database\QueryException $e) {
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
                  'message' => 'El id del Certificatione no existe'
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

      public function GetCertificacionbyId($id){
        $Certification = Certification::where('sell_your_car_id', $id)->get();
        $data = array(
            'status' => 'success',
            'code'   => '200',
            'Certification' => $Certification
        );  
        return response()->json($data, $data['code']);    
      }

      public function getcert_vehicle($id){
        $id = Sell_your_car::firstWhere('id', $id);
        $data_vehicle_certification = Certification::firstWhere('sell_your_car_id', $id->id);
        if (is_object($data_vehicle_certification) && !is_null($data_vehicle_certification)) {
          $data = array(
            'status' => 'success',
            'code' => '200',
            'DataVehicleCertification' => $data_vehicle_certification
          );
        }

        return response()->json($data, $data['code']);
      }
}
