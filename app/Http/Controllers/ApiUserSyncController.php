<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SyncUserJob;

class ApiUserSyncController extends Controller
{
    /**
     * Sincroniza los usuarios consumiendo la API externa.
     *
     * Los datos esperados de la API son:
     * {
     *   "idpersona": 29,
     *   "personanombre": "IVAN JAVIER",
     *   "personaap": "CASTRO CHOQUE",
     *   "idcargo": 5,
     *   "cargo": "AUXILIAR",
     *   "idoficina": 1,
     *   "oficina": "DESPACHO DEL ALCALDE MUNICIPAL",
     *   "latitude": "-21.534231237189875",
     *   "longitude": "-64.73472213644303"
     * }
     *
     * Para cada registro:
     *  - El campo "name" se construye concatenando "personanombre" y "personaap".
     *  - El email se genera tomando el apellido y el nombre (sin espacios, en minúsculas) seguido de "@gmail.com".
     *    Ejemplo: "CASTRO CHOQUE" + "IVAN JAVIER" → "castrochoqueivanjavier@gmail.com".
     *  - El password se genera a partir del string "1234" concatenado con "personanombre" (sin espacios y en minúsculas),
     *    y luego se hashea.
     *
     * Se utiliza updateOrCreate basado en el email generado.
     * Además, si el usuario es nuevo, se le asigna el rol "solicitante".
     *
     * Para mejorar la escalabilidad, cada registro se envía a un Job que se procesará en segundo plano.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function syncUsers()
    {
        // Aumenta el tiempo máximo de ejecución y la memoria asignada
        ini_set('max_execution_time', 600);
        ini_set('memory_limit', '1024M');

        // Realiza la petición HTTP al endpoint del servicio externo
        $response = Http::timeout(300)->get('https://5bee-131-0-197-113.ngrok-free.app/authRouter');

        if ($response->successful()) {
            $data = $response->json();

            // Si se recibe un único objeto, lo encapsula en un arreglo para iterar
            if (!is_array($data) || isset($data['idpersona'])) {
                $data = [$data];
            }

            // Despacha un Job para cada registro para procesarlos en segundo plano
            foreach ($data as $item) {
                SyncUserJob::dispatch($item);
            }

            // Retorna inmediatamente, ya que el procesamiento se hará en background
            return redirect()->route('admin.user.index')
                ->with('success', 'Proceso de sincronización iniciado. Los usuarios se procesarán en segundo plano.');
        }

        return redirect()->route('admin.user.index')
            ->with('error', 'Error al sincronizar usuarios.');
    }
}
