<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Service;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
                    $status = 404;
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

    // Método para que los clientes contraten servicios y agregar clientes a servicios
    public function attach(Request $request, string $type = null) {
        $data     = [];
        $status   = 200;
        $message  = '';
        $client   = null;
        $service  = null;
        $contract = null;
        $types    = null;

        try {
            switch($type) {
                case 'clients':
                    $client = Client::findOrFail($request->client_id);
                    $client->services()->attach($request->service_id);

                    $service = $client->services()->where('services.id', $request->service_id)->first();

                    $message  = 'Servicio agregado exitosamente';
                    $contract = $service->pivot;
                    break;
                case 'services':
                    $service = Service::findOrFail($request->service_id);
                    $service->clients()->attach($request->client_id);

                    $client = $service->clients()->where('clients.id', $request->client_id)->first();

                    $message  = 'Cliente agregado exitosamente';
                    $contract = $client->pivot;
                    break;
                default:
                    $type   = 'Error';
                    $types  = 'Se espera el parámetro type: clients || services';
                    $status = 404;
                    break;
            }

            if($type === 'Error') {
                $data = [$type => $types];
            }
            else {
                $data = [
                    'message'  => $message,
                    'client'   => $client,
                    'service'  => $service,
                    'contract' => $contract,
                ];
            }
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

    // Método para que los clientes den de baja el contrato de servicios y a la inversa
    public function detach(Request $request, string $type = null) {
        $data     = [];
        $status   = 200;
        $client   = null;
        $service  = null;
        $message  = '';
        $types    = null;

        try {
            switch($type) {
                case 'clients':
                    $client = Client::findOrFail($request->client_id);
                    $client->services()->detach($request->service_id);

                    $service  = $client->services()->where('services.id', $request->service_id)->first();
                    $message  = 'Servicio descontratado exitosamente';
                    break;
                case 'services':
                    $service = Service::findOrFail($request->service_id);
                    $service->clients()->detach($request->client_id);

                    $client = $service->clients()->where('clients.id', $request->client_id)->first();
                    $message  = 'Cliente descontratado exitosamente';
                    break;
                default:
                    $type   = 'Error';
                    $types  = 'Se espera el parámetro type: clients || services';
                    $status = 404;
                    break;
            }

            if($type === 'Error') {
                $data = [$type => $types];
            }
            else {
                $data = [
                    'message'  => $message,
                    'client'   => $client,
                    'service'  => $service,
                ];
            }
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
