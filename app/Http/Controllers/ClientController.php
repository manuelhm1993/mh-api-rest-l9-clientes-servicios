<?php

namespace App\Http\Controllers;

use App\MH\Classes\Helper;
use App\Models\Client;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ClientController extends Controller
{
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
            // Validación de datos de entrada
            $validated = Helper::validarDatosDeEntrada($request->all(), 'client');

            // Si el campo status existe, significa que la validación falló
            if(isset($validated['status'])) {
                return response()->json(['errors' => $validated['data']], $validated['status']);
            }

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
    public function show($id)
    {
        $data   = [];
        $status = 200;

        try {
            // Buscar el cliente con el id recibido, si no existe lanza una excepción
            $client = Client::findOrFail($id);
            $data = ['client'  => $client];
        }
        catch (ModelNotFoundException $e) {
            $data = ['error' => $e->getMessage()];
            $status = 404;
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
    public function update(Request $request, $id)
    {
        $data   = [];
        $status = 200;

        try {
            $client = Client::findOrFail($id);
            // Validación de datos de entrada
            $validated = Helper::validarDatosDeEntrada($request->all(), 'client', true, $client->id);

            // Si el campo status existe, significa que la validación falló
            if(isset($validated['status'])) {
                return response()->json(['errors' => $validated['data']], $validated['status']);
            }

            $client->update($validated);

            $data = [
                'message' => 'Cliente actualizado exitosamente',
                'client'  => $client
            ];
        }
        catch (ModelNotFoundException $e) {
            $data = ['error' => $e->getMessage()];
            $status = 404;
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
    public function destroy($id)
    {
        $data   = [];
        $status = 200;

        try {
            // Buscar el cliente con el id recibido, si no existe lanza una excepción
            $client = Client::findOrFail($id);
            $client->delete();

            $data = [
                'message' => 'Cliente eliminado exitosamente',
                'client'  => $client
            ];
        }
        catch (ModelNotFoundException $e) {
            $data = ['error' => $e->getMessage()];
            $status = 404;
        }
        catch (\Exception $e) {
            $data = ['error' => $e->getMessage()];
            $status = 400;
        }

        return response()->json($data, $status);
    }
}
