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
        $this->info('=== Install larrockCMS core packages ===');

        $packages = [
            'larrock-menu', 'larrock-users', 'larrock-pages', 'larrock-blocks',
            'larrock-contact', 'larrock-admin-seo', 'larrock-search'
        ];

        foreach ($packages as $package) {
            $this->info('composer require fanamurov/'. $package .':^1.0');
            echo shell_exec('composer require fanamurov/'. $package .':^1.0');
        }

        $this->info('LarrockCMS components installed');
    }
}