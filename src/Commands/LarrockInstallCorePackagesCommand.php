<?php

namespace Larrock\Core\Commands;

use Illuminate\Console\Command;

class LarrockInstallCorePackagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larrock:installcorepackages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install larrockCMS core packages';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('=== Install larrockCMS core components ===');

        echo shell_exec('composer require fanamurov/larrock-menu:^1.0 fanamurov/larrock-users:^1.0 fanamurov/larrock-pages:^1.0 fanamurov/larrock-blocks:^1.0 fanamurov/larrock-contact:^1.0 fanamurov/larrock-admin-seo:^1.0 fanamurov/larrock-search:^1.0 --prefer-dist');

        $this->info('LarrockCMS core components installed');
    }
}
