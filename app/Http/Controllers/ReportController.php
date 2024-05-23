<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Exports\ReportExport;
use Maatwebsite\Excel\Facades\Excel;
   
use Illuminate\Support\Facades\DB;


class ReportController extends Controller 
{
    //


public function descargar(){
  return  Excel::download(new ReportExport, 'reporte.csv');

}
 

    
 
}
