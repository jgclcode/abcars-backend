<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Models\Brand;

class BrandController extends Controller
{

  public function index(Request $request){
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);
 
      $brand = Brand::paginate( 10 );
      $data = array(
        'code' => 200,
        'status' => 'success',
        'brands' => $brand
      );
 

      
    return response()->json($data, $data['code']);
  }

  public function store(Request $request){


    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);
    if($checkToken){
      if (!is_array($request->all())) {
        $data = array(
          'status' => 'error',
          'code'   => '200',
          'message'  => "request must be an array"
        );
      }

      $rules = [
        'name' => 'required|max:255|string',
        'description' => 'max:255|string',
        'location' => 'max:255|string',
        'contact' => 'max:255|string',
        'picture' => 'File'                   
      ];

      try{
        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails()){
          // Esta condición se ejecuta si la validación de uno o más campos es incorrecta
          $data = array(
            'status' => 'error',
            'code'   => '200',
            'errors'  => $validator->errors()->all()
          );
        }else{
          // Crear el brand
          $brand = new Brand();
          $brand->name = $request->name;
          $brand->description = is_null($request->description) ? "" : $request->description;                
          $brand->location = is_null($request->location) ? "" : $request->location  ;
          $brand->contact = is_null($request->contact) ? null : $request->contact  ;
          //////////////////////////////////////////// 
          // Verificar si existe la carpeta vehicles
          $nombre_directorio = 'brands';
          $directorio = storage_path() . '/app/' . $nombre_directorio;
          if (!file_exists($directorio)) {                
              mkdir($directorio, 0777, true);
          }
          // Fin verificar si existe la carpeta vehicles                                  
          $image = $request->file('picture');
          if( is_object( $image ) && !empty( $image )){
            $nombre = \ImageHelper::upload($image, 'brands');
            $brand->picture = $nombre;
          }                            
          ////////////////////////////////////////////
          $brand->save();                
          
          $data = array(
            'status' => 'success',
            'code'   => '200',
            'message' => 'La brand se ha creado correctamente'
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

    if ( is_array($request->all()) && $checkToken){

      $rules = [          
        'name' => 'required|max:255|string',
        'description' => 'max:255|string',
        'location' => 'max:255|string',
        'contact' => 'max:255|string',
        'picture' => 'File'  
      ];

      try {
        // Obtener usuario
        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails() ){
          // Esta condición se ejecuta si la validación de uno o más campos es incorrecta
          $data = array(
            'status' => 'error',
            'code'   => '200',
            'errors'  => $validator->errors()->all()
          );
        }else{            
          $pwd = hash('sha256', $request->password);
          $brand = Brand::find( $id );

          if( is_object($brand) && !empty($brand)){                
            $brand->name = $request->name;
            $brand->description = is_null($request->description) ? "" : $request->description;                
            $brand->location = is_null($request->location) ? "" : $request->location  ;
            $brand->contact = is_null($request->contact) ? null : $request->contact  ;        
            ////////////////////////////////////////////                           
            $image = $request->file('picture');
            if( is_object( $image ) && !empty( $image )){
                \ImageHelper::delete( 'brands', $brand->picture );
                $nombre = \ImageHelper::upload($image, 'brands');
                $brand->picture = $nombre;
            }                               
            ////////////////////////////////////////////
            $brand->save();       

            $data = array(
              'status' => 'success',
              'code'   => '200',
              'message' => 'Brand actualizada correctamente'                
            );
          }else{
            $data = array(
              'status' => 'error',
              'code'   => '200',
              'message' => 'Brand no existe'
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

  public function destroy(Request $request,$id){
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);

    if( is_array($request->all()) && $checkToken ){
      // Inicio Try catch
      try{
        $brand = Brand::find( $id );

        if( is_object($brand) && !is_null($brand) ){

          try{
            $brand->delete();

            $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'Brand ha sido eliminada correctamente'
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
            'message' => 'El id del la Brand no existe'
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

  // Get brand image
  public function getImageBrand($filename) {
    header('Access-Control-Allow-Origin: *');
    $file = '';

    try {
      $file = Storage::disk('brands')->get($filename);
    } catch( \Exception $e ){
      $file = Storage::disk('brands')->get('abcars.svg');
    }

    return new Response($file, 200);
  }

  // Get all Brands
  public function allBrands() {
    $brands = Brand::all();
    $data = array(
      'code' => 200,
      'status' => 'success',
      'brands' => $brands
    );
      
    return response()->json($data, $data['code']);
  }

  public function brandByName( String $name ,Request $request){    
    // Comprobar si el usuario esta identificado
    $token = $request->header('Authorization');
    $jwtAuth = new \App\Helpers\JwtAuth();
    $checkToken = $jwtAuth->checkToken($token);
    $brand = Brand::where( 'name', $name )->first();
    
      $data = array(
        "status"      => "success",
        "code"        => 200,
        "brand" => $brand
      );
 
    return response()->json($data, $data['code']);

  }

  public function getBrandsWithTotal( int $total = 10 ){
    $brand = Brand::paginate( $total );
    $data = array(
      'code' => 200,
      'status' => 'success',
      'brands' => $brand
      );
      
    return response()->json($data, $data['code']);
  }

  // Get brand by id 
  public function getBrandById(  int $brand_id ,Request $request){
 
      $brand = Brand::find( $brand_id );
      $data = array(
        'code' => 200,
        'status' => 'success',
        'brand' => $brand
      );
 

    return response()->json($data, $data['code']);
  }

  public function search_brand(String $word = '', int $cantidad)
  {
    // dd($word);
    // Word
    if ($word === 'a') {
      $word_condition = "brands.name LIKE NOT NULL";
    }else{
      $word_condition = "brands.name LIKE '%$word%' ";
    }
    $brands = Brand::select('brands.*')
          ->whereRaw(
            "
              (
                $word_condition
              )
            "
          )
          ->paginate($cantidad);
    $data = array(
      'code' => 200,
      'status' => 'success',
      'brands' => $brands
    );

    return response()->json($data, $data['code']);
    
  }
}
