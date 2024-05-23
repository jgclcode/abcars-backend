<?php

namespace App\Http\Controllers;

use App\Models\Technician;
use Illuminate\Http\Request;

class TechnicianController extends Controller
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Technician  $technician
     * @return \Illuminate\Http\Response
     */
    public function show(Technician $technician)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Technician  $technician
     * @return \Illuminate\Http\Response
     */
    public function edit(Technician $technician)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Technician  $technician
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Technician $technician)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Technician  $technician
     * @return \Illuminate\Http\Response
     */
    public function destroy(Technician $technician)
    {
        //
    }

    public function technicians($user_id){
        $data_technician = Technician::firstWhere('user_id', $user_id);
        if (is_object($data_technician) && !is_null($data_technician)) {
            $data = array(
                'status' => 'success',
                'code' => '200',
                'data_technician' => $data_technician
            );
        }else{
            $data = array(
              'status' => 'error',
              'code'   => '404',
              'message' => 'El id del Usuario no existe'
            );
          }

        return response()->json($data, $data['code']); 
    }
}
