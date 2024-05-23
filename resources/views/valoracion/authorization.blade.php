<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Check List de Autorizacion</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <table class="table " style="font-size: 11px; width: 100%; ">
                        <tbody>
                            <tr>
                                <td>
                                    <img src='https://abcars.mx/logos/logo.png' style="width: 20%;">
                                    <h1 style=" text-align: center;  font-weight: bold; font-size: 12px;">
                                        AUTORIZACION DE ACONDICIONAMIENTO AUTOS SEMINUEVOS
                                    </h1>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p>
                                        <span class="font-weight-bold">FECHA DE TOMA:</span> <span class="sspan">

                                            {{ substr($sell_your_car->updated_at,0,10) }}</span>
                                        <span class="font-weight-bold">MARCA: </span> <span class="sspan">
                                            {{ $marca->name }}</span>
                                        <span class="font-weight-bold"> MODELO:</span> <span class="sspan">
                                            {{ $modelo->name }}
                                        </span>
                                        <span class="font-weight-bold"> COLOR:</span> <span class="sspan">  {{ $checklist ->color }}</span>
                                    </p>
                                    <p>
                                        <span class="font-weight-bold">FECHA DE INGRESO A SERVICIO:</span>
                                        ___________<span class="sspan"> </span>
                                        <span class="font-weight-bold">VIN:</span> <span
                                            class="span">{{ $sell_your_car->vin }}</span>
                                        <span class="font-weight-bold">AÑO:</span> <span
                                            class="sspan">{{ $sell_your_car->year }}</span>
                                        <span class="font-weight-bold">VALUADOR:</span> <span class="sspan"> {{$valuador->name }} {{$valuador->surname }}</span>
                                    </p>
                                    <p>
                                        <span class="font-weight-bold">FECHA INGRESO A TALLER :</span> <span
                                            class="sspan"> ___________</span>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="font-weight-bold">
                                        COTIZACION SERVICIO
                                    </p>
                                    @foreach($cys as $c)
                                    <p>{{ $c['name'] }}

                                        @switch($c['type_part'])

                                        @case("original")
                                        ${{ $c['priceOriginal'] }}
                                        @break

                                        @case("generic")
                                        ${{ $c['priceGeneric'] }}
                                        @break

                                        @default
                                        ${{ $c['priceUsed'] }}
                                        @endswitch
                                    </p>
                                    @endforeach
                                    <p class="font-weight-bold">
                                        TOTAL COTIZACION SERVICIO ${{ $checklist ->spare_parts }}
                                    </p>
                                    <p class="font-weight-bold">
                                       TOTAL MANO DE OBRA ${{ $checklist ->workforce }}
                                    </p>
                                    <p class="font-weight-bold">
                                        COTIZACION H Y P
                                    </p>
 
                                    @foreach($hyp as $h)
                                    <p>{{ $h['name'] }} ${{ $h['amount'] }} </p>
                                    @endforeach
                                    <p class="font-weight-bold">
                                        TOTAL  HYP ${{ $checklist ->hyp }}
                                    </p>

                                    <p class="font-weight-bold">
                                        GRAN TOTAL ${{ $checklist ->total }}
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <footer style="font-size: 11px;!important">
                        <p style="text-align: center;">
                            <span class="font-weight-bold">ELABORA:</span> <span class="mybox">CORINA ESTEBANEZ / MICHEL
                                MIA
                            </span>
                            <span class="font-weight-bold">V.B: </span> <span class="mybox">EMMANUEL MATINEZ /OSVALDO
                                ROSAS
                            </span>
                        </p>
                        <p class="py-2">
                            <span class="font-weight-bold">AUTORIZA CAMBIOS:</span>
                            <span class="mybox">GTE. POSVENTA LUIS CALDERON R.</span>
                            <span class="font-weight-bold">AUTORIZACION DE GASTOS:</span>
                            <span class="mybox">GTE. GRAL. ELIZABETH CALDERON R.</span>
                        </p>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
<style>
    body {
        font-family: DejaVu Sans, sans-serif;


    }

    table,
    tr,
    td {
        border: none !important;
    }

    .sspan {

        text-decoration: underline;
    }

    .mybox {
        border-top-style: solid;
        border-top-color: #000;
    }



    html {
        min-height: 100%;
        position: relative;
    }

    body {
        margin: 0;
        margin-bottom: 40px;
    }

    footer {

        position: absolute;
        bottom: 0;
        width: 100%;


    }

</style>
