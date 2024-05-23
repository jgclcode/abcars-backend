<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Check_vehicle;
use App\Models\Sell_your_car;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Response;
use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;
//prueba

use ZipArchive;


class Ckeck_vehicleController extends Controller
{
    public function index(Request $request){
 
        $check_vehicles = Check_vehicle::paginate( 10 );
         $data = array(
          'code' => 200,
          'status' => 'success',
          'check_vehicles' => $check_vehicles
        );

      return response()->json($data, $data['code']);
    }


    public function store(Request $request){

         if(is_array($request->all())){
          //especificacion de tipado y campos requeridos 
          $rules = [
            'vehicle_id' => 'required|exists:vehicles,id',                    
            'category' => 'required|in:interior,bodywork,electric,transmission,motor',
            'comment' => 'required|string',
            'path' => 'File|required',                    
          ];
                
            try{
              //validacion de tipado y campos requeridos 
              $validator = \Validator::make($request->all(), $rules);
    
              if($validator->fails()){
                //existio un error en los campos enviados 
                $data = array(
                  'status' => 'error',
                  'code'   => '200',
                  'errors'  => $validator->errors()->all()
                );
              }else{     
                
                $nombre_directorio = 'check';
                $directorio = storage_path() . '/app/' . $nombre_directorio;
                if (!file_exists($directorio)) {
                    mkdir($directorio, 0777, true);
                }
 
                $check = new Check_vehicle();
                $check->vehicle_id = $request->vehicle_id;
                $check->category = $request->category;
                $check->comment = $request->comment;

                // Verifica que la imagen esté dentro de la solicitud para almacenarla
                $image = $request->file('path');
 
                if( is_object( $image ) && !empty( $image )){

 
                    $nombre = \ImageHelper::upload_vehicleImage($image, $nombre_directorio, $check->vehicle_id,10);
 
                    // Verifica que el proceso de guardado de la imágen se completara de manera satisfactoria
                    if($nombre){
                        $check->path = $nombre;
                    } else {
                        $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'message' => 'La imagen no se ha podido guardar correctamente.'
                        );

                        return response()->json($data, $data['code']);
                    }
                }

                $check->save();                  
    
                $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'comment' => $check->comment,
                    'image' => $check->path,
                    'message' => 'check registrado exitosamente'
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
              'message'  => "Complete todos los campos"
          );
        }
          
        return response()->json($data, $data['code']);
    }
  
    public function getImage($filename){
      header('Access-Control-Allow-Origin: *');
        $file = '';
        try{
            $file = Storage::disk('check')->get($filename);
        }catch( \Exception $e ){
            $file = Storage::disk('check')->get('principal.png');
        }
        return new Response($file, 200);
    }

    public function checkByVehicle($id){
      
      //$vehicle = Vehicle::where('id', $id)->get();

      $vehicles = Vehicle::where('id', $id)->withTrashed()->get();

       


      if($vehicles->isEmpty()){

        $data = array(
          'status' => 'error',
          'code'   => '200',
          'message'  => 'el vin no existe'
        );      
      }
      else{

        $interior = Check_vehicle::where('vehicle_id',$id)->where('category','interior')->get();
        $bodywork = Check_vehicle::where('vehicle_id',$id)->where('category','bodywork')->get();
        $electric = Check_vehicle::where('vehicle_id',$id)->where('category','electric')->get();
        $transmission = Check_vehicle::where('vehicle_id',$id)->where('category','transmission')->get();
        $motor = Check_vehicle::where('vehicle_id',$id)->where('category','motor')->get();

       $info= array(
          'interior' =>$interior,
          'bodywork' =>$bodywork,
          'electric' =>$electric,
          'transmission' =>$transmission,
          'motor' =>$motor
       );

        $data = array(
          'status' => 'success',
          'code'   => '200',
          'vehicle'  => $vehicles,
          'data' => $info
        );
      }

      return response()->json($data, $data['code']);

    }

    public function searchVehicleByVin($vin){
      
      $vehicles = Vehicle::where('vin', $vin)->withTrashed()->get();
      if($vehicles->isEmpty()){

        $vehicles_of_valuations = Sell_your_car::where('vin', $vin)->first();
        $carbon = new \Carbon\Carbon(); 

        if( !is_object($vehicles_of_valuations)){
          //No existe en vehicles ni en veluaciones
          $vehicle = new Vehicle;
          $vehicle->name = "No tiene";
          $vehicle->description = "No tiene";
          $vehicle->vin = $vin;
          $vehicle->location =  NULL;
          $vehicle->yearModel = 2023;
          $vehicle->purchaseDate =$carbon->now();
          $vehicle->price = 0;
          $vehicle->priceList = 0;
          $vehicle->salePrice = 0;
          $vehicle->type = "pre_owned";
          $vehicle->carline = "No Tiene";
          $vehicle->cylinders = 1;
          $vehicle->colorInt = "negro";
          $vehicle->colorExt = "blanco";
          $vehicle->status = "inactive";
          $vehicle->plates = NULL;
          $vehicle->transmission = 'automatico';
          $vehicle->inventoryDays = 0;
          $vehicle->km = 0;
          $vehicle->numKeys = 1;
          $vehicle->studs = "no";
          $vehicle->spareTire = "no";
          $vehicle->hydraulicJack = "no";
          $vehicle->extinguiser = "no";
          $vehicle->reflectives = "no";
          $vehicle->handbook = "no";
          $vehicle->insurancePolicy = "no";
          $vehicle->powerCables = "no"; 
          $vehicle->promotion = NULL;
          $vehicle->priceOffer = NULL;
          $vehicle->carmodel_id = 1;
          $vehicle->vehiclebody_id = 12;
          $vehicle->branch_id =  8;
          $vehicle->client_id = 1;  
          $vehicle->deleted_at = $carbon->now();
          $vehicle->save();

          $vehicless = Vehicle::where('vin', $vin)->withTrashed()->get();


          $data = array(
            'status' => 'success',
            'code'   => '200',
            'vehicle' => $vehicless
          );
        }
        else{

          $vehicles_of_valuations->load('brand');
          $vehicles_of_valuations->load('carmodel');
          //realizar el insert en la tabla de vehicles 
          // marca  : $vehicles_of_valuations->brand->id
          // modelo : $vehicles_of_valuations->carmodel->id
          // vin    : $vin
          
          $vehicle = new Vehicle;
          $vehicle->name = "No tiene";
          $vehicle->description = "No tiene";
          $vehicle->vin = $vin;
          $vehicle->location =  NULL;
          $vehicle->yearModel = $vehicles_of_valuations->year;
          $vehicle->purchaseDate =$carbon->now();
          $vehicle->price = 0;
          $vehicle->priceList = 0;
          $vehicle->salePrice = 0;
          $vehicle->type = "pre_owned";
          $vehicle->carline = $vehicles_of_valuations->version;
          $vehicle->cylinders = 1;
          $vehicle->colorInt = "negro";
          $vehicle->colorExt = "blanco";
          $vehicle->status = "inactive";
          $vehicle->plates = NULL;
          $vehicle->transmission = 'automatico';
          $vehicle->inventoryDays = 0;
          $vehicle->km = $vehicles_of_valuations->km;
          $vehicle->numKeys = 1;
          $vehicle->studs = "no";
          $vehicle->spareTire = "no";
          $vehicle->hydraulicJack = "no";
          $vehicle->extinguiser = "no";
          $vehicle->reflectives = "no";
          $vehicle->handbook = "no";
          $vehicle->insurancePolicy = "no";
          $vehicle->powerCables = "no"; 
          $vehicle->promotion = NULL;
          $vehicle->priceOffer = NULL;
          $vehicle->carmodel_id = $vehicles_of_valuations->carmodel->id;
          $vehicle->vehiclebody_id = 12;
          $vehicle->branch_id =  8;
          $vehicle->client_id = 1;  
          $vehicle->deleted_at = $carbon->now();
          $vehicle->save();

          $vehicless = Vehicle::where('vin', $vin)->withTrashed()->get();

          
          $data = array(
            'status' => 'success',
            'code'   => '200',
            'vehicle'  => $vehicless
          );
        }

      }
      else{
        $data = array(
          'status' => 'success',
          'code'   => '200',
          'vehicle'  => $vehicles
        );
      }

      return response()->json($data, $data['code']);
    }

    public function getVehiclesReviewed(){
      
     /* $vehicles = DB::select(" select vehicles.id, vehicles.name, vehicles.vin, vehicles.yearModel  from vehicles
      inner join check_vehicles 
      on check_vehicles.vehicle_id= vehicles.id 
      group by vehicles.id ");  */

      $vehicles = Vehicle::select('vehicles.id', 'vehicles.name', 'vehicles.vin', 'vehicles.yearModel')
      ->has('checks')
      ->withTrashed()
      ->get();

      $data = array(
        'status' => 'success',
        'code'   => '200',
        'vehicle'  => $vehicles
      );

      return response()->json($data, $data['code']);
    }

    public function ReportCheck($id){

      $vehicles = Vehicle::select('vehicles.id', 'vehicles.name', 'vehicles.vin', 'vehicles.yearModel')
                          ->where('id',$id)
                          ->has('checks')
                          ->withTrashed()
                          ->get();
 
      $vehicles->load('checks');

      $report = PDF::loadView('check.report' ,compact(['vehicles']));
      return ($report->download('Reporte.pdf'));
      
    }
    


    public function downloadFolder($vin){
        $folderPath = storage_path('app/check/'.$vin.''); // Ruta de la carpeta que deseas descargar

        if (File::exists($folderPath)) {
            $zipFileName = ''.$vin.'.zip';
            $zipFilePath = storage_path('app/public/' . $zipFileName);

            $zip = new ZipArchive();
            if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
                $files = new \RecursiveIteratorIterator(
                    new \RecursiveDirectoryIterator($folderPath),
                    \RecursiveIteratorIterator::LEAVES_ONLY
                );

                foreach ($files as $name => $file) {
                    if (!$file->isDir()) {
                        $filePath = $file->getRealPath();
                        $relativePath = substr($filePath, strlen($folderPath) + 1);
                        $zip->addFile($filePath, $relativePath);
                    }
                }

                $zip->close();

                return response()->download($zipFilePath)->deleteFileAfterSend();
            } else {

              $data = array(
                'status' => 'error',
                'code'   => '200',
                'message'  => 'No se pudo crear el archivo ZIP'
              );
              return response()->json($data, $data['code']);
            }
        }else {
          $data = array(
            'status' => 'error',
            'code'   => '200',
            'message'  => 'el vehiculo no existe'
          );
          return response()->json($data, $data['code']);

        }
    }
}
