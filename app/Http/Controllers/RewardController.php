<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\User;
use App\Models\Client;
use App\Models\RewardRequest;

class RewardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        // Get rewards.
        $reward = RewardRequest::paginate(10);
        $reward->load(['client']);

        $data = array(
            'code' => 200,
            'status' => 'success',
            'reward' => $reward
        );
    
        return response()->json($data, $data['code']);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {
        // Check if the user is authorized
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        // Checking request
        if (is_array($request->all()) && $checkToken) {
            // Rules
            $rules = [
                'status' => 'string|in:transferred,authorized,progress,unauthorized,closed',           
                'client_id' => 'required|numeric',
            ];

            try {
                // Validate items
                $validator = \Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    // Error into items
                    $data = array(
                        'code' => 400,
                        'status' => "error",
                        'message' => $validator->errors()->all(),
                    );
                } else {
                    // Find Reward Request
                    $reward = new RewardRequest();
                    
                    // Create reward                    
                    $reward->client_id = $request->client_id;
                    
                    if ($reward->save()) {
                        $data = array(
                            'code'   => 200,
                            'status' => 'success',
                            'message' => "Creación de la solicitud de reward correctamente",
                            'rewards' => $reward->load('client')
                        );
                    } else {
                        $data = array(
                            'code'   => 400,
                            'status' => 'error',
                            'message' => "No se pudo crear la solicitud de reward, verifique la información"
                        );
                    }
                }
            } catch (Exception $e) {
                $data = array(
                    'code'   => 400,
                    'status' => "error",
                    'message' => "Los datos enviados no son correctos, " . $e
                );
            }
        } else {
            $data = array(
                'code' => 401,
                'status' => "error",
                'message' => "El usuario no esta identificado",
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
    public function update(Request $request, $id) {
        // Check if the user is authorized
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        // Checking request
        if (is_array($request->all()) && $checkToken) {
            // Rules
            $rules = [
                'status' => 'required|string|in:transferred,authorized,progress,unauthorized,closed',
            ];

            try {
                // Validate items
                $validator = \Validator::make($request->all(), $rules);

                if ($validator->fails()) {
                    // Error into items
                    $data = array(
                        'code' => 400,
                        'status' => "error",
                        'message' => $validator->errors()->all(),
                    );
                } else {
                    // Find Reward Request
                    $reward = RewardRequest::find($id);

                    if (is_object($reward) && !empty($reward)) {
                        // Update
                        $reward->status = $request->status;
                        
                        if ($reward->save()) {
                            $data = array(
                                'code'   => 200,
                                'status' => 'success',
                                'message' => "Actualización de la solicitud correctamente",
                                'rewards' => $reward
                            );
                        } else {
                            $data = array(
                                'code'   => 400,
                                'status' => 'error',
                                'message' => "Actualización de la solicitud fallida"
                            );
                        }
                    } else {
                        $data = array(
                            'code'   => 404,
                            'status' => "error",
                            'message' => "La solicitud de reward no existe"
                        );
                    }
                }
            } catch (Exception $e) {
                $data = array(
                    'code'   => 400,
                    'status' => "error",
                    'message' => "Los datos enviados no son correctos, " . $e
                );
            }
        } else {
            $data = array(
                'code' => 401,
                'status' => "error",
                'message' => "El usuario no esta identificado",
            );
        }

        return response()->json($data, $data['code']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id) {
        // Check if the user is authorized
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        // Checking request
        if (is_array($request->all()) && $checkToken) {           
            // Reset points
            $client = Client::find($id);            

            if (is_object($client) && !empty($client)) {                
                $client->points = 0;

                // Find if exists reward request
                $reward = RewardRequest::where('client_id', $id)->first();
                if (is_object($reward)) {
                    $reward->status = 'closed';
                    $reward->updated_at = date('Y-m-d H:i:s');
                    $reward->save();
                }
                
                if ($client->save()) {
                    $data = array(
                        'code'   => 200,
                        'status' => 'success',
                        'message' => "Reinicio de puntos correctamente",
                        'client' => $client,
                        'rewards' => $reward,
                    );
                } else {
                    $data = array(
                        'code'   => 400,
                        'status' => 'error',
                        'message' => "Reinicio de puntos fallida"
                    );
                }
            } else {
                $data = array(
                    'code'   => 404,
                    'status' => "error",
                    'message' => "El cliente no existe"
                );
            }
        } else {
            $data = array(
                'code' => 401,
                'status' => "error",
                'message' => "El usuario no esta identificado",
            );
        }

        return response()->json($data, $data['code']);
    }

    /**
     * Find by Client
     */
    public function findClientReward(Request $request, $id) {
        // Check if the user is authorized
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);
        
        // Checking request
        if (is_array($request->all()) && $checkToken) {
            // Get rewards for user.
            $user = User::find($id)->load('clients');
            $redemption = RewardRequest::where('client_id', $user['clients'][0]->id)->where('status', 'progress')->first();

            $data = array(
                'code' => 200,
                'status' => 'success',
                'reward' => $user,
                'redemption' => is_object($redemption)
            );
        } else {
            $data = array(
                'code' => 401,
                'status' => "error",
                'message' => "El usuario no esta identificado",
            );
        }
    
        return response()->json($data, $data['code']);
    }

    /**
     * Checking reference no auto payment
     */
    public function checkingReference(Request $request, $reference, $email) {
        // Check if the user is authorized
        $token = $request->header('Authorization');
        $jwtAuth = new \App\Helpers\JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        // Checking request
        if (is_array($request->all()) && $checkToken) {
            // Find user of get client
            $user = User::where('email', $email)->first();

            if (is_object($user) && !empty($user)) {   
                $client = $user->load('clients');

                // Get reference rewards
                $reference_rewards = $client->clients[0]->rewards;

                if ($reference_rewards === $reference) {
                    $data = array(
                        'code'   => 400,
                        'status' => "error",
                        'message' => "¡Opps, al parecer no puedes auto referenciarte a ti mismo!"
                    );
                } else {
                    $data = array(
                        'code'   => 200,
                        'status' => 'success',
                        'message' => "Referencia ingresada valida para ser asignada",
                        'rewards' => $client
                    );
                }
            } else {
                $data = array(
                    'code'   => 404,
                    'status' => "error",
                    'message' => "El usuario no existe"
                );
            }
        } else {
            $data = array(
                'code' => 401,
                'status' => "error",
                'message' => "El usuario no esta identificado",
            );
        }

        return response()->json($data, $data['code']);
    }

    /**
     * Send email to user when send request of cash redemption 
    */

}
