<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Damage;

class DamageController extends Controller
{    
    public function index()
    {
        //
    }
    
    public function store(Request $request)
    {
        //
    }    
    
    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    public function getDamages( String $status, String $without = '' ){        
        $without = explode ( ',', $without );
        $damages = Damage::where('status', $status)->whereNotIn( 'id', $without )->get();
        $data = array(
            'code' => 200,
            'status' => 'success',
            'damages' => $damages
        );
        return response()->json($data, $data['code']);
    }
}
