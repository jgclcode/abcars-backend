<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Document;

class DocumentController extends Controller
{    
    public function index()
    {
        $documents = Document::all();
        $data = array(
            'code' => 200, 
            'status' => 'success',
            'documents' => $documents
        );
        return response()->json($data, $data['code']);
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
}
