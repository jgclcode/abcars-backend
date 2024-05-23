<?php
namespace App\Helpers;
use App\Models\Vehicle;
use App\Mail\Email;
use App\Mail\Email_Request;
use App\Mail\Email_Notification;
use App\Mail\Email_VehicleAvailable;
use App\Mail\Email_VehicleSale;
use App\Mail\Email_Job;
use App\Mail\Email_JobAdmin;
use App\Mail\Email_ResetPassword;
use App\Mail\Email_DeleteChoice;
use App\Mail\Email_ValuationNotification;
use App\Mail\Email_Vehicle_Accepted;
use App\Mail\Email_Vehicle_Denied;
use App\Mail\Email_user;
use App\Mail\Email_Financing;
use App\Mail\Email_External_Valuation;
use App\Mail\Email_Notification_Client;



use Illuminate\Support\Facades\Mail;

class EmailHelper {

    public static function sendEmail( String $email, String $password, String $name, String $surname ){
        $data = new \stdClass();
        $data->email = $email;
        $data->password = $password;
        $data->name = $name;
        $data->surname = $surname;

        Mail::to($email)->send(new Email($data));
    }

    public static function sendEmail_Request( String $email, String $name, String $surname, String $marca, String $modelo, String $anio, String $version, String $plan, String $tipo, String $dispocision ){
        $data = new \stdClass();
        $data->email = $email;
        $data->name = $name;
        $data->surname = $surname;
        //datos de modelo del vehiculo
        $data->marca = $marca;
        $data->modelo = $modelo;
        $data->anio = $anio;
        $data->version = $version;
        //datos de financiamiento 
        $data->plan = $plan;
        $data->tipo = $tipo;
        $data->dispocision = $dispocision;
        Mail::to($email)->send(new Email_Request($data));
    }

    public static function Email_Notification( String $email, String $name, String $surname, String $vehicle, String $vin, String $vehicle_name ){
        $data = new \stdClass();
        $data->email = $email;
        $data->name = $name;
        $data->surname = $surname;
        //datos de modelo del vehiculo
        $data->description = $vehicle;
        $data->vehicle_name = $vehicle_name;
        $data->vin = $vin;
        Mail::to($email)->send(new Email_Notification($data));
    }

    public static function Email_VehicleAvailable( String $email, String $name, String $surname, String $vehicle, String $vin ){
        $data = new \stdClass();
        $data->email = $email;
        $data->name = $name;
        $data->surname = $surname;
        //datos de modelo del vehiculo
        $data->description = $vehicle;
        $data->vin = $vin;
        Mail::to($email)->send(new Email_VehicleAvailable($data));
    }

    public static function Email_VehicleSale( String $email, String $name, String $surname, String $vehicle, String $vin ){
        $data = new \stdClass();
        $data->email = $email;
        $data->name = $name;
        $data->surname = $surname;
        //datos de modelo del vehiculo
        $data->description = $vehicle;
        // Vehicles
        $price = 100000;
        $vehicle = Vehicle::where('vin', $vin)->first();
        if( is_object($vehicle) ){
            $price = $vehicle->salePrice;
        }
        $max = $price + 100000;
        $min = $price - 100000;

        $vehicles = Vehicle::whereBetween('salePrice', [$min, $max])->where('vin', '!=', $vin)->get();
        if ($vehicles->count() >= 5) {
            $vehicles = $vehicles->random(5);
        } else {
            $vehicles = $vehicles->random($vehicles->count());
        }
        // Fin Vehicles
        $data->vehicles = $vehicles->load(['vehicle_images']);
        Mail::to($email)->send(new Email_VehicleSale($data));
    }

    public static function Notification_Job( String $email, String $name, String $surname){
        $data = new \stdClass();
        $data->email = $email;
        $data->name = $name;
        $data->surname = $surname;
        Mail::to($email)->send(new Email_Job($data));
    }

    public static function Email_JobAdmin( String $email, String $name, String $surname, String $phone, String $date_of_birth, $file,String $email2){
        $data = new \stdClass();
        $data->email = $email;
        $data->name = $name;
        $data->surname = $surname;
        $data->phone = $phone;
        $data->date_of_birth = $date_of_birth;
        $data->file = $file;
        $data->email2 = $email2;
        $data->file = $file;
        Mail::to($email)->send(new Email_JobAdmin($data));
    }

    public static function resetPassword( String $email, String $name, String $surname, String $token ){
        $data = new \stdClass();
        $data->email = $email;
        $data->name = $name;
        $data->surname = $surname;
        $data->token = $token;
        Mail::to($email)->send(new Email_ResetPassword($data));
    }

    public static function choice( String $email, String $name, String $surname, String $modelo ){
        $data = new \stdClass();
        $data->email = $email;
        $data->name = ucfirst($name);
        $data->surname = ucfirst($surname);       
        $data->modelo = $modelo;
        Mail::to($email)->send(new Email_DeleteChoice($data));
    }

    public static function NewValuation(String $email, string $marca, String $modelo ,int $anio
     ,String $name, String $surname, int $phone, String $emailC ,String $date , String $hour){
        $data = new \stdClass();
        $data->email = $email;
        $data->marca = $marca;
        $data->modelo = $modelo;
        $data->anio = $anio;
        $data->name = ucfirst($name);       
        $data->surname = ucfirst($surname);       
        $data->phone = $phone;    
        $data->date = $date;    
        $data->hour = $hour;    
        $data->emailC = $emailC;    
        Mail::to($email)->send(new Email_ValuationNotification($data));
     }


     public static function vehicleAccepted( String $email, String $name, String $surname, String $date,string $hour ){
        $data = new \stdClass();
        $data->email = $email;
        $data->name = ucfirst($name);
        $data->surname = ucfirst($surname);       
        $data->date = $date;
        $data->hour = $hour;
        Mail::to($email)->send(new Email_Vehicle_Accepted($data));
    }

    public static function vehicleDenied( String $email, String $name, String $surname ){
        $data = new \stdClass();
        $data->email = $email;
        $data->name = ucfirst($name);
        $data->surname = ucfirst($surname);       
        Mail::to($email)->send(new Email_Vehicle_Denied($data));
    }

    public static function sendEmailuser( String $email, String $name, String $surname ){
        $data = new \stdClass();
        $data->email = $email;
        $data->name = $name;
        $data->surname = $surname;

        Mail::to($email)->send(new Email_user($data));
    }

    public static function sendMailCholula(String $email, String $name, String $lastName, String $motherName, String $phone, String $brand, String $model, String $year, String $price, String $hitch, String $montly_fees)
    {
        $data = new \stdClass();
        $data->email = $email;
        $data->name = $name;
        $data->lastName = $lastName;
        $data->motherName = $motherName;
        $data->phone = $phone;
        // datos del vehículo
        $data->brand = $brand;
        $data->model = $model;
        $data->year = $year;
        $data->price = $price;
        $data->hitch = $hitch;
        $data->montly_fees = $montly_fees;
        Mail::to($email)->send(new Email_Financing($data));
    }
    
    public static function sendMailExternalValuation(String $email, String $name, String $lastName, 
                                          String $phone, String $emailClient, String $brand, 
                                          String $model, String $subsidiary, String $date, String $hour, $id)
    {
        $data = new \stdClass();
        $data->id = $id;
        $data->email = $email;
        $data->name = $name;
        $data->lastName = $lastName;
        $data->phone = $phone;
        $data->phone = $emailClient;
        // datos del vehículo
        $data->brand = $brand;
        $data->model = $model;
        $data->year = $subsidiary;
        $data->date = $date;
        $data->hour = $hour;
        Mail::to($email)->send(new Email_External_Valuation($data));
    }
    
    public static function sendMailClient(String $email, String $name, String $surName, String $brand, String $model, String $year, String $price, String $hitch, String $montly_fees, String $status)
    {
        $data = new \stdClass();
        $data->email = $email;
        $data->name = $name;
        $data->surName = $surName;
        // datos del vehículo
        $data->brand = $brand;
        $data->model = $model;
        $data->year = $year;
        $data->price = $price;
        $data->hitch = $hitch;
        $data->montly_fees = $montly_fees;
        // data status
        $data->status = $status;
        Mail::to($email)->send(new Email_Notification_Client($data));
    }
}
