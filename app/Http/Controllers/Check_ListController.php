<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Check_List;
use App\Models\Foreing_review;
use App\Models\Interior_review;
use App\Models\Mechanical_electronic;
use App\Models\Certification;
use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Sell_your_car;
use App\Models\Spare_part;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\User;
use App\Models\Technician;
use App\Models\Vehicle;
use App\Models\Brand;
use App\Models\Carmodel;
use App\Models\Client;
use Illuminate\Support\Facades\Storage;
// use App\Mode
class Check_ListController extends Controller
{
    public function index(){       
      $Check_List = Check_List::latest()->paginate( 45 );
      $data = array(
        'code' => 200,
        'status' => 'success',
        'Check_List' => $Check_List
      );
  
      return response()->json($data, $data['code']);
    }
  
    public function store(Request $request){
      //verificacion de datos que este lleno 
      if(is_array($request->all()) ) {
        //especificacion de tipado y campos requeridos 
        $rules = [
          'distributor'=> 'string',   
          'valuation_date'=> 'date',   
          // 'warranty_manual'=> 'in:yes,no',     
          // 'direct_purchase'=> 'in:yes,no',    
          // 'take_into_account'=> 'in:yes,no',   
          // 'valid_warranty'=> 'in:yes,no',     
          'color'=> 'string',    
          'cilindres'=> 'string',    
          'engine_type'=> 'numeric',    
          // 'plates'=> 'string',   
          // 'req1'=> 'in:a1,a2,a3,a4',     
          /* 'req2'=> 'in:a1,a2,a3,a4',     
          'req3'=> 'in:a1,a2,a3,a4',     
          'req4 '=> 'in:a1,a2,a3,a4',    
          'req5'=> 'in:a1,a2,a3,a4',     
          'req6'=> 'in:a1,a2,a3,a4',     
          'req7'=> 'in:a1,a2,a3,a4',     
          'req8'=> 'in:a1,a2,a3,a4',     
          'req9'=> 'in:a1,a2,a3,a4',     
          'req10'=> 'in:a1,a2,a3,a4',     
          'req11'=> 'in:a1,a2,a3,a4',     
          'req12'=> 'in:a1,a2,a3,a4',     
          'req13 '=> 'in:a1,a2,a3,a4',    
          'req14'=> 'in:a1,a2,a3,a4',     
          'req15'=> 'in:a1,a2,a3,a4',     
          'req16'=> 'in:a1,a2,a3,a4',     
          'req17'=> 'in:a1,a2,a3,a4',     
          'req18'=> 'in:a1,a2,a3,a4',     
          'req19'=> 'in:a1,a2,a3,a4',     
          'req20 '=> 'in:a1,a2,a3,a4',    
          'req21'=> 'in:a1,a2,a3,a4',     
          'req22'=> 'in:a1,a2,a3,a4', */     
          /* 'iq1'=> 'in:a1,a2,a3,a4',     
          'iq2'=> 'in:a1,a2,a3,a4',     
          'iq3 '=> 'in:a1,a2,a3,a4',    
          'iq4'=> 'in:a1,a2,a3,a4',     
          'iq5'=> 'in:a1,a2,a3,a4',     
          'iq6'=> 'in:a1,a2,a3,a4',     
          'iq7'=> 'in:a1,a2,a3,a4',     
          'iq8'=> 'in:a1,a2,a3,a4',     
          'iq9'=> 'in:a1,a2,a3,a4',     
          'iq10'=> 'in:a1,a2,a3,a4',     
          'iq11'=> 'in:a1,a2,a3,a4',     
          'iq12'=> 'in:a1,a2,a3,a4',     
          'iq13'=> 'in:a1,a2,a3,a4',     
          'iq14 '=> 'in:a1,a2,a3,a4',    
          'iq15'=> 'in:a1,a2,a3,a4',     
          'iq16'=> 'in:a1,a2,a3,a4',     
          'iq17'=> 'in:a1,a2,a3,a4', */     
          /* 'meq1'=> 'in:a1,a2,a3,a4',     
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
          'meq51'=> 'in:a1,a2,a3,a4',  */    
          /* 'cvq1'=> 'in:a1,a2,a3,a4',     
          'cvq2'=> 'in:a1,a2,a3,a4',     
          'cvq3'=> 'in:a1,a2,a3,a4',     
          'cvq4'=> 'in:a1,a2,a3,a4',     
          'cvq5'=> 'in:a1,a2,a3,a4',     
          'cvq6'=> 'in:a1,a2,a3,a4',     
          'cvq7'=> 'in:a1,a2,a3,a4',     
          'cvq8'=> 'in:a1,a2,a3,a4',     
          'cvq9'=> 'in:a1,a2,a3,a4',     
          'cvq11'=> 'in:a1,a2,a3,a4',     
          'cvq12'=> 'in:a1,a2,a3,a4',  */    
          'take'=> 'numeric',    
          'sale'=> 'numeric',    
          'take_intelimotors'=> 'numeric',    
          'sale_intelimotors'=> 'numeric',    
          'workforce'=> 'numeric',    
          'spare_parts'=> 'numeric',    
          'hyp'=> 'numeric',    
          'total'=> 'numeric',    
          'take_value'=> 'numeric',    
          'final_offer'=> 'numeric',    
          // 'comments'=> 'string',    
          'name_technical'=> 'string',    
          'firm_technical'=> 'string',    
          'name_manager'=> 'string',    
          'firm_manager'=> 'string',    
          'name_appraiser'=> 'string',    
          'firm_appraiser'=> 'string',    
          // 'status' => 'in:reviewed,quoted,bought,rejected,readyForSale',
          'technician_id' => 'exists:users,id',
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
            // Crear el Check_List
            $Check_List = new Check_List();
            $Check_List->distributor = $request-> distributor;  
            $Check_List->valuation_date = $request-> valuation_date;  
            /* $Check_List->warranty_manual = $request-> warranty_manual;   
            $Check_List->direct_purchase  = $request-> direct_purchase;  
            $Check_List->take_into_account= $request-> take_into_account;    
            $Check_List->valid_warranty = $request-> valid_warranty; */   
            $Check_List->color = $request-> color;   
            $Check_List->cilindres = $request-> cilindres;   
            $Check_List->engine_type = $request-> engine_type;   
            $Check_List->plates= $request-> plates;   
            $Check_List->origin_country = $request->origin_country;   
            $Check_List->transmission = $request->transmission;
            $Check_List->engine_suction = $request->engine_suction;
            $Check_List->start_stop = $request->start_stop;
            // $Check_List->req1 = $request-> req1;    
            /* $Check_List->req2 = $request-> req2;     
            $Check_List->req3 = $request-> req3;      
            $Check_List->req4 = $request-> req4;     
            $Check_List->req5 = $request-> req5;     
            $Check_List->req6 = $request-> req6;     
            $Check_List->req7 = $request-> req7;     
            $Check_List->req8 = $request-> req8;     
            $Check_List->req9 = $request-> req9;     
            $Check_List->req10 = $request-> req10;     
            $Check_List->req11 = $request-> req11;     
            $Check_List->req12 = $request-> req12;     
            $Check_List->req13 = $request-> req13;      
            $Check_List->req14 = $request-> req14;      
            $Check_List->req15 = $request-> req15;     
            $Check_List->req16 = $request-> req16;      
            $Check_List->req17 = $request-> req17;      
            $Check_List->req18 = $request-> req18;     
            $Check_List->req19 = $request-> req19;      
            $Check_List->req20 = $request-> req20;     
            $Check_List->req21 = $request-> req21;      
            $Check_List->req22 = $request-> req22; */      
            /* $Check_List->iq1 = $request-> iq1;   
            $Check_List->iq2 = $request-> iq2;   
            $Check_List->iq3 = $request-> iq3;   
            $Check_List->iq4 = $request-> iq4;   
            $Check_List->iq5 = $request-> iq5;   
            $Check_List->iq6 = $request-> iq6;   
            $Check_List->iq7 = $request-> iq7;   
            $Check_List->iq8 = $request-> iq8;   
            $Check_List->iq9 = $request-> iq9;   
            $Check_List->iq10 = $request-> iq10;    
            $Check_List->iq11 = $request-> iq11;    
            $Check_List->iq12 = $request-> iq12;    
            $Check_List->iq13 = $request-> iq13;    
            $Check_List->iq14 = $request-> iq14;    
            $Check_List->iq15 = $request-> iq15;    
            $Check_List->iq16 = $request-> iq16;    
            $Check_List->iq17 = $request-> iq17; */    
            /* $Check_List->meq1 = $request-> meq1;    
            $Check_List->meq2 = $request-> meq2;   
            $Check_List->meq3 = $request-> meq3;    
            $Check_List->meq4 = $request-> meq4;     
            $Check_List->meq5 = $request-> meq5;    
            $Check_List->meq6 = $request-> meq6;    
            $Check_List->meq7 = $request-> meq7;    
            $Check_List->meq8 = $request-> meq8;    
            $Check_List->meq9 = $request-> meq9;   
            $Check_List->meq10 = $request-> meq10;    
            $Check_List->meq11 = $request-> meq11;    
            $Check_List->meq12 = $request-> meq12;   
            $Check_List->meq13 = $request-> meq13;    
            $Check_List->meq14 = $request-> meq14;     
            $Check_List->meq15 = $request-> meq15;     
            $Check_List->meq16 = $request-> meq16;     
            $Check_List->meq17 = $request-> meq17;     
            $Check_List->meq18 = $request-> meq18;     
            $Check_List->meq19 = $request-> meq19;    
            $Check_List->meq20 = $request-> meq20;    
            $Check_List->meq21 = $request-> meq21;    
            $Check_List->meq22 = $request-> meq22;   
            $Check_List->meq23 = $request-> meq23;  
            $Check_List->meq24 = $request-> meq24; 
            $Check_List->meq25 = $request-> meq25; 
            $Check_List->meq26 = $request-> meq26; 
            $Check_List->meq27 = $request-> meq27; 
            $Check_List->meq28 = $request-> meq28; 
            $Check_List->meq29 = $request-> meq29; 
            $Check_List->meq30 = $request-> meq30; 
            $Check_List->meq31 = $request-> meq31; 
            $Check_List->meq32 = $request-> meq32; 
            $Check_List->meq33 = $request-> meq33; 
            $Check_List->meq34 = $request-> meq34;  
            $Check_List->meq35 = $request-> meq35;  
            $Check_List->meq36 = $request-> meq36;  
            $Check_List->meq37 = $request-> meq37;  */ 
            //nuevos campos break
            /* $Check_List->breakedd = $request->breakedd; 
            $Check_List->breakeid = $request->breakeid; 
            $Check_List->breakeit = $request->breakeit; 
            $Check_List->breakedt = $request->breakedt;
            $Check_List->meq38 = $request-> meq38;  
            $Check_List->meq39 = $request-> meq39;  
            $Check_List->meq40 = $request-> meq40; */
            //nuevos campos   depth
            /* $Check_List->depthdd = $request->depthdd; 
            $Check_List->depthid = $request->depthid; 
            $Check_List->depthit = $request->depthit; 
            $Check_List->depthdt = $request->depthdt;
            $Check_List->meq41 = $request-> meq41;  
            $Check_List->meq42 = $request-> meq42;  
            $Check_List->meq43 = $request-> meq43;  
            $Check_List->meq44 = $request-> meq44; 
            $Check_List->meq45 = $request-> meq45; 
            $Check_List->meq46 = $request-> meq46; 
            $Check_List->meq47 = $request-> meq47; 
            $Check_List->meq48 = $request-> meq48; 
            $Check_List->meq49 = $request-> meq49; 
            $Check_List->meq50 = $request-> meq50; 
            $Check_List->meq51 = $request-> meq51; */ 
            /* $Check_List->cvq1 = $request-> cvq1;  */
            /* $Check_List->cvq2 = $request-> cvq2;  */
            /* $Check_List->cvq3 = $request-> cvq3;  */
            /* $Check_List->cvq4 = $request-> cvq4;    */  
            /* $Check_List->cvq5 = $request-> cvq5;  */
            /* $Check_List->cvq6 = $request-> cvq6;  */
            /* $Check_List->cvq7 = $request-> cvq7;  */
            /* $Check_List->cvq8 = $request-> cvq8;  */
            /* $Check_List->cvq9 = $request-> cvq9;  */
            /* $Check_List->cvq11 = $request-> cvq11;  *///cambiar a 10
            /* $Check_List->cvq12 = $request-> cvq12;  */
            $Check_List->take = $request-> take; 
            $Check_List->sale = $request-> sale; 
            $Check_List->take_intelimotors = $request-> take_intelimotors; 
            $Check_List->sale_intelimotors = $request-> sale_intelimotors; 
            $Check_List->workforce = $request-> workforce; 
            $Check_List->spare_parts = $request-> spare_parts; 
            $Check_List->hyp = $request-> hyp; 
            $Check_List->total = $request-> total; 
            $Check_List->take_value = $request-> take_value; 
            $Check_List->final_offer = $request-> final_offer; 
            $Check_List->comments = $request-> comments; 
            $Check_List->name_technical = $request-> name_technical; 
            $Check_List->firm_technical = $request-> firm_technical; 
            $Check_List->name_manager = $request-> name_manager; 
            $Check_List->firm_manager = $request-> firm_manager; 
            $Check_List->name_appraiser = $request-> name_appraiser; 
            $Check_List->firm_appraiser = $request-> firm_appraiser; 
            // $Check_List->status = $request->status;
            $Check_List->technician_id = $request->technician_id;
            $Check_List->sell_your_car_id = $request-> sell_your_car_id; 
            $Check_List->save();  
  
            $data = array(
              'status' => 'success',
              'code'   => '200',
              'message' => 'Check_List creado exitosamente',
              'Check_List' => $Check_List
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
          'distributor'=> 'string',   
          'valuation_date'=> 'string',   
          /* 'warranty_manual'=> 'in:yes,no',     
          'direct_purchase'=> 'in:yes,no',    
          'take_into_account'=> 'in:yes,no',   
          'valid_warranty'=> 'in:yes,no', */     
          'color'=> 'string',    
          'cilindres'=> 'string',    
          'engine_type'=> 'numeric',
          // 'plates'=> 'string',   
          // 'req1'=> 'in:a1,a2,a3,a4',     
          // 'req2'=> 'in:a1,a2,a3,a4',     
          // 'req3'=> 'in:a1,a2,a3,a4',     
          // 'req4 '=> 'in:a1,a2,a3,a4',    
          // 'req5'=> 'in:a1,a2,a3,a4',     
          // 'req6'=> 'in:a1,a2,a3,a4',     
          // 'req7'=> 'in:a1,a2,a3,a4',     
          // 'req8'=> 'in:a1,a2,a3,a4',     
          // 'req9'=> 'in:a1,a2,a3,a4',     
          // 'req10'=> 'in:a1,a2,a3,a4',     
          // 'req11'=> 'in:a1,a2,a3,a4',     
          // 'req12'=> 'in:a1,a2,a3,a4',     
          // 'req13 '=> 'in:a1,a2,a3,a4',    
          // 'req14'=> 'in:a1,a2,a3,a4',     
          // 'req15'=> 'in:a1,a2,a3,a4',     
          // 'req16'=> 'in:a1,a2,a3,a4',     
          // 'req17'=> 'in:a1,a2,a3,a4',     
          // 'req18'=> 'in:a1,a2,a3,a4',     
          // 'req19'=> 'in:a1,a2,a3,a4',     
          // 'req20 '=> 'in:a1,a2,a3,a4',    
          // 'req21'=> 'in:a1,a2,a3,a4',     
          // 'req22'=> 'in:a1,a2,a3,a4',     
          // 'iq1'=> 'in:a1,a2,a3,a4',     
          // 'iq2'=> 'in:a1,a2,a3,a4',     
          // 'iq3 '=> 'in:a1,a2,a3,a4',    
          // 'iq4'=> 'in:a1,a2,a3,a4',     
          // 'iq5'=> 'in:a1,a2,a3,a4',     
          // 'iq6'=> 'in:a1,a2,a3,a4',     
          // 'iq7'=> 'in:a1,a2,a3,a4',     
          // 'iq8'=> 'in:a1,a2,a3,a4',     
          // 'iq9'=> 'in:a1,a2,a3,a4',     
          // 'iq10'=> 'in:a1,a2,a3,a4',     
          // 'iq11'=> 'in:a1,a2,a3,a4',     
          // 'iq12'=> 'in:a1,a2,a3,a4',     
          // 'iq13'=> 'in:a1,a2,a3,a4',     
          // 'iq14 '=> 'in:a1,a2,a3,a4',    
          // 'iq15'=> 'in:a1,a2,a3,a4',     
          // 'iq16'=> 'in:a1,a2,a3,a4',     
          // 'iq17'=> 'in:a1,a2,a3,a4',     
          // 'meq1'=> 'in:a1,a2,a3,a4',     
          // 'meq2'=> 'in:a1,a2,a3,a4',     
          // 'meq3'=> 'in:a1,a2,a3,a4',     
          // 'meq4'=> 'in:a1,a2,a3,a4',     
          // 'meq5'=> 'in:a1,a2,a3,a4',     
          // 'meq6'=> 'in:a1,a2,a3,a4',     
          // 'meq7'=> 'in:a1,a2,a3,a4',     
          // 'meq8'=> 'in:a1,a2,a3,a4',     
          // 'meq9'=> 'in:a1,a2,a3,a4',     
          // 'meq10'=> 'in:a1,a2,a3,a4',     
          // 'meq11'=> 'in:a1,a2,a3,a4',     
          // 'meq12'=> 'in:a1,a2,a3,a4',     
          // 'meq13'=> 'in:a1,a2,a3,a4',     
          // 'meq14'=> 'in:a1,a2,a3,a4',     
          // 'meq15'=> 'in:a1,a2,a3,a4',     
          // 'meq16'=> 'in:a1,a2,a3,a4',     
          // 'meq17'=> 'in:a1,a2,a3,a4',     
          // 'meq18'=> 'in:a1,a2,a3,a4',     
          // 'meq19'=> 'in:a1,a2,a3,a4',     
          // 'meq20'=> 'in:a1,a2,a3,a4',     
          // 'meq21'=> 'in:a1,a2,a3,a4',     
          // 'meq22'=> 'in:a1,a2,a3,a4',     
          // 'meq23'=> 'in:a1,a2,a3,a4',     
          // 'meq24'=> 'in:a1,a2,a3,a4',     
          // 'meq25'=> 'in:a1,a2,a3,a4',     
          // 'meq26'=> 'in:a1,a2,a3,a4',     
          // 'meq27'=> 'in:a1,a2,a3,a4',     
          // 'meq28'=> 'in:a1,a2,a3,a4',     
          // 'meq29'=> 'in:a1,a2,a3,a4',     
          // 'meq30'=> 'in:a1,a2,a3,a4',     
          // 'meq31'=> 'in:a1,a2,a3,a4',     
          // 'meq32'=> 'in:a1,a2,a3,a4',     
          // 'meq33'=> 'in:a1,a2,a3,a4',     
          // 'meq34'=> 'in:a1,a2,a3,a4',     
          // 'meq35'=> 'in:a1,a2,a3,a4',     
          // 'meq36'=> 'in:a1,a2,a3,a4',     
          // 'meq37'=> 'in:a1,a2,a3,a4', 
          // //nuevos campos 
          // 'breakedd'=> 'numeric',  
          // 'breakeid'=> 'numeric',  
          // 'breakeit'=> 'numeric',  
          // 'breakedt'=> 'numeric',      
          // 'meq38'=> 'in:a1,a2,a3,a4',     
          // 'meq39'=> 'in:a1,a2,a3,a4',     
          // 'meq40'=> 'in:a1,a2,a3,a4',
          // //nuevos campos
          // 'depthdd'=> 'numeric', 
          // 'depthid'=> 'numeric', 
          // 'depthit'=> 'numeric', 
          // 'depthdt'=> 'numeric',      
          // 'meq41'=> 'in:a1,a2,a3,a4',     
          // 'meq42'=> 'in:a1,a2,a3,a4',     
          // 'meq43'=> 'in:a1,a2,a3,a4',     
          // 'meq44'=> 'in:a1,a2,a3,a4',     
          // 'meq45'=> 'in:a1,a2,a3,a4',     
          // 'meq46'=> 'in:a1,a2,a3,a4',     
          // 'meq47'=> 'in:a1,a2,a3,a4',     
          // 'meq48'=> 'in:a1,a2,a3,a4', 
          // 'meq49'=> 'in:a1,a2,a3,a4',     
          // 'meq50'=> 'in:a1,a2,a3,a4',     
          // 'meq51'=> 'in:a1,a2,a3,a4',     
          // 'cvq1'=> 'in:a1,a2,a3,a4',     
          // 'cvq2'=> 'in:a1,a2,a3,a4',     
          // 'cvq3'=> 'in:a1,a2,a3,a4',     
          // 'cvq4'=> 'in:a1,a2,a3,a4',     
          // 'cvq5'=> 'in:a1,a2,a3,a4',     
          // 'cvq6'=> 'in:a1,a2,a3,a4',     
          // 'cvq7'=> 'in:a1,a2,a3,a4',     
          // 'cvq8'=> 'in:a1,a2,a3,a4',     
          // 'cvq9'=> 'in:a1,a2,a3,a4',     
          // 'cvq11'=> 'in:a1,a2,a3,a4',     
          // 'cvq12'=> 'in:a1,a2,a3,a4',     
          'take'=> 'numeric',    
          'sale'=> 'numeric',    
          'take_intelimotors'=> 'numeric',    
          'sale_intelimotors'=> 'numeric',
          'workforce'=> 'numeric',    
          'spare_parts'=> 'numeric',    
          'hyp'=> 'numeric',    
          'total'=> 'numeric',    
          'take_value'=> 'numeric',    
          'final_offer'=> 'numeric',    
          // 'comments'=> 'string',    
          'name_technical'=> 'string',    
          'firm_technical'=> 'string',    
          'name_manager'=> 'string',    
          'firm_manager'=> 'string',    
          'name_appraiser'=> 'string',    
          'firm_appraiser'=> 'string',   
          // 'status' => 'in:reviewed,quoted,bought,rejected,readyForSale',
          'preparation' => 'in:yes,no',
          'sell_your_car_id' => 'required|exists:sell_your_cars,id'                        
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
            $Check_List = Check_List::find( $id );
  
            if( is_object($Check_List) && !empty($Check_List) ){                
              $Check_List->distributor = $request-> distributor;  
              $Check_List->valuation_date = $request-> valuation_date;  
              /* $Check_List->warranty_manual = $request-> warranty_manual;   
              $Check_List->direct_purchase  = $request-> direct_purchase;  
              $Check_List->take_into_account= $request-> take_into_account;    
              $Check_List->valid_warranty = $request-> valid_warranty; */   
              $Check_List->color = $request-> color;   
              $Check_List->cilindres = $request-> cilindres;   
              $Check_List->engine_type = $request-> engine_type;   
              $Check_List->plates= $request-> plates;   
              $Check_List->origin_country = $request->origin_country;   
              $Check_List->transmission = $request->transmission;
              $Check_List->engine_suction = $request->engine_suction;
              $Check_List->start_stop = $request->start_stop;
              // $Check_List->req1 = $request-> req1;    
              // $Check_List->req2 = $request-> req2;     
              // $Check_List->req3 = $request-> req3;      
              // $Check_List->req4 = $request-> req4;     
              // $Check_List->req5 = $request-> req5;     
              // $Check_List->req6 = $request-> req6;     
              // $Check_List->req7 = $request-> req7;     
              // $Check_List->req8 = $request-> req8;     
              // $Check_List->req9 = $request-> req9;     
              // $Check_List->req10 = $request-> req10;     
              // $Check_List->req11 = $request-> req11;     
              // $Check_List->req12 = $request-> req12;     
              // $Check_List->req13 = $request-> req13;      
              // $Check_List->req14 = $request-> req14;      
              // $Check_List->req15 = $request-> req15;     
              // $Check_List->req16 = $request-> req16;      
              // $Check_List->req17 = $request-> req17;      
              // $Check_List->req18 = $request-> req18;     
              // $Check_List->req19 = $request-> req19;      
              // $Check_List->req20 = $request-> req20;     
              // $Check_List->req21 = $request-> req21;      
              // $Check_List->req22 = $request-> req22;      
              // $Check_List->iq1 = $request-> iq1;   
              // $Check_List->iq2 = $request-> iq2;   
              // $Check_List->iq3 = $request-> iq3;   
              // $Check_List->iq4 = $request-> iq4;   
              // $Check_List->iq5 = $request-> iq5;   
              // $Check_List->iq6 = $request-> iq6;   
              // $Check_List->iq7 = $request-> iq7;   
              // $Check_List->iq8 = $request-> iq8;   
              // $Check_List->iq9 = $request-> iq9;   
              // $Check_List->iq10 = $request-> iq10;    
              // $Check_List->iq11 = $request-> iq11;    
              // $Check_List->iq12 = $request-> iq12;    
              // $Check_List->iq13 = $request-> iq13;    
              // $Check_List->iq14 = $request-> iq14;    
              // $Check_List->iq15 = $request-> iq15;    
              // $Check_List->iq16 = $request-> iq16;    
              // $Check_List->iq17 = $request-> iq17;    
              // $Check_List->meq1 = $request-> meq1;    
              // $Check_List->meq2 = $request-> meq2;   
              // $Check_List->meq3 = $request-> meq3;    
              // $Check_List->meq4 = $request-> meq4;     
              // $Check_List->meq5 = $request-> meq5;    
              // $Check_List->meq6 = $request-> meq6;    
              // $Check_List->meq7 = $request-> meq7;    
              // $Check_List->meq8 = $request-> meq8;    
              // $Check_List->meq9 = $request-> meq9;   
              // $Check_List->meq10 = $request-> meq10;    
              // $Check_List->meq11 = $request-> meq11;    
              // $Check_List->meq12 = $request-> meq12;   
              // $Check_List->meq13 = $request-> meq13;    
              // $Check_List->meq14 = $request-> meq14;     
              // $Check_List->meq15 = $request-> meq15;     
              // $Check_List->meq16 = $request-> meq16;     
              // $Check_List->meq17 = $request-> meq17;     
              // $Check_List->meq18 = $request-> meq18;     
              // $Check_List->meq19 = $request-> meq19;    
              // $Check_List->meq20 = $request-> meq20;    
              // $Check_List->meq21 = $request-> meq21;    
              // $Check_List->meq22 = $request-> meq22;   
              // $Check_List->meq23 = $request-> meq23;  
              // $Check_List->meq24 = $request-> meq24; 
              // $Check_List->meq25 = $request-> meq25; 
              // $Check_List->meq26 = $request-> meq26; 
              // $Check_List->meq27 = $request-> meq27; 
              // $Check_List->meq28 = $request-> meq28; 
              // $Check_List->meq29 = $request-> meq29; 
              // $Check_List->meq30 = $request-> meq30; 
              // $Check_List->meq31 = $request-> meq31; 
              // $Check_List->meq32 = $request-> meq32; 
              // $Check_List->meq33 = $request-> meq33; 
              // $Check_List->meq34 = $request-> meq34;  
              // $Check_List->meq35 = $request-> meq35;  
              // $Check_List->meq36 = $request-> meq36;  
              // $Check_List->meq37 = $request-> meq37;  
              // //nuevos campos break
              // $Check_List->breakedd = $request->breakedd; 
              // $Check_List->breakeid = $request->breakeid; 
              // $Check_List->breakeit = $request->breakeit; 
              // $Check_List->breakedt = $request->breakedt;
              // $Check_List->meq38 = $request-> meq38;  
              // $Check_List->meq39 = $request-> meq39;  
              // $Check_List->meq40 = $request-> meq40;
              // //nuevos campos   depth
              // $Check_List->depthdd = $request->depthdd; 
              // $Check_List->depthid = $request->depthid; 
              // $Check_List->depthit = $request->depthit; 
              // $Check_List->depthdt = $request->depthdt;
              // $Check_List->meq41 = $request-> meq41;  
              // $Check_List->meq42 = $request-> meq42;  
              // $Check_List->meq43 = $request-> meq43;  
              // $Check_List->meq44 = $request-> meq44; 
              // $Check_List->meq45 = $request-> meq45; 
              // $Check_List->meq46 = $request-> meq46; 
              // $Check_List->meq47 = $request-> meq47; 
              // $Check_List->meq48 = $request-> meq48; 
              // $Check_List->meq49 = $request-> meq49; 
              // $Check_List->meq50 = $request-> meq50; 
              // $Check_List->meq51 = $request-> meq51; 
              // $Check_List->cvq1 = $request-> cvq1; 
              // $Check_List->cvq2 = $request-> cvq2; 
              // $Check_List->cvq3 = $request-> cvq3; 
              // $Check_List->cvq4 = $request-> cvq4;     
              // $Check_List->cvq5 = $request-> cvq5; 
              // $Check_List->cvq6 = $request-> cvq6; 
              // $Check_List->cvq7 = $request-> cvq7; 
              // $Check_List->cvq8 = $request-> cvq8; 
              // $Check_List->cvq9 = $request-> cvq9; 
              // $Check_List->cvq11 = $request-> cvq11; //cambiar a 10
              // $Check_List->cvq12 = $request-> cvq12; 
              $Check_List->take = $request-> take; 
              $Check_List->sale = $request-> sale; 
              $Check_List->take_intelimotors = $request-> take_intelimotors; 
              $Check_List->sale_intelimotors = $request-> sale_intelimotors;
              $Check_List->workforce = $request-> workforce; 
              $Check_List->spare_parts = $request-> spare_parts; 
              $Check_List->hyp = $request-> hyp; 
              $Check_List->total = $request-> total; 
              $Check_List->take_value = $request-> take_value; 
              $Check_List->final_offer = $request-> final_offer; 
              $Check_List->comments = $request-> comments; 
              $Check_List->name_technical = $request-> name_technical; 
              $Check_List->firm_technical = $request-> firm_technical; 
              $Check_List->name_manager = $request-> name_manager; 
              $Check_List->firm_manager = $request-> firm_manager; 
              $Check_List->name_appraiser = $request-> name_appraiser; 
              $Check_List->firm_appraiser = $request-> firm_appraiser; 
              // $Check_List->status = $request->status;
              $Check_List->sell_your_car_id = $request-> sell_your_car_id; 
              $Check_List->save();  
  
                $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'message' => 'Check_List se ha actualizado correctamente'               
                );
            }else{
                $data = array(
                  'status' => 'error',
                  'code'   => '200',
                  'message' => 'id de Check_List no existe'
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
          $Check_List = Check_List::find( $id );
  
          if( is_object($Check_List) && !is_null($Check_List) ){
  
            try{
                $Check_List->delete();
  
                $data = array(
                  'status' => 'success',
                  'code'   => '200',
                  'message' => 'Check_List ha sido eliminado correctamente'
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
                'message' => 'El id del Check_List no existe'
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

    public function check_pdf($vin){
      //obtener vin
      $findbyvin = Sell_your_car::firstWhere('vin', $vin);
      $sparePart = Spare_part::where('sell_your_car_id', $findbyvin->id)->get();
      $findvin = $findbyvin->load('brand', 'carmodel', 'client_sale');
      if( is_object($findbyvin) && !is_null($findbyvin) ){

        // $vin_check_list = Check_List::find( $findbyvin ->id);
        $vin_check_list = Check_List::firstWhere('sell_your_car_id', $findbyvin ->id);
        // $revExt=Foreing_review::find($vin_check_list ->sell_your_car_id);
        $revExt=Foreing_review::firstWhere('sell_your_car_id', $vin_check_list ->sell_your_car_id);
        // $revInt=Interior_review::find($vin_check_list ->sell_your_car_id);
        $revInt=Interior_review::firstWhere('sell_your_car_id', $vin_check_list ->sell_your_car_id);
        // $mecElec=Mechanical_electronic::find($vin_check_list ->sell_your_car_id);
        $mecElec=Mechanical_electronic::firstWhere('sell_your_car_id', $vin_check_list ->sell_your_car_id);
        // $cert=Certification::find($vin_check_list ->sell_your_car_id);
        $cert=Certification::firstWhere('sell_your_car_id', $vin_check_list ->sell_your_car_id);
        //dd($cert);

        $sumGeneric = $sparePart->sum(function($spare) {
          return $spare->priceGeneric * $spare->amount;
        });

        $sumUsed = $sparePart->sum(function($spare) {
          return $spare->priceUsed * $spare->amount;
        });

        //-> user_id    propietario
        /* $valuador =User::firstWhere('id', $vin_check_list -> user_id);
        $technician = Technician::firstWhere('id', $vin_check_list ->technician_id); */
        $tecval = User::firstWhere('id', $vin_check_list->technician_id);
  //       $tecnico=User::firstWhere('id', $technician -> user_id);
 
        $report = PDF::loadView('check_list.check' ,compact('cert','mecElec','revInt','revExt','vin_check_list','findbyvin','tecval', 'findvin', 'sparePart', 'sumGeneric', 'sumUsed'));
        //return ($report->setPaper( 'A4' , 'Landscape')->download('CheclList.pdf'));
        // return ($report->download('CheckList.pdf'));
        return ($report->stream());
      }else{
          $data = array(
            'status' => 'error',
            'code'   => '404',
            'message' => 'El vin del sell your car no existe'
          );
          return response()->json($data, $data['code']); 
      }
    }
    
    public function check_pdf_customer($vin){
      //obtener vin
      $findbyvin = Sell_your_car::firstWhere('vin', $vin);
      $sparePart = Spare_part::where('sell_your_car_id', $findbyvin->id)->get();
      
      $findvin = $findbyvin->load('brand', 'carmodel', 'client_sale');
      if( is_object($findbyvin) && !is_null($findbyvin) ){

        
        $vin_check_list = Check_List::firstWhere('sell_your_car_id', $findbyvin ->id);
        
        $revExt=Foreing_review::firstWhere('sell_your_car_id', $vin_check_list ->sell_your_car_id);
        
        $revInt=Interior_review::firstWhere('sell_your_car_id', $vin_check_list ->sell_your_car_id);
        
        $mecElec=Mechanical_electronic::firstWhere('sell_your_car_id', $vin_check_list ->sell_your_car_id);
        
        $cert=Certification::firstWhere('sell_your_car_id', $vin_check_list ->sell_your_car_id);
        


        
        
        $tecval = User::firstWhere('id', $vin_check_list->technician_id);
  
 
        $report = PDF::loadView('check_list.check_customer' ,compact('cert','mecElec','revInt','revExt','vin_check_list','findbyvin','tecval', 'findvin', 'sparePart'));
        //return ($report->setPaper( 'A4' , 'Landscape')->download('CheclList.pdf'));
        // return ($report->download('CheckList.pdf'));
        return ($report->stream());
      }else{
          $data = array(
            'status' => 'error',
            'code'   => '404',
            'message' => 'El vin del sell your car no existe'
          );
          return response()->json($data, $data['code']); 
      }
    }

    public function getchecklist($id){ /* Era $vin */
      $id = Sell_your_car::firstWhere('id', $id); /* Era 'vin', $vin */
      $data_check_list = Check_List::firstWhere( 'sell_your_car_id', $id->id );
      if(is_object($data_check_list) && !is_null($data_check_list)){
        $data = array(
          'status' => 'success',
          'code' => '200',
          'DataChecklist' => $data_check_list->load('user', 'user_technician')
        );
      }else{
        $data = array(
          'status' => 'error',
          'code'   => '404',
          'message' => 'El id del Check_List no existe'
        );
      }

      return response()->json($data, $data['code']); 
    }

    public function getappraiser_technician(){
      $appraiserTechnicians = User::role('appraiser_technician')->get();
      if (is_object($appraiserTechnicians) && !is_null($appraiserTechnicians)) {
        $data = array(
          'status' => 'success',
          'code' => '200',
          'AppraiserTechnicians' => $appraiserTechnicians
        );
      }
      // return $appraiserTechnicians;
      return response()->json($data, $data['code']);
    }

    public function getchecklistall($id){

      $id = Sell_your_car::firstWhere('id', $id);
      $data_foreing_review = Foreing_review::firstWhere('sell_your_car_id', $id->id);
      $data_interior_review = Interior_review::firstWhere( 'sell_your_car_id', $id->id);
      $data_mechanical_electronic = Mechanical_electronic::firstWhere( 'sell_your_car_id', $id->id);
      $data_certificacion = Certification::firstWhere( 'sell_your_car_id', $id->id);
      $data_spare_part = Spare_part::firstWhere( 'sell_your_car_id', $id->id);

      if (is_object($data_foreing_review) && !is_null($data_foreing_review) && 
          is_object($data_interior_review) && !is_null($data_interior_review) && 
          is_object($data_mechanical_electronic) && !is_null($data_mechanical_electronic) && 
          is_object($data_certificacion) && !is_null($data_certificacion) &&
          is_object($data_spare_part) && !is_null($data_spare_part)
        ) {
        return true;
      } else {
        return false;
      }

    }

    public function qrgenerate($vin){      
      if($_SERVER['SERVER_NAME']=="abcars-backend.test"){
        
        QrCode::size(173)->merge('https://abcars.mx/assets/images/logo.svg', .3, true)->generate("http://abcars-backend.test/api/qrsale/".$vin.""  ,'../../public/qrcodes/'.$vin.'.svg');
    
      }else if($_SERVER['SERVER_NAME']=="127.0.0.1"){
        QrCode::size(173)->merge('https://abcars.mx/assets/images/logo.svg', .3, true)->generate("http://abcars-backend.test/api/qrsale/".$vin.""  ,'../public/qrcodes/'.$vin.'.svg');
      }else{       
        //QrCode::size(173)->merge('https://abcars.mx/assets/images/logo.svg', .3, true)->generate("https://abcars.mx/abcars-backend/api/qrsale/".$vin.""  ,'/home/abcars/public_html/abcars-backend/public/qrcodes/'.$vin.'.svg');
        QrCode::size(173)->merge('https://abcars.mx/assets/images/logo.svg', .3, true)->generate("http://abcars-backend.test/api/qrsale/".$vin.""  ,'../public/qrcodes/'.$vin.'.svg');

      }
      //$path = "https://abcars.mx/abcars-backend/public/qrcodes/" . $vin . ".svg";

      $path = "http://abcars-backend.test/qrcodes/" . $vin . ".svg";
      // echo $path;
      // die();
      $report = PDF::loadView('qr.qr' ,compact(['vin', 'path']));
      return ($report->download('.'.$vin.'.pdf'));

    }


    public function qrgenerateInvenrario($vin){   
      // if (!file_exists("../public/qrcodes/")) {                
      //   mkdir("qrcodes", 0777, true);
      // }   
      // if($_SERVER['SERVER_NAME']=="abcars-backend.test"){
        
      //  QrCode::size(173)->merge('https://abcars.mx/assets/images/logo.svg', .3, true)->generate("http://abcars-backend.test/api/qrsale/".$vin.""  ,'../../public/qrcodes/'.$vin.'.svg');
    
      // }else if($_SERVER['SERVER_NAME']=="127.0.0.1"){
      //   QrCode::size(173)->merge('https://abcars.mx/assets/images/logo.svg', .3, true)->generate("http://abcars-backend.test/api/qrsale/".$vin.""  ,'../public/qrcodes/'.$vin.'.svg');
      // }else{       
        //QrCode::size(173)->merge('https://abcars.mx/assets/images/logo.svg', .3, true)->generate("https://abcars.mx/abcars-backend/api/qrsale/".$vin.""  ,'/home/abcars/public_html/abcars-backend/public/qrcodes/'.$vin.'.svg');
         //$Cqr = QrCode::size(173)->generate("".$vin."",'.svg');
        $pathQr=   QrCode::size(500)->generate($vin);
        Storage::disk('QrImages')->put($vin.'.svg',$pathQr);
        $path = Storage::disk('QrImages')->path($vin.'.svg');



      // }
      //$path = "https://abcars.mx/abcars-backend/public/qrcodes/" . $vin . ".svg";
      // $path = "qrcodes/" . $vin . ".svg";
      // echo($path);
      // die();
      // $ht = PDF::loadHTML('<object data="'.$path.'" type="image/svg+xml">');
      $report =  PDF::loadView('qr.qr',compact(['vin','path']));
      return ($report->download('.'.$vin.'.pdf'));

    }
    
    public function quotationUpdate(Request $request, $id){
      // Comprobar si el usuario esta identificado
      $token = $request->header('Authorization');
      $jwtAuth = new \App\Helpers\JwtAuth();
      $checkToken = $jwtAuth->checkToken($token);

      if (is_array($request->all()) && $checkToken) {
        $rules = [
          'warranty_manual'=> 'in:yes,no',     
          'direct_purchase'=> 'in:yes,no',    
          'take_into_account'=> 'in:yes,no',   
          'valid_warranty'=> 'in:yes,no', 
          'direct_purchase_take_account'=> 'in:yes,no', 
          'seller' => 'string',
          'take'=> 'numeric',    
          'sale'=> 'numeric', 
          'take_intelimotors'=> 'numeric',    
          'sale_intelimotors'=> 'numeric',
          'workforce'=> 'numeric',    
          'spare_parts'=> 'numeric',    
          'hyp'=> 'numeric',    
          'total'=> 'numeric', 
          'take_value'=> 'numeric',    
          'final_offer'=> 'numeric',    
          // 'comments'=> 'string',    
          'name_technical'=> 'string',    
          'firm_technical'=> 'string',    
          'name_manager'=> 'string',    
          'firm_manager'=> 'string',    
          'name_appraiser'=> 'string',    
          'firm_appraiser'=> 'string',   
          // 'status' => 'in:reviewed,quoted,bought,rejected,readyForSale', 
          'sell_your_car_id' => 'required|exists:sell_your_cars,id'
        ];

        try {
          // Obtener package
          $validator = \Validator::make($request->all(), $rules);
          if($validator->fails()){
            // error en los datos ingresados
            $data = array(
              'status' => 'error',
              'code'   => '200',
              'errors'  => $validator->errors()->all()
            );
          }else{
            $Check_List = Check_List::find( $id );

            if (is_object($Check_List) && !empty($Check_List)) {
              $Check_List->warranty_manual = $request-> warranty_manual;   
              $Check_List->direct_purchase  = $request-> direct_purchase;  
              $Check_List->take_into_account= $request-> take_into_account;    
              $Check_List->valid_warranty = $request-> valid_warranty;
              $Check_List->direct_purchase_take_account = $request-> direct_purchase_take_account;
              $Check_List->seller = $request->seller;
              $Check_List->ownedPreowned = $request->ownedPreowned;
              $Check_List->take = $request-> take; 
              $Check_List->sale = $request-> sale; 
              $Check_List->take_intelimotors = $request-> take_intelimotors; 
              $Check_List->sale_intelimotors = $request-> sale_intelimotors;
              $Check_List->workforce = $request-> workforce; 
              $Check_List->spare_parts = $request-> spare_parts; 
              $Check_List->hyp = $request-> hyp; 
              $Check_List->total = $request-> total; 
              $Check_List->take_value = $request-> take_value; 
              $Check_List->final_offer = $request-> final_offer; 
              $Check_List->new_offer = $request-> new_offer; 
              $Check_List->comments = $request-> comments; 
              $Check_List->name_technical = $request-> name_technical; 
              $Check_List->firm_technical = $request-> firm_technical; 
              $Check_List->name_manager = $request-> name_manager; 
              $Check_List->firm_manager = $request-> firm_manager; 
              $Check_List->name_appraiser = $request-> name_appraiser; 
              $Check_List->firm_appraiser = $request-> firm_appraiser; 
              // $Check_List->status = $request->status;
              $Check_List->sell_your_car_id = $request-> sell_your_car_id; 
              $Check_List->save();

              $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'Check_List se ha actualizado correctamente'               
              );
            }else {
              $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'id de Check_List no existe'
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

    public function updatestatus(Request $request, $id){
      // Comprobar si el usuario esta identificado
      $token = $request->header('Authorization');
      $jwtAuth = new \App\Helpers\JwtAuth();
      $checkToken = $jwtAuth->checkToken($token);

      if (is_array($request->all()) && $checkToken) {
        $rules = [
          // 'status' => 'in:reviewed,quoted,bought,rejected,readyForSale',
          'preparation' => 'in:yes,no',
          'user_id' => 'exists:users,id'
        ];

        try {
          $validator = \Validator::make($request->all(), $rules);
          if ($validator->fails()) {
            // error en los datos ingresados
            $data = array(
              'status' => 'error',
              'code'   => '200',
              'errors'  => $validator->errors()->all()
            );
          } else {
            $Check_List = Check_List::find( $id );

            if (is_object($Check_List) && !empty($Check_List)) {
              // $Check_List->status = $request->status;
              $Check_List->preparation = $request->preparation;
              $Check_List->user_id = $request->user_id;
              $Check_List->save();
              $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'Check_List se ha actualizado correctamente',               
                'checklist' => $Check_List
              );
            } else {
              $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'id de Check_List no existe'
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
      } else{
        $data = array(
          'status' => 'error',
          'code'   => '200',
          'message' => 'El usuario no está identificado'
        );
      }

      return response()->json($data, $data['code']); 
    }

    public function updatebought(Request $request, $id){
      // Comprobar si el usuario esta identificado
      $token = $request->header('Authorization');
      $jwtAuth = new \App\Helpers\JwtAuth();
      $checkToken = $jwtAuth->checkToken($token);

      if (is_array($request->all()) && $checkToken) {
        $rules = [
          // 'status' => 'in:reviewed,quoted,bought,rejected,readyForSale',
          'preparation' => 'in:yes,no',
          'user_id' => 'exists:users,id'
        ];

        try {
          $validator = \Validator::make($request->all(), $rules);
          if ($validator->fails()) {
            $data = array(
              'status' => 'error',
              'code'   => '200',
              'errors'  => $validator->errors()->all()
            );
          } else {
            $Check_List = Check_List::find( $id );

            if (is_object($Check_List) && !empty($Check_List)) {
              // $Check_List->status = $request->status;
              $Check_List->preparation = $request->preparation;
              $Check_List->user_id = $request->user_id;
              $Check_List->save();
              $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'Check_List se ha actualizado correctamente',               
                'checklist' => $Check_List
              );
            } else {
              $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'id de Check_List no existe'
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
      } else{
        $data = array(
          'status' => 'error',
          'code'   => '200',
          'message' => 'El usuario no está identificado'
        );
      }

      return response()->json($data, $data['code']); 
    }

    public function updatestatusforms(Request $request, $id){
      $token = $request->header('Authorization');
      $jwtAuth = new \App\Helpers\JwtAuth();
      $checkToken = $jwtAuth->checkToken($token);

      if (is_array($request->all()) && $checkToken) {
        $rules = [
          // 'status' => 'in:reviewed,quoted,bought,rejected,readyForSale',
          'warranty_manual'=> 'in:yes,no',     
          'direct_purchase'=> 'in:yes,no',    
          'take_into_account'=> 'in:yes,no',   
          'valid_warranty'=> 'in:yes,no',
          'direct_purchase_take_account'=> 'in:yes,no'
        ];

        try {
          $validator = \Validator::make($request->all(), $rules);
          if ($validator->fails()) {
            $data = array(
              'status' => 'error',
              'code'   => '200',
              'errors'  => $validator->errors()->all()
            );
          } else {
            $Check_List = Check_List::find( $id );

            if (is_object($Check_List) && !empty($Check_List)) {
              // $Check_List->status = $request->status;
              $Check_List->warranty_manual = $request-> warranty_manual;   
              $Check_List->direct_purchase  = $request-> direct_purchase;  
              $Check_List->take_into_account= $request-> take_into_account;    
              $Check_List->valid_warranty = $request-> valid_warranty;
              $Check_List->direct_purchase_take_account = $request-> direct_purchase_take_account;
              $Check_List->save();
              $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'Check_List se ha actualizado correctamente',               
                'checklist' => $Check_List
              );
            } else {
              $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'id de Check_List no existe'
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
      } else{
        $data = array(
          'status' => 'error',
          'code'   => '200',
          'message' => 'El usuario no está identificado'
        );
      }

      return response()->json($data, $data['code']);
    }

    public function check_view($vin){ 
      //obtener vin
      $findbyvin = Sell_your_car::firstWhere('vin', $vin);
      if( is_object($findbyvin) && !is_null($findbyvin) ){         // $vin_check_list = Check_List::find( $findbyvin->id);
        $vin_check_list = Check_List::firstWhere('sell_your_car_id', $findbyvin ->id);

        $marca= Brand::firstWhere('id', $findbyvin->brand_id);
        $modelo= Carmodel::firstWhere('id', $findbyvin->carmodel_id);
        $client= Client::firstWhere('id', $findbyvin->client_id);
        $cliente= User::firstWhere('id', $client->user_id);
        $tecval = User::firstWhere('id', $vin_check_list->technician_id);

        // $tec=$vin_check_list->load('user'); /* , 'user_technician' */
        $idtec= $vin_check_list ->user_id ;
        // $valuador = User::firstWhere('id',$idtec);
         
        // $revExt=Foreing_review::find($vin_check_list ->sell_your_car_id);
        $revExt=Foreing_review::firstWhere('sell_your_car_id', $vin_check_list ->sell_your_car_id);
        // $revInt=Interior_review::find($vin_check_list ->sell_your_car_id);
        $revInt=Interior_review::firstWhere('sell_your_car_id', $vin_check_list ->sell_your_car_id);
        // $mecElec=Mechanical_electronic::find($vin_check_list ->sell_your_car_id);
        $mecElec=Mechanical_electronic::firstWhere('sell_your_car_id', $vin_check_list ->sell_your_car_id);
        // $cert=Certification::find($vin_check_list ->sell_your_car_id);
        $cert=Certification::firstWhere('sell_your_car_id', $vin_check_list ->sell_your_car_id);
        /* return view('check_list.check_view', [
          'data_client' => $client,
          'cliente'=>$cliente, 
          'modelo'=>$modelo,
          'marca' =>$marca,
          'vin_check_list' => $vin_check_list,
          'revInt'=>$revInt,
          'revExt'=>$revExt,
          'mecElec'=>$mecElec,        
          'cert'=>$cert,
          'findbyvin' => $findbyvin,
          // 'tec' => $tec,
          'tecval' => $tecval,
          // 'valuador'=>$valuador
        ]); */

        $report = PDF::loadView('check_list.check_view' , ['data_client' => $client, 'marca' =>$marca, 'modelo'=>$modelo, 'findbyvin' => $findbyvin, 'vin_check_list' => $vin_check_list, 'cliente'=>$cliente, 'tecval' => $tecval, 'revExt'=>$revExt, 'revInt'=>$revInt, 'mecElec'=>$mecElec, 'cert'=>$cert]);
        return ($report->download('valuacion.pdf'));
        // return ($report->stream());

      }else{
          $data = array(
            'status' => 'error',
            'code'   => '404',
            'message' => 'El vin del sell your car no existe'
          );
          return response()->json($data, $data['code']); 
      }
        

    } 

    public function check_view_sale($vin){
      //obtener vin
      $findbyvin = Sell_your_car::firstWhere('vin', $vin);
      if( is_object($findbyvin) && !is_null($findbyvin) ){
        $vin_check_list = Check_List::find( $findbyvin ->id);

        $revExt=Foreing_review::find($vin_check_list ->sell_your_car_id);
        $revInt=Interior_review::find($vin_check_list ->sell_your_car_id);
        $mecElec=Mechanical_electronic::find($vin_check_list ->sell_your_car_id);
        $cert=Certification::find($vin_check_list ->sell_your_car_id);

       return view('check_list.check_view2', [
        'vin_check_list' => $vin_check_list,
        'revInt'=>$revInt,
        'revExt'=>$revExt,
        'mecElec'=>$mecElec,        
        'cert'=>$cert,
        'findbyvin' => $findbyvin
    ]);
      }else{
          $data = array(
            'status' => 'error',
            'code'   => '404',
            'message' => 'El vin del sell your car no existe'
          );
          return response()->json($data, $data['code']); 
      }
    }

    public function getcheckFront($id){ 
      $sellYourCar = Sell_your_car::find($id); 
      $data_check_list = Check_List::firstWhere( 'sell_your_car_id', $sellYourCar->id );
      $tecval = User::firstWhere('id', $data_check_list->technician_id);
      $valuator = User::firstWhere('id', $data_check_list->user_id);

      if(is_object($data_check_list) && !is_null($data_check_list)){
        $revExt = Foreing_review::where('sell_your_car_id', $sellYourCar->id)->first();
        $revInt = Interior_review::where('sell_your_car_id', $sellYourCar->id)->first();
        $mecElec = Mechanical_electronic::where('sell_your_car_id', $sellYourCar->id)->first();
        $cert = Certification::where('sell_your_car_id', $sellYourCar->id)->first();


        $data = array(
          'status' => 'success',
          'code' => '200',
          'revExt'=> $revExt,
          'revInt'=> $revInt,
          'mecElec'=> $mecElec,
          'cert'=> $cert,
          'tecval' => $tecval,
          'valuator' => $valuator,
          // 'DataChecklist' => $data_check_list->load('user') /* , 'user_technician' */
          'checklist' => $data_check_list
        );
      }else{
        $data = array(
          'status' => 'error',
          'code'   => '404',
          'message' => 'El id del Check_List no existe'
        );
      }

      return response()->json($data, $data['code']); 
    }
} 