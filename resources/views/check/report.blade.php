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

    <title>Detalles de vehiculos</title>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr>
                <td class="w-50">
                    <img  src='https://abcars.mx/logos/logo.png' style=" width: 200px; display: left;">
                </td>
                <td class="w-50">
                    <div>
                        <h2 class="bold">REPORTE DE DETALLES</h2>
                    </div>


                </td>
            </tr>
        </table>


        @foreach( $vehicles as $vehicle )

        <h2> Auto: {{ $vehicle ->name }} <br> Vin: {{ $vehicle ->vin }}</h2>

        @foreach( $vehicle ->checks as $check )

        @if($check ->category == "interior")

        <table class="items-table mt" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th class="left ">INTERIOR</th>
                    <th> </th>
                </tr>
            </thead>
            <tr class="item">
                <td class="w-50 "><p>{{$check ->comment}} </p> </td>
                <td class=""><img style=" width: 300px;" src="https://sandbox.abcars.mx/abcars-backend/api/check_images/{{$check ->path}}">
                </td>
            </tr>

        </table>

 
        @elseif($check ->category == "bodywork")

        <table class="items-table mt" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th class="left">CARROCERIA</th>
                    <th> </th>
                </tr>
            </thead>
            <tr class="item">
                <td class=" w-50"><p>{{$check ->comment}} </p> </td>
                <td class=""><img style=" width: 300px;"
                        src="https://sandbox.abcars.mx/abcars-backend/api/check_images/{{$check ->path}}">
                </td>
            </tr>
        </table>

 
        @elseif($check ->category == "electric")

        <table class="items-table mt" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th class="left ">ELECTRICO</th>
                    <th> </th>
                </tr>
            </thead>
            <tr class="item">
                <td class="w-50"><p>{{$check ->comment}} </p> </td>
                <td class=""><img style=" width: 300px;"
                        src="https://sandbox.abcars.mx/abcars-backend/api/check_images/{{$check ->path}}">
                </td>
            </tr>
        </table>

 
        @elseif($check ->category == "transmission")

        <table class="items-table mt" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th class="left ">TRANSMISIÓN</th>
                    <th> </th>
                </tr>
            </thead>

            <tr class="item">
                <td class=" w-50"><p>{{$check ->comment}} </p> </td>
                <td class=""><img style=" width: 300px;"
                        src="https://sandbox.abcars.mx/abcars-backend/api/check_images/{{$check ->path}}">
                </td>
            </tr>
        </table>

 
        @elseif($check ->category == "motor")

        <table class="items-table mt" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th class="left ">MOTOR</th>
                    <th> </th>
                </tr>
            </thead>

            <tr class="item">
                <td class="w-50 "><p>{{$check ->comment}} </p> </td>
                <td class=""><img style=" width: 300px;" src="https://sandbox.abcars.mx/abcars-backend/api/check_images/{{$check ->path}}">
                </td>
            </tr>
        </table>

        @endif



        @endforeach

        @endforeach

 
    </div>
</body>

</html>


<style>
    img {
        margin: auto;
        display: block;
        width: 350px;
    }

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
        font-size: 15px !important;
    }

    .bb {
        padding-top: 10px !important;
    }
</style>