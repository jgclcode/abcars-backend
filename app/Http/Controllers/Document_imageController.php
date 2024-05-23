<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Document;
use App\Models\Document_image;
use App\Models\Sell_your_car;
use App\Models\Check_List;
use Illuminate\Validation\Rule;

class Document_imageController extends Controller
{
    public function index()
    {
        $documents = Document_image::all();
        $data = array(
            'code' => 200, 
            'status' => 'success',
            'documents' => $documents
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
            // Take information Form
            'contratoCompraVentaFirmado' => 'in:a1,a2,a3', 
            'facturaOriginal' => 'in:a1,a2,a3', 
            'copiaFacturaOrigen' => 'in:a1,a2,a3', 
            'copiaFielIne' => 'in:a1,a2,a3', 
            'curp' => 'in:a1,a2,a3', 
            'acuseRespuestaCambioRol' => 'in:a1,a2,a3', 
            'adeudosTenencia' => 'in:a1,a2,a3',
            'montoAdeudoTenencia' => 'required|numeric', 
            'tenenciasOriginales' => 'in:a1,a2,a3',
            // 'agnosTenencias' => 'required|integer',
            'tarjetaDeCirculacion' => 'in:a1,a2,a3',
            'copiaGuiaAutometrica' => 'in:a1,a2,a3',
            // 'compraGuiaA' => 'required|numeric', 
            // 'ventaGuiaA' => 'required|numeric', 
            'consultaIntelimotors' => 'in:a1,a2,a3',
            // 'compraIntelimotors' => 'required|numeric', 
            // 'ventaIntelimotors' => 'required|numeric', 
            'facturaOriginalFinanciera' => 'in:a1,a2,a3',
            'verificacionFiscalDeFacturas' => 'in:a1,a2,a3',
            'validacionIne' => 'in:a1,a2,a3',
            'comprobanteDomicilio' => 'in:a1,a2,a3',
            'repuve' => 'in:a1,a2,a3',
            'checklistCienPuntos' => 'in:a1,a2,a3',
            'copiasFacturasIntermediasConEndoso' => 'in:a1,a2,a3',
            'validacionFacturaParteAgencia' => 'in:a1,a2,a3',
            'constanciaSituacionFiscal' => 'in:a1,a2,a3',
            'cambioRolCdTac' => 'in:a1,a2,a3',
            'consultaTransunion' => 'in:a1,a2,a3',
            'fotomultas' => 'in:a1,a2,a3',
            'montoFotomultas' => 'required|numeric', 
            'pdiCheckBateria' => 'in:a1,a2,a3',
            // Car documentation form
            'manualDelPropietario' => 'in:a1,a2,a3',
            'gato' => 'in:a1,a2,a3',
            'llantaRefaccion' => 'in:a1,a2,a3',
            'antena' => 'in:a1,a2,a3',
            'comprobanteUltimaVerificacion' => 'in:a1,a2,a3',
            'carnetDeServicio' => 'in:a1,a2,a3',
            'maneralOLlaveDeTuercas' => 'in:a1,a2,a3',
            'reflejantes' => 'in:a1,a2,a3',
            'duplicadoDeLlaves' => 'in:a1,a2,a3',
            'bajaDePlacas' => 'in:a1,a2,a3',
            'birlosDeSeguridad' => 'in:a1,a2,a3',
            'peliculaDeSeguridad' => 'in:a1,a2,a3',
            'cablesPasaCorriente' => 'in:a1,a2,a3',
            // Pictures inside car documentation form
            'numSerie' => 'in:a1,a2,a3',
            'herramienta' => 'in:a1,a2,a3',
            'odometroKilometraje' => 'in:a1,a2,a3',
            'manualYPoliza' => 'in:a1,a2,a3',
            'llantas' => 'in:a1,a2,a3',
            'sellosDeServicio' => 'in:a1,a2,a3',
            'unidadFrenteTraseraCostadosCajuelaYCofre' => 'in:a1,a2,a3',
            'llantaRefaccionFoto' => 'in:a1,a2,a3',
            'fotosEnRampaParteBajaYDagnos' => 'in:a1,a2,a3',
            //  Plate process Form Documents
            'placasFisicas' => 'in:a1,a2,a3',
            'pagosCompletosTenencias' => 'in:a1,a2,a3',
            'facturaConEndosos' => 'in:a1,a2,a3',
            'tarjetaDeCirculacionPlates' => 'in:a1,a2,a3',
            'ineCopiaFiel' => 'in:a1,a2,a3',
            // To purchases in liquidation
            'edoCtaFinancieraObancoIndicaMontoALiquidar' => 'in:a1,a2,a3',
            // For legal persons
            'actaConstitutiva' => 'in:a1,a2,a3',
            'ineRepresentanteMoral' => 'in:a1,a2,a3',
            'poderRepresentanteLegal' => 'in:a1,a2,a3',
            // Observations
            // Upload file
            // 'picture'    => 'required|File',                                          
            // 'check_list_id' => 'required|exists:check_lists,id', 
            'sell_your_car_id' => 'required|exists:sell_your_cars,id', 
            // 'document_id' => 'required|exists:documents,id', 
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
                // Crear
                $document_image = new Document_image;                         
                $document_image->contratoCompraVentaFirmado = $request->contratoCompraVentaFirmado;
                $document_image->facturaOriginal = $request->facturaOriginal;
                $document_image->copiaFacturaOrigen = $request->copiaFacturaOrigen;
                $document_image->copiaFielIne = $request->copiaFielIne;
                $document_image->curp = $request->curp;
                $document_image->acuseRespuestaCambioRol = $request->acuseRespuestaCambioRol;
                $document_image->adeudosTenencia = $request->adeudosTenencia;
                $document_image->montoAdeudoTenencia = $request->montoAdeudoTenencia;
                $document_image->tenenciasOriginales = $request->tenenciasOriginales;
                // $document_image->agnosTenencias = $request->agnosTenencias;
                $document_image->tenencia_12 = $request->tenencia_12;
                $document_image->tenencia_11 = $request->tenencia_11;
                $document_image->tenencia_10 = $request->tenencia_10;
                $document_image->tenencia_9 = $request->tenencia_9;
                $document_image->tenencia_8 = $request->tenencia_8;
                $document_image->tenencia_7 = $request->tenencia_7;
                $document_image->tenencia_6 = $request->tenencia_6;
                $document_image->tenencia_5 = $request->tenencia_5;
                $document_image->tenencia_4 = $request->tenencia_4;
                $document_image->tenencia_3 = $request->tenencia_3;
                $document_image->tenencia_2 = $request->tenencia_2;
                $document_image->tenencia_1 = $request->tenencia_1;
                $document_image->tarjetaDeCirculacion = $request->tarjetaDeCirculacion;
                $document_image->copiaGuiaAutometrica = $request->copiaGuiaAutometrica;
                // $document_image->compraGuiaA = $request->compraGuiaA;
                // $document_image->ventaGuiaA = $request->ventaGuiaA;
                $document_image->consultaIntelimotors = $request->consultaIntelimotors;
                // $document_image->compraIntelimotors = $request->compraIntelimotors;
                // $document_image->ventaIntelimotors = $request->ventaIntelimotors;
                $document_image->facturaOriginalFinanciera = $request->facturaOriginalFinanciera;
                $document_image->verificacionFiscalDeFacturas = $request->verificacionFiscalDeFacturas;
                $document_image->validacionIne = $request->validacionIne;
                $document_image->comprobanteDomicilio = $request->comprobanteDomicilio;
                $document_image->repuve = $request->repuve;
                $document_image->checklistCienPuntos = $request->checklistCienPuntos;
                $document_image->copiasFacturasIntermediasConEndoso = $request->copiasFacturasIntermediasConEndoso;
                $document_image->validacionFacturaParteAgencia = $request->validacionFacturaParteAgencia;
                $document_image->constanciaSituacionFiscal = $request->constanciaSituacionFiscal;
                $document_image->cambioRolCdTac = $request->cambioRolCdTac;
                $document_image->consultaTransunion = $request->consultaTransunion;
                $document_image->fotomultas = $request->fotomultas;
                $document_image->montoFotomultas = $request->montoFotomultas;
                $document_image->pdiCheckBateria = $request->pdiCheckBateria;
                // Car documentation form
                $document_image->manualDelPropietario = $request->manualDelPropietario;
                $document_image->gato = $request->gato;
                $document_image->llantaRefaccion = $request->llantaRefaccion;
                $document_image->antena = $request->antena;
                $document_image->comprobanteUltimaVerificacion = $request->comprobanteUltimaVerificacion;
                $document_image->carnetDeServicio = $request->carnetDeServicio;
                $document_image->maneralOLlaveDeTuercas = $request->maneralOLlaveDeTuercas;
                $document_image->reflejantes = $request->reflejantes;
                $document_image->duplicadoDeLlaves = $request->duplicadoDeLlaves;
                $document_image->bajaDePlacas = $request->bajaDePlacas;
                $document_image->birlosDeSeguridad = $request->birlosDeSeguridad;
                $document_image->peliculaDeSeguridad = $request->peliculaDeSeguridad;
                $document_image->cablesPasaCorriente = $request->cablesPasaCorriente;
                // Pictures inside car documentation form
                $document_image->numSerie = $request->numSerie;
                $document_image->herramienta = $request->herramienta;
                $document_image->odometroKilometraje = $request->odometroKilometraje;
                $document_image->manualYPoliza = $request->manualYPoliza;
                $document_image->llantas = $request->llantas;
                $document_image->sellosDeServicio = $request->sellosDeServicio;
                $document_image->unidadFrenteTraseraCostadosCajuelaYCofre = $request->unidadFrenteTraseraCostadosCajuelaYCofre;
                $document_image->llantaRefaccionFoto = $request->llantaRefaccionFoto;
                $document_image->fotosEnRampaParteBajaYDagnos = $request->fotosEnRampaParteBajaYDagnos;
                //  Plate process Form Documents
                $document_image->placasFisicas = $request->placasFisicas;
                $document_image->pagosCompletosTenencias = $request->pagosCompletosTenencias;
                $document_image->facturaConEndosos = $request->facturaConEndosos;
                $document_image->tarjetaDeCirculacionPlates = $request->tarjetaDeCirculacionPlates;
                $document_image->ineCopiaFiel = $request->ineCopiaFiel;
                // To purchases in liquidation
                $document_image->edoCtaFinancieraObancoIndicaMontoALiquidar = $request->edoCtaFinancieraObancoIndicaMontoALiquidar;
                // For legal persons
                $document_image->actaConstitutiva = $request->actaConstitutiva;
                $document_image->ineRepresentanteMoral = $request->ineRepresentanteMoral;
                $document_image->poderRepresentanteLegal = $request->poderRepresentanteLegal;
                // Observations
                $document_image->observations = $request->observations;
                // Upload file
                $document_image->path = '';

                // $document_image->check_list_id = $request->check_list_id;
                $document_image->sell_your_car_id = $request->sell_your_car_id;
                // $document_image->document_id = $request->document_id;
                ////////////////////////////////////////////  
                // Verificar si existe la carpeta document_images
                /* $nombre_directorio = 'document_images';
                $directorio = storage_path() . '/app/' . $nombre_directorio;
                if (!file_exists($directorio)) {                
                    mkdir($directorio, 0777, true);
                } */
                // Fin verificar si existe la carpeta document_images                           
                /* $image = $request->file('picture');
                if( is_object( $image ) && !empty( $image )){
                    $nombre = \ImageHelper::upload($image, $nombre_directorio);
                    $document_image->path = $nombre;
                }   */                          
                ////////////////////////////////////////////
                $document_image->save();
                /* if( $document_image->save() ){
                    $check_list = Check_list::find( $document_image->check_list_id );
                    if( is_object($check_list) && !is_null($check_list) ){            
                        $has_all_the_images = "si";
                        $documents = Document::all();
                        foreach( $documents as $document ){
                            $image_exists = Document_image::where('check_list_id', $check_list->id)
                                                ->where('document_id', $document->id)
                                                ->first();  
                            if( !is_object( $image_exists ) ){
                                $has_all_the_images = "no"; 
                            }              
                        }            
                        
                        $sell_your_car = Sell_your_car::find($check_list->sell_your_car_id);
                        if( is_object($sell_your_car) && !is_null($sell_your_car) && $has_all_the_images == "si" ){
                            $sell_your_car->status = 'ready_to_buy';
                            $sell_your_car->save();
                        }
                    }                                       
                }  */               
                
                $data = array(
                    'status' => 'success',
                    'code'   => '200',
                    'message' => 'El registro se guardo correctamente', /* La imagen ha sido subida correctamente */
                    'document' => $document_image/* ->document->load('document_images') */
                );                

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
    
    public function update(Request $request, int $id)
    {
        //
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        if (is_array($request->all()) && $checkToken) {
            $rules=[
                // Take information Form
                'contratoCompraVentaFirmado' => 'in:a1,a2,a3', 
                'facturaOriginal' => 'in:a1,a2,a3', 
                'copiaFacturaOrigen' => 'in:a1,a2,a3', 
                'copiaFielIne' => 'in:a1,a2,a3', 
                'curp' => 'in:a1,a2,a3', 
                'acuseRespuestaCambioRol' => 'in:a1,a2,a3', 
                'adeudosTenencia' => 'in:a1,a2,a3',
                'montoAdeudoTenencia' => 'required|numeric', 
                'tenenciasOriginales' => 'in:a1,a2,a3',
                // 'agnosTenencias' => 'required|integer',
                'tarjetaDeCirculacion' => 'in:a1,a2,a3',
                'copiaGuiaAutometrica' => 'in:a1,a2,a3',
                // 'compraGuiaA' => 'required|numeric', 
                // 'ventaGuiaA' => 'required|numeric', 
                'consultaIntelimotors' => 'in:a1,a2,a3',
                // 'compraIntelimotors' => 'required|numeric', 
                // 'ventaIntelimotors' => 'required|numeric', 
                'facturaOriginalFinanciera' => 'in:a1,a2,a3',
                'verificacionFiscalDeFacturas' => 'in:a1,a2,a3',
                'validacionIne' => 'in:a1,a2,a3',
                'comprobanteDomicilio' => 'in:a1,a2,a3',
                'repuve' => 'in:a1,a2,a3',
                'checklistCienPuntos' => 'in:a1,a2,a3',
                'copiasFacturasIntermediasConEndoso' => 'in:a1,a2,a3',
                'validacionFacturaParteAgencia' => 'in:a1,a2,a3',
                'constanciaSituacionFiscal' => 'in:a1,a2,a3',
                'cambioRolCdTac' => 'in:a1,a2,a3',
                'consultaTransunion' => 'in:a1,a2,a3',
                'fotomultas' => 'in:a1,a2,a3',
                'montoFotomultas' => 'required|numeric', 
                'pdiCheckBateria' => 'in:a1,a2,a3',
                // Car documentation form
                'manualDelPropietario' => 'in:a1,a2,a3',
                'gato' => 'in:a1,a2,a3',
                'llantaRefaccion' => 'in:a1,a2,a3',
                'antena' => 'in:a1,a2,a3',
                'comprobanteUltimaVerificacion' => 'in:a1,a2,a3',
                'carnetDeServicio' => 'in:a1,a2,a3',
                'maneralOLlaveDeTuercas' => 'in:a1,a2,a3',
                'reflejantes' => 'in:a1,a2,a3',
                'duplicadoDeLlaves' => 'in:a1,a2,a3',
                'bajaDePlacas' => 'in:a1,a2,a3',
                'birlosDeSeguridad' => 'in:a1,a2,a3',
                'peliculaDeSeguridad' => 'in:a1,a2,a3',
                'cablesPasaCorriente' => 'in:a1,a2,a3',
                // Pictures inside car documentation form
                'numSerie' => 'in:a1,a2,a3',
                'herramienta' => 'in:a1,a2,a3',
                'odometroKilometraje' => 'in:a1,a2,a3',
                'manualYPoliza' => 'in:a1,a2,a3',
                'llantas' => 'in:a1,a2,a3',
                'sellosDeServicio' => 'in:a1,a2,a3',
                'unidadFrenteTraseraCostadosCajuelaYCofre' => 'in:a1,a2,a3',
                'llantaRefaccionFoto' => 'in:a1,a2,a3',
                'fotosEnRampaParteBajaYDagnos' => 'in:a1,a2,a3',
                //  Plate process Form Documents
                'placasFisicas' => 'in:a1,a2,a3',
                'pagosCompletosTenencias' => 'in:a1,a2,a3',
                'facturaConEndosos' => 'in:a1,a2,a3',
                'tarjetaDeCirculacionPlates' => 'in:a1,a2,a3',
                'ineCopiaFiel' => 'in:a1,a2,a3',
                // To purchases in liquidation
                'edoCtaFinancieraObancoIndicaMontoALiquidar' => 'in:a1,a2,a3',
                // For legal persons
                'actaConstitutiva' => 'in:a1,a2,a3',
                'ineRepresentanteMoral' => 'in:a1,a2,a3',
                'poderRepresentanteLegal' => 'in:a1,a2,a3',
                // Observations
                // Upload file
                // 'picture'    => 'required|File',                                          
                // 'check_list_id' => 'required|exists:check_lists,id',
                'sell_your_car_id' => 'required|exists:sell_your_cars,id',
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
                }else {
                    $documents = Document_image::find($id);
                    if (is_object($documents) && !empty($documents)) {
                        // Take information Form
                        $documents->contratoCompraVentaFirmado = $request->contratoCompraVentaFirmado;
                        $documents->facturaOriginal = $request->facturaOriginal;
                        $documents->copiaFacturaOrigen = $request->copiaFacturaOrigen;
                        $documents->copiaFielIne = $request->copiaFielIne;
                        $documents->curp = $request->curp;
                        $documents->acuseRespuestaCambioRol = $request->acuseRespuestaCambioRol;
                        $documents->adeudosTenencia = $request->adeudosTenencia;
                        $documents->montoAdeudoTenencia = $request->montoAdeudoTenencia;
                        $documents->tenenciasOriginales = $request->tenenciasOriginales;
                        // $documents->agnosTenencias = $request->agnosTenencias;
                        $documents->tenencia_12 = $request->tenencia_12;
                        $documents->tenencia_11 = $request->tenencia_11;
                        $documents->tenencia_10 = $request->tenencia_10;
                        $documents->tenencia_9 = $request->tenencia_9;
                        $documents->tenencia_8 = $request->tenencia_8;
                        $documents->tenencia_7 = $request->tenencia_7;
                        $documents->tenencia_6 = $request->tenencia_6;
                        $documents->tenencia_5 = $request->tenencia_5;
                        $documents->tenencia_4 = $request->tenencia_4;
                        $documents->tenencia_3 = $request->tenencia_3;
                        $documents->tenencia_2 = $request->tenencia_2;
                        $documents->tenencia_1 = $request->tenencia_1;
                        $documents->tarjetaDeCirculacion = $request->tarjetaDeCirculacion;
                        $documents->copiaGuiaAutometrica = $request->copiaGuiaAutometrica;
                        // $documents->compraGuiaA = $request->compraGuiaA;
                        // $documents->ventaGuiaA = $request->ventaGuiaA;
                        $documents->consultaIntelimotors = $request->consultaIntelimotors;
                        // $documents->compraIntelimotors = $request->compraIntelimotors;
                        // $documents->ventaIntelimotors = $request->ventaIntelimotors;
                        $documents->facturaOriginalFinanciera = $request->facturaOriginalFinanciera;
                        $documents->verificacionFiscalDeFacturas = $request->verificacionFiscalDeFacturas;
                        $documents->validacionIne = $request->validacionIne;
                        $documents->comprobanteDomicilio = $request->comprobanteDomicilio;
                        $documents->repuve = $request->repuve;
                        $documents->checklistCienPuntos = $request->checklistCienPuntos;
                        $documents->copiasFacturasIntermediasConEndoso = $request->copiasFacturasIntermediasConEndoso;
                        $documents->validacionFacturaParteAgencia = $request->validacionFacturaParteAgencia;
                        $documents->constanciaSituacionFiscal = $request->constanciaSituacionFiscal;
                        $documents->cambioRolCdTac = $request->cambioRolCdTac;
                        $documents->consultaTransunion = $request->consultaTransunion;
                        $documents->fotomultas = $request->fotomultas;
                        $documents->montoFotomultas = $request->montoFotomultas;
                        $documents->pdiCheckBateria = $request->pdiCheckBateria;
                        // Car documentation form
                        $documents->manualDelPropietario = $request->manualDelPropietario;
                        $documents->gato = $request->gato;
                        $documents->llantaRefaccion = $request->llantaRefaccion;
                        $documents->antena = $request->antena;
                        $documents->comprobanteUltimaVerificacion = $request->comprobanteUltimaVerificacion;
                        $documents->carnetDeServicio = $request->carnetDeServicio;
                        $documents->maneralOLlaveDeTuercas = $request->maneralOLlaveDeTuercas;
                        $documents->reflejantes = $request->reflejantes;
                        $documents->duplicadoDeLlaves = $request->duplicadoDeLlaves;
                        $documents->bajaDePlacas = $request->bajaDePlacas;
                        $documents->birlosDeSeguridad = $request->birlosDeSeguridad;
                        $documents->peliculaDeSeguridad = $request->peliculaDeSeguridad;
                        $documents->cablesPasaCorriente = $request->cablesPasaCorriente;
                        // Pictures inside car documentation form
                        $documents->numSerie = $request->numSerie;
                        $documents->herramienta = $request->herramienta;
                        $documents->odometroKilometraje = $request->odometroKilometraje;
                        $documents->manualYPoliza = $request->manualYPoliza;
                        $documents->llantas = $request->llantas;
                        $documents->sellosDeServicio = $request->sellosDeServicio;
                        $documents->unidadFrenteTraseraCostadosCajuelaYCofre = $request->unidadFrenteTraseraCostadosCajuelaYCofre;
                        $documents->llantaRefaccionFoto = $request->llantaRefaccionFoto;
                        $documents->fotosEnRampaParteBajaYDagnos = $request->fotosEnRampaParteBajaYDagnos;
                        //  Plate process Form Documents
                        $documents->placasFisicas = $request->placasFisicas;
                        $documents->pagosCompletosTenencias = $request->pagosCompletosTenencias;
                        $documents->facturaConEndosos = $request->facturaConEndosos;
                        $documents->tarjetaDeCirculacionPlates = $request->tarjetaDeCirculacionPlates;
                        $documents->ineCopiaFiel = $request->ineCopiaFiel;
                        // To purchases in liquidation
                        $documents->edoCtaFinancieraObancoIndicaMontoALiquidar = $request->edoCtaFinancieraObancoIndicaMontoALiquidar;
                        // For legal persons
                        $documents->actaConstitutiva = $request->actaConstitutiva;
                        $documents->ineRepresentanteMoral = $request->ineRepresentanteMoral;
                        $documents->poderRepresentanteLegal = $request->poderRepresentanteLegal;
                        // Observations
                        $documents->observations = $request->observations;
                        // Upload file
                        ////////////////////////////////////////////  
                        // Verificar si existe la carpeta document_images
                        $nombre_directorio = 'document_images';
                        $directorio = storage_path() . '/app/' . $nombre_directorio;
                        if (!file_exists($directorio)) {                
                            mkdir($directorio, 0777, true);
                        }
                        // Fin verificar si existe la carpeta document_images                           
                        $image = $request->file('picture');
                        if( is_object( $image ) && !empty( $image )){
                            \ImageHelper::delete( $nombre_directorio, $document_image->path );
                            $nombre = \ImageHelper::upload($image, $nombre_directorio);
                            $document_image->path = $nombre;
                        }                            
                        ////////////////////////////////////////////
                        
                        // $documents->check_list_id = $request->check_list_id;
                        $documents->sell_your_car_id = $request->sell_your_car_id;
                        $documents->save();

                        $data = array(
                            'status' => 'success',
                            'code'   => '200',
                            'message' => 'Documents se ha actualizado correctamente'
                        );
                    }else {
                        $data = array(
                            'status' => 'error',
                            'code'   => '200',
                            'message' => 'id de documents no existe'
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
            // Fin Try catch
        }else {
            $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'El usuario no está identificado'
            );
        }

        return response()->json($data, $data['code']);
    }

    public function destroy($id)
    {
        //
    }
    
    public function getDocumentImage( int $check_list_id, int $document_id ){
        $document_image = Document_image::where('check_list_id', $check_list_id)
                                        ->where('document_id', $document_id)
                                        ->first();  
        $data = array(
            'code' => 200,
            'status' => 'success',
            'document_image' => $document_image
        );

        return response()->json($data, $data['code']);
    }

    public function getdocument_pdf(int $sellyourcar_id){
        $id = Sell_your_car::firstWhere('id', $sellyourcar_id);
        $checklistId = Check_List::firstWhere('sell_your_car_id', $id->id);

        if( is_object($checklistId) && !is_null($checklistId)){
            // $docImageId = Document_image::firstWhere('check_list_id', $checklistId->id);
            $docImageId = Document_image::firstWhere('sell_your_car_id', $id->id);
            // dd($docImageId);
            if (is_object($docImageId) && !is_null($docImageId)) {
                $data = array(
                    "datos" => true /* $checklistId */
                );
                return response()->json($data);
            } else {
                $data = array(
                    "datos" => false /* $checklistId */
                );
                return response()->json($data);
            }
        }else{
            $data = array(
                "datos" => false
            );
            return response()->json($data);
        }

        // dd($checklistId);
        /* if (is_null($checklistId)) {
            return false;
        }else{
            $docImageId = Document_image::firstWhere('check_list_id', $checklistId->id);
    
            if (is_object($docImageId) && !is_null($docImageId)) {
                return true;
            }

        } */
    }

    // public function getDocumentation( int $check_list_id){
    public function getDocumentation( int $sell_your_car_id){
        // $documentation = Document_image::where('check_list_id', $check_list_id)->first();
        $documentation = Document_image::where('sell_your_car_id', $sell_your_car_id)->first();
        $data = array(
            'code' => 200,
            'status' => 'success',
            'documents' => $documentation
        );
        return response()->json($data, $data['code']);
    }

    // public function getpdfwatch( int $checklist_id){
    public function getpdfwatch( int $sellyourcar_id){
        // $documentation = Document_image::where('check_list_id', $checklist_id)->first();
        $documentation = Document_image::where('sell_your_car_id', $sellyourcar_id)->first();
        return response()->file(storage_path().'/app/document_images/'.$documentation->path);
    }

    public function update_image( Request $request, int $id ){
        if (!is_array($request->all())) {
            $data = array(
              'status' => 'error',
              'code'   => '200',
              'message'  => "request must be an array"
            );
        }

        $rules = [            
            'picture'    => 'required|File'                                          
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
                // Crear
                $document_image = Document_image::find($id);                         
                if( is_object($document_image) && !is_null($document_image) ){
                    ////////////////////////////////////////////  
                    // Verificar si existe la carpeta document_images
                    $nombre_directorio = 'document_images';
                    $directorio = storage_path() . '/app/' . $nombre_directorio;
                    if (!file_exists($directorio)) {                
                        mkdir($directorio, 0777, true);
                    }
                    // Fin verificar si existe la carpeta document_images                           
                    $image = $request->file('picture');
                    if( is_object( $image ) && !empty( $image )){
                        \ImageHelper::delete( $nombre_directorio, $document_image->path );
                        $nombre = \ImageHelper::upload($image, $nombre_directorio);
                        $document_image->path = $nombre;
                    }                            
                    ////////////////////////////////////////////
                    $document_image->save();                
                    
                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'La imagen ha sido actualizada correctamente',
                        // 'document' => $document_image->document->load('document_images')
                    );     
                }else{
                    $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'message' => 'El registro no existe'
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

        return response()->json($data, $data['code']);
    }

    public function update_pdf( Request $request, int $id ){
        if (!is_array($request->all())) {
            $data = array(
              'status' => 'error',
              'code'   => '200',
              'message'  => "request must be an array"
            );
        }

        $rules = [            
            'picture'    => 'required|File'                                          
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
                // Crear
                // $checklist_id = Check_List::firstWhere('id', $id);
                $sell_your_car_id = Sell_your_car::firstWhere('id', $id);
                // $document_image = Document_image::firstWhere('check_list_id', $checklist_id->id);                         
                $document_image = Document_image::firstWhere('sell_your_car_id', $sell_your_car_id->id);                         
                if( is_object($document_image) && !is_null($document_image) ){
                    ////////////////////////////////////////////  
                    // Verificar si existe la carpeta document_images
                    $nombre_directorio = 'document_images';
                    $directorio = storage_path() . '/app/' . $nombre_directorio;
                    if (!file_exists($directorio)) {                
                        mkdir($directorio, 0777, true);
                    }
                    // Fin verificar si existe la carpeta document_images                           
                    $image = $request->file('picture');
                    if( is_object( $image ) && !empty( $image )){
                        \ImageHelper::delete( $nombre_directorio, $document_image->path );
                        // Crear nombre y ruta para guardar imagen
                        $nombre = time() .  pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '.pdf'; 
                        // Quitar espacios en nombre del file
                        $nombre = str_replace(' ', '', $nombre);
                        $image->move($directorio,$nombre);
                        $document_image->path = $nombre;
                    }                            
                    ////////////////////////////////////////////
                    $document_image->save();                
                    
                    $data = array(
                        'status' => 'success',
                        'code'   => '200',
                        'message' => 'El archivo pdf ha sido actualizado correctamente'
                    );     
                }else{
                    $data = array(
                        'status' => 'error',
                        'code'   => '200',
                        'message' => 'El registro no existe'
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

        return response()->json($data, $data['code']);
    }

    public function getImage($filename){
        header('Access-Control-Allow-Origin: *');
        $file = '';
        try{
            $file = Storage::disk('document_images')->get($filename);            
        }catch( \Exception $e ){     
            $file = Storage::disk('vehicles')->get('principal.png');
        }
        return new Response($file, 200);
    }

    /**
     * Download Document Imagen
    */
    public function downloadDocumentImagen(String $filename) {
        header('Access-Control-Allow-Origin: *');
        $file = '';

        try{
            $file = Storage::disk('document_images')->download($filename);
        }catch( \Exception $e ){     
            $file = Storage::disk('vehicles')->download('principal.png');
        }
        
        return $file;        
    }
}
