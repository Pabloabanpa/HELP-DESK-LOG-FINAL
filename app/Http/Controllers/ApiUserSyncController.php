<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;

class ApiUserSyncController extends Controller
{
    /**
     * Sincroniza los usuarios consumiendo la API externa.
     *
     * Cada persona del servicio se registrará como usuario local.
     * Se asigna en el campo "name" la concatenación de "personanombre" y "personaap",
     * se genera el correo concatenando el nombre y apellido (en minúsculas y separados por puntos)
     * y se asigna como contraseña "1234" seguido del nombre (sin espacios).
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function syncUsers()
    {
        // Aumenta el tiempo máximo de ejecución y el timeout de la petición
        set_time_limit(300);
        $response = Http::timeout(300)->get('https://16d0-131-0-197-113.ngrok-free.app/authRouter');

        if ($response->successful()) {
            $data = $response->json();

            // Si se recibe un único objeto, encapsúlalo en un array
            if (!is_array($data) || isset($data['idpersona'])) {
                $data = [$data];
            }

            // Obtén todos los emails de usuarios existentes en la BD
            $existingEmails = User::pluck('email')->toArray();

            // Prepara un arreglo para insertar solo los usuarios nuevos
            $newUsers = [];

            foreach ($data as $item) {
                // Genera el nombre completo
                $fullName = trim($item['personanombre']) . ' ' . trim($item['personaap']);
                // Genera el email a partir del nombre completo (minúsculas y puntos)
                $dummyEmail = strtolower(str_replace(' ', '.', $fullName)) . '@ejemplo.com';

                // Si el usuario ya existe, lo omitimos
                if (in_array($dummyEmail, $existingEmails)) {
                    continue;
                }

                // Genera la contraseña: "1234" concatenado con el nombre sin espacios
                $passwordPlain = '1234' . str_replace(' ', '', $item['personanombre']);

                $newUsers[] = [
                    'name'       => $fullName,
                    'email'      => $dummyEmail,
                    'cargo'      => $item['cargo'],
                    'oficina'    => $item['oficina'],
                    'password'   => Hash::make($passwordPlain),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // Inserta en lote solo si hay nuevos usuarios
            if (!empty($newUsers)) {
                User::insert($newUsers);
            }

            return redirect()->route('admin.user.index')->with('success', 'Usuarios sincronizados correctamente.');
        }

        return redirect()->route('admin.user.index')->with('error', 'Error al sincronizar usuarios.');
    }
}
