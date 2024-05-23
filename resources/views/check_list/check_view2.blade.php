<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PDF Check List de Valuación</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
    integrity="sha384-9aIt2nRpC12Uk9gS9baDl412NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
  </script>

</head>

<body>
  <div class="container-fluid">

    <div class="row py-1">
      <div class="col-md-6 col-sm-6">
        <img src='https://abcars.mx/logos/logo.png' style="width: 50%; ">
      </div>
    </div>


    <div class="row mt-2">
      <div class="col-md-6 col-sm-12">
        <h2 style="font-size: 14px !important; font-weight: bold !important;">VERIFICACIÓN
          MECANICA Y ESTÉTICA <br>Vin:
          {{$findbyvin->vin}}</h2>
      </div>
      <div class="col-md-6 col-sm-12">
        <h2 style="font-size: 14px !important; font-weight: bold !important;">CÓDIGO DE COLORES:</h2>

        <div class="row">
 
          <div class="col-md-3 col-sm-6">
            <span class="a3">&#9608 </span> Si
          </div>
          <div class="col-md-3 col-sm-6">
            <span class="a2">&#9608 </span>
            No
          </div>
          <div class="col-md-3 col-sm-6">
            <span class="a4">&#9608 </span> N/A </span>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-2">
      <div class="col-md-6 col-sm-12">
        <h2 style="background: rgb(254, 194, 73); color: white; font-size: 12px;">
          REVISIÓN EXTERIOR
        </h2>
        Vehículo ha sufrido modificaciones <br>
        <span style="font-weight: bold !important;    font-size: 12px; " class="{{$revExt -> req1}}">
          &#9608; </span>A) Carrocería
        <br>
        <span style="font-weight: bold !important;    font-size: 12px; " class="{{$revExt -> req2}}">
          &#9608;</span> B)
        Chasis<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revExt -> req3}}">&#9608;</span> C)
        Kits deportivos <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revExt -> req4}}">&#9608;</span> D)
        Chips de desempeño u otros <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revExt -> req5}}">&#9608;</span>
        Costado derecho y alineación de puertas <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revExt -> req6}}">&#9608;</span>
        Costado izquierdo y alineación de puertas <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revExt -> req7}}">&#9608;</span>
        Defensa delantera (fascia, protectores, molduras, alineación, acabado) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revExt -> req8}}">&#9608;</span>
        Cofre (acabado, brisa, decoloración, granizo, pintura original, alineación) <br>
        <span style="font-weight: bold !important;    font-size: 12px;"
          class="{{$revExt -> req9}}">&#9608;</span>
        Toldos (rieles, capote) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revExt -> req14}}">&#9608;</span>
        Defensa trasera (fascia, protectores, molduras, alineación, acabado, caja, emblemas,
        etc.) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revExt -> req12}}">&#9608;</span>
        Tapa de gasolina <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revExt -> req12}}">&#9608;</span>
        Tapa de cajuela/caja/bedliner (acabado, brisa, decoloración, granizo, pintura
        original) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revExt -> req13}}">&#9608;</span>
        Cajuela (llanta de refacción, herramientas, gato, red de carga, etc.) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revExt -> req14}}">&#9608;</span>
        Rines y ruedas/cubierta de neumáticos/biseles/tapones (rasguños, picaduras) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revExt -> req15}}">&#9608;</span>
        Cristal (golpes, rasguños, picaduras, estrellado, originalidad) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revExt -> req16}}">&#9608;</span>
        Estribos (Fijos o eléctricos) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revExt -> req17}}">&#9608;</span>
        Retrovisores (Espejo, carcasa) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revExt -> req18}}">&#9608;</span>
        Antena <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revExt -> req19}}">&#9608;</span>
        Sellos, gomas, empaques de puertas <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revExt -> req20}}">&#9608;</span>
        Puertas/Cerraduras <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revExt -> req21}}">&#9608;</span>
        Luces exteriores (DRL, bajas, altas, freno, reversa, emergencia, direccionales,
        espejo) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revExt -> req22}}">&#9608;</span>
        Alarma (operativos, a distancia) <br><br>
        <h2 style="background: rgb(254, 194, 73); color: white; font-size: 12px;">
          INTERIOR
        </h2>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revInt -> iq1}}">&#9608;</span>
        Apertura remota (funcional) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revInt -> iq2}}">&#9608;</span>
        Freno de estacionamiento <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revInt -> iq3}}">&#9608;</span>
        Asientos, anclaje de seguridad para niños <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revInt -> iq4}}">&#9608;</span>
        Cinturones <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revInt -> iq5}}">&#9608;</span>
        Cristales <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revInt -> iq6}} ">&#9608;</span>
        Quemacocos (operativos, condiciones, sin entradas de agua/aire) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revInt -> iq7}}">&#9608;</span>
        Sistema de navegación <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revInt -> iq8}}">&#9608;</span>
        Sistema de audio y dvd <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revInt -> iq9}}">&#9608;</span>
        Conectividad, revisión de usb/aux/bluetooth <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revInt -> iq14}}">&#9608;</span>
        Reloj/termómetro <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revInt -> iq12}}">&#9608;</span>
        Computadora de viaje <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revInt -> iq12}}">&#9608;</span>
        Toma corriente(s) (operativo) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revInt -> iq13}}">&#9608;</span>
        Luces de interior (luces de mapa, plafón, puertas, tablero, etc.) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revInt -> iq14}}">&#9608;</span>
        Desempañador trasero (operacional) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revInt -> iq15}}">&#9608;</span>
        Panel de instrumentos (limpieza, rasgaduras, funciones) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revInt -> iq16}}">&#9608;</span>
        Asientos traseros/reposacabezas (operación, estado, limpieza, rasgaduras) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$revInt -> iq17}}">&#9608;</span>
        Consola/tapa del compartimiento - del/tras (funcionamiento, estado) <br><br>
        <h2 style="background: rgb(254, 194, 73); color: white; font-size: 12px; ">
          MECANICA Y ELECTRICA
        </h2>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq1}}">&#9608;</span>
        Escaneo de vehículo <br>
        Detectar códigos de motor
        <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq2}}">&#9608;</span>
        Sensores (sensores reversa, punto ciego, proximidad, etc.) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq3}}">&#9608;</span>
        Medidores/tonos de aviso <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq4}}">&#9608;</span>
        Encendido y estabilidad motor <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class=" {{$mecElec -> meq5}}">&#9608;</span>
        Funcionamiento del motor/desempeño/aceleración <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq6}}">&#9608;</span>
        Transmisión automático/manual (funcionamiento correcto) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq7}}">&#9608;</span>
        Control de tracción (operación) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq8}}">&#9608;</span>
        Frenos/abs (operación, sensación pedal, función ABS) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq9}}">&#9608;</span>
        Dirección/alineación y balanceo (a 80 km/operación, ruidos, vibración) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq10}}">&#9608;</span>
        Chasis/alineación (ruido/vibración/aspereza) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq12}}">&#9608;</span>
        Caja de transferencia (operación, F/RWD, 4W, AWD) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq12}}">&#9608;</span>
        Control de crucero (aspera, aceleración, desaceleración, cancelar) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq13}}">&#9608;</span>
        Velocímetro/tacómetro y odómetro <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq14}}">&#9608;</span>
        Calentador/aire acondicionado (soplador, controles, eficiencia) <br>
        <span style="font-weight: bold !important;    font-size: 12px; " class="{{$mecElec -> meq15}}">
          &#9608;</span>
        Volante de dirección telescópico y de altura <br>
      </div>
      <div class="col-md-6 col-sm-12">
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq16}}">&#9608;</span>
        Claxon <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq17}}">&#9608;</span>
        Limpiaparabrisas/chisgueteros y plumas (Estado físico y funcionamiento) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq18}}">&#9608;</span>
        Ajustes de pedales/volante <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq19}}">&#9608;</span>
        Inspección visual (fugas evidentes, piezas o estampas faltantes) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq20}}">&#9608;</span>
        Sistema de enfriamiento del motor/radiador/mangueras (fugas)<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq21}}">&#9608;</span>
        Sistema de dirección (bomba, cremallera, columna, motor, fugas, operación)<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq22}}">&#9608;</span>
        Sistema eléctrico (batería, alternador, arnés, cables, computadoras, etc.)<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq23}}">&#9608;</span>
        Sistema de frenos (cilindro, bomba, booster de freno, líneas, calipers, discos)<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq24}}">&#9608;</span>
        Sistema de encendido (marcha, bujías, bobinas, condición de enrutamiento)<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq25}} ">&#9608;</span>
        Sistema de combustible (bomba, líneas, fugas, conexiones, funcionamiento)<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq26}}">&#9608;</span>
        Compresor a/ac (polea, correa, funcionamiento, eficiencia, controles)<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq27}}">&#9608;</span>
        Inspección de filtros<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq28}}">&#9608;</span>
        Inspección de mangueras<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq29}}">&#9608;</span>
        Inspección de bandas<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class=" {{$mecElec -> meq30}}">&#9608;</span>
        Prueba de batería<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq31}} ">&#9608;</span>
        Prueba de compresión/fugas y degradación de aceite motor<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class=" {{$mecElec -> meq32}}">&#9608;</span>
        Verificar estado de catalizador/sensores de oxígeno/emisiones<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class=" {{$mecElec -> meq33}}">&#9608;</span>
        Prueba de eficiencia de a/ac y carga si es necesario<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq34}}">&#9608;</span>
        Visual (cuerpo, parte inferior del cuerpo, debajo de la carrocería)<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq35}}">&#9608;</span>
        Marco (signos de reparación/daños)<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq36}} ">&#9608;</span>
        Sistema de escape sin daños
        <br>
        Pastillas de freno, balatas (condición)
        <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq37}} ">&#9608;</span>
        espesor > 6mm frenos disco > 2mm tambor de espesor
        <br>
        Derecha Delantera (DD) <span style="font-weight: bold !important;    font-size: 12px; ">
          {{$mecElec -> breakedd}})</span> mm <br>
        Izquierda Delantera (ID) <span
          style="font-weight: bold !important;    font-size: 12px; ">{{$mecElec -> breakeid}})</span>
        mm <br>
        Izquierda Trasera (IT) <span
          style="font-weight: bold !important;    font-size: 12px; ">{{$mecElec -> breakeit}})</span>
        mm <br>
        Derecha Trasera (DT) <span
          style="font-weight: bold !important;    font-size: 12px; ">{{$mecElec -> breakedt}})</span>
        mm <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq38}} ">&#9608;</span>
        Discos, pinzas, calipers tambores (condición y dimensiones, rectificar si es
        necesario) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq39}}">&#9608;</span>
        Freno hidráulico (nivel, líneas, mangueras)
        <br>
        Neumáticos.
        <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq40}}">&#9608;</span>
        Profundidad 8/32 o mayor, marca, tipo, tamaño, IGUALES las 5
        <br>
        Profundidad Derecha Delantera (DD) <span
          style="font-weight: bold !important;    font-size: 12px; ">{{$mecElec -> depthdd}})</span>
        mm <br>
        Profundidad Izquierda Delantera (ID) <span
          style="font-weight: bold !important;    font-size: 12px; ">{{$mecElec -> depthid}})</span>
        mm <br>
        Profundidad Izquierda Trasera (IT) <span
          style="font-weight: bold !important;    font-size: 12px; ">{{$mecElec -> depthit}})</span>
        mm <br>
        Profundidad Delantera Trasera (DT) <span
          style="font-weight: bold !important;    font-size: 12px; ">{{$mecElec -> depthdt}})</span>
        mm <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq41}}">&#9608;</span>
        Ruedas de acero o aleación originales según modelo y versión <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq42}}">&#9608;</span>
        Amortiguadores (operación, fugas, etc.) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq43}}">&#9608;</span>
        Resorte/barras estabilizadoras. <br>
        <!-- es un titulo  se complementa con otra pregunta  de abajo -->
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq44}}">&#9608;</span>
        Soportes motor/caja/escape (condición, montaje, bujes) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq45}} ">&#9608;</span>
        Dirección/enlace (la barra de dirección/terminales, la articulación) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq46}} ">&#9608;</span>
        Compartimiento del motor (acabado, el aislamiento, las calcomanías) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq47}}">&#9608;</span>
        Motor (condición, funcionamiento, sin fugas ni golpes) <br>
        <span style="font-weight: bold !important;    font-size: 12px; " class=" {{$mecElec -> meq48}}">
          &#9608;</span>
        Transmisión (condición, funcionamiento, sin fugas ni golpes) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq49}}">&#9608;</span>
        Caja de transferencia (condición, funcionamiento, sin fugas ni golpes) <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$mecElec -> meq50}}">&#9608;</span>
        Montaje, ejes (condición, funcionamiento, sin fugas y golpes) <br>
        <span style="font-weight: bold !important;    font-size: 12px; " class=" {{$mecElec -> meq51}}">&#9608;
        </span>
        Diferencial (condición, funcionamiento, sin fugas y golpes) <br> <br>
        <h2 style="background: rgb(254, 194, 73); color: white; font-size: 12px;">
          CERTIFICACIÓN DE VEHÍCULO
        </h2>
        <span style="font-weight: bold !important;    font-size: 12px; " class="{{$cert -> cvq1}}">
          &#9608;</span>
        Manual de propietario <br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class=" {{$cert -> cvq2}}">&#9608;</span>
        Campañas abiertas<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$cert -> cvq3}}  ">&#9608;</span> El
        vehículo es certificable<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class=" {{$cert -> cvq4}}">&#9608;</span>
        Fecha de último mantenimiento<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$cert -> cvq5}} ">&#9608;</span>
        Detallado exterior e interior<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class=" {{$cert -> cvq6}}">&#9608;</span>
        Documentación completa<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$cert -> cvq7}} ">&#9608;</span>
        Prueba de estado de salud de la batería<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class=" {{$cert -> cvq8}}">&#9608;</span>
        Realizar campañas abiertas<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class="{{$cert -> cvq9}}">&#9608;</span>
        Cambio de aceite de motor y filtro (monitor reestablecer la vida del aceite)<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class=" {{$cert -> cvq11}}">&#9608;</span>
        Inspeccionar/cambiar filtros (de acerdo al programa del fabricante)<br>
        <span style="font-weight: bold !important;    font-size: 12px; "
          class=" {{$cert -> cvq12}}">&#9608;</span>
        Inspeccionar y poner a nivel todos los fluídos<br> <br>
        <br> <br>
      </div>
    </div>
  </div>
</body>

</html>

<style>
  .a1 {
    color: violet;
  }

  .a2 {
    color: red;
  }

  .a3 {
    color: green;
  }

  .a4 {
    color: gray;
  }
</style>