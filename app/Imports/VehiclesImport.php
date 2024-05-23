<?php

namespace App\Imports;
use App\Models\Brand;
use App\Models\Branch;
use App\Models\Carmodel;
use App\Models\Client;
use App\Models\Source;
use App\Models\State;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Vehiclebody;

use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class VehiclesImport implements WithHeadingRow, ToCollection
{    
    public function collection(Collection $rows)
    {
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

        foreach ($rows as $key => $row ) 
        {
            // Obtener estado 
            $state = null;
            if( isset( $row['estado_iso_3'] ) && strlen($row['estado_iso_3']) > 0  ){
                $iso = strtoupper(trim($row['estado_iso_3']));
                $state = State::where('iso', $iso)->first();
                if( !is_object($state) ){
                    $added_elements['errors']['estado-fila' . ($key+1)] = "El estado con iso3: " . $iso . " no existe";
                }
            }else{
                $added_elements['errors']['fila'. ($key+1) .'estado'] = 'La columna "estado_iso_3" es necesaria';                
            }

            // Obtener sucursal ( si no existe se crea )
            $branch = null;
            if( isset( $row['sucursal'] ) && strlen($row['sucursal']) > 0  ){                
                if( is_object( $state ) ){
                    $branch_name = strtolower(trim($row['sucursal']));              
                    $branch = Branch::where('name', $branch_name )->first();
                    if( !is_object($branch) ){
                        $branch = new Branch;
                        $branch->name = $branch_name;
                        $branch->state_id = $state->id;
                        $branch->save();
                    }
                }                
            }else{
                $added_elements['errors']['fila'. ($key+1) .'sucursal'] = 'La columna "sucursal" es necesaria';
            }

            // Obtener marca ( si no existe se crea )
            $brand = null;
            if( isset( $row['marca'] ) && strlen($row['marca']) > 0 ){                
                if( is_object( $branch ) ){
                    $brand_name = strtolower(trim($row['marca']));              
                    $brand = Brand::where('name', $brand_name )->first();
                    if( !is_object($brand) ){
                        $brand = new Brand;
                        $brand->name = $brand_name;                        
                        $brand->save();
                    }
                }                
            }else{
                $added_elements['errors']['fila'. ($key+1) .'marca'] = 'La columna "marca" es necesaria';                
            }

            // Obtener Carrocería
            $vehiclebody = null;            
            if( isset($row['carroceria']) ){                
                if( is_object( $brand ) ){
                    $vehiclebody_name = strtolower(trim($row['carroceria'])); 
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
            if( isset( $row['fuente_cliente'] ) && strlen($row['fuente_cliente']) > 0 ){                
                if( is_object( $branch ) ){
                    $source_name = strtolower(trim($row['fuente_cliente']));              
                    $source = Source::where('name', $source_name )->first();
                    if( !is_object($source) ){
                        $source = new Source;
                        $source->name = $source_name;                        
                        $source->save();
                    }
                }                
            }else{
                $added_elements['errors']['fila'. ($key+1) .'fuente_cliente'] = 'La columna "fuente_cliente" es necesaria';                
            }

            // Obtener cliente ( si no existe se crea )     
            $client = null;
            // Nombre
            if( isset( $row['nombre_quien_vende'] ) && strlen($row['nombre_quien_vende']) > 0 ){                                
                //correo
                if( isset( $row['correo_quien_vende'] ) && strlen(trim($row['correo_quien_vende'])) > 0 && $this->validarEmail(trim($row['correo_quien_vende'])) ){                
                    //telefono 1
                    if( isset( $row['telefono1_quien_vende'] ) && strlen(trim($row['telefono1_quien_vende'])) > 0 && strlen(trim($row['telefono1_quien_vende'])) == 10 && is_numeric(trim($row['telefono1_quien_vende'])) ){                
                        if( is_object($source) ){
                            $client_name = strtolower(trim($row['nombre_quien_vende']));              
                            $client_surname = isset( $row['apellidos_quien_vende'] ) && strlen($row['apellidos_quien_vende']) > 0 ? strtolower(trim($row['apellidos_quien_vende'])) : null;
                            $client_email = strtolower(trim($row['correo_quien_vende']));              
                            $client_telefono1 = strtolower(trim($row['telefono1_quien_vende']));
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
                                    if( isset( $row['telefono2_quien_vende'] ) && strlen(trim($row['telefono2_quien_vende'])) > 0 && strlen(trim($row['telefono2_quien_vende'])) == 10 && is_numeric(trim($row['telefono2_quien_vende'])) ){                
                                        $client->phone2 = strtolower(trim($row['telefono2_quien_vende']));
                                    }      
                                    $client->points = 0;
                                    $client->curp = 'Vendedor del vehiculo';
                                    $client->rewards = 0;
                                    $client->user_id = $user->id;
                                    $client->source_id = $source->id;
                                    $client->save();
                                }
                            }
                        }
                    }else{
                        $added_elements['errors']['fila'. ($key+1) .'telefono1_quien_vende'] = 'La columna "telefono1_quien_vende" debe tener 10 caracteres numericos';                
                    }   
                }else{
                    $added_elements['errors']['fila'. ($key+1) .'correo_quien_vende'] = 'La columna "correo_quien_vende" es necesaria';                
                }                                               
            }else{
                $added_elements['errors']['fila'. ($key+1) .'nombre_quien_vende'] = 'La columna "nombre_quien_vende" es necesaria';                
            }

            // Obtener vehiculo
            $vehicle = null;
            // Nombre del vehiculo
            $vehicle_name = null;
            if( is_object( $client ) ){
                if( isset( $row['nombre_comercial'] ) && strlen($row['nombre_comercial']) > 0 ){                
                    $vehicle_name = strtolower(trim($row['nombre_comercial']));              
                }else{
                    $added_elements['errors']['fila'. ($key+1) .'nombre_comercial'] = 'La columna "nombre_comercial" es necesaria';                
                } 
            }              
            
            // Modelo del vehiculo
            $model = null;
            if( !is_null($vehicle_name) ){
                if( isset( $row['modelo'] ) && strlen(trim($row['modelo'])) > 0 ){                
                    $vehicle_modelo = strtolower(trim($row['modelo']));              
                    $model = Carmodel::where('name', $vehicle_modelo)->first();
                    if( !is_object($model) ){
                        $model = new Carmodel;
                        $model->name = $vehicle_modelo;
                        $model->brand_id = $brand->id;
                        $model->save();
                    }
                }else{
                    $added_elements['errors']['fila'. ($key+1) .'modelo'] = 'La columna "modelo" es necesaria';                
                }  
            }  

            // Vin del vehiculo
            $vehicle_vin = null;
            if( is_object($model) && !is_null($model) ){
                if( isset( $row['vin'] ) && strlen(trim($row['vin'])) == 17 ){                
                    $vehicle_vin = strtolower(trim($row['vin']));              
                }else{
                    $added_elements['errors']['fila'. ($key+1) .'vin'] = 'La columna "vin" debe tener 17 caracteres';                
                }  
            }            

            // Descripción para web
            $vehicle_descripcion = null;
            if( !is_null($vehicle_vin) ){
                if( isset( $row['descripcion_para_web'] ) && strlen(trim($row['descripcion_para_web'])) > 0 ){                
                    $vehicle_descripcion = strtolower(trim($row['descripcion_para_web']));              
                }else{
                    $added_elements['errors']['fila'. ($key+1) .'descripcion_para_web'] = 'La columna "descripcion_para_web" es necesaria';                
                }  
            }             

            // Año modelo del vehículo
            $vehicle_anio_model = null;
            if( !is_null($vehicle_descripcion) ){
                if( isset( $row['anio_modelo'] ) && is_numeric( $row['anio_modelo'] ) ){                
                    $vehicle_anio_model = strtolower(trim($row['anio_modelo']));              
                }else{
                    $added_elements['errors']['fila'. ($key+1) .'anio_modelo'] = 'La columna "anio_modelo" debe ser un número';                
                }  
            } 

            // Fecha compra 
            $vehicle_fecha_compra = null;
            if( !is_null($vehicle_anio_model) ){
                if( isset( $row['fecha_compra'] ) && strlen(trim($row['fecha_compra'])) > 0 ){                
                    if( gettype ( $row['fecha_compra'] ) == 'string' ){
                        $vehicle_fecha_compra = date("Y-m-d", strtotime( str_replace( '/', '-', $row['fecha_compra'] ) ));  
                    }else{
                        $vehicle_fecha_compra = \Carbon\Carbon::createFromDate(1900, 01, 01); 
                        $dateWithDays = $date->addDays( $row['fecha_compra'] );                       
                    }                      
                }else{
                    $added_elements['errors']['fila'. ($key+1) .'fecha_compra'] = 'La columna "fecha_compra" es incorrecta';                
                }  
            } 

            // Precio 
            $vehicle_precio = null;
            if( !is_null($vehicle_fecha_compra) ){
                if( isset( $row['precio'] ) && is_numeric($row['precio']) ){                
                    $vehicle_precio = strtolower(trim($row['precio']));                                      
                }else{
                    $added_elements['errors']['fila'. ($key+1) .'precio'] = 'La columna "precio" es incorrecta';                
                }  
            } 

            // Precio lista
            $vehicle_precio_lista = null;
            if( !is_null($vehicle_precio) ){
                if( isset( $row['precio_lista'] ) && is_numeric($row['precio_lista']) ){                
                    $vehicle_precio_lista = strtolower(trim($row['precio_lista']));                                      
                }else{
                    $added_elements['errors']['fila'. ($key+1) .'precio_lista'] = 'La columna "precio_lista" es incorrecta';                
                }  
            } 

            // Precio venta
            $vehicle_precio_venta = null;
            if( !is_null($vehicle_precio_lista ) ){
                if( isset( $row['precio_venta'] ) && is_numeric($row['precio_venta']) ){                
                    $vehicle_precio_venta = strtolower(trim($row['precio_venta']));                                      
                }else{
                    $added_elements['errors']['fila'. ($key+1) .'precio_venta'] = 'La columna "precio_venta" es incorrecta';                
                }  
            }

            // Carline
            $vehicle_carline = null;
            if( !is_null($vehicle_precio_venta ) ){
                if( isset( $row['carline'] ) && strlen(trim($row['carline'])) > 0 ){                
                    $vehicle_carline = strtolower(trim($row['carline']));                                      
                }else{
                    $added_elements['errors']['fila'. ($key+1) .'carline'] = 'La columna "carline" es necesario';                
                }  
            }

            // Cilindros
            $vehicle_cilindros = null;
            if( !is_null($vehicle_carline ) ){
                if( isset( $row['cilindros'] ) && is_numeric(trim($row['cilindros'])) ){                
                    $vehicle_cilindros = strtolower(trim($row['cilindros']));                                      
                }else{
                    $added_elements['errors']['fila'. ($key+1) .'cilindros'] = 'La columna "cilindros" es necesario';                
                }  
            }

            // Color interior
            $vehicle_color_interior = null;
            if( !is_null($vehicle_cilindros ) ){
                if( isset( $row['color_interior'] ) && strlen(trim($row['color_interior'])) > 0 ){                
                    $vehicle_color_interior = strtolower(trim($row['color_interior']));                                      
                }else{
                    $added_elements['errors']['fila'. ($key+1) .'color_interior'] = 'La columna "color_interior" es necesario';                
                }  
            }

            // Color exterior
            $vehicle_color_exterior = null;
            if( !is_null( $vehicle_color_interior ) ){
                if( isset( $row['color_exterior'] ) && strlen(trim($row['color_exterior'])) > 0 ){                
                    $vehicle_color_exterior = strtolower(trim($row['color_exterior']));                                      
                }else{
                    $added_elements['errors']['fila'. ($key+1) .'color_exterior'] = 'La columna "color_exterior" es necesario';                
                }  
            }

            // Condición del vehículo 
            $vehicle_condicion = null;
            if( !is_null( $vehicle_color_exterior ) ){
                if( 
                    isset( $row['condicion_vehiculo'] ) && strlen(trim($row['condicion_vehiculo'])) > 0 && 
                    ( trim($row['condicion_vehiculo']) == 'nuevo' || trim($row['condicion_vehiculo']) == 'seminuevo' || trim($row['condicion_vehiculo']) == 'demo' )
                ){                
                    $vehicle_condicion = trim($row['condicion_vehiculo']) == 'nuevo' ? 'new' : ( trim($row['condicion_vehiculo']) == 'seminuevo' ? 'pre_owned' : 'demo') ;
                }else{
                    $added_elements['errors']['fila'. ($key+1) .'condicion_vehiculo'] = 'La columna "condicion_vehiculo" es necesario y debe ser "nuevo", "seminuevo" o "demo"';                
                }  
            }            

            // Transmision 
            $vehicle_transmision = null;
            if( !is_null( $vehicle_condicion ) ){
                if( 
                    isset( $row['transmision'] ) && strlen(trim($row['transmision'])) > 0 &&
                    ( trim($row['transmision']) == 'automatico' || trim($row['transmision']) == 'manual' || trim($row['transmision']) == 'cvt' || trim($row['transmision']) == 'triptronic' )
                ){                
                    $vehicle_transmision = strtolower(trim($row['transmision']));                                      
                }else{
                    $added_elements['errors']['fila'. ($key+1) .'transmision'] = 'La columna "transmision" es necesario y debe ser "automatico", "manual", "cvt" o "triptronic"';                
                }  
            }

            // Promoción    
            $vehicle_promocion = null;                                                 
            if( 
                isset( $row['promocion'] ) && strlen(trim($row['promocion'])) > 0
            ){                
                $vehicle_promocion = strtolower(trim($row['promocion']));                                      
            } 
            
            // Ubicación del vehículo
            $vehicle_ubicacion = null;            
            if( isset( $row['ubicacion'] ) && strlen(trim($row['ubicacion'])) > 0 ){                
                $vehicle_ubicacion = strtolower(trim($row['ubicacion']));              
            }
            // Placas 
            $vehicle_placas = null;            
            if( 
                isset( $row['placas'] ) && strlen(trim($row['placas'])) > 0 
            ){                
                $vehicle_placas = strtolower(trim($row['placas']));                                      
            }
            
            // Dias en inventario 
            $vehicle_dias_inventario = null;            
            if( 
                isset( $row['dias_inventario'] ) && is_numeric(trim($row['dias_inventario']))                    
            ){                
                $vehicle_dias_inventario = strtolower(trim($row['dias_inventario']));                                      
            }    
            
            // Kilometraje
            $vehicle_kilometraje = null;            
            if( 
                isset( $row['kilometraje'] ) && is_numeric(trim($row['kilometraje']))                    
            ){                
                $vehicle_kilometraje = strtolower(trim($row['kilometraje']));                                      
            } 
            
            // numero de llaves
            $vehicle_numero_llaves = null;            
            if( 
                isset( $row['numero_llaves'] ) && is_numeric(trim($row['numero_llaves']))                    
            ){                
                $vehicle_numero_llaves = strtolower(trim($row['numero_llaves']));                                      
            } 

            // birlos
            $vehicle_birlos = null;            
            if( 
                isset( $row['birlos'] ) && strtolower(trim($row['birlos'])) === 'si'                  
            ){                
                $vehicle_birlos = 'yes';
            } 

            // llanta de refacción
            $vehicle_llanta_refaccion = null;            
            if( 
                isset( $row['llanta_refaccion'] ) && strtolower(trim($row['llanta_refaccion'])) === 'si'                  
            ){                
                $vehicle_llanta_refaccion = 'yes';
            } 

            // gato
            $vehicle_gato = null;            
            if( 
                isset( $row['gato'] ) && strtolower(trim($row['gato'])) === 'si'                  
            ){                
                $vehicle_gato = 'yes';
            } 

            // extintor
            $vehicle_extintor = null;            
            if( 
                isset( $row['extintor'] ) && strtolower(trim($row['extintor'])) === 'si'                  
            ){                
                $vehicle_extintor = 'yes';
            } 

            // reflejantes
            $vehicle_reflejantes = null;            
            if( 
                isset( $row['reflejantes'] ) && strtolower(trim($row['reflejantes'])) === 'si'                  
            ){                
                $vehicle_reflejantes = 'yes';
            }

            // manuales
            $vehicle_manuales = null;            
            if( 
                isset( $row['manuales'] ) && strtolower(trim($row['manuales'])) === 'si'                  
            ){                
                $vehicle_manuales = 'yes';
            }

            // poliza
            $vehicle_poliza = null;            
            if( 
                isset( $row['poliza'] ) && strtolower(trim($row['poliza'])) === 'si'                  
            ){                
                $vehicle_poliza = 'yes';
            }

            // cables_corriente
            $vehicle_cables_corriente = null;            
            if( 
                isset( $row['cables_corriente'] ) && strtolower(trim($row['cables_corriente'])) === 'si'                  
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
                    $vehicle->numKeys = $vehicle_numero_llaves;

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
                        $added_elements['added']['fila'. ($key+1) .'creado'] = 'El vehículo con vin "' . $vehicle_vin .'" ha sido registrado con exito';                
                        $added_elements['total']++;
                    }                    
                }else{
                    $added_elements['exists']['fila'. ($key+1) .'existente'] = 'El vehículo con vin "' . $vehicle_vin .'" ya ha sido registrado con anterioridad';                
                }
                
            }
            
        }
        Session::flash('added_elements', $added_elements );        
    } 
    
    private function validarEmail( String $email ){
        return preg_match('#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#', trim( $email ) );
    }
}
