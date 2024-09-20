<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Check List de Valuación</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>

<body>
    <div class="container-fluid">

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <table class="table" style="font-size: 8px;">
                        <td>
                            <h2 style="font-size:  11px !important; font-weight: bold !important;">100 Puntos, Check List 
                                <span style="font-size:  10px !important; font-weight: light !important;">de Valuación y Certificación de Unidades</span>
                            </h2>
                        </td>
                        <td>
                            <img src="{{ public_path('./img/logo-text.png') }}" alt="" style="width: 50%">
                        </td>
                    </table>
                </div>

                <div class="row">
                    <table class="table" style="font-size: 8px;">
                        <td>
                            Nombre Cliente: {{ $findvin->client_sale->user->name }} {{ $findvin->client_sale->user->surname }}
                        </td>
                        <td>
                            Teléfono: {{ $findvin->client_sale->phone1 }}
                        </td>
                        <td>
                            Distribuidor: {{ $vin_check_list->distributor }}
                        </td>
                    </table>
                </div>

                <div class="row">
                    <table  class="table" style="font-size: 8px;">
                        <td>
                            Fecha de valuación: {{ $vin_check_list->valuation_date }}
                        </td>
                        <td>
                            VIN: {{ $findvin->vin }}
                        </td>
                        <td>
                            Marca: {{ $findvin->brand->name }}
                        </td>
                        <td>
                            Modelo: {{ $findvin->carmodel->name }}
                        </td>
                        <td>
                            Versión: {{ $findvin->version }}
                        </td>
                        <td>
                            Año: {{ $findvin->year }}
                        </td>
                        <!-- <td>
                            Kms: {{ $findvin->km }}
                        </td> -->
                    </table>
                </div>

                <div class="row">
                    <table class="table" style="font-size: 8px;">
                            <tr>
                                <td>
                                    Kms: {{ $findvin->km }}
                                </td>
                                <td>
                                    Color: {{ $vin_check_list->color }}
                                </td>
                                <td>
                                    Placa: 
                                </td>
                                @if( $vin_check_list->plates === null )
                                    <td> No tiene </td>
                                @else
                                    <td> {{ $vin_check_list->plates }}</td>
                                @endif
                            </tr>

                            <tr>
                                <td>
                                    Manual de Garantía:
                                </td>
                                <td> si  /  no </td>
                                
                                <td>
                                    Compra Directa:
                                </td>
                                @if( $vin_check_list->direct_purchase === 'no')
                                    <td> no </td>
                                @else
                                    <td> si </td>
                                @endif
                                
                                <td>
                                    Toma a Cuenta
                                </td>
                                @if( $vin_check_list->take_into_account === 'no')
                                    <td> no </td>
                                @else
                                    <td> si </td>
                                @endif

                                <td>
                                    Garantía Vigente:
                                </td>
                                <td> si  /  no </td>
                            </tr>
                    </table>
                </div>

                <div class="row">
                    <table class="table " style="font-size: 8px;">
                        <td>
                            <h2 style="font-size:  11px !important; font-weight: bold !important;">VERIFICACIÓN MECANICA Y ESTÉTICA</h2>
                            <h2 style="background: rgb(254, 194, 73); color: white; font-size: 9px;">REVISIÓN EXTERIOR</h2>
                            Vehículo ha sufrido modificaciones
                            <br>

                            <span style="font-weight: bold !important; " class="{{$revExt -> req1}}"></span>
                            A) Carrocería
                            <br>

                            <span style="font-weight: bold !important; " class="{{$revExt -> req2}}"></span> 
                            B) Chasis
                            <br>

                            <span style="font-weight: bold !important; " class="{{$revExt -> req3}}"></span> 
                            C) Kits deportivos 
                            <br>

                            <span style="font-weight: bold !important; " class="{{$revExt -> req4}}"></span> 
                            D) Chips de desempeño u otros 
                            <br>

                            <span style="font-weight: bold !important; " class="{{$revExt -> req5}}"></span>
                            Costado derecho y alineación de puertas 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$revExt -> req6}}"></span>
                            Costado izquierdo y alineación de puertas 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$revExt -> req7}}"></span>
                            Defensa delantera (fascia, protectores, molduras, alineación, acabado)
                            <br>
                                
                            <span style="font-weight: bold !important; " class="{{$revExt -> req8}}"></span>
                            Cofre (acabado, brisa, decoloración, granizo, pintura original, alineación) 
                            <br>
                            
                            <span style="font-weight: bold !important;" class="{{$revExt -> req9}}"></span>
                            Toldos (rieles, capote) 
                            <br>
                                
                            <span style="font-weight: bold !important; " class="{{$revExt -> req14}}"></span>
                            Defensa trasera (fascia, protectores, molduras, alineación, acabado, caja, emblemas, etc.) 
                            <br>
                                    
                            <span style="font-weight: bold !important; " class="{{$revExt -> req11}}"></span>
                            Tapa de gasolina 
                            <br>
                                    
                            <span style="font-weight: bold !important; " class="{{$revExt -> req12}}"></span>
                            Tapa de cajuela/caja/bedliner (acabado, brisa, decoloración, granizo, pintura original) 
                            <br>
                                    
                            <span style="font-weight: bold !important; " class="{{$revExt -> req13}}"></span>
                            Cajuela (llanta de refacción, herramientas, gato, red de carga, etc.) 
                            <br>

                            <span style="font-weight: bold !important; " class="{{$revExt -> req14}}"></span>
                            Rines y ruedas/cubierta de neumáticos/biseles/tapones (rasguños, picaduras) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$revExt -> req15}}"></span>
                            Cristal (golpes, rasguños, picaduras, estrellado, originalidad) 
                            <br>
                                    
                            <span style="font-weight: bold !important; " class="{{$revExt -> req16}}"></span>
                            Estribos (Fijos o eléctricos) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$revExt -> req17}}"></span>
                            Retrovisores (Espejo, carcasa) 
                            <br>
                                    
                            <span style="font-weight: bold !important; " class="{{$revExt -> req18}}"></span>
                            Antena 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$revExt -> req19}}"></span>
                            Sellos, gomas, empaques de puertas 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$revExt -> req20}}"></span>
                            Puertas/Cerraduras 
                            <br>
                                    
                            <span style="font-weight: bold !important; " class="{{$revExt -> req21}}"></span>
                            Luces exteriores (DRL, bajas, altas, freno, reversa, emergencia, direccionales, espejo) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$revExt -> req22}}"></span>
                            Alarma (operativos, a distancia) 
                            <br>
                            <br>
                            <h2 style="background: rgb(254, 194, 73); color: white; font-size: 9px; mt-2">INTERIOR</h2>

                            <span style="font-weight: bold !important; " class="{{$revInt -> iq1}}"></span>
                            Apertura remota (funcional) 
                            <br>
                                    
                            <span style="font-weight: bold !important; " class="{{$revInt -> iq2}}"></span>
                            Freno de estacionamiento 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$revInt -> iq3}}"></span>
                            Asientos, anclaje de seguridad para niños 
                            <br>
                                    
                            <span style="font-weight: bold !important; " class="{{$revInt -> iq4}}"></span>
                            Cinturones 
                            <br>
                                    
                            <span style="font-weight: bold !important; " class="{{$revInt -> iq5}}"></span>
                            Cristales 
                            <br>

                            <span style="font-weight: bold !important; " class="{{$revInt -> iq6}} "></span>
                            Quemacocos (operativos, condiciones, sin entradas de agua/aire) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$revInt -> iq7}}"></span>
                            Sistema de navegación 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$revInt -> iq8}}"></span>
                            Sistema de audio y dvd 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$revInt -> iq9}}"></span>
                            Conectividad, revisión de usb/aux/bluetooth 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$revInt -> iq14}}"></span>
                            Reloj/termómetro 
                            <br>
                                    
                            <span style="font-weight: bold !important; " class="{{$revInt -> iq11}}"></span>
                            Computadora de viaje 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$revInt -> iq12}}"></span>
                            Toma corriente(s) (operativo) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$revInt -> iq13}}"></span>
                            Luces de interior (luces de mapa, plafón, puertas, tablero, etc.) 
                            <br>

                            <span style="font-weight: bold !important; " class="{{$revInt -> iq14}}"></span>
                            Desempañador trasero (operacional) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$revInt -> iq15}}"></span>
                            Panel de instrumentos (limpieza, rasgaduras, funciones) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$revInt -> iq16}}"></span>
                            Asientos traseros/reposacabezas (operación, estado, limpieza, rasgaduras) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$revInt -> iq17}}"></span>
                            Consola/tapa del compartimiento - del/tras (funcionamiento, estado)

                        </td>
                        <td>
                            <!-- <h2 style="font-size:  11px !important; font-weight: bold !important; margin-bottom:0 !important;">Vin: {{$findbyvin->vin}}</h2> -->
                            <span>
                                <h3 style="font-size:  11px !important; font-weight: bold !important; margin-top:0 !important;">Técnico: {{$tecval->name}} {{$tecval->surname}}</h3> 
                            </span> 
                            <h2 style="background: rgb(254, 194, 73); color: white; font-size: 9px; ">MECANICA Y ELECTRICA</h2>
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq1}}"></span>
                            Escaneo de vehículo 
                            <br>
                            
                            Detectar códigos de motor
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq2}}"></span>
                            Sensores (sensores reversa, punto ciego, proximidad, etc.) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq3}}"></span>
                            Medidores/tonos de aviso 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq4}}"></span>
                            Encendido y estabilidad motor 
                            <br>
                            
                            <span style="font-weight: bold !important; " class=" {{$mecElec -> meq5}}"></span>
                            Funcionamiento del motor/desempeño/aceleración 
                            <br>
                        
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq6}}"></span>
                            Transmisión automático/manual (funcionamiento correcto) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq7}}"></span>
                            Control de tracción (operación) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq8}}"></span>
                            Frenos/abs (operación, sensación pedal, función ABS) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq9}}"></span>
                            Dirección/alineación y balanceo (a 80 km/operación, ruidos, vibración) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq10}}"></span>
                            Chasis/alineación (ruido/vibración/aspereza) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq11}}"></span>
                            Caja de transferencia (operación, F/RWD, 4W, AWD) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq12}}"></span>
                            Control de crucero (aspera, aceleración, desaceleración, cancelar) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq13}}"></span>
                            Velocímetro/tacómetro y odómetro 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq14}}"></span>
                            Calentador/aire acondicionado (soplador, controles, eficiencia) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq15}}"></span>
                            Volante de dirección telescópico y de altura 
                            <br>

                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq16}}"></span>
                            Claxon 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq17}}"></span>
                            Limpiaparabrisas/chisgueteros y plumas (Estado físico y funcionamiento) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq18}}"></span>
                            Ajustes de pedales/volante 
                            <br>

                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq19}}"></span>
                            Inspección visual (fugas evidentes, piezas o estampas faltantes) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq20}}"></span>
                            Sistema de enfriamiento del motor/radiador/mangueras (fugas)
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq21}}"></span>
                            Sistema de dirección (bomba, cremallera, columna, motor, fugas, operación)
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq22}}"></span>
                            Sistema eléctrico (batería, alternador, arnés, cables, computadoras, etc.)
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq23}}"></span>
                            Sistema de frenos (cilindro, bomba, booster de freno, líneas, calipers, discos)
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq24}}"></span>
                            Sistema de encendido (marcha, bujías, bobinas, condición de enrutamiento)
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq25}} "></span>
                            Sistema de combustible (bomba, líneas, fugas, conexiones, funcionamiento)
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq26}}"></span>
                            Compresor a/ac (polea, correa, funcionamiento, eficiencia, controles)
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq27}}"></span>
                            Inspección de filtros
                            <br>

                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq28}}"></span>
                            Inspección de mangueras
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq29}}"></span>
                            Inspección de bandas
                            <br>
                            
                            <span style="font-weight: bold !important; " class=" {{$mecElec -> meq30}}"></span>
                            Prueba de batería
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq31}} "></span>
                            Prueba de compresión/fugas y degradación de aceite motor
                            <br>
                            
                            <span style="font-weight: bold !important; " class=" {{$mecElec -> meq32}}"></span>
                            Verificar estado de catalizador/sensores de oxígeno/emisiones
                            <br>
                            
                            <span style="font-weight: bold !important; " class=" {{$mecElec -> meq33}}"></span>
                            Prueba de eficiencia de a/ac y carga si es necesario
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq34}}"></span>
                            Visual (cuerpo, parte inferior del cuerpo, debajo de la carrocería)
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq35}}"></span>
                            Marco (signos de reparación/daños)
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq36}} "></span>
                            Sistema de escape sin daños
                            <br>
                            Pastillas de freno, balatas (condición)
                            <br>

                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq37}} "></span>
                            espesor > 6mm frenos disco > 2mm tambor de espesor
                            <br>

                            Derecha Delantera (DD) <span style="font-weight: bold !important; ">{{$mecElec -> breakedd}})</span> mm 
                            <br>
                            
                            Izquierda Delantera (ID) <span style="font-weight: bold !important; ">{{$mecElec -> breakeid}})</span> mm
                            <br>
                            
                            Izquierda Trasera (IT) <span style="font-weight: bold !important; ">{{$mecElec -> breakeit}})</span> mm 
                            <br>
                            
                            Derecha Trasera (DT) <span style="font-weight: bold !important; ">{{$mecElec -> breakedt}})</span> mm
                            <br>
                        
                        </td>
                    </table>
                </div>

                <div class="row">
                    <table class="table " style="font-size: 8px;">
                        <td>
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq38}} "></span>
                            Discos, pinzas, calipers tambores (condición y dimensiones, rectificar si es necesario) 
                            <br>

                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq39}}"></span>
                            Freno hidráulico (nivel, líneas, mangueras)
                            <br>
                            
                            Neumáticos.
                            <br>
                                    
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq40}}"></span>
                            Profundidad 8/32 o mayor, marca, tipo, tamaño, IGUALES las 5
                            <br>
                                
                            Profundidad Derecha Delantera (DD) <span style="font-weight: bold !important; ">{{$mecElec -> depthdd}})</span> mm 
                            <br>
                            
                            Profundidad Izquierda Delantera (ID) <span style="font-weight: bold !important; ">{{$mecElec -> depthid}})</span> mm 
                            <br>
                            
                            Profundidad Izquierda Trasera (IT) <span style="font-weight: bold !important; ">{{$mecElec -> depthit}})</span> mm 
                            <br>
                            
                            Profundidad Delantera Trasera (DT) <span style="font-weight: bold !important; ">{{$mecElec -> depthdt}})</span> mm 
                            <br>

                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq41}}"></span>
                            Ruedas de acero o aleación originales según modelo y versión 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq42}}"></span>
                            Amortiguadores (operación, fugas, etc.) 
                            <br>
                            
                            <span style="font-weight: bold !important;  " class="{{$mecElec -> meq43}}"></span>
                            Resorte/barras estabilizadoras. 
                            <br>

                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq44}}"></span>
                            Soportes motor/caja/escape (condición, montaje, bujes) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq45}} "></span>
                            Dirección/enlace (la barra de dirección/terminales, la articulación) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq46}} "></span>
                            Compartimiento del motor (acabado, el aislamiento, las calcomanías) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq47}}"></span>
                            Motor (condición, funcionamiento, sin fugas ni golpes) 
                            <br>

                            <span style="font-weight: bold !important; " class=" {{$mecElec -> meq48}}"></span>
                            Transmisión (condición, funcionamiento, sin fugas ni golpes) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq49}}"></span>
                            Caja de transferencia (condición, funcionamiento, sin fugas ni golpes) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$mecElec -> meq50}}"></span>
                            Montaje, ejes (condición, funcionamiento, sin fugas y golpes) 
                            <br>
                            
                            <span style="font-weight: bold !important; " class=" {{$mecElec -> meq51}}"></span>
                            Diferencial (condición, funcionamiento, sin fugas y golpes) 
                            <br>
                            <br>

                            <h2 style="background: rgb(254, 194, 73); color: white; font-size: 9px;">CERTIFICACIÓN DE VEHÍCULO</h2>
                            <span style="font-weight: bold !important; " class="{{$cert -> cvq1}}"></span>
                            Manual de propietario 
                            <br>

                            <span style="font-weight: bold !important; " class=" {{$cert -> cvq2}}"></span>
                            Campañas abiertas
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$cert -> cvq3}}  "></span> 
                            El vehículo es certificable
                            <br>
                            
                            <span style="font-weight: bold !important; " class=" {{$cert -> cvq4}}"></span>
                            Fecha de último mantenimiento
                            <br>

                            <span style="font-weight: bold !important; " class="{{$cert -> cvq5}} "></span>
                            Detallado exterior e interior
                            <br>
                            
                            <span style="font-weight: bold !important; " class=" {{$cert -> cvq6}}"></span>
                            Documentación completa
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$cert -> cvq7}} "></span>
                            Prueba de estado de salud de la batería
                            <br>
                            
                            <span style="font-weight: bold !important; " class=" {{$cert -> cvq8}}"></span>
                            Realizar campañas abiertas
                            <br>
                            
                            <span style="font-weight: bold !important; " class="{{$cert -> cvq9}}"></span>
                            Cambio de aceite de motor y filtro (monitor reestablecer la vida del aceite)
                            <br>
                            
                            <span style="font-weight: bold !important; " class=" {{$cert -> cvq11}}"></span>
                            Inspeccionar/cambiar filtros (de acerdo al programa del fabricante)
                            <br>
                            
                            <span style="font-weight: bold !important; " class=" {{$cert -> cvq12}}"></span>
                            Inspeccionar y poner a nivel todos los fluídos
                            <br>
                            <br>

                            <span style="font-weight: bold !important;  "></span>CÓDIGOS DE REFERENCIA:</span>
                            <br>
                            
                            <span style="font-weight: bold !important;">
                                <span class="a1"> </span> 
                                Inspección Realizada
                                <span class="a2"> </span>
                                Requiere Servicio
                                <span class="a3"> </span> 
                                N/A
                            </span>
                        </td>
                        <td>
                        </td>
                    </table>
                </div>
            </div>
        </div>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>
