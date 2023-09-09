<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Service;
use Illuminate\Http\Request;

class ClientServiceController extends Controller
{
    // Método para devolver los clientes con sus servicios contratados y a la inversa
    public function contracts(string $type = null) {
        $data   = [];
        $status = 200;
        $types  = null;

        try {
            switch($type) {
                case 'clients':
                    // Devuelve todos los clientes con sus servicios (tengan o no contrataciones)
                    $types = Client::with('services')->get();

                    // Devuelve todos los clientes que tengan servicios contratados
                    // $clients = Client::has('services')->with('services')->get();
                    break;
                case 'services':
                    // Devuelve todos los clientes con sus servicios (tengan o no contrataciones)
                    $types = Service::with('clients')->get();

                    // Devuelve todos los clientes que tengan servicios contratados
                    // $services = Service::has('clients')->with('services')->get();
                    break;
                default:
                    $type  = 'Error';
                    $types = 'Se espera el parámetro type: clients || services';
                    break;
            }

            $data = [$type => $types];
        }
        catch (\Exception $e) {
            $data = ['error' => $e->getMessage()];
            $status = 400;
        }

        return response()->json($data, $status);
    }

    /* // Método para que los clientes contraten servicios
    public function attach(Request $request) {
        $data   = [];
        $status = 200;

        try {
            $client = Client::findOrFail($request->client_id);
            $client->services()->attach($request->service_id);
            $service = $client->services()->where('services.id', $request->service_id)->first();

            $data = [
                'message'  => 'Servicio agregado exitosamente',
                'client'   => $client,
                'service'  => $service,
                'contract' => $service->pivot,
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

    // Método para que los clientes den de baja el contrato de servicios
    public function detach(Request $request) {
        $data   = [];
        $status = 200;

        try {
            $client = Client::findOrFail($request->client_id);
            $client->services()->detach($request->service_id);
            $service = $client->services()->where('services.id', $request->service_id)->first();

            $data = [
                'message'  => 'Servicio descontratado exitosamente',
                'client'   => $client,
                'service'  => $service,
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
    } */
}
