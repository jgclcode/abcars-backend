<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Rutas públicas
Route::get('/getRandomVehicles', [App\Http\Controllers\VehicleController::class, 'getRandomVehicles']);

// Route::get('/brandsByActiveVehicles/{modelNames?}/{years?}/{carrocerias?}/{price?}/{states?}/{transmissions?}', [App\Http\Controllers\VehicleController::class, 'brandsByActiveVehicles']);
Route::get('/brandsByActiveVehicles/{modelNames?}/{years?}/{carrocerias?}/{minprice?}/{price?}/{states?}/{transmissions?}', [App\Http\Controllers\VehicleController::class, 'brandsByActiveVehicles']);

// Route::get('/modelsByActiveVehicles/{brandNames?}/{years?}/{carrocerias?}/{price?}/{states?}/{transmissions?}', [App\Http\Controllers\VehicleController::class, 'modelsByActiveVehicles']);
Route::get('/modelsByActiveVehicles/{brandNames?}/{years?}/{carrocerias?}/{minprice?}/{price?}/{states?}/{transmissions?}', [App\Http\Controllers\VehicleController::class, 'modelsByActiveVehicles']);

// Route::get('/yearsByActiveVehicles/{brandNames?}/{modelNames?}/{carrocerias?}/{price?}/{states?}/{transmissions?}', [App\Http\Controllers\VehicleController::class, 'yearsByActiveVehicles']);
Route::get('/yearsByActiveVehicles/{brandNames?}/{modelNames?}/{carrocerias?}/{minprice?}/{price?}/{states?}/{transmissions?}', [App\Http\Controllers\VehicleController::class, 'yearsByActiveVehicles']);

// Route::get('/vehiclebodiesByActiveVehicles/{brandNames?}/{modelNames?}/{years?}/{price?}/{states?}/{transmissions?}', [App\Http\Controllers\VehicleController::class, 'vehiclebodiesByActiveVehicles']);
Route::get('/vehiclebodiesByActiveVehicles/{brandNames?}/{modelNames?}/{years?}/{minprice?}/{price?}/{states?}/{transmissions?}', [App\Http\Controllers\VehicleController::class, 'vehiclebodiesByActiveVehicles']);

// Route::get('/statesByActiveVehicles/{brandNames?}/{modelNames?}/{carrocerias?}/{years?}/{price?}/{transmissions?}', [App\Http\Controllers\VehicleController::class, 'statesByActiveVehicles']);
Route::get('/statesByActiveVehicles/{brandNames?}/{modelNames?}/{carrocerias?}/{years?}/{minprice?}/{price?}/{transmissions?}', [App\Http\Controllers\VehicleController::class, 'statesByActiveVehicles']);

// Route::get('/transmissionsByActiveVehicles/{brandNames?}/{modelNames?}/{carrocerias?}/{years?}/{price?}/{states?}', [App\Http\Controllers\VehicleController::class, 'transmissionsByActiveVehicles']);
Route::get('/transmissionsByActiveVehicles/{brandNames?}/{modelNames?}/{carrocerias?}/{years?}/{minprice?}/{price?}/{states?}', [App\Http\Controllers\VehicleController::class, 'transmissionsByActiveVehicles']);

Route::get('getMinMaxPrices', [App\Http\Controllers\VehicleController::class, 'minMaxPrices']);

// Route::get('/vehiclesSearch/{cantidad}/{brandNames?}/{modelNames?}/{years?}/{carrocerias?}/{price?}/{word?}/{orden?}/{states?}/{transmissions?}', [App\Http\Controllers\VehicleController::class, 'vehiclesSearch']);
Route::get('/vehiclesSearch/{cantidad}/{brandNames?}/{modelNames?}/{years?}/{carrocerias?}/{minprice?}/{price?}/{word?}/{orden?}/{states?}/{transmissions?}', [App\Http\Controllers\VehicleController::class, 'vehiclesSearch']);

Route::get('image_vehicle/{name}', [App\Http\Controllers\Vehicle_ImageController::class, 'getImage']);

Route::get('/vehicleByVin/{vin}', [App\Http\Controllers\VehicleController::class, 'vehiclesByVin']);

Route::get('/getRecommendedCarsByVin/{vin}', [App\Http\Controllers\VehicleController::class, 'getRecommendedCarsByVin']);

Route::get('brands', [App\Http\Controllers\BrandController::class, 'allBrands']);

Route::get('/vehicle/carmodels/{brand_id}', [App\Http\Controllers\VehicleController::class, 'modelsVehicle']);

Route::resource('states', App\Http\Controllers\StateController::class, [
    'only' => ['index']
]);

Route::resource('sheet_quote', App\Http\Controllers\Sheet_quoteController::class, [
        'only' => ['store']
]);

Route::post('prospection_chevrolet', [App\Http\Controllers\Sheet_quoteController::class, 'prospection_chevrolet']);

Route::resource('request', App\Http\Controllers\RequestController::class, [
    'only' => ['store']
]);

