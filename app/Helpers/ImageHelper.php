<?php
namespace App\Helpers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use App\Models\Vehicle;
use App\Models\Sell_your_car;

class ImageHelper {

    public static function fileGetContentsCurl($url) {
        $options[CURLOPT_HEADER] = 0;
        $options[CURLOPT_RETURNTRANSFER] = 1;
        $options[CURLOPT_URL] = $url;

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    public static function saveImage($image, $file_name) {

        try{
            $file =  fopen($file_name , 'w');
            if(false === $file) throw new \RuntimeException("Failed to open file");
            if(false === fwrite($file, $image)) throw new \RuntimeException("Failed to write to file");
            if(false === fclose($file)) throw new \RuntimeException("Failed to close file");

        }catch(Exception $e){
            return false;
        }

        return true;
    }

    public static function upload_vehicleImage( $image, String $directory , $vehicle_id, $quality = false) {

        // Buscar el vehiculo por id
       // $vehicle = Vehicle::find( $vehicle_id )->withTrashed()->get();
        $vehicle = Vehicle::where('id', $vehicle_id)->withTrashed()->first();

        // Crear nombre y ruta para guardar imagen
        $name = time().pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) ."_".$vehicle_id.'_'.$vehicle->vin.'.jpg';

        // Quitar espacios en nombre del file
        $name = str_replace(' ', '', $name);
        $path = storage_path() . "/app/$directory/".$name;

        if($quality == false){
            $compressedImageURL = cloudinary()->upload($image->getRealPath(), [
                'folder' => 'uploads',
                'transformation' => [
                    'quality' => 'auto',
                    'fetch_format' => 'auto'
                ]
            ])->getSecurePath();
        
        } else {
            
            $compressedImageURL = cloudinary()->upload($image->getRealPath(), [
                'folder' => 'uploads',
                'quality' => $quality,
            ])->getSecurePath();

        }

        $compressedImage = self::fileGetContentsCurl($compressedImageURL);

        // Valida si la imagen comprimida se ha descargado satisfactoriamente de Cloudinary
        if($compressedImage === false){
            // En caso de que no exista la imagen entonces se retorna un nombre con valor falso
            // Se podría regresar una cadena con un valor específico como "FallaCompresion"
            $name = false;

            // Se retorna el valor de $name
            return $name;

        } else {
            // En caso de que exista, se guarda la imagen
            $saved =  self::saveImage($compressedImage, $path);

            // Valida el guardado de la imágen
            if($saved){
                // En caso de que la imagen se guarde correctamente entonces se regresa el valor $name
                // Se retorna el valor en $name
                return $name;
            } else {
                // En caso de que la imagen no se guarde correctamente se establece $name con valor falso
                // Se podría regresar una cadena con un valor específico como "FallaGuardado"
                $name = false;

                // Se retorna el valor en $name
                return $name;
            }
        }
    }

    public static function upload(  $image, String $directory ) {
        // Crear nombre y ruta para guardar imagen
        $nombre = time() .  pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) . '.png';

        // Quitar espacios en nombre del file
        $nombre = str_replace(' ', '', $nombre);

        $ruta = storage_path() . "/app/$directory/" . $nombre;

        //Guardar imagen con nuevas medidas
        Image::make( $image )
            ->encode('png', 65)
            ->resize(1080, null, function ($constraint) {
                $constraint->aspectRatio();
                })
                ->save($ruta);

        return $nombre;
    }

    public static function upload_damageImage( $image, String $directory, $sell_your_car_id)
    {
        // Buscar el sell_your_car por id
        $sell_your_car = Sell_your_car::find($sell_your_car_id);

        // Crear nombre y ruta para guardar imagen
        $name = time().pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME) ."_".$sell_your_car_id.'_'.$sell_your_car->vin.'.jpg';

        // Quitar espacios en nombre del file
        $name = str_replace(' ', '', $name);
        $path = storage_path() . "/app/$directory/".$name;

        $compressedImageURL = cloudinary()->upload($image->getRealPath(), [
            'folder' => 'damages',
            'transformation' => [
                'quality' => 'auto',
                'fetch_format' => 'auto'
            ]
        ])->getSecurePath();

        $compressedImage = self::fileGetContentsCurl($compressedImageURL);

        // Valida si la imagen comprimida se ha descargado satisfactoriamente de Cloudinary
        if($compressedImage === false){
            // En caso de que no exista la imagen entonces se retorna un nombre con valor falso
            // Se podría regresar una cadena con un valor específico como "FallaCompresion"
            $name = false;

            // Se retorna el valor de $name
            return $name;

        } else {
            // En caso de que exista, se guarda la imagen
            $saved =  self::saveImage($compressedImage, $path);

            // Valida el guardado de la imagen
            if($saved){
                // En caso de que la imagen se guarde correctamente entonces se regresa el valor $name
                // Se retorna el valor en $name
                return $name;
            } else {
                // En caso de que la imagen no se guarde correctamente se establece $name con valor falso
                // Se podría regresar una cadena con un valor específico como "FallaGuardado"
                $name = false;

                // Se retorna el valor en $name
                return $name;
            }
        }
    }

    public static function delete( String $directory, $name ){
        Storage::delete($directory . "/" . $name);
    }
}
