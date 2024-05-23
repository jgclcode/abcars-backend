<?php

namespace App\Http\Controllers;
use App\Models\Location_vehicle;
// use App\Models\Vehicle;
use Illuminate\Htpp\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Vehicle;


class Location_vehicleController extends Controller
{
    /**
 * Display a listing of the resource.
 *
 * @return \Illuminate\Http\Response
 */
public function index()
{
    $vehicle_location = Location_vehicle::get();
    return response() ->json(["data" => $vehicle_location], 200);
}

/**
 * Show the form for creating a new resource.
 *
 * @return \Illuminate\Http\Response
 */
public function create()
{
    //
}

/**
 * Store a newly created resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
private function saveInformation($option, Request $request)
{
  $vehicle = Vehicle::where('id',$request->vehicle_id)->first();
  $vehicle->km = $request->mileage;
  $respuesta = $vehicle->update();
  if($respuesta===true){
    $option->name = $request->name;
    $option->name_guard = $request->name_guard;
    $option->mileage = $request->mileage;
    $option->reception = $request->reception;
    $option->chofer = $request->chofer;
    $option->vehicle_id = $request->vehicle_id;                
    $option->save();  
  }
}
public function store(Request $request)
{
    if ( is_array($request->all())){

        $rules =[
            'name' => 'required|max:255',
            'name_guard' => 'required|max:255',
            'mileage' => 'required',
            'reception'  => 'required',
            'chofer' =>'required',
            'vehicle_id' => 'required|exists:vehicles,id'                    
        ];
          try {
              // Obtener advisers
              $validator = \Validator::make($request->all(), $rules);     
   
              if ($validator->fails()){
                
                  // error en los datos ingresados
                  $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'errors'  => $validator->errors()->all()
                  );
              }else{            
                  $location_vehicle = Location_vehicle::where('vehicle_id',$request->vehicle_id)->first();
                  if( is_object($location_vehicle) && !empty($location_vehicle) ){                
                    $this->saveInformation($location_vehicle,  $request);
                      $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'vehicle location se ha actualizado correctamente'
                      );
                    }else{
                      $location = new Location_vehicle();
                      $this->saveInformation($location,  $request);
                      $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'Vehicle location create  success '
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
          'message' => 'vehicle location no está identificado'
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

/**
 * Update the specified resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function update(Request $request, $id)
{
    //
}
public function getVehiclesNotTransit($name_location){
  $vehicle_location = Location_vehicle::where('name',$name_location)->get();
  return response() ->json(["data" => $vehicle_location], 200);
}
public function getLocationvehiclesId($id){
  try{
    $vehicle_location = Location_vehicle::where('vehicle_id',$id)->get();
    return response() ->json($vehicle_location,200);
  }
  catch (Exception $e){
    echo $e;
  }

}
public function transito($vehicle_id)
{
     $location_vehicle = Location_vehicle::where('vehicle_id',$vehicle_id)->first();


    try{ 
      if(is_object($location_vehicle)){
        $location_vehicle = Location_vehicle::where('vehicle_id',$vehicle_id)->first();
        $location_vehicle->name = "transito"; 
        $location_vehicle ->save();
        $data = array(
          'status' => 'success',
          'code'   => '200',
          'message'  => 'Vehicle update success ' 
        );
      
      }
      else{
        $data = array(
          'status' => 'error',
          'code' => '200',
          'message' => 'No existe Vehicle'
        );
      } 

    }catch(Exception $e){
      $data = array(
        'status' => 'error',
        'code' => '200',
        'message' => 'Error al enviar, '. $e
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
public function destroy($id)
{
    //
}
}
