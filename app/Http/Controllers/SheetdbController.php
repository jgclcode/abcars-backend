<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Branch;
use App\Models\Carmodel;
use App\Models\Client;
use App\Models\Source;
use App\Models\State;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Vehiclebody;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use SheetDB\SheetDB;

class SheetdbController extends Controller
{
    public function get(){
        //nota por defecto agarra la primer hoja dentro de nuestro documento 
        // si se desea cambiar de hoja simplemente se pasa como parametro
        //   $sheetdb= new SheetDB('6vnlf2qwfhg6m','nombre de la hoja');
       $sheetdb= new SheetDB('vtuakqauntcw3');
       $sheetdb= $sheetdb->get();
       // Variables
       // Variables
       $added_elements = array();

       if( Session::has('added_elements') ){
           $added_elements = Session::get('added_elements');                             
       }else{
           $added_elements = array(
               'total' => 0,
               'exists' => array(),
               'added' => array(),
               'errors' => array()
           );
       } 

       // Ingreso de datos
       for($i = 0; $i < count($sheetdb); $i++) {

            // Obtener estado 
            $state = null;
            if( isset( $sheetdb[$i]->ESTADO_ISO_3 ) && strlen($sheetdb[$i]->ESTADO_ISO_3) > 0  ){
                $iso = strtoupper(trim($sheetdb[$i]->ESTADO_ISO_3));
                $state = State::where('iso', $iso)->first();
                if( !is_object($state) ){
                    $added_elements['errors']['estado-fila' . ($i+1)] = "El estado con iso3: " . $iso . " no existe";
                }
            }else{
                $added_elements['errors']['fila'. ($i+1) .'estado'] = 'La columna "estado_iso_3" es necesaria';                
            }
            
            // Obtener sucursal ( si no existe se crea )
            $branch = null;
            if( isset( $sheetdb[$i]->SUCURSAL ) && strlen($sheetdb[$i]->SUCURSAL) > 0  ){                
                if( is_object( $state ) ){
                    $branch_name = strtolower(trim($sheetdb[$i]->SUCURSAL));              
                    $branch = Branch::where('name', $branch_name )->first();
                    if( !is_object($branch) ){
                        $branch = new Branch;
                        $branch->name = $branch_name;
                        $branch->state_id = $state->id;
                        $branch->save();
                    }                    
                }                
            }else{
                $added_elements['errors']['fila'. ($i+1) .'sucursal'] = 'La columna "sucursal" es necesaria';                
            }

            // Obtener marca ( si no existe se crea )
            $brand = null;
            if( isset( $sheetdb[$i]->MARCA ) && strlen($sheetdb[$i]->MARCA) > 0 ){                
                if( is_object( $branch ) ){
                    $brand_name = strtolower(trim($sheetdb[$i]->MARCA));              
                    $brand = Brand::where('name', $brand_name )->first();
                    if( !is_object($brand) ){
                        $brand = new Brand;
                        $brand->name = $brand_name;                        
                        $brand->save();
                    }
                }                
            }else{
                $added_elements['errors']['fila'. ($i+1) .'marca'] = 'La columna "marca" es necesaria';                
            }

            // Obtener Carrocería
            $vehiclebody = null;            
            if( isset($sheetdb[$i]->CARROCERIA) ){                
                if( is_object( $brand ) ){
                    $vehiclebody_name = strtolower(trim($sheetdb[$i]->CARROCERIA)); 
                    $vehiclebody = Vehiclebody::where('name', $vehiclebody_name )->first();
                    if( !is_object($vehiclebody) ){                        
                        $vehiclebody = Vehiclebody::where('name', 'otro' )->first();                        
                    }
                }
            }else{
                $vehiclebody = Vehiclebody::where('name', 'ninguno' )->first();
            } 
            
            // Obtener fuente del cliente ( si no existe se crea )     
            $source = null;
            if( isset( $sheetdb[$i]->FUENTE_CLIENTE ) && strlen($sheetdb[$i]->FUENTE_CLIENTE) > 0 ){                
                if( is_object( $branch ) ){
                    $source_name = strtolower(trim($sheetdb[$i]->FUENTE_CLIENTE));              
                    $source = Source::where('name', $source_name )->first();
                    if( !is_object($source) ){
                        $source = new Source;
                        $source->name = $source_name;                        
                        $source->save();
                    }
                }                
            }else{
                $added_elements['errors']['fila'. ($i+1) .'fuente_cliente'] = 'La columna "fuente_cliente" es necesaria';                
            }

            // Obtener cliente ( si no existe se crea )     
            $client = null;
            // Nombre
            if( isset( $sheetdb[$i]->NOMBRE_QUIEN_VENDE ) && strlen($sheetdb[$i]->NOMBRE_QUIEN_VENDE) > 0 ){                                
                //correo
                if( isset( $sheetdb[$i]->CORREO_QUIEN_VENDE ) && strlen(trim($sheetdb[$i]->CORREO_QUIEN_VENDE)) > 0 && $this->validarEmail(trim($sheetdb[$i]->CORREO_QUIEN_VENDE)) ){                
                    //telefono 1
                    if( isset( $sheetdb[$i]->TELEFONO1_QUIEN_VENDE ) && strlen(trim($sheetdb[$i]->TELEFONO1_QUIEN_VENDE)) > 0 && strlen(trim($sheetdb[$i]->TELEFONO1_QUIEN_VENDE)) == 10 && is_numeric(trim($sheetdb[$i]->TELEFONO1_QUIEN_VENDE)) ){                
                        if( is_object($source) ){
                            $client_name = strtolower(trim($sheetdb[$i]->NOMBRE_QUIEN_VENDE));              
                            $client_surname = isset( $sheetdb[$i]->APELLIDOS_QUIEN_VENDE ) && strlen($sheetdb[$i]->APELLIDOS_QUIEN_VENDE) > 0 ? strtolower(trim($sheetdb[$i]->APELLIDOS_QUIEN_VENDE)) : null;
                            $client_email = strtolower(trim($sheetdb[$i]->CORREO_QUIEN_VENDE));              
                            $client_telefono1 = strtolower(trim($sheetdb[$i]->TELEFONO1_QUIEN_VENDE));
                            $user = User::where('email', $client_email )->first();                                
                            $client = is_object($user) ? $user->clients()->first() : null;

                            if( !is_object($client) ){
                                if( !is_object($user) ){
                                    $user = new User;
                                    $user->name = $client_name;
                                    $user->surname = $client_surname;
                                    $user->email = $client_email;
                                    $user->gender = 'f';
                                    $user->password = $pwd = hash('sha256', 'Bienvenido a abscars');   
                                    $user->save();
                                }
                                if( is_object($user) ){                                        
                                    $client = new Client;
                                    $client->phone1 = $client_telefono1;
                                    if( isset( $sheetdb[$i]->TELEFONO2_QUIEN_VENDE ) && strlen(trim($sheetdb[$i]->TELEFONO2_QUIEN_VENDE)) > 0 && strlen(trim($sheetdb[$i]->TELEFONO2_QUIEN_VENDE)) == 10 && is_numeric(trim($sheetdb[$i]->TELEFONO2_QUIEN_VENDE)) ){                
                                        $client->phone2 = strtolower(trim($sheetdb[$i]->TELEFONO2_QUIEN_VENDE));
                                    }      
                                    $client->points = 0;
                                    $client->curp = 'Vendedor del vehiculo';
                                    $client->user_id = $user->id;
                                    $client->source_id = $source->id;
                                    $client->save();
                                }
                            }
                            if( is_object( $user ) ){
                                $user->name = $client_name;
                                $user->surname = $client_surname;
                                $user->email = $client_email;
                                $user->gender = 'f';
                                $user->password = $pwd = hash('sha256', 'Bienvenido a abscars');   
                                $user->save();
                            }
                            if( is_object( $client ) ){
                                $client->phone1 = $client_telefono1;
                                if( isset( $sheetdb[$i]->TELEFONO2_QUIEN_VENDE ) && strlen(trim($sheetdb[$i]->TELEFONO2_QUIEN_VENDE)) > 0 && strlen(trim($sheetdb[$i]->TELEFONO2_QUIEN_VENDE)) == 10 && is_numeric(trim($sheetdb[$i]->TELEFONO2_QUIEN_VENDE)) ){                
                                    $client->phone2 = strtolower(trim($sheetdb[$i]->TELEFONO2_QUIEN_VENDE));
                                }      
                                $client->points = 0;
                                $client->curp = 'Vendedor del vehiculo';
                                $client->user_id = $user->id;
                                $client->source_id = $source->id;
                                $client->save();
                            }
                        }
                    }else{
                        $added_elements['errors']['fila'. ($i+1) .'telefono1_quien_vende'] = 'La columna "telefono1_quien_vende" debe tener 10 caracteres numericos';                
                    }   
                }else{
                    $added_elements['errors']['fila'. ($i+1) .'correo_quien_vende'] = 'La columna "correo_quien_vende" es necesaria';                
                }                                               
            }else{
                $added_elements['errors']['fila'. ($i+1) .'nombre_quien_vende'] = 'La columna "nombre_quien_vende" es necesaria';                
            }

            // Obtener vehiculo
            $vehicle = null;
            // Nombre del vehiculo
            $vehicle_name = null;
            if( is_object( $client ) ){
                if( isset( $sheetdb[$i]->NOMBRE_COMERCIAL ) && strlen($sheetdb[$i]->NOMBRE_COMERCIAL) > 0 ){                
                    $vehicle_name = strtolower(trim($sheetdb[$i]->NOMBRE_COMERCIAL));              
                }else{
                    $added_elements['errors']['fila'. ($i+1) .'nombre_comercial'] = 'La columna "nombre_comercial" es necesaria';                
                } 
            } 
            
            // Modelo del vehiculo
            $model = null;
            if( !is_null($vehicle_name) ){
                if( isset( $sheetdb[$i]->MODELO ) && strlen(trim($sheetdb[$i]->MODELO)) > 0 ){                
                    $vehicle_modelo = strtolower(trim($sheetdb[$i]->MODELO));              
                    $model = Carmodel::where('name', $vehicle_modelo)->first();
                    if( !is_object($model) ){
                        $model = new Carmodel;
                        $model->name = $vehicle_modelo;
                        $model->brand_id = $brand->id;
                        $model->save();
                    }
                }else{
                    $added_elements['errors']['fila'. ($i+1) .'modelo'] = 'La columna "modelo" es necesaria';                
                }  
            }  

            // Vin del vehiculo
            $vehicle_vin = null;
            if( is_object($model) && !is_null($model) ){
                if( isset( $sheetdb[$i]->VIN ) && strlen(trim($sheetdb[$i]->VIN)) == 17 ){                
                    $vehicle_vin = strtolower(trim($sheetdb[$i]->VIN));              
                }else{
                    $added_elements['errors']['fila'. ($i+1) .'vin'] = 'La columna "vin" debe tener 17 caracteres';                
                }  
            } 
            
            // Descripción para web
            $vehicle_descripcion = null;
            if( !is_null($vehicle_vin) ){
                if( isset( $sheetdb[$i]->DESCRIPCION_PARA_WEB ) && strlen(trim($sheetdb[$i]->DESCRIPCION_PARA_WEB)) > 0 ){                
                    $vehicle_descripcion = strtolower(trim($sheetdb[$i]->DESCRIPCION_PARA_WEB));              
                }else{
                    $added_elements['errors']['fila'. ($i+1) .'descripcion_para_web'] = 'La columna "descripcion_para_web" es necesaria';                
                }  
            } 

            // Año modelo del vehículo
            $vehicle_anio_model = null;
            if( !is_null($vehicle_descripcion) ){
                if( isset( $sheetdb[$i]->ANIO_MODELO ) && is_numeric( $sheetdb[$i]->ANIO_MODELO ) ){                
                    $vehicle_anio_model = strtolower(trim($sheetdb[$i]->ANIO_MODELO));              
                }else{
                    $added_elements['errors']['fila'. ($i+1) .'anio_modelo'] = 'La columna "anio_modelo" debe ser un número';                
                }  
            } 

            // Fecha compra 
            $vehicle_fecha_compra = null;
            if( !is_null($vehicle_anio_model) ){
                if( isset( $sheetdb[$i]->FECHA_COMPRA ) && strlen(trim($sheetdb[$i]->FECHA_COMPRA)) > 0 ){                
                    if( gettype ( $sheetdb[$i]->FECHA_COMPRA ) == 'string' ){
                        $vehicle_fecha_compra = date("Y-m-d", strtotime( str_replace( '/', '-', $sheetdb[$i]->FECHA_COMPRA ) ));  
                    }else{
                        $vehicle_fecha_compra = \Carbon\Carbon::createFromDate(1900, 01, 01); 
                        $dateWithDays = $date->addDays( $sheetdb[$i]->FECHA_COMPRA );                       
                    }                      
                }else{
                    $added_elements['errors']['fila'. ($i+1) .'fecha_compra'] = 'La columna "fecha_compra" es incorrecta';                
                }  
            } 

            // Precio 
            $vehicle_precio = null;
            if( !is_null($vehicle_fecha_compra) ){
                if( isset( $sheetdb[$i]->PRECIO ) && is_numeric($sheetdb[$i]->PRECIO) ){                
                    $vehicle_precio = strtolower(trim($sheetdb[$i]->PRECIO));                                      
                }else{
                    $added_elements['errors']['fila'. ($i+1) .'precio'] = 'La columna "precio" es incorrecta';                
                }  
            } 

            // Precio lista
            $vehicle_precio_lista = null;
            if( !is_null($vehicle_precio) ){
                if( isset( $sheetdb[$i]->PRECIO_LISTA ) && is_numeric($sheetdb[$i]->PRECIO_LISTA) ){                
                    $vehicle_precio_lista = strtolower(trim($sheetdb[$i]->PRECIO_LISTA));                                      
                }else{
                    $added_elements['errors']['fila'. ($i+1) .'precio_lista'] = 'La columna "precio_lista" es incorrecta';                
                }  
            } 

            // Precio venta
            $vehicle_precio_venta = null;
            if( !is_null($vehicle_precio_lista ) ){
                if( isset( $sheetdb[$i]->PRECIO_VENTA ) && is_numeric($sheetdb[$i]->PRECIO_VENTA) ){                
                    $vehicle_precio_venta = strtolower(trim($sheetdb[$i]->PRECIO_VENTA));                                      
                }else{
                    $added_elements['errors']['fila'. ($i+1) .'precio_venta'] = 'La columna "precio_venta" es incorrecta';                
                }  
            }

            // Carline
            $vehicle_carline = null;
            if( !is_null($vehicle_precio_venta ) ){
                if( isset( $sheetdb[$i]->CARLINE ) && strlen(trim($sheetdb[$i]->CARLINE)) > 0 ){                
                    $vehicle_carline = strtolower(trim($sheetdb[$i]->CARLINE));                                      
                }else{
                    $added_elements['errors']['fila'. ($i+1) .'carline'] = 'La columna "carline" es necesario';                
                }  
            }

            // Cilindros
            $vehicle_cilindros = null;
            if( !is_null($vehicle_carline ) ){
                if( isset( $sheetdb[$i]->CILINDROS ) && is_numeric(trim($sheetdb[$i]->CILINDROS)) ){                
                    $vehicle_cilindros = strtolower(trim($sheetdb[$i]->CILINDROS));                                      
                }else{
                    $added_elements['errors']['fila'. ($i+1) .'cilindros'] = 'La columna "cilindros" es necesario';                
                }  
            }

            // Color interior
            $vehicle_color_interior = null;
            if( !is_null($vehicle_cilindros ) ){
                if( isset( $sheetdb[$i]->COLOR_INTERIOR ) && strlen(trim($sheetdb[$i]->COLOR_INTERIOR)) > 0 ){                
                    $vehicle_color_interior = strtolower(trim($sheetdb[$i]->COLOR_INTERIOR));                                      
                }else{
                    $added_elements['errors']['fila'. ($i+1) .'color_interior'] = 'La columna "color_interior" es necesario';                
                }  
            }

            // Color exterior
            $vehicle_color_exterior = null;
            if( !is_null( $vehicle_color_interior ) ){
                if( isset( $sheetdb[$i]->COLOR_EXTERIOR ) && strlen(trim($sheetdb[$i]->COLOR_EXTERIOR)) > 0 ){                
                    $vehicle_color_exterior = strtolower(trim($sheetdb[$i]->COLOR_EXTERIOR));                                      
                }else{
                    $added_elements['errors']['fila'. ($i+1) .'color_exterior'] = 'La columna "color_exterior" es necesario';                
                }  
            }

            // Condición del vehículo 
            $vehicle_condicion = null;
            if( !is_null( $vehicle_color_exterior ) ){
                if( 
                    isset( $sheetdb[$i]->CONDICION_VEHICULO ) && strlen(trim($sheetdb[$i]->CONDICION_VEHICULO)) > 0 && 
                    ( trim($sheetdb[$i]->CONDICION_VEHICULO) == 'nuevo' || trim($sheetdb[$i]->CONDICION_VEHICULO) == 'seminuevo' || trim($sheetdb[$i]->CONDICION_VEHICULO) == 'demo' )
                ){                
                    $vehicle_condicion = trim($sheetdb[$i]->CONDICION_VEHICULO) == 'nuevo' ? 'new' : ( trim($sheetdb[$i]->CONDICION_VEHICULO) == 'seminuevo' ? 'pre_owned' : 'demo') ;
                }else{
                    $added_elements['errors']['fila'. ($i+1) .'condicion_vehiculo'] = 'La columna "condicion_vehiculo" es necesario y debe ser "nuevo", "seminuevo" o "demo"';                
                }  
            } 

            // Transmision 
            $vehicle_transmision = null;
            if( !is_null( $vehicle_condicion ) ){
                if( 
                    isset( $sheetdb[$i]->TRANSMISION ) && strlen(trim($sheetdb[$i]->TRANSMISION)) > 0 &&
                    ( trim($sheetdb[$i]->TRANSMISION) == 'automatico' || trim($sheetdb[$i]->TRANSMISION) == 'manual' || trim($sheetdb[$i]->TRANSMISION) == 'cvt' || trim($sheetdb[$i]->TRANSMISION) == 'triptronic' )
                ){                
                    $vehicle_transmision = strtolower(trim($sheetdb[$i]->TRANSMISION));                                      
                }else{
                    $added_elements['errors']['fila'. ($i+1) .'transmision'] = 'La columna "transmision" es necesario y debe ser "automatico", "manual", "cvt" o "triptronic"';                
                }  
            }

            // Promoción    
            $vehicle_promocion = null;                                                 
            if( 
                isset( $sheetdb[$i]->PROMOCION ) && strlen(trim($sheetdb[$i]->PROMOCION)) > 0
            ){                
                $vehicle_promocion = strtolower(trim($sheetdb[$i]->PROMOCION));                                      
            } 

            // Ubicación del vehículo
            $vehicle_ubicacion = null;            
            if( isset( $sheetdb[$i]->UBICACION ) && strlen(trim($sheetdb[$i]->UBICACION)) > 0 ){                
                $vehicle_ubicacion = strtolower(trim($sheetdb[$i]->UBICACION));              
            }
            // Placas 
            $vehicle_placas = null;            
            if( 
                isset( $sheetdb[$i]->PLACAS ) && strlen(trim($sheetdb[$i]->PLACAS)) > 0 
            ){                
                $vehicle_placas = strtolower(trim($sheetdb[$i]->PLACAS));                                      
            }

            // Dias en inventario 
            $vehicle_dias_inventario = null;            
            if( 
                isset( $sheetdb[$i]->DIAS_INVENTARIO ) && is_numeric(trim($sheetdb[$i]->DIAS_INVENTARIO))                    
            ){                
                $vehicle_dias_inventario = strtolower(trim($sheetdb[$i]->DIAS_INVENTARIO));                                      
            }   

            // Kilometraje
            $vehicle_kilometraje = null;            
            if( 
                isset( $sheetdb[$i]->KILOMETRAJE ) && is_numeric(trim($sheetdb[$i]->KILOMETRAJE))                    
            ){                
                $vehicle_kilometraje = strtolower(trim($sheetdb[$i]->KILOMETRAJE));                                      
            } 

            // numero de llaves
            $vehicle_numero_llaves = null;            
            if( 
                isset( $sheetdb[$i]->NUMERO_LLAVES ) && is_numeric(trim($sheetdb[$i]->NUMERO_LLAVES))                    
            ){                
                $vehicle_numero_llaves = strtolower(trim($sheetdb[$i]->NUMERO_LLAVES));                                      
            } 

            // birlos
            $vehicle_birlos = null;            
            if( 
                isset( $sheetdb[$i]->BIRLOS ) && strtolower(trim($sheetdb[$i]->BIRLOS)) === 'si'                  
            ){                
                $vehicle_birlos = 'yes';
            } 

            // llanta de refacción
            $vehicle_llanta_refaccion = null;            
            if( 
                isset( $sheetdb[$i]->LLANTA_REFACCION ) && strtolower(trim($sheetdb[$i]->LLANTA_REFACCION)) === 'si'                  
            ){                
                $vehicle_llanta_refaccion = 'yes';
            } 

            // gato
            $vehicle_gato = null;            
            if( 
                isset( $sheetdb[$i]->GATO ) && strtolower(trim($sheetdb[$i]->GATO)) === 'si'                  
            ){                
                $vehicle_gato = 'yes';
            } 

            // extintor
            $vehicle_extintor = null;            
            if( 
                isset( $sheetdb[$i]->EXTINTOR ) && strtolower(trim($sheetdb[$i]->EXTINTOR)) === 'si'                  
            ){                
                $vehicle_extintor = 'yes';
            } 

            // reflejantes
            $vehicle_reflejantes = null;            
            if( 
                isset( $sheetdb[$i]->REFLEJANTES ) && strtolower(trim($sheetdb[$i]->REFLEJANTES)) === 'si'                  
            ){                
                $vehicle_reflejantes = 'yes';
            }

            // manuales
            $vehicle_manuales = null;            
            if( 
                isset( $sheetdb[$i]->MANUALES ) && strtolower(trim($sheetdb[$i]->MANUALES)) === 'si'                  
            ){                
                $vehicle_manuales = 'yes';
            }

            $vehicle_poliza = null;            
            if( 
                isset( $sheetdb[$i]->POLIZA ) && strtolower(trim($sheetdb[$i]->POLIZA)) === 'si'                  
            ){                
                $vehicle_poliza = 'yes';
            }

            // cables_corriente
            $vehicle_cables_corriente = null;            
            if( 
                isset( $sheetdb[$i]->CABLES_CORRIENTE ) && strtolower(trim($sheetdb[$i]->CABLES_CORRIENTE)) === 'si'                  
            ){                
                $vehicle_cables_corriente = 'yes';
            }

            // Guardar vehículo            
            if( !is_null( $vehicle_transmision ) ){
                $vehicle = Vehicle::where('vin', $vehicle_vin)->first();
                if( !is_object( $vehicle ) ){
                    $vehicle = new Vehicle;

                    $vehicle->name = $vehicle_name;                    
                    $vehicle->description = $vehicle_descripcion;
                    $vehicle->vin = $vehicle_vin;
                    $vehicle->location = $vehicle_ubicacion;
                    $vehicle->yearModel = $vehicle_anio_model;                    
                    $vehicle->purchaseDate = $vehicle_fecha_compra;
                    $vehicle->price = $vehicle_precio;
                    $vehicle->priceList = $vehicle_precio_lista;
                    $vehicle->salePrice = $vehicle_precio_venta;                    
                    $vehicle->type = $vehicle_condicion;
                    $vehicle->carline = $vehicle_carline;
                    $vehicle->cylinders = $vehicle_cilindros;
                    $vehicle->colorInt = $vehicle_color_interior;                    
                    $vehicle->colorExt = $vehicle_color_exterior;
                    $vehicle->status = 'active';
                    $vehicle->plates = $vehicle_placas;
                    $vehicle->transmission = $vehicle_transmision; 
                    $vehicle->promotion = $vehicle_promocion;                    
                    // Opcionales
                    if( !is_null($vehicle_dias_inventario) )
                    $vehicle->inventoryDays = $vehicle_dias_inventario;

                    if( !is_null($vehicle_kilometraje) )
                    $vehicle->km = $vehicle_kilometraje;

                    if( !is_null($vehicle_numero_llaves) )
                    $vehicle->numkeys = $vehicle_numero_llaves;

                    if( !is_null($vehicle_birlos) )
                    $vehicle->studs = $vehicle_birlos;

                    if( !is_null($vehicle_llanta_refaccion) )
                    $vehicle->spareTire = $vehicle_llanta_refaccion;

                    if( !is_null($vehicle_gato) )
                    $vehicle->hydraulicJack = $vehicle_gato;

                    if( !is_null($vehicle_extintor) )
                    $vehicle->extinguiser = $vehicle_extintor;

                    if( !is_null($vehicle_reflejantes) )
                    $vehicle->reflectives = $vehicle_reflejantes;

                    if( !is_null($vehicle_manuales) )
                    $vehicle->handbook = $vehicle_manuales;

                    if( !is_null($vehicle_poliza) )
                    $vehicle->insurancePolicy = $vehicle_poliza;

                    if( !is_null($vehicle_cables_corriente) )
                    $vehicle->powerCables = $vehicle_cables_corriente;
                    // Fin opcionales
                    $vehicle->Carmodel_id = $model->id;
                    $vehicle->vehiclebody_id = $vehiclebody->id; 
                    $vehicle->branch_id = $branch->id; 
                    $vehicle->client_id = $client->id;             
                    if( $vehicle->save() ){
                        $added_elements['added']['fila'. ($i+1) .'creado'] = 'El vehículo con vin "' . $vehicle_vin .'" ha sido registrado con exito';                
                        
                    }                    
                }else{
                    $vehicle->name = $vehicle_name;                    
                    $vehicle->description = $vehicle_descripcion;                    
                    $vehicle->location = $vehicle_ubicacion;
                    $vehicle->yearModel = $vehicle_anio_model;                    
                    $vehicle->purchaseDate = $vehicle_fecha_compra;
                    $vehicle->price = $vehicle_precio;
                    $vehicle->priceList = $vehicle_precio_lista;
                    $vehicle->salePrice = $vehicle_precio_venta;                    
                    $vehicle->type = $vehicle_condicion;
                    $vehicle->carline = $vehicle_carline;
                    $vehicle->cylinders = $vehicle_cilindros;
                    $vehicle->colorInt = $vehicle_color_interior;                    
                    $vehicle->colorExt = $vehicle_color_exterior;                    
                    $vehicle->plates = $vehicle_placas;
                    $vehicle->transmission = $vehicle_transmision; 
                    $vehicle->promotion = $vehicle_promocion;                    
                    // Opcionales
                    if( !is_null($vehicle_dias_inventario) )
                    $vehicle->inventoryDays = $vehicle_dias_inventario;

                    if( !is_null($vehicle_kilometraje) )
                    $vehicle->km = $vehicle_kilometraje;

                    if( !is_null($vehicle_numero_llaves) )
                    $vehicle->numkeys = $vehicle_numero_llaves;

                    if( !is_null($vehicle_birlos) )
                    $vehicle->studs = $vehicle_birlos;

                    if( !is_null($vehicle_llanta_refaccion) )
                    $vehicle->spareTire = $vehicle_llanta_refaccion;

                    if( !is_null($vehicle_gato) )
                    $vehicle->hydraulicJack = $vehicle_gato;

                    if( !is_null($vehicle_extintor) )
                    $vehicle->extinguiser = $vehicle_extintor;

                    if( !is_null($vehicle_reflejantes) )
                    $vehicle->reflectives = $vehicle_reflejantes;

                    if( !is_null($vehicle_manuales) )
                    $vehicle->handbook = $vehicle_manuales;

                    if( !is_null($vehicle_poliza) )
                    $vehicle->insurancePolicy = $vehicle_poliza;

                    if( !is_null($vehicle_cables_corriente) )
                    $vehicle->powerCables = $vehicle_cables_corriente;
                    // Fin opcionales
                    $vehicle->Carmodel_id = $model->id;
                    $vehicle->vehiclebody_id = $vehiclebody->id; 
                    $vehicle->branch_id = $branch->id; 
                    $vehicle->client_id = $client->id;             
                    if( $vehicle->save() ){
                        $added_elements['exists']['fila'. ($i+1) .'existente'] = 'El vehículo con vin "' . $vehicle_vin .'" ya ha sido actualizado';                                        
                    }                    
                }
                
            }
            Session::flash('added_elements', $added_elements ); 

            $response = $added_elements = Session::get('added_elements');                       
       }

       $data = array(
            'code' => 200,
            'status' => 'success',
            'respuesta' => $response
       );
       return response()->json($data, $data['code']); 
    }

    private function validarEmail( String $email ){
        return preg_match('#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#', trim( $email ) );
    }
}
