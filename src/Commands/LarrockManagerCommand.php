<?php

namespace Larrock\Core\Commands;

use Illuminate\Console\Command;

class LarrockManagerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larrock:manager';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Package manager for LarrockCMS';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('=== LarrockCMS Package manager ===');

        $packages = [
            'fanamurov/larrock-catalog', 'fanamurov/larrock-cart', 'fanamurov/larrock-wizard',
            'fanamurov/larrock-discount', 'fanamurov/larrock-feed', 'fanamurov/larrock-category',
            'fanamurov/larrock-reviews', 'fanamurov/larrock-smartbanners', 'fanamurov/larrock-menu',
            'fanamurov/larrock-users', 'fanamurov/larrock-pages', 'fanamurov/larrock-blocks',
            'fanamurov/larrock-contact', 'fanamurov/larrock-admin-seo', 'fanamurov/larrock-search',
            'fanamurov/larrock-yandex-kassa', 'fanamurov/larrock-vscale'
        ];

        $question = array_prepend($packages, 'All');
        $question[] = 'Do not install other packages';

        $name = $this->choice('What to install/update?', $question);

        if($name === 'all') {
            $this->info('Install all packages LarrockCMS');
            echo shell_exec('composer require '. implode(':^1.0 ', $packages) .' --prefer-dist');
        }elseif($name !== 'Do not install other packages'){
            $this->info('composer require fanamurov/'. $name .':^1.0 --prefer-dist');
            echo shell_exec('composer require fanamurov/'. $name .':^1.0 --prefer-dist');
        }

        if($name !== 'Do not install other packages'){
            $this->call('vendor:publish');
            $this->call('migrate');
        }

        $this->info('The task is completed! Thank you for using LarrockCMS');
    }
}