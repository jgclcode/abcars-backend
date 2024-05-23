<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JobsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        
        //especificacion de tipado y campos requeridos 
        $rules = [
            'name' => 'required|max:255|string',
            'surname' => 'required|max:255|string',
            'email' => 'required|max:255|string',
            'phone' => 'required|integer',
            'date_of_birth' => 'required|date',
            'file' => 'required|mimes:pdf'
        ];
        
        try{
            //validacion de tipado y campos requeridos 
            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                //existio un error en los campos enviados 
                $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'errors'  => $validator->errors()->all()
                );
            }else{        
                //envio a usuario
                \EmailHelper::Notification_Job( $request->email, $request->name, $request->surname); 
                //envio a administrador                
                \EmailHelper::Email_JobAdmin( "f170004@gmail.com", $request->name, $request->surname, $request->phone, $request->date_of_birth,  $request->file('file') ,$request->email );                
        
                $data = array(
                'status' => 'success',
                'code'   => '200',
                'message' => 'Alerta de empleo enviada exitosamente'
                );                
            }

        }catch (Exception $e){
            $data = array(
            'status' => 'error',
            'code'   => '200',
            'message' => 'Los datos enviados no son correctos, ' . $e
            );
        }   
    
        return response()->json($data, $data['code']);
    }
    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
