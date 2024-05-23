<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sheet_quote;

class Sheet_quoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sheet_quote = Sheet_quote::get();
        $data = array(
            'code' => 200,
            'status' => 'success',
            'sheet_quote' => $sheet_quote
        );
        return response()->json($data, $data['code']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!is_array($request->all())) {
            $data = array(
                'status'  => 'error',
                'code'    => '200',
                'message' => 'request must be an array'
            );
        }

        $rules = [
            'body'     => 'required|max:255|string',
            'brand'    => 'required|max:255|string',
            'model'    => 'required|max:255|string',
            'name'     => 'required|max:255|string',
            'surname'  => 'required|max:255|string',
            'email'    => 'required|max:255|string',
            'phone'    => 'required|integer',
            // 'buyType'  => 'required|max:255|string'
            'buyType'  => 'max:255|string',
            'clientPriceOffer' => 'integer'
        ];

        try {
            // validación de tipado y campos requeridos
            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                // existió un error en los campos enviados
                $data = array(
                    'status' => 'error',
                    'code'   => '200',
                    'errors' => $validator->errors()->all()
                );
            } else {
                $sheetQuotation = array(
                    'model'             => $request->model,
                    'name'              => $request->name,
                    'surname'           => $request->surname,
                    'email'             => $request->email,
                    'phone'             => $request->phone,
                    'buyType'           => $request->buyType,
                    'clientPriceOffer'  => $request->clientPriceOffer,
                    'fecha'   => date_format(date_create(), "d-m-y H:i:s"), 
                    'cuandoDeseaEstrenar'  => $request->wantRelease,
                    'inversionInicial'  => $request->initialCredit,
                    'situacionProfesional'  => $request->WhatsCurrentProfessionalSituation,
                    'commentarioLead'  => $request->commentaryLead
                );
                // dd($request->body, $request->brand, $request->model,
                // $request->name, $request->surname, $request->email,
                // $request->phone, $request->buyType);
                // Creación de sheet_quote
                $sheet_quote = new Sheet_quote();
                $sheet_quote->body = $request->body;
                $sheet_quote->brand = $request->brand;
                $sheet_quote->model = $request->model;
                $sheet_quote->name = $request->name;
                $sheet_quote->surname = $request->surname;
                $sheet_quote->email = $request->email;
                $sheet_quote->phone = $request->phone;
                $sheet_quote->buyType = $request->buyType;
                $sheet_quote->clientPriceOffer = $request->clientPriceOffer;
                $sheet_quote->save();

                $this->sheetQuotation( $sheetQuotation );

                $data = array(
                    'status'    => 'success',
                    'code'      => '200',
                    'message'   => 'La cotización en spreadsheet se ha creado exitosamente.',
                    'sheet_quote' => $sheet_quote
                );
            }
        } catch (Exception $e) {
            $data = array(
                'status' => 'error',
                'code'   => '200',
                'message' => 'Los datos enviados no son correctos, ' . $e
            );
        }

        return response()->json($data, $data['code']);
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

    public function sheetQuotation( Array $datos )
    {
        $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, "https://hooks.zapier.com/hooks/catch/8825119/3d0kl8b");
        curl_setopt($ch, CURLOPT_URL, "https://hooks.zapier.com/hooks/catch/8825119/3x68csl");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($datos));

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    public function get_client_price_offer()
    {
        // $client_price_offer = Sheet_quote::whereNotNull('clientPriceOffer')
        //                             ->orderBy('id', 'desc')
        //                             ->paginate(10);
        //                             $data = array(
        //                                 'code' => 200,
        //                                 'status' => 'success',
        //                                 'client_price_offer' => $client_price_offer
        //                             );
        //                             return response()->json($data, $data['code']);

        $client_price_offer = Sheet_quote::select('sheet_quotes.id', 'sheet_quotes.name', 'sheet_quotes.surname', 'sheet_quotes.phone', 'sheet_quotes.body',
                                                  'sheet_quotes.brand', 'sheet_quotes.model', 'vehicles.priceList', 'vehicles.vin', 'sheet_quotes.clientPriceOffer',
                                                  'sheet_quotes.created_at')
                                            ->join('vehicles', 'vehicles.name', 'sheet_quotes.model')
                                            ->where('vehicles.status', 'active')
                                            ->whereNull('vehicles.deleted_at')
                                            ->whereNotNull('sheet_quotes.clientPriceOffer')
                                            ->orderBy('sheet_quotes.id', 'desc')
                                            ->paginate(10);
                                            $data = array(
                                                        'code' => 200,
                                                        'status' => 'success',
                                                        'client_price_offer' => $client_price_offer
                                                    );
                                            return response()->json($data, $data['code']);
    }

    public function search_report_offer(String $word = '', int $cantidad)
    {
        // dd($word);

        // Word
        if ($word === 'a') {
            $word_condition = "sheet_quotes.body LIKE NOT NULL";
        }else {
            $word_condition = "sheet_quotes.body LIKE '%$word%' 
                            OR sheet_quotes.brand LIKE '%$word%' 
                            OR sheet_quotes.model LIKE '%$word%' 
                            OR sheet_quotes.name LIKE '%$word%' 
                            OR sheet_quotes.surname LIKE '%$word%' 
                            OR sheet_quotes.phone LIKE '%$word%' 
                            OR sheet_quotes.clientPriceOffer LIKE '%$word%' 
                            OR vehicles.vin LIKE '%$word%' 
                            ";
        }

        $sheet_quotes = Sheet_quote::select('sheet_quotes.*', 'vehicles.priceList', 'vehicles.vin')
                                    ->join('vehicles', 'vehicles.name', 'sheet_quotes.model')
                                    ->where('vehicles.status', 'active')
                                    ->whereNull('vehicles.deleted_at')
                                    ->whereNotNull('clientPriceOffer')
                                    ->whereRaw(
                                        "
                                            ( 
                                                $word_condition 
                                            )
                                        "
                                    )
                                    ->paginate($cantidad);
        $data = array(
            'code' => 200,
            'status' => 'success',
            'sheet_quotes' => $sheet_quotes 
        );

        return response()->json($data, $data['code']);

    }
}