<br>

        <div class="row">
            <table class="table " style="font-size: 8px;">
                <td>
                    <br>
                    <br>
                    <span style="font-size:  10px !important; font-weight: bold !important;  ">__________________________________</span>
                    <br>
                    
                    <span style="font-size:  10px !important; font-weight: bold !important;  ">TECNICO CERTIFICADO POR GM</span>
                    <br>
                    
                </td>
                
                <td>
                    <br>
                    <br>
                    <span style="font-size:  10px !important; font-weight: bold !important;  ">_______________________________</span>
                    <br>
                    
                    <span style="font-size:  10px !important; font-weight: bold !important;  ">GERENTE DE SEMINUEVOS</span>
                    <br>
                </td>
                
                <td>
                    <br>
                    <br>
                    <span style="font-size:  10px !important; font-weight: bold !important;  ">_______________________________</span>
                    <br>
                    
                    <span style="font-size:  10px !important; font-weight: bold !important;  ">VALUADOR - COMPRADOR</span>
                    <br>
                </td>
            </table>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <table class="table " style="font-size: 8px;">
 
                        <!-- <tbody>
                            <tr> -->
                                <td>
                                    
                                    <h3 style="font-size:  11px !important; font-weight: bold !important; margin-top:0 !important;">
                                        COTIZACIÓN
                                    </h3> 
                                    
                                    <span style="font-size:  10px !important; font-weight: bold !important;  ">Referencia libro:</span>
                                    <br> 

                                    <span style="font-weight: bold !important;  "></span>Toma: $ {{ $vin_check_list->take }}</span>
                                    <br>
                                    
                                    <span style="font-weight: bold !important;  "></span>Venta: $ {{ $vin_check_list->sale }}</span>
                                    <br>

                                    <span style="font-size:  10px !important; font-weight: bold !important;  ">Referencia intelimotors:</span>
                                    <br>

                                    <span style="font-weight: bold !important;  "></span>Baja: $ {{ $vin_check_list->take_intelimotors }}</span>
                                    <br>
                                    
                                    <span style="font-weight: bold !important;  "></span>Alta: $ {{ $vin_check_list->sale_intelimotors }}</span>
                                    <br>
                                    <span style="font-size:  10px !important; font-weight: bold !important;  ">Reacondicionamiento:</span>
                                    <br> 

                                    <span style="font-weight: bold !important;  "></span>Mano de obra: $ {{ $vin_check_list->workforce }}</span>
                                    <br>
                                    <br>

                                </td>

                                <td>
                                    <span style="font-size:  10px !important; font-weight: bold !important;  ">COMENTARIOS:</span>
                                    <br> 

                                    <!-- <span style="font-weight: bold !important;  "></span>{{ $revExt->commentary }}</span> -->
                                    <span style="font-weight: bold !important;  "></span>{{ $mecElec->commentaryMechanical }}</span>
                                    <br>
                                    <span style="font-weight: bold !important;  "></span>{{ $revExt->commentary }}</span>
                                    <br>
                                    <span style="font-weight: bold !important;  "></span>{{ $vin_check_list->comments }}</span>
                                    <br>
                                </td>
                            <!-- </tr>

                        </tbody> -->
                    </table>
                </div>
            </div>
        </div>

        <div class="row">
            <table class="table" style="font-size: 8px;">
                <td>
                    <table style="font-size: 8px;">
                        <tr>
                            <td>Original</td>
                            <td></td>
                            <td></td>
                            <td>Genérica</td>
                            <td></td>
                            <td></td>
                            <td>Usada/Reparada</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Nom Refacción</td>
                            <td>Precio</td>
                            <td>Cantidad</td>
                            <td>Nom Refacción</td>
                            <td>Precio</td>
                            <td>Cantidad</td>
                            <td>Nom Refacción</td>
                            <td>Precio</td>
                            <td>Cantidad</td>
                        </tr>
                        @foreach($sparePart as $spare)
                            <tr>
                                <td>{{ $spare->name }}</td>
                                <td>{{ $spare->priceOriginal }}</td>
                                <td>{{ $spare->amount }}</td>
                                <td>{{ $spare->name }}</td>
                                <td>{{ $spare->priceGeneric }}</td>
                                <td>{{ $spare->amount }}</td>
                                <td>{{ $spare->name }}</td>
                                <td>{{ $spare->priceUsed }}</td>
                                <td>{{ $spare->amount }}</td>
                            </tr>
                        @endforeach
                    </table>

                    <!-- <span style="font-weight: bold !important;  "></span>Partes/refacciones Originales: $ {{ $vin_check_list->spare_parts }}</span> -->
                    <span style="font-weight: bold !important;  "></span>Partes/refacciones Originales: $ {{ $sumOriginal }}</span>
                    <br>
                    
                    <span style="font-weight: bold !important;  "></span>Partes/refacciones Genéricas: $ {{ $sumGeneric }}</span>
                    <br>
                    
                    <span style="font-weight: bold !important;  "></span>Partes/refacciones Usadas: $ {{ $sumUsed }}</span>
                    <br>
                    
                    <span style="font-weight: bold !important;  "></span>HyP: $ {{ $vin_check_list->hyp }}</span>
                    <br>
                    
                    <span style="font-weight: bold !important;  "></span>Total: $ {{ $vin_check_list->total }}</span>
                    <br>
                    <span style="font-size:  10px !important; font-weight: bold !important;  ">Toma y Oferta Final:</span>
                    <br>
                    <span style="font-weight: bold !important;  "></span>Valor toma: $ {{ $vin_check_list->take_value }}</span>
                    <br>
                    
                    <span style="font-weight: bold !important;  "></span>Oferta final: $ {{ $vin_check_list->final_offer }}</span>
                    <br>
                </td>
            </table>
        </div>


    </div>
</body>

</html>

<style>
    .a2:after {
        font-weight: bold !important;
        color: red;
        content: 'x';
    }

    .a1:after {
        font-weight: bold !important;
        color: green;
        content: '✓'
    }

    .a3:after {
        font-weight: bold !important;
        color: gray;
        content: 'n/a'
    }

    .bb {

        border-top-style: solid;
        border-top-color: gray;
    }

    body {
        font-family: DejaVu Sans, sans-serif;
    }

</style>
