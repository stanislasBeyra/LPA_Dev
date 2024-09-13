<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CacheClear extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cacheclear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Effacement des caches en cours...');

        // Exécute les commandes Artisan pour effacer les caches
        Artisan::call('config:clear');
        $this->info('Configuration cache effacé');

        Artisan::call('route:clear');
        $this->info('Route cache effacé');

        Artisan::call('view:clear');
        $this->info('Vue cache effacé');

        Artisan::call('cache:clear');
        $this->info('Cache effacé');

        $this->info('Tous les caches ont été effacés avec succès !');

        return 0;
    }
}
