<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Lista Cotización Valuaciones</title>
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
                            <h2 style="font-size:  11px !important; font-weight: bold !important;">Reporte, Cotización Refacciones 
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
                            Fecha de valuación: {{ $vin_check_list->valuation_date }}
                        </td>
                    </table>
                </div>
                <div class="row">
                    <table class="table" style="font-size: 8px">
                        <td>
                            Marca: {{ $findvin->brand->name }}
                        </td>
                        <td>
                            Modelo: {{ $findvin->carmodel->name }}
                        </td>
                        <td>
                            VIN: {{ $findvin->vin }}
                        </td>
                    </table>
                </div>
                <div class="row">
                    <table class="table" style="font-size: 8px">
                        <td>
                            Versión: {{ $findvin->version }}
                        </td>
                        <td>
                            Año: {{ $findvin->year }}
                        </td>
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
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- <table class="table" style="font: size 8px;">
                <td> -->
                    <h3 style="font-size:  11px !important; font-weight: bold !important; margin-top:0 !important;">
                        COTIZACIÓN
                    </h3>
                <!-- </td>
            </table> -->
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
                            <td>Usada/Reparada</td>
                            <td></td>
                            <td></td>
                        </tr>
                        <tr>
                            <td>Nom Refacción</td>
                            <td>Precio</td>
                            <td>Cantidad</td>
                            <td>Precio</td>
                            <td>Cantidad</td>
                            <td>Precio</td>
                            <td>Cantidad</td>
                        </tr>
                        @foreach($sparePart as $spare)
                            <tr>
                                <td>{{ $spare->name }}</td>
                                <td>{{ $spare->priceOriginal }}</td>
                                <td>{{ $spare->amount }}</td>
                                <td>{{ $spare->priceGeneric }}</td>
                                <td>{{ $spare->amount }}</td>
                                <td>{{ $spare->priceUsed }}</td>
                                <td>{{ $spare->amount }}</td>
                            </tr>
                        @endforeach
                    </table>
                    <span>Partes/refacciones Originales: $ {{ $sumOriginal }}</span>
                    <br>
                    <span>Partes/refacciones Genéricas: $ {{ $sumGeneric }}</span>
                    <br>
                    <span>Partes/refacciones Usadas: $ {{ $sumUsed }}</span>
                    <br>
                </td>
            </table>
        </div>
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
                    
                    <span style="font-size:  10px !important; font-weight: bold !important;  ">REFACCIONES</span>
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
    </div>
</body>
</html>