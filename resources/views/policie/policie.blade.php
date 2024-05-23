<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        * {
            font-family: 'Lato', sans-serif;
        }

    </style>

    <title>Poliza</title>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td class="w-50">

                    <div>SERVICIO BRINDADO POR:</div>
                    <div> AUTOMOTRIZ BALDERRAMA PUEBLA S.A DE CV</div>
                </td>

                <td class="w-50">
                    <div><span class="bold">CERTIFICADO DE GARANTÍA "AB" VEHÍCULOS SEMINUEVOS</span></div>
                    <div>
                        <p> Covertura para autos seminuevos hasta con 10 años o 150 000 kms.Sin limites de eventos por
                            14 meses, limite de la covertura 35% del valor comercial
                            de la unidad, en uno o varios eventos.</p>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="w-50">
                    <div>Fecha de Firma: {{$policie-> signature_date}}</div>
                    <div> Lugar de Firma:{{$policie-> signature_place}} </div>
                </td>
                <td class="w-50">
                    <div> Vigencia de Garantía: {{$policie->warranty_period}} </div>
                    <div> Id Garantía: {{$policie->id_warranty}}</div>
                </td>
            </tr>
        </table>

        <table class="items-table mt" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th class="left ">VIGENCIA</th>
                    <th> </th>
                </tr>
            </thead>
            <tr class="item">
                <td class=" ">Fecha de Inicio G M: {{$policie->start_date_gm}}</td>
                <td class="">Fecha de fin de G de Marca: {{$policie->ending_date_gm}}</td>
            </tr>
            <tr class="item">
                <td class="">Fecha de Inicio G E: {{$policie->start_date_ge}}</td>
                <td class="">Fecha de Fin de G Extendida: {{$policie->ending_date_ge}}</td>
            </tr>
        </table>
        <table class="items-table mt" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th class="left">DATOS DEL COMPRADOR</th>
                    <th> </th>
                </tr>
            </thead>
            <tr class="item">
                <td class=" ">Nombre: {{$user->name}} {{$user->surname}}</td>
                <td class="">Dirección: {{$client->address}}</td>
            </tr>
            <tr class="item">
                <td class="">Municipio: {{$client->municipality}}</td>
                <td class="">RFC: {{$client->rfc}}</</td>
            </tr>
            <tr class="item">
                <td class="">Estado: {{$client->state}}</td>
                <td class="">Teléfono: {{$client->phone1}}</td>
            </tr>
            <tr class="item">
                <td class="">CP: {{$client->cp}}</td>
                <td class="">Correo: {{$user->email}}</td>
            </tr>

        </table>

        <table class="items-table mt" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th class="left ">DATOS DEL VEHÍCULO</th>
                    <th> </th>
                </tr>
            </thead>
            <tr class="item">
                <td class=" ">Marca: {{$marca->name}}</td>
                <td class="">Kilometraje: {{$vehicle->km}}</td>
            </tr>
            <tr class="item">
                <td class="">Modelo: {{$vehicle->carmodel->name}}</td>
                <td class="">Cilindros: {{$vehicle->cylinders}}</td>
            </tr>
            <tr class="item">
                <td class="">Año Modelo: {{$vehicle->yearModel}}</td>
                <td class="">Mátricula: {{$vehicle->plates}}</td>
            </tr>
            <tr class="item">
                <td class="">Vin: {{$vehicle->vin}}</td>
                <td class="">KM ULT SERV: {{$policie->km_last_service}} Km</td>
            </tr>
            <tr class="item">
                <td class="">KM PROX SERV: {{$policie->km_next_service}} Km</td>
                <td class=""></td>
            </tr>
        </table>
        <p> Los firmantes declaran haber leido los términos y condiciones delconvenio de servicios mecanica
            automotriz, así como los datos que conforman el certificado de garantía vehículos seminuevos estando en
            total entendimiento
            y aceptación de los mismos
        </p>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td class="w-50">
                    <div> <span >Empresa </span> </div>
 
                </td>
                <td class="w-50">
                    <div> <span>Comprador </span> </div>

                 </td>
            </tr>


            <tr >
                <td class="w-50 bb">
                     <div >  <span class="mybox"> Nombre y Firma Autorizada</span> </div>

                </td>
                <td class="w-50 bb">
 
                    <div  > <span class="mybox"> Leyenda, "He leido y Aceptado" , Firma y Fecha</span> </div>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>


<style>
    .invoice-box {
        background-color: #fff;
        color: #2a2a2a;
        height: auto;
        margin: 0 auto;
        max-width: 21.5cm;
    }

    .invoice-box table {
        width: 100%;
        line-height: inherit;
        text-align: left;
    }

    .items-table td {
        padding: 5px;
        vertical-align: top;
        border-bottom: 1px solid #eee;
    }

    .items-table th {
        padding: 5px;
        background: #eee;
        border-bottom: 1px solid #ddd;
        font-weight: bold;
    }

    .items-table .total {
        border-top: 2px solid #eee;
        font-weight: bold;
        text-align: right;
    }

    .w-50 {
        width: 50%;
    }
    .mybox {
        padding: 1rem 0;
        border-top-style: solid;
        border-top-color: #000;
    }


    .bold {
        font-weight: bold;
    }

    .text-right {
        text-align: right;
    }

    .text-center {
        text-align: center;
    }

    .left {
        text-align: left;
    }

    .options {
        padding: 1rem 0;
        text-align: center;
    }



    @media print {
        .invoice-box {
            margin: 0;
            padding: 0;
        }

        .options {
            display: none;
        }
    }

    @page {
        margin: 0.8cm;
    }

    body {
        font-size: 10px !important;
    }
    .bb{
        padding-top: 10px !important;
    }

</style>
