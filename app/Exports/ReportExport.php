<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use App\Models\Item;


 
use Maatwebsite\Excel\Concerns\FromCollection;

class ReportExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        // 
        $items = Item::whereNotIn('category', ['Oculto'])->get();


            $vehicles = DB::select(" SELECT 
            v.name,
            v.vin,
            c.name as model,
            (
            SELECT
            GROUP_CONCAT(
            CONCAT( ' ', fi.value) SEPARATOR ','
            ) as dato
            FROM forms f 
            INNER JOIN form_item fi ON fi.form_id = f.id
            INNER JOIN items i ON fi.item_id = i.id
            WHERE f.vehicle_vin = v.vin
            ) as datos              
            FROM vehicles v 
            INNER JOIN carmodels c ON c.id = v.carmodel_id 
            INNER JOIN forms f ON f.vehicle_vin = v.vin
            INNER JOIN form_item fi ON fi.form_id = f.id
            INNER JOIN items i on fi.item_id = i.id             
            WHERE  i.category != 'Oculto'       
            GROUP BY 
            v.name, v.vin, c.name
        ");  

        $reporte =array();
        foreach ($vehicles as $value) {
            $array = explode(",", $value->datos);
            array_push ($reporte ,array(
                'vehiculo' =>  $value->name,
                'vin' =>  $value->vin,
                'respuestas' => $array
            ));
        }
         return view('check.table', ['reporte' => $reporte ,'items' =>$items]);
    }


 
}
