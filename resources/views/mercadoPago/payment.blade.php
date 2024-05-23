<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Payments</title>

        <!-- CSS only -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-uWxY/CJNBR+1zjPWmfnSnVxwRheevXITnMqoEIeG1LJrdI0GlVs/9cVSyPYXdcSF" crossorigin="anonymous">
    </head>

    <body>
        @php
            // SDK de Mercado Pago
            require base_path('vendor/autoload.php');

            // Agrega credenciales
            MercadoPago\SDK::setAccessToken(config('services.mercadopago.token'));

            // Crea un objeto de preferencia
            $preference = new MercadoPago\Preference();

            // Crea un ítem en la preferencia
            $item = new MercadoPago\Item();
            $item->title = strtoupper($vehicle->name);
            $item->id = $vin;
            $item->category_id = $client->id;
            $item->quantity = 1;
            $item->unit_price = $customerService->monto;

            // $preference->notification_url = "https://5129-187-188-172-113.ngrok-free.app/api/webhooks";
            $preference->notification_url = "https://abcars.mx/abcars-backend/api/webhooks";

            $preference->back_urls = array(
                "success" => route('pagos', ['vehicleId' => $vehicle->id, 'clientId' => $client->id]),
                "pending" => route('pagos', ['vehicleId' => $vehicle->id, 'clientId' => $client->id]),
                "failure" => "https://abcars.mx/error-process",
            );
            
            // Número total de pagos, sin pago de intereses
            $preference->payment_methods = array(
                "installments" => 1
            );

            $preference->auto_return = "approved";
            $preference->items = array($item);
            $preference->save();
        @endphp

        <div class="row">
            <div class="container" stype="margin-top: 100px margin-bottom: 50px">
                <div class="row justify-content-center">
                    <div class="col-md-10 mt-5">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Arror Back -->
                                    <!-- <div class="col-12 mt-4 text-start">
                                        <a class="text-decoration-none text-dark" href="https://abcars.mx/compra-tu-auto/acquisition/vehicle/pay/{{ $vehicle->vin }}">
                                            <i class="fas fa-arrow-circle-left"></i> Regresar
                                        </a>
                                    </div> -->

                                    <div class="col-12 text-center">
                                        <div class="py-3 text-center">
                                            <h1 class="fs-2 fw-bold">
                                                Verificar Pago
                                            </h1>
                                            <p class="lead">Pago sera procesado por la plataforma de Mercado Pago.</p>
                                        </div>

                                        <h4 class="text-center">Resumen</h4>
                                
                                        <ol class="list-group mb-3">
                                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                              <div class="ms-2 me-auto">
                                                <div class="fw-bold">Vehículo</div>
                                            </div>
                                            
                                            <span class="text-muted">
                                                {{ strtoupper($vehicle->name) }}
                                              </span>
                                            </li>

                                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                              <div class="ms-2 me-auto">
                                                <div class="fw-bold">Total del Vehículo</div>
                                              </div>

                                              <span class="badge bg-secondary">
                                                $<strong id="total">{{ number_format($vehicle->salePrice, 2) }}</strong> MXN
                                              </span>
                                            </li>

                                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                              <div class="ms-2 me-auto">
                                                <div class="fw-bold">Precio por Reservarlo</div>
                                              </div>

                                              <span class="badge bg-success">
                                                $</strong><strong id="total">{{ number_format($customerService->monto, 2) }}</strong> MXN
                                              </span>
                                            </li>

                                            {{-- Checking has reference to rewards --}}
                                            @if($reference)
                                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                                    <div class="ms-2 me-auto">
                                                    <div class="fw-bold">Referencia de Rewards</div>
                                                    </div>
    
                                                    <p class="fw-bold">
                                                        {{ $reference }}
                                                    </p>
                                                </li>
                                            @endif
                                        </ol>

                                        <div class="cho-container"></div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-12">
                                        <div class="text-center mt-3" id="divPaymentButton"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Font Awesome 5 links-->
        <script src="https://kit.fontawesome.com/fddf5c0916.js" crossorigin="anonymous"></script>
        
        <!-- SDK MercadoPago.js V2 -->
        <script src="https://sdk.mercadopago.com/js/v2"></script>

        <script>
            // Agrega credenciales de SDK
            const mp = new MercadoPago("{{config('services.mercadopago.key')}}", { locale: 'es-MX' });

            // Inicializa el checkout
            mp.checkout({
                preference: {
                    id: '{{ $preference->id }}'
                },
                render: {
                    container: '.cho-container', // Indica el nombre de la clase donde se mostrará el botón de pago
                    label: 'Pagar', // Cambia el texto del botón de pago (opcional)
                },
                theme: {
                    elementsColor: '#ffcb54'
                }
            });
        </script>    
    </body>
</html>