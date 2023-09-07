<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    // Reglas de validación de datos de entrada, para declarar una constante se usa const NOMBRE sin '$'
    private const RULES = [
        'name'    => 'required|string|max:255',
        'email'   => 'required|string|email|unique:clients|max:255',
        'phone'   => 'nullable|string',
        'address' => 'nullable|string', // Permite que el campo sea nulo
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data   = [];
        $status = 200;

        try {
            $clients = Client::all();
            $data = ['clients' => $clients];
        }
        catch (\Exception $e) {
            $data = ['error' => $e->getMessage()];
            $status = 400;
        }

        return response()->json($data, $status);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data   = [];
        $status = 200;

        try {
            // Validación exitosa
            $validated = $this->validarDatosDeEntrada($request->all());

            $client = Client::create($validated);
            $data = [
                'message' => 'Cliente creado exitosamente',
                'client'  => $client
            ];
        }
        catch (\Exception $e) {
            $data = ['error' => $e->getMessage()];
            $status = 400;
        }

        return response()->json($data, $status);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        $data   = [];
        $status = 200;

        try {
            $data = ['client'  => $client];
        }
        catch (\Exception $e) {
            $data = ['error' => $e->getMessage()];
            $status = 400;
        }

        return response()->json($data, $status);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $data   = [];
        $status = 200;

        try {
            // Validación exitosa
            $validated = $this->validarDatosDeEntrada($request->all());

            $client->update($validated);

            $data = [
                'message' => 'Cliente actualizado exitosamente',
                'client'  => $client
            ];
        }
        catch (\Exception $e) {
            $data = ['error' => $e->getMessage()];
            $status = 400;
        }

        return response()->json($data, $status);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        //
    }

    // Valida los campos de entrada y retorna un array con los datos validados o una respuesta de error
    private function validarDatosDeEntrada(array $incomingData) {
        // Las constantes de clase automáticamente son propiedades static
        $validator = Validator::make($incomingData, self::RULES);

        if ($validator->fails()) {
            $data = $validator->errors();
            $status = 400;

            return response()->json($data, $status);
        }

        return $validator->validated();
    }
}
