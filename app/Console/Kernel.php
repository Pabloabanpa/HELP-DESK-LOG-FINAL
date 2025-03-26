<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define la programación de tareas (cron jobs).
     */
    protected function schedule(Schedule $schedule)
    {
        // Aquí puedes programar tareas, por ejemplo:
        // $schedule->command('sync:users')->hourly();
    }

    /**
     * Registra los comandos Artisan personalizados.
     */
    protected function commands()
    {
        // Carga cualquier comando que se encuentre en app/Console/Commands
        $this->load(__DIR__.'/Commands');

        // Carga rutas de consola si las usas (routes/console.php)
        require base_path('routes/console.php');
    }
}
