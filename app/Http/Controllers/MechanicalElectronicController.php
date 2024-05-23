<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sell_your_car;
use App\Models\Mechanical_electronic;

class MechanicalElectronicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Mechanical_electronic = Mechanical_electronic::paginate( 10 );
        $data = array(
          'code' => 200,
          'status' => 'success',
          'Mechanical_electronic' => $Mechanical_electronic
        );
    
        return response()->json($data, $data['code']);
    }


    public function store(Request $request){
        //verificacion de datos que este lleno 
        if(is_array($request->all()) ) {
          //especificacion de tipado y campos requeridos 
          $rules = [    
            'meq1'=> 'in:a1,a2,a3,a4',     
            'meq2'=> 'in:a1,a2,a3,a4',     
            'meq3'=> 'in:a1,a2,a3,a4',     
            'meq4'=> 'in:a1,a2,a3,a4',     
            'meq5'=> 'in:a1,a2,a3,a4',     
            'meq6'=> 'in:a1,a2,a3,a4',     
            'meq7'=> 'in:a1,a2,a3,a4',     
            'meq8'=> 'in:a1,a2,a3,a4',     
            'meq9'=> 'in:a1,a2,a3,a4',     
            'meq10'=> 'in:a1,a2,a3,a4',     
            'meq11'=> 'in:a1,a2,a3,a4',     
            'meq12'=> 'in:a1,a2,a3,a4',     
            'meq13'=> 'in:a1,a2,a3,a4',     
            'meq14'=> 'in:a1,a2,a3,a4',     
            'meq15'=> 'in:a1,a2,a3,a4',     
            'meq16'=> 'in:a1,a2,a3,a4',     
            'meq17'=> 'in:a1,a2,a3,a4',     
            'meq18'=> 'in:a1,a2,a3,a4',     
            'meq19'=> 'in:a1,a2,a3,a4',     
            'meq20'=> 'in:a1,a2,a3,a4',     
            'meq21'=> 'in:a1,a2,a3,a4',     
            'meq22'=> 'in:a1,a2,a3,a4',     
            'meq23'=> 'in:a1,a2,a3,a4',     
            'meq24'=> 'in:a1,a2,a3,a4',     
            'meq25'=> 'in:a1,a2,a3,a4',     
            'meq26'=> 'in:a1,a2,a3,a4',     
            'meq27'=> 'in:a1,a2,a3,a4',     
            'meq28'=> 'in:a1,a2,a3,a4',     
            'meq29'=> 'in:a1,a2,a3,a4',     
            'meq30'=> 'in:a1,a2,a3,a4',     
            'meq31'=> 'in:a1,a2,a3,a4',     
            'meq32'=> 'in:a1,a2,a3,a4',     
            'meq33'=> 'in:a1,a2,a3,a4',     
            'meq34'=> 'in:a1,a2,a3,a4',     
            'meq35'=> 'in:a1,a2,a3,a4',     
            'meq36'=> 'in:a1,a2,a3,a4',     
            'meq37'=> 'in:a1,a2,a3,a4', 
            //nuevos campos 
            'breakedd'=> 'numeric',  
            'breakeid'=> 'numeric',  
            'breakeit'=> 'numeric',  
            'breakedt'=> 'numeric',      
            'meq38'=> 'in:a1,a2,a3,a4',     
            'meq39'=> 'in:a1,a2,a3,a4',     
            'meq40'=> 'in:a1,a2,a3,a4',
            //nuevos campos
            'depthdd'=> 'numeric', 
            'depthid'=> 'numeric', 
            'depthit'=> 'numeric', 
            'depthdt'=> 'numeric',      
            'meq41'=> 'in:a1,a2,a3,a4',     
            'meq42'=> 'in:a1,a2,a3,a4',     
            'meq43'=> 'in:a1,a2,a3,a4',     
            'meq44'=> 'in:a1,a2,a3,a4',     
            'meq45'=> 'in:a1,a2,a3,a4',     
            'meq46'=> 'in:a1,a2,a3,a4',     
            'meq47'=> 'in:a1,a2,a3,a4',     
            'meq48'=> 'in:a1,a2,a3,a4',     
            'meq49'=> 'in:a1,a2,a3,a4',     
            'meq50'=> 'in:a1,a2,a3,a4',     
            'meq51'=> 'in:a1,a2,a3,a4',     
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
              // Crear el Mechanical_electronic
              $Mechanical_electronic = new Mechanical_electronic();  
              $Mechanical_electronic->meq1 = $request-> meq1;    
              $Mechanical_electronic->meq2 = $request-> meq2;   
              $Mechanical_electronic->meq3 = $request-> meq3;    
              $Mechanical_electronic->meq4 = $request-> meq4;     
              $Mechanical_electronic->meq5 = $request-> meq5;    
              $Mechanical_electronic->meq6 = $request-> meq6;    
              $Mechanical_electronic->meq7 = $request-> meq7;    
              $Mechanical_electronic->meq8 = $request-> meq8;    
              $Mechanical_electronic->meq9 = $request-> meq9;   
              $Mechanical_electronic->meq10 = $request-> meq10;    
              $Mechanical_electronic->meq11 = $request-> meq11;    
              $Mechanical_electronic->meq12 = $request-> meq12;   
              $Mechanical_electronic->meq13 = $request-> meq13;    
              $Mechanical_electronic->meq14 = $request-> meq14;     
              $Mechanical_electronic->meq15 = $request-> meq15;     
              $Mechanical_electronic->meq16 = $request-> meq16;     
              $Mechanical_electronic->meq17 = $request-> meq17;     
              $Mechanical_electronic->meq18 = $request-> meq18;     
              $Mechanical_electronic->meq19 = $request-> meq19;    
              $Mechanical_electronic->meq20 = $request-> meq20;    
              $Mechanical_electronic->meq21 = $request-> meq21;    
              $Mechanical_electronic->meq22 = $request-> meq22;   
              $Mechanical_electronic->meq23 = $request-> meq23;  
              $Mechanical_electronic->meq24 = $request-> meq24; 
              $Mechanical_electronic->meq25 = $request-> meq25; 
              $Mechanical_electronic->meq26 = $request-> meq26; 
              $Mechanical_electronic->meq27 = $request-> meq27; 
              $Mechanical_electronic->meq28 = $request-> meq28; 
              $Mechanical_electronic->meq29 = $request-> meq29; 
              $Mechanical_electronic->meq30 = $request-> meq30; 
              $Mechanical_electronic->meq31 = $request-> meq31; 
              $Mechanical_electronic->meq32 = $request-> meq32; 
              $Mechanical_electronic->meq33 = $request-> meq33; 
              $Mechanical_electronic->meq34 = $request-> meq34;  
              $Mechanical_electronic->meq35 = $request-> meq35;  
              $Mechanical_electronic->meq36 = $request-> meq36;  
              $Mechanical_electronic->meq37 = $request-> meq37;  
              //nuevos campos break
              $Mechanical_electronic->breakedd = $request->breakedd; 
              $Mechanical_electronic->breakeid = $request->breakeid; 
              $Mechanical_electronic->breakeit = $request->breakeit; 
              $Mechanical_electronic->breakedt = $request->breakedt;
              $Mechanical_electronic->meq38 = $request-> meq38;  
              $Mechanical_electronic->meq39 = $request-> meq39;  
              $Mechanical_electronic->meq40 = $request-> meq40;
              //nuevos campos   depth
              $Mechanical_electronic->depthdd = $request->depthdd; 
              $Mechanical_electronic->depthid = $request->depthid; 
              $Mechanical_electronic->depthit = $request->depthit; 
              $Mechanical_electronic->depthdt = $request->depthdt;
              $Mechanical_electronic->meq41 = $request-> meq41;  
              $Mechanical_electronic->meq42 = $request-> meq42;  
              $Mechanical_electronic->meq43 = $request-> meq43;  
              $Mechanical_electronic->meq44 = $request-> meq44; 
              $Mechanical_electronic->meq45 = $request-> meq45; 
              $Mechanical_electronic->meq46 = $request-> meq46; 
              $Mechanical_electronic->meq47 = $request-> meq47; 
              $Mechanical_electronic->meq48 = $request-> meq48; 
              $Mechanical_electronic->meq49 = $request-> meq49; 
              $Mechanical_electronic->meq50 = $request-> meq50; 
              $Mechanical_electronic->meq51 = $request-> meq51; 
              $Mechanical_electronic->commentaryMechanical = $request->commentaryMechanical;
              $Mechanical_electronic->sell_your_car_id = $request-> sell_your_car_id;   
              $Mechanical_electronic->save();  
    
              $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'Mechanical_electronic creado exitosamente',
                'Mechanical_electronic' => $Mechanical_electronic
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id){
        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        if ( is_array($request->all()) && $checkToken ) {
    
          $rules =[
            'meq1'=> 'in:a1,a2,a3,a4',     
            'meq2'=> 'in:a1,a2,a3,a4',     
            'meq3'=> 'in:a1,a2,a3,a4',     
            'meq4'=> 'in:a1,a2,a3,a4',     
            'meq5'=> 'in:a1,a2,a3,a4',     
            'meq6'=> 'in:a1,a2,a3,a4',     
            'meq7'=> 'in:a1,a2,a3,a4',     
            'meq8'=> 'in:a1,a2,a3,a4',     
            'meq9'=> 'in:a1,a2,a3,a4',     
            'meq10'=> 'in:a1,a2,a3,a4',     
            'meq11'=> 'in:a1,a2,a3,a4',     
            'meq12'=> 'in:a1,a2,a3,a4',     
            'meq13'=> 'in:a1,a2,a3,a4',     
            'meq14'=> 'in:a1,a2,a3,a4',     
            'meq15'=> 'in:a1,a2,a3,a4',     
            'meq16'=> 'in:a1,a2,a3,a4',     
            'meq17'=> 'in:a1,a2,a3,a4',     
            'meq18'=> 'in:a1,a2,a3,a4',     
            'meq19'=> 'in:a1,a2,a3,a4',     
            'meq20'=> 'in:a1,a2,a3,a4',     
            'meq21'=> 'in:a1,a2,a3,a4',     
            'meq22'=> 'in:a1,a2,a3,a4',     
            'meq23'=> 'in:a1,a2,a3,a4',     
            'meq24'=> 'in:a1,a2,a3,a4',     
            'meq25'=> 'in:a1,a2,a3,a4',     
            'meq26'=> 'in:a1,a2,a3,a4',     
            'meq27'=> 'in:a1,a2,a3,a4',     
            'meq28'=> 'in:a1,a2,a3,a4',     
            'meq29'=> 'in:a1,a2,a3,a4',     
            'meq30'=> 'in:a1,a2,a3,a4',     
            'meq31'=> 'in:a1,a2,a3,a4',     
            'meq32'=> 'in:a1,a2,a3,a4',     
            'meq33'=> 'in:a1,a2,a3,a4',     
            'meq34'=> 'in:a1,a2,a3,a4',     
            'meq35'=> 'in:a1,a2,a3,a4',     
            'meq36'=> 'in:a1,a2,a3,a4',     
            'meq37'=> 'in:a1,a2,a3,a4', 
            //nuevos campos 
            'breakedd'=> 'numeric',  
            'breakeid'=> 'numeric',  
            'breakeit'=> 'numeric',  
            'breakedt'=> 'numeric',      
            'meq38'=> 'in:a1,a2,a3,a4',     
            'meq39'=> 'in:a1,a2,a3,a4',     
            'meq40'=> 'in:a1,a2,a3,a4',
            //nuevos campos
            'depthdd'=> 'numeric', 
            'depthid'=> 'numeric', 
            'depthit'=> 'numeric', 
            'depthdt'=> 'numeric',      
            'meq41'=> 'in:a1,a2,a3,a4',     
            'meq42'=> 'in:a1,a2,a3,a4',     
            'meq43'=> 'in:a1,a2,a3,a4',     
            'meq44'=> 'in:a1,a2,a3,a4',     
            'meq45'=> 'in:a1,a2,a3,a4',     
            'meq46'=> 'in:a1,a2,a3,a4',     
            'meq47'=> 'in:a1,a2,a3,a4',     
            'meq48'=> 'in:a1,a2,a3,a4',     
            'meq49'=> 'in:a1,a2,a3,a4',     
            'meq50'=> 'in:a1,a2,a3,a4',     
            'meq51'=> 'in:a1,a2,a3,a4',     
            'sell_your_car_id' => 'required|exists:sell_your_cars,id'                   
          ];
          try {
              // Obtener Mechanical_electronice
              $validator = \Validator::make($request->all(), $rules);
              if ($validator->fails() ) {
                // error en los datos ingresados
                $data = array(
                  'status' => 'error',
                  'code'   => '200',
                  'errors'  => $validator->errors()->all()
                );
              }else{            
                $Mechanical_electronic = Mechanical_electronic::find( $id );
                if( is_object($Mechanical_electronic) && !empty($Mechanical_electronic) ){                
                    $Mechanical_electronic->meq1 = $request-> meq1;    
                    $Mechanical_electronic->meq2 = $request-> meq2;   
                    $Mechanical_electronic->meq3 = $request-> meq3;    
                    $Mechanical_electronic->meq4 = $request-> meq4;     
                    $Mechanical_electronic->meq5 = $request-> meq5;    
                    $Mechanical_electronic->meq6 = $request-> meq6;    
                    $Mechanical_electronic->meq7 = $request-> meq7;    
                    $Mechanical_electronic->meq8 = $request-> meq8;    
                    $Mechanical_electronic->meq9 = $request-> meq9;   
                    $Mechanical_electronic->meq10 = $request-> meq10;    
                    $Mechanical_electronic->meq11 = $request-> meq11;    
                    $Mechanical_electronic->meq12 = $request-> meq12;   
                    $Mechanical_electronic->meq13 = $request-> meq13;    
                    $Mechanical_electronic->meq14 = $request-> meq14;     
                    $Mechanical_electronic->meq15 = $request-> meq15;     
                    $Mechanical_electronic->meq16 = $request-> meq16;     
                    $Mechanical_electronic->meq17 = $request-> meq17;     
                    $Mechanical_electronic->meq18 = $request-> meq18;     
                    $Mechanical_electronic->meq19 = $request-> meq19;    
                    $Mechanical_electronic->meq20 = $request-> meq20;    
                    $Mechanical_electronic->meq21 = $request-> meq21;    
                    $Mechanical_electronic->meq22 = $request-> meq22;   
                    $Mechanical_electronic->meq23 = $request-> meq23;  
                    $Mechanical_electronic->meq24 = $request-> meq24; 
                    $Mechanical_electronic->meq25 = $request-> meq25; 
                    $Mechanical_electronic->meq26 = $request-> meq26; 
                    $Mechanical_electronic->meq27 = $request-> meq27; 
                    $Mechanical_electronic->meq28 = $request-> meq28; 
                    $Mechanical_electronic->meq29 = $request-> meq29; 
                    $Mechanical_electronic->meq30 = $request-> meq30; 
                    $Mechanical_electronic->meq31 = $request-> meq31; 
                    $Mechanical_electronic->meq32 = $request-> meq32; 
                    $Mechanical_electronic->meq33 = $request-> meq33; 
                    $Mechanical_electronic->meq34 = $request-> meq34;  
                    $Mechanical_electronic->meq35 = $request-> meq35;  
                    $Mechanical_electronic->meq36 = $request-> meq36;  
                    $Mechanical_electronic->meq37 = $request-> meq37;  
                    //nuevos campos break
                    $Mechanical_electronic->breakedd = $request->breakedd; 
                    $Mechanical_electronic->breakeid = $request->breakeid; 
                    $Mechanical_electronic->breakeit = $request->breakeit; 
                    $Mechanical_electronic->breakedt = $request->breakedt;
                    $Mechanical_electronic->meq38 = $request-> meq38;  
                    $Mechanical_electronic->meq39 = $request-> meq39;  
                    $Mechanical_electronic->meq40 = $request-> meq40;
                    //nuevos campos   depth
                    $Mechanical_electronic->depthdd = $request->depthdd; 
                    $Mechanical_electronic->depthid = $request->depthid; 
                    $Mechanical_electronic->depthit = $request->depthit; 
                    $Mechanical_electronic->depthdt = $request->depthdt;
                    $Mechanical_electronic->meq41 = $request-> meq41;  
                    $Mechanical_electronic->meq42 = $request-> meq42;  
                    $Mechanical_electronic->meq43 = $request-> meq43;  
                    $Mechanical_electronic->meq44 = $request-> meq44; 
                    $Mechanical_electronic->meq45 = $request-> meq45; 
                    $Mechanical_electronic->meq46 = $request-> meq46; 
                    $Mechanical_electronic->meq47 = $request-> meq47; 
                    $Mechanical_electronic->meq48 = $request-> meq48; 
                    $Mechanical_electronic->meq49 = $request-> meq49; 
                    $Mechanical_electronic->meq50 = $request-> meq50; 
                    $Mechanical_electronic->meq51 = $request-> meq51; 
                    $Mechanical_electronic->commentaryMechanical = $request->commentaryMechanical;
                    $Mechanical_electronic->sell_your_car_id = $request-> sell_your_car_id;   
                    $Mechanical_electronic->save();                            
                    $data = array(
                      'status' => 'success',
                      'code'   => '200',
                      'message' => 'Mechanical_electronice actualizado correctamente'                
                    );
                }else{
                    $data = array(
                      'status' => 'error',
                      'code'   => '200',
                      'message' => 'El Mechanical_electronice no existe'
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id){

        // Comprobar si el usuario esta identificado
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
    
        if ( is_array($request->all()) && $checkToken ) {
            // Inicio Try catch
            try {
              $Mechanical_electronic = Mechanical_electronic::find( $id );
              if( is_object($Mechanical_electronic) && !is_null($Mechanical_electronic) ){
    
                  try {
                      $Mechanical_electronic->delete();
    
                      $data = array(
                          'status' => 'success',
                          'code'   => '200',
                          'message' => 'El Mechanical_electronice ha sido eliminado correctamente'
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
                  'message' => 'El id del Mechanical_electronice no existe'
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
      
      public function GetMecanicalbyId($id){
        $Mechanical_electronic = Mechanical_electronic::where('sell_your_car_id', $id)->get();
        $data = array(
            'status' => 'success',
            'code'   => '200',
            'Mechanical_electronic' => $Mechanical_electronic
        );  
        return response()->json($data, $data['code']);    
    }

    public function getmechanic_electronic($id){
      $id = Sell_your_car::firstWhere('id', $id);
      $data_mechanic_electronic = Mechanical_electronic::firstWhere('sell_your_car_id', $id->id);
      if (is_object($data_mechanic_electronic) && !is_null($data_mechanic_electronic)) {
        $data = array(
          'status' => 'success',
          'code' => '200',
          'DataMechanicElectronic' => $data_mechanic_electronic
        );
      }else{
        $data = array(
          'status' => 'error',
          'code' => '404',
          'message' => 'El id del Mechanic Electronic no existe'
        );
      }

      return response()->json($data, $data['code']);
    }
}
