<?php

namespace App\Http\Controllers;

use App\MH\Classes\Helper;
use App\Models\Service;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
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
            $services = Service::all();
            $data = ['services' => $services];
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
            $validated = $this->validarDatosDeEntrada($request->all(), 'service');

            // Si el campo status existe, significa que la validación falló
            if(isset($validated['status'])) {
                return response()->json(['errors' => $validated['data']], $validated['status']);
            }

            $service = Service::create($validated);
            $data = [
                'message' => 'Servicio creado exitosamente',
                'service'  => $service
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
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data   = [];
        $status = 200;

        try {
            // Buscar el servicio con el id recibido, si no existe lanza una excepción
            $service = Service::findOrFail($id);
            $data = ['service'  => $service];
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
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data   = [];
        $status = 200;

        try {
            $service = Service::findOrFail($id);
            // Validación de datos de entrada
            $validated = $this->validarDatosDeEntrada($request->all(), 'service');

            // Si el campo status existe, significa que la validación falló
            if(isset($validated['status'])) {
                return response()->json(['errors' => $validated['data']], $validated['status']);
            }

            $service->update($validated);

            $data = [
                'message' => 'Servicio actualizado exitosamente',
                'service'  => $service
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
     * @param  \App\Models\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data   = [];
        $status = 200;

        try {
            // Buscar el servicio con el id recibido, si no existe lanza una excepción
            $service = Service::findOrFail($id);
            $service->delete();

            $data = [
                'message' => 'Servicio eliminado exitosamente',
                'service'  => $service
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

    // Valida los campos de entrada y retorna un array con los datos validados o una respuesta de error
    private function validarDatosDeEntrada(array $incomingData, string $class, bool $email = false, $id = null) {
        $validator = Validator::make($incomingData, Helper::getRules($class, $email, $id));

        if ($validator->fails()) {
            // Devuelve un array con todos los errores
            $data = $validator->errors()->all();
            $status = 400;

            return compact('data', 'status');
        }

        return $validator->validated();
    }
}
