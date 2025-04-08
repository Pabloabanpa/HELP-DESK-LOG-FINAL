<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Los datos del usuario externo.
     *
     * @var array
     */
    protected $item;

    /**
     * Crea una nueva instancia del Job.
     *
     * @param array $item
     * @return void
     */
    public function __construct(array $item)
    {
        $this->item = $item;
    }

    /**
     * Ejecuta el Job de sincronización.
     *
     * Actualiza o crea un usuario basado en el email generado y asigna el rol "solicitante"
     * si el usuario es nuevo.
     *
     * @return void
     */
    public function handle()
    {
        $item = $this->item;
        $fullName = trim($item['personanombre']) . ' ' . trim($item['personaap']);

        $emailLocalPart = strtolower(str_replace(' ', '', $item['personaap'] . $item['personanombre']));
        $dummyEmail = $emailLocalPart . '@gmail.com';

        $passwordBase = '1234' . str_replace(' ', '', strtolower($item['personanombre']));

        // Usa updateOrCreate basado en el email
        $user = User::updateOrCreate(
            ['email' => $dummyEmail],
            [
                'name'       => $fullName,
                'cargo'      => $item['cargo'],
                'oficina'    => $item['oficina'],
                'latitude'   => $item['latitude'] ?? null,
                'longitude'  => $item['longitude'] ?? null,
                'password'   => Hash::make($passwordBase)
            ]
        );

        // Asigna el rol "solicitante" si es un usuario nuevo
        if ($user->wasRecentlyCreated) {
            $user->assignRole('solicitante');
        }
    }
}