// servicio para notification financing
Route::resource('financing', App\Http\Controllers\FinancingController::class, [
    'only' => ['store']
]);

// Define cada ruta explícitamente
Route::post('sell_your_car', [App\Http\Controllers\Sell_your_carController::class, 'store']);

// Define cada ruta explícitamente
Route::post('clients', [App\Http\Controllers\ClientController::class, 'store']);

Route::middleware(['check.ip'])->group(function () {

    Route::get('/vehiclesSearchAll/{cantidad}/{brandNames?}/{modelNames?}/{years?}/{carrocerias?}/{minprice?}/{price?}/{word?}/{orden?}/{states?}', [App\Http\Controllers\VehicleController::class, 'vehiclesSearchAll']);

    Route::get('/vehicleById/{vehicle_id}', [App\Http\Controllers\VehicleController::class, 'vehiclesById']);

    Route::post('/askInformationVehicle', [App\Http\Controllers\VehicleController::class, 'askInformationVehicle']);

    Route::resource('vehicle_image', App\Http\Controllers\Vehicle_ImageController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    // Ruta para obtner imagenes de vehículos
    Route::post('setImageWithoutFile', [App\Http\Controllers\Vehicle_ImageController::class, 'setImageWithoutFile']);
    Route::post('deleteImagesToExternalWebSite', [App\Http\Controllers\Vehicle_ImageController::class, 'deleteImagesToExternalWebSite']);
    Route::post('changeOrder', [App\Http\Controllers\Vehicle_ImageController::class, 'changeOrder']);

    Route::get('/users/{total?}', [App\Http\Controllers\UserController::class, 'index']);
    Route::post('/register', [App\Http\Controllers\UserController::class, 'register']);
    Route::post('/login', [App\Http\Controllers\UserController::class, 'login']);
    Route::post('/newLogin', [App\Http\Controllers\UserController::class, 'newLogin']);
    Route::post('/recoverAccount', [App\Http\Controllers\UserController::class, 'recoverAccount']);
    Route::put('/resetPassword', [App\Http\Controllers\UserController::class, 'resetPassword']);
    Route::get('/user/{id}', [App\Http\Controllers\UserController::class, 'getUser']);
    Route::get('/userById/{id}', [App\Http\Controllers\UserController::class, 'userById']);
    Route::get('/sellerById/{id}', [App\Http\Controllers\UserController::class, 'sellerById']);
    Route::get('/user/email/{email}', [App\Http\Controllers\UserController::class, 'getUserByEmail']);
    Route::post('/user/update/{id}', [App\Http\Controllers\UserController::class, 'update']);
    Route::put('/user/updateUserAndClient/{id}', [App\Http\Controllers\UserController::class, 'updateUserAndClient']);
    Route::post('/user/createUserAndClient', [App\Http\Controllers\UserController::class, 'createUserAndClient']);
    Route::delete('user/delete/{id}', [App\Http\Controllers\UserController::class, 'destroy']);
    Route::delete('user/deleteUserAndClient/{id}', [App\Http\Controllers\UserController::class, 'deleteUserAndClient']);
    Route::get('getUsersWithoutClient/{client_id?}', [App\Http\Controllers\UserController::class, 'getUsersWithoutClient']);

    Route::post('user/image/{id}', [App\Http\Controllers\UserController::class, 'setImage']);
    Route::get('user/image/{name}', [App\Http\Controllers\UserController::class, 'getImage']);

    Route::get('/getClientByUser/{user_id}', [App\Http\Controllers\UserController::class, 'getClient']);

    Route::post('/excel', [App\Http\Controllers\UserController::class, 'excelTest']);
    //servicios para branches
    Route::resource('branch', App\Http\Controllers\BranchController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::get('getBranches/{word?}', [App\Http\Controllers\BranchController::class, 'getBranches']);
    Route::get('getBranchById/{branch_id}', [App\Http\Controllers\BranchController::class, 'getBranchById']);

    //servicios para brands
    Route::resource('brand', App\Http\Controllers\BrandController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::get('brand/imagen/{name}', [App\Http\Controllers\BrandController::class, 'getImageBrand']);
    Route::post('brands/update/{brand_id}', [App\Http\Controllers\BrandController::class, 'update']);
    Route::get('/getBrandsWithTotal/{total?}', [App\Http\Controllers\BrandController::class, 'getBrandsWithTotal']);

    // Get All Brands
    Route::get('brandByName/{name}', [App\Http\Controllers\BrandController::class, 'brandByName']);
    Route::get('getBrandById/{brand_id}', [App\Http\Controllers\BrandController::class, 'getBrandById']);

    //servicios para sources
    Route::resource('source', App\Http\Controllers\SourceController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::get('sources/getAll', [App\Http\Controllers\SourceController::class, 'getAll']);

    // Define cada ruta explícitamente
    Route::get('clients', [App\Http\Controllers\ClientController::class, 'index']);
    Route::put('clients/{id}', [App\Http\Controllers\ClientController::class, 'update']);
    Route::delete('clients/{id}', [App\Http\Controllers\ClientController::class, 'destroy']);

    Route::put('/client/updateDataToPolicie/{client_id}', [App\Http\Controllers\ClientController::class, 'updateDataToPolicie']);

    Route::get('/client/{total?}', [App\Http\Controllers\ClientController::class, 'index']);
    // Get client by user_id
    Route::get('client/user_id/{user_id}', [App\Http\Controllers\ClientController::class, 'getClientByUser']);
    // Get vehicles by client
    Route::get('client/vehicles/{user_id}', [App\Http\Controllers\ClientController::class, 'vehiclesByClient']);
    // Get added services a client
    Route::get('client/services/{type}/{user_id}', [App\Http\Controllers\ClientController::class, 'servicesAddedClient']);
    // Get client by id
    Route::get('getClientById/{client_id}', [App\Http\Controllers\ClientController::class, 'getClientById']);
    // Get Prospectus to insurance policies
    Route::get('getProspectusToInsurancePolicies/{word}', [App\Http\Controllers\ClientController::class, 'getProspectusToInsurancePolicies']);

    //servicios para vehiclebodies
    Route::resource('vehiclebody', App\Http\Controllers\VehiclebodyController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::get('getVehiclebodyByName/{word?}', [App\Http\Controllers\VehiclebodyController::class, 'vehiclebodyByName']);
    Route::get('getVehiclebodyByID/{vehiclebody_id}', [App\Http\Controllers\VehiclebodyController::class, 'vehiclebodyByID']);

    //servicios para Package
    Route::resource('package', App\Http\Controllers\PackageController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    //servicios para quote
    Route::resource('quote', App\Http\Controllers\QuoteController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::get('quote/client/{word}/{quote?}/{status?}', [App\Http\Controllers\ClientController::class, 'getClientsQuoteServices']);

    //servicios para carmodel
    Route::resource('carmodel', App\Http\Controllers\CarmodelController::class, [
        'only' => ['index','store','update','destroy']
    ]);
    Route::get('carmodelByName/{name}/{brand_id}', [App\Http\Controllers\CarmodelController::class, 'carmodelByName']);
    Route::get('carmodelByID/{carmodel_id}', [App\Http\Controllers\CarmodelController::class, 'carmodelByID']);

    //servicios para service|
    Route::resource('service', App\Http\Controllers\ServiceController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::post('quote/service', [App\Http\Controllers\QuoteController::class, 'setServiceToQuote']);

    Route::get('getDataQuotes/{word?}', [App\Http\Controllers\QuoteController::class, 'getDataQuotes']);

    Route::put('quotes/newStatus/{quote_id}', [App\Http\Controllers\QuoteController::class, 'newStatus']);

    // Services without paginate
    Route::get('services/customer', [App\Http\Controllers\ServiceController::class, 'servicesCustomerClear']);


    // Subida de datos con excel
    Route::post('/load/vehicles', [App\Http\Controllers\VehicleController::class, 'load']);

    // Subida de promociones con excel
    Route::post('/addPromotions/vehicles', [App\Http\Controllers\VehicleController::class, 'addPromotions']);

    // Servicios para permisos
    Route::resource('permissions', App\Http\Controllers\PermissionController::class, [
        'only' => ['index', 'store', 'update', 'destroy']
    ]);

    // Servicios para roles
    Route::resource('role', App\Http\Controllers\RoleController::class, [
        'only' => ['index', 'store', 'update', 'destroy']
    ]);
    Route::get('/roles/{total?}', [App\Http\Controllers\RoleController::class, 'index']);

    // Servicios para Roles y Permissions
    Route::resource('rolepermission', App\Http\Controllers\RolePermissionController::class, [
        'only' => ['index', 'store', 'update', 'destroy']
    ]);

    // Servicio de pago PayPal
    Route::post('payments/pay', [App\Http\Controllers\PaymentController::class, 'payPaypal']);
    Route::get('payments/approval', [App\Http\Controllers\PaymentController::class, 'approval'])->name('approval');
    Route::get('payments/cancelled', [App\Http\Controllers\PaymentController::class, 'cancelled'])->name('cancelled');

    //servicios para aggregates
    Route::resource('aggregate', App\Http\Controllers\AggregateController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    //servicios para assist
    Route::resource('assist', App\Http\Controllers\AssistController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    //servicios para vehicle
    Route::resource('vehicle', App\Http\Controllers\VehicleController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::get('getVehiclesPurchasedByClient/{client_id}', [App\Http\Controllers\VehicleController::class,'getVehiclesPurchasedByClient']);

    //servicios para advisers
    Route::resource('advisers', App\Http\Controllers\AdvisersController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    //servicios para Valuatores
    Route::resource('valuatores', App\Http\Controllers\ValuatoresController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    // Define cada ruta explícitamente
    Route::get('sell_your_car', [App\Http\Controllers\Sell_your_carController::class, 'index']);
    Route::put('sell_your_car/{id}', [App\Http\Controllers\Sell_your_carController::class, 'update']);
    Route::delete('sell_your_car/{id}', [App\Http\Controllers\Sell_your_carController::class, 'destroy']);

    // servicio para llenar la tabla de citas vender tu auto y posteriormente realizar la valuación
    Route::get('sell_car', [App\Http\Controllers\Sell_your_carController::class, 'sell_car']);

    // Servicio para llenar los campos necesarios en el formulario Checklist de valuación
    Route::get('sell-car-valuation/{vin}', [App\Http\Controllers\Sell_your_carController::class, 'sell_car_valuation']);

    // servicio para valuations
    Route::resource('valuations', App\Http\Controllers\ValuationController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    // servicio para bills
    Route::resource('bills', App\Http\Controllers\BillController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    // servicio para purchases
    Route::resource('purchases', App\Http\Controllers\PurchaseController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    // servicio para expenses
    Route::resource('expenses', App\Http\Controllers\ExpenseController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    // servicio para sales
    Route::resource('sales', App\Http\Controllers\SaleController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    // servicio para incidentes de servicios
    Route::resource('service_incident', App\Http\Controllers\Service_incidentController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::get('getIncidents/{word?}', [App\Http\Controllers\Service_incidentController::class, 'getIncidents']);

    Route::put('incidents/updateStatus/{service_incident_id}', [App\Http\Controllers\Service_incidentController::class, 'updateStatus']);

    Route::get('service_incidentByClientId/{client_id}', [App\Http\Controllers\Service_incidentController::class, 'getAllByClientId']);

    // servicio para incidentes de vehiculos comprados por el cliente
    Route::resource('vehicle_incident', App\Http\Controllers\Vehicle_incidentController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::get('vehicle_incidentByClientId/{client_id}', [App\Http\Controllers\Vehicle_incidentController::class, 'getAllByClientId']);

    // Servicio que recibe el vin y el id de usuario del pago
    Route::get('payment/{vin}/{userId}/{reference?}', [App\Http\Controllers\PaymentController::class, 'pay']);
    // servicio para sets
    Route::resource('sets', App\Http\Controllers\SetsController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::get('vehicle_incidentByClientId/{client_id}', [App\Http\Controllers\Vehicle_incidentController::class, 'getAllByClientId']);

    Route::get('/get_set_vehicle/{vehicle_id}', [App\Http\Controllers\VehicleController::class, 'get_set_vehicle']);

    // Servicio para obtener los choices por usuario en sessionStorage
    Route::get('choices/{user_id}', [App\Http\Controllers\ChoiceController::class, 'getChoices']);

    // Servicio para verificar el auto apartado
    Route::get('apartado/{vin}', [App\Http\Controllers\ChoiceController::class, 'getApartado']);

    // Route::get('apartar_y_desapartar/{vin}', [App\Http\Controllers\ChoiceController::class, 'apartar_y_desapartar']);

    // servicio para choices
    Route::resource('choices', App\Http\Controllers\ChoiceController::class, [
        'only' => ['index','store','update','destroy']
    ]);
    Route::get('choices/client/{user_id}', [App\Http\Controllers\ChoiceController::class, 'getChoicesByClient']);
    Route::get('getchoices', [App\Http\Controllers\ChoiceController::class, 'getChoicesWithUser']);

    // servicio para logs
    Route::resource('logs', App\Http\Controllers\LogsController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::get('log/getLastProcess/{process}', [App\Http\Controllers\LogsController::class, 'getLastProcess']);

    // servicio para notification
    Route::resource('notification', App\Http\Controllers\NotificationController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::get('sendEmailAvailableVehicle', [App\Http\Controllers\NotificationController::class, 'sendEmailAvailableVehicle']);
    Route::get('sendEmailSaleVehicle', [App\Http\Controllers\NotificationController::class, 'sendEmailSaleVehicle']);

    // servicio para notification empleo
    Route::resource('jobs', App\Http\Controllers\JobsController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::get('financing/by/user/{user_id}', [App\Http\Controllers\FinancingController::class, 'financingsbyUser']);
    Route::post('financing/files/{financing_id}', [App\Http\Controllers\FinancingController::class, 'uploadFilesFinancing']);
    Route::post('financing/files/preview/{financing_id}', [App\Http\Controllers\FinancingController::class, 'previewFilesFinancing']);

    // servicio para notification reference
    Route::resource('reference', App\Http\Controllers\ReferenceController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    // Service of Check_List
    Route::resource('checklist', App\Http\Controllers\Check_ListController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::put('updatequotation/{id}', [App\Http\Controllers\Check_ListController::class, 'quotationUpdate']);
    Route::put('updatestatus/{id}', [App\Http\Controllers\Check_ListController::class, 'updatestatus']);
    Route::put('updatestatusforms/{id}', [App\Http\Controllers\Check_ListController::class, 'updatestatusforms']);
    Route::put('updatebought/{id}', [App\Http\Controllers\Check_ListController::class, 'updatebought']);

    Route::get('getRolByNameAndUserId/{name}/{user_id}', [App\Http\Controllers\RoleController::class, 'getRolByNameAndUserId']);
    //PDF REQUIERE VIN
    Route::get('getCheckListbyId/{id}', [App\Http\Controllers\Check_ListController::class, 'check_pdf']);
    Route::get('getCheckListbyIdCustomer/{id}', [App\Http\Controllers\Check_ListController::class, 'check_pdf_customer']);

    Route::get('getchecklist/{vin}', [App\Http\Controllers\Check_ListController::class, 'getchecklist']);
    Route::get('getchecklistall/{id}', [App\Http\Controllers\Check_ListController::class, 'getchecklistall']);
    Route::get('getmechanic_electronic/{id}', [App\Http\Controllers\MechanicalElectronicController::class, 'getmechanic_electronic']);
    Route::get('getinterior_review/{id}', [App\Http\Controllers\InteriorReviewController::class, 'getinterior_review']);
    Route::get('getcert_vehicle/{id}', [App\Http\Controllers\CertificationController::class, 'getcert_vehicle']);

    Route::get('qrgenerate/{vin}', [App\Http\Controllers\Check_ListController::class, 'qrgenerate']);
    Route::get('qrgenerateInventario/{vin}',[App\Http\Controllers\Check_ListController::class,'qrgenerateInvenrario']);

    Route::get('rolebyid/{id_role}', [App\Http\Controllers\RoleController::class, 'GetRoleById']);

    Route::get('requestInventoryUnitsByBusinessUnitId/{pageNumber?}', [App\Http\Controllers\IntelimotorController::class, 'requestInventoryUnitsByBusinessUnitId']);
    Route::get('requestUnitByVin/{vin}', [App\Http\Controllers\IntelimotorController::class, 'requestUnitByVin']);
    Route::get('allUnitsWithoutImages', [App\Http\Controllers\IntelimotorController::class, 'allUnitsWithoutImages']);
    Route::get('vehicleSold/{vin}', [App\Http\Controllers\VehicleController::class, 'vehicleSold']);
    Route::patch('vehicles/updateStatus/{vehicle_id}', [App\Http\Controllers\VehicleController::class, 'updateStatus']);
    Route::put('updatePromotion', [App\Http\Controllers\VehicleController::class, 'updatePromotion']);
    Route::get('getActiveVehicles/{word?}/{total?}', [App\Http\Controllers\VehicleController::class, 'getActiveVehicles']);
    Route::get('getActiveVehiclesLocation/{word?}/{total?}', [App\Http\Controllers\VehicleController::class, 'getActiveVehiclesLocation']);

    // Service of Rewards
    Route::resource('rewards', App\Http\Controllers\RewardController::class, [
        'only' => ['index', 'store', 'update', 'destroy']
    ]);
    Route::get('rewards/{user_id}', [App\Http\Controllers\RewardController::class, 'findClientReward']);
    Route::get('rewards/checking/{reference}/{email}', [App\Http\Controllers\RewardController::class, 'checkingReference']);


    Route::resource('shield', App\Http\Controllers\ShieldController::class, [
        'only' => ['index', 'store', 'update', 'destroy']
    ]);
    Route::get('/shields/{total?}', [App\Http\Controllers\ShieldController::class, 'index']);

    Route::get('shields/imagen/{name}', [App\Http\Controllers\ShieldController::class, 'getImage']);

    Route::post('updateWithImage/{shield_id}', [App\Http\Controllers\ShieldController::class, 'updateWithImage']);

    Route::post('assignShield/{vehicle_id}/{shield_id}', [App\Http\Controllers\ShieldController::class, 'assignShield']);
    Route::post('removeShield/{vehicle_id}/{shield_id}', [App\Http\Controllers\ShieldController::class, 'removeShield']);

    Route::get('existsShieldIntoVehicle/{vehicle_id}/{shield_id}', [App\Http\Controllers\ShieldController::class, 'existsShieldIntoVehicle']);

    Route::get('shields/getShieldById/{shield_id}', [App\Http\Controllers\ShieldController::class, 'getShieldById']);

    Route::get('technicians/{user_id}', [App\Http\Controllers\TechnicianController::class, 'technicians']);

    //servicios para Valuatores
    Route::resource('document', App\Http\Controllers\DocumentController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::resource('document_image', App\Http\Controllers\Document_imageController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::post('update_document_image/{document_image_id}', [App\Http\Controllers\Document_imageController::class, 'update_image']);
    Route::post('update_document_pdf/{document_image_id}', [App\Http\Controllers\Document_imageController::class, 'update_pdf']);
    Route::get('getDocumentImage/{check_list_id}/{document_id}', [App\Http\Controllers\Document_imageController::class, 'getDocumentImage']);
    Route::get('getDocumentation/{check_list_id}', [App\Http\Controllers\Document_imageController::class, 'getDocumentation']);
    Route::get('document_images/{name}', [App\Http\Controllers\Document_imageController::class, 'getImage']);
    Route::get('document/download/{name}', [App\Http\Controllers\Document_imageController::class, 'downloadDocumentImagen']);

    Route::resource('damage', App\Http\Controllers\DamageController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::resource('damage_image', App\Http\Controllers\Damage_imageController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::post('update_damage_image/{damage_image_id}', [App\Http\Controllers\Damage_imageController::class, 'update_image']);
    Route::get('getDamageImage/{sell_your_car_id}/{damage_id}', [App\Http\Controllers\Damage_imageController::class, 'getDamageImage']);
    Route::get('damage_images/{name}', [App\Http\Controllers\Damage_imageController::class, 'getImage']);
    Route::get('damage_imgs/{name}/{vin}', [App\Http\Controllers\Damage_imageController::class, 'getImg']); /** Antes damage_imgs/{name}/{dmg_id} */
    Route::get('damage_images/download/{name}', [App\Http\Controllers\Damage_imageController::class, 'downloadDamageImagen']);
    //NUEVO QR
    Route::get('qrsale/{vin}', [App\Http\Controllers\Check_ListController::class, 'check_view_sale']);


    /* Spare Parts */
    Route::resource('spare_parts', App\Http\Controllers\Spare_partController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::get('getSpare_partsBySellYourCar/{sell_your_car_id}', [App\Http\Controllers\Spare_partController::class, 'getSpare_partsBySellYourCar']);
    Route::put('spare_parts/updateStatus/{spare_part_id}', [App\Http\Controllers\Spare_partController::class, 'updateStatus']);

    /* Painting work */
    Route::resource('painting_works', App\Http\Controllers\Painting_workController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::get('getPainting_worksBySellYourCar/{sell_your_car_id}', [App\Http\Controllers\Painting_workController::class, 'getPainting_worksBySellYourCar']);
    Route::put('painting_works/updateStatus/{painting_work_id}', [App\Http\Controllers\Painting_workController::class, 'updateStatus']);


    Route::resource('foreing_review', App\Http\Controllers\ForeingReviewController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::get('foreing_reviews/getForeingReviewById/{foreing_review_id}', [App\Http\Controllers\ForeingReviewController::class, 'getForeingReviewById']);
    Route::get('foreing_reviews/getForeingReviewBySellYourCarId/{sell_your_car_id}', [App\Http\Controllers\ForeingReviewController::class, 'getForeingReviewBySellYourCarId']);
    //vehiculos de abcars
    Route::get('getVehiclesAbcars', [App\Http\Controllers\VehicleController::class, 'getVehiclesAbcars']);

    
    Route::get('getSaleVehiclesWithoutChoice', [App\Http\Controllers\VehicleController::class, 'getSaleVehiclesWithoutChoice']);

    //api interior reviews
    Route::resource('interior_reviews', App\Http\Controllers\InteriorReviewController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    //api mechanicalelectronic
    Route::resource('mechanicalelectronic', App\Http\Controllers\MechanicalElectronicController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    //api certification
    Route::resource('certification', App\Http\Controllers\CertificationController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::get('GetCertificacionbyId/{sell_your_car_id}', [App\Http\Controllers\CertificationController::class, 'GetCertificacionbyId']);

    Route::get('GetInteriorbyId/{sell_your_car_id}', [App\Http\Controllers\InteriorReviewController::class, 'GetInteriorbyId']);

    Route::get('GetForeingnbyId/{sell_your_car_id}', [App\Http\Controllers\ForeingReviewController::class, 'GetForeingnbyId']);

    Route::get('GetMecanicalbyId/{sell_your_car_id}', [App\Http\Controllers\MechanicalElectronicController::class, 'GetMecanicalbyId']);

    Route::get('getDamages/{status}/{ids?}', [App\Http\Controllers\DamageController::class, 'getDamages']);

    //desapartar
    Route::get('DeleteApart/{vin}', [App\Http\Controllers\ChoiceController::class, 'DeleteApart']);

    Route::get('getVehicleSale', [App\Http\Controllers\VehicleController::class, 'getVehicleSale']);

    Route::get('getProspects', [App\Http\Controllers\Sell_your_carController::class, 'getProspects']);

    Route::get('getVehiclesAbcarsxml', [App\Http\Controllers\VehicleController::class, 'getVehiclesAbcarsxml']);

    Route::get('getcheckFront/{id}', [App\Http\Controllers\Check_ListController::class, 'getcheckFront']);
    Route::get('dynamicAssignmentToValuator/{sell_your_car_id}', [App\Http\Controllers\Sell_your_carController::class, 'dynamicAssignmentToValuator']);
    Route::put('updatestandbyparts/{id}', [App\Http\Controllers\Sell_your_carController::class, 'updatestandbyparts']);

    //api polizas
    Route::resource('policies', App\Http\Controllers\PolicieController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    //api caracteristicas de servicios
    Route::resource('serviceFeature', App\Http\Controllers\Service_featureController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    //api service response
    Route::resource('serviceResponse', App\Http\Controllers\Service_responseController::class, [
        'only' => ['index','store','update','destroy']
    ]);

    Route::get('getServiceFeatureById/{id}', [App\Http\Controllers\Service_featureController::class, 'getServiceFeatureById']);

    Route::get('getPoliciebyid/{id}', [App\Http\Controllers\PolicieController::class, 'getPoliciebyid']);

    Route::get('getfeaturesbyquote/{id_quote}', [App\Http\Controllers\Service_responseController::class, 'getfeaturesbyquote']);

    Route::get('painting_works_images/{img_damage}', [App\Http\Controllers\Painting_workController::class, 'imgDamage']);
    Route::get('getVehiclebyStatus/{status}', [App\Http\Controllers\VehicleController::class, 'getVehiclebyStatus']);

    Route::get('notificationvaluation/{id}/{email_val}', [App\Http\Controllers\Sell_your_carController::class, 'notificationvaluation']);

    Route::get('notificationvaluationA/{id}', [App\Http\Controllers\Sell_your_carController::class, 'notificationvaluationA']);

    Route::get('notificationvaluationD/{id}', [App\Http\Controllers\Sell_your_carController::class, 'notificationvaluationD']);

    Route::get('authorization/{id}', [App\Http\Controllers\ValuationController::class, 'authorization']);

    Route::get('getVehiclebyBranch/{brach_id}', [App\Http\Controllers\VehicleController::class, 'getVehiclebyBranch']);

    Route::get('sendmails/{user_id}', [App\Http\Controllers\UserController::class, 'sendmails']);

    Route::get('getvaluatordates/{user_id}', [App\Http\Controllers\Sell_your_carController::class, 'getvaluatordates']);

    Route::get('assignmentToValuator/{user_id}/{sell_car_id}', [App\Http\Controllers\Sell_your_carController::class, 'assignmentToValuator']);

    Route::get('getappraiser_technician', [App\Http\Controllers\Check_ListController::class, 'getappraiser_technician']);

    Route::get('getdocument_pdf/{sellyourcar_id}', [App\Http\Controllers\Document_imageController::class, 'getdocument_pdf']);

    Route::get('getpdfwatch/{checklist_id}', [App\Http\Controllers\Document_imageController::class, 'getpdfwatch']);

    Route::put('update_estimated_payment/{id}', [App\Http\Controllers\Sell_your_carController::class, 'update_estimated_payment']);

    Route::get('financing_front/{financing_id}', [App\Http\Controllers\FinancingController::class, 'financing_ine_front']);

    Route::get('financing_back/{financing_id}', [App\Http\Controllers\FinancingController::class, 'financing_ine_back']);

    Route::get('search_financing/{word?}/{cantidad}', [App\Http\Controllers\FinancingController::class, 'search_financing']);

    Route::get('search_brand/{word?}/{cantidad}', [App\Http\Controllers\BrandController::class, 'search_brand']);

    Route::get('search_model/{word?}/{cantidad}', [App\Http\Controllers\CarmodelController::class, 'search_model']);
    Route::resource('vehicle_location', App\Http\Controllers\Location_vehicleController::class,[
        'only' => ['index','store','update','destroy']
    ]);

    Route::get('/vehicles_sales/{cantidad}/{brandNames?}/{modelNames?}/{years?}/{carrocerias?}/{price?}/{word?}/{orden?}/{states?}', [App\Http\Controllers\VehicleController::class, 'vehicles_sales']);

    Route::get('transito/{vehicle_id}', [App\Http\Controllers\Location_vehicleController::class, 'transito']);

    Route::get('getVehiclesNotTransit/{name_location}', [App\Http\Controllers\Location_vehicleController::class, 'getVehiclesNotTransit']);

    Route::post('/form_vehicle/{vin}', [App\Http\Controllers\Vehicle_ReviewController::class, 'initializeForm']);

    Route::post('/form_save', [App\Http\Controllers\Vehicle_ReviewController::class, 'saveForm']);

    Route::get('transito/{vehicle_id}', [App\Http\Controllers\Location_vehicleController::class, 'transito']);

    Route::get('getLocationvehiclesId/{id}',[App\Http\Controllers\Location_vehicleController::class, 'getLocationvehiclesId']);

    Route::get('getVehiclesNotTransit/{name_location}', [App\Http\Controllers\Location_vehicleController::class, 'getVehiclesNotTransit']);

    Route::resource('/maintenanceAppointment', App\Http\Controllers\MaintenanceController::class,[
        'only' => ['index','store','update','destroy']
    ]);
    Route::get('feedVehiclesToBusinessPro', [App\Http\Controllers\BusinessProController::class, 'getVehiclesToBusinessPro']);

    //ckeck_vehicles
    Route::resource('/Ckeckvehicles', App\Http\Controllers\Ckeck_vehicleController::class,[
        'only' => ['index','store','update','destroy']
    ]);

    Route::get('check_images/{name}', [App\Http\Controllers\Ckeck_vehicleController::class, 'getImage']);

    Route::get('checkByVehicle/{vehicle_id}', [App\Http\Controllers\Ckeck_vehicleController::class, 'checkByVehicle']);

    Route::get('searchVehicleByVin/{vin_id}', [App\Http\Controllers\Ckeck_vehicleController::class, 'searchVehicleByVin']);

    Route::get('getVehiclesReviewed', [App\Http\Controllers\Ckeck_vehicleController::class, 'getVehiclesReviewed']);

    Route::get('ReportCheck/{vehicle_id}', [App\Http\Controllers\Ckeck_vehicleController::class, 'ReportCheck']);

    Route::post('addSetImage', [App\Http\Controllers\ControllerSetImage::class, 'addSetImage']);

    Route::get('getSetvehicles', [App\Http\Controllers\ControllerSetImage::class, 'getSetvehicles']);

    Route::get('getsetImages/{vin}', [App\Http\Controllers\ControllerSetImage::class, 'getsetImages']);

    Route::get('getSetImage/{name}/{vin}', [App\Http\Controllers\ControllerSetImage::class, 'getsetImage']);

    Route::get('deleteSetImages/{vin}', [App\Http\Controllers\ControllerSetImage::class, 'deleteSetImages']);

    Route::post('conversionForm', [App\Http\Controllers\ConversionFormController::class, 'saveConversionForm']);

    Route::get('getValuationsCount', [App\Http\Controllers\Spare_partController::class, 'getValuationsCount']);

    Route::get('getNowPrintValuation/{datePrint}/{dateEndPrint}', [App\Http\Controllers\Spare_partController::class, 'getNowPrintValuation']);

    Route::get('valuation_print/{valuatorid?}/{datePrintValuation?}/{datePrintEndValuation?}', [App\Http\Controllers\Spare_partController::class, 'export']);

    Route::get('get_valuators', [App\Http\Controllers\ValuatoresController::class, 'get_valuators']);

    Route::post('changeOrderShieldImages', [App\Http\Controllers\ShieldController::class, 'changeOrderShieldImages']);

    // Route::resource('vehicle_360_image', App\Http\Controllers\Vehicle_360_imageController::class, [
    //     'only' => ['index','store','update','destroy']
    // ]);

    // Route::post('upload360Images', [App\Http\Controllers\Vehicle_360_imageController::class, 'upload360Images']);
    // Route::post('delete360Images', [App\Http\Controllers\Vehicle_360_imageController::class, 'delete360Images']);
    // Route::get('image_360_vehicle/{name}', [App\Http\Controllers\Vehicle_360_imageController::class, 'getImage']);

    Route::get('get_client_price_offer', [App\Http\Controllers\Sheet_quoteController::class, 'get_client_price_offer']);

    Route::get('search_report_offer/{word?}/{cantidad}', [App\Http\Controllers\Sheet_quoteController::class, 'search_report_offer']);

    Route::get('/getPreownedVehiclesXml', [App\Http\Controllers\VehicleController::class, 'getPreownedVehiclesXml']);

    Route::post('webhooks', [App\Http\Controllers\WebhooksController::class, 'webhook']);
    //INTEGRACION DE FACEBOOK
    Route::post('publish', [App\Http\Controllers\IntegrationfbController::class, 'publish']);
    Route::post('deletePublish', [App\Http\Controllers\IntegrationfbController::class, 'deletePublish']);
    Route::get('getToken', [App\Http\Controllers\IntegrationfbController::class, 'getToken']);

    Route::get('get_valuation_quotes', [App\Http\Controllers\Sell_your_carController::class, 'getValuationQuotes']);
    Route::get('get_valuation_quote/{id}', [App\Http\Controllers\Sell_your_carController::class, 'getValuationQuote']);

    Route::get('get_active_valuator', [App\Http\Controllers\Sell_your_carController::class, 'getActiveValuator']);

    Route::get('exists_assign_valuator/{uId}/{sycId}', [App\Http\Controllers\Sell_your_carController::class, 'existsAssignValuator']);
    Route::get('/ml_access_token/{code?}', [App\Http\Controllers\MercadoLibreController::class, 'access_token']);
    Route::get('/ml_refresh_access_token', [App\Http\Controllers\MercadoLibreController::class, 'refresh_access_token']);
    Route::post('/ml_post_vehicle', [App\Http\Controllers\MercadoLibreController::class, 'post_vehicle_ml']);
    Route::post('/update_vehicle_ml', [App\Http\Controllers\MercadoLibreController::class, 'update_vehicle_ml']);
    Route::post('/delete_vehicle_ml', [App\Http\Controllers\MercadoLibreController::class, 'delete_vehicle_ml']);

    Route::put('request/{id}', [App\Http\Controllers\RequestController::class, 'update']);
    Route::delete('request/{id}', [App\Http\Controllers\RequestController::class, 'destroy']);
});