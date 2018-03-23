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
    protected $description = 'Manage larrockCMS packages';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('=== LarrockCMS Manager ===');

        $packages = [
            'Do not install other packages', 'larrock-catalog', 'larrock-cart', 'larrock-wizard', 'larrock-discount',
            'larrock-feed', 'larrock-category', 'larrock-reviews', 'larrock-smartbanners',
            'larrock-menu', 'larrock-users', 'larrock-pages', 'larrock-blocks', 'larrock-contact',
            'larrock-admin-seo', 'larrock-search', 'larrock-yandex-kassa', 'all'
        ];

        $name = $this->choice('What to install/update?', $packages);

        if($name === 'all') {
            $this->info('Install all packages LarrockCMS');
            foreach ($packages as $package) {
                if ($package !== 'all' && $package !== 'Do not install other packages') {
                    $this->info('composer require fanamurov/' . $package .':^1.0 --prefer-dist');
                    echo shell_exec('composer require fanamurov/' . $package .':^1.0 --prefer-dist');
                }
            }
        }elseif($name === 'Do not install other packages'){
            $this->info('Do not install other packages');
        }else{
            $this->info('composer require fanamurov/'. $name .':^1.0 --prefer-dist');
            echo shell_exec('composer require fanamurov/'. $name .':^1.0 --prefer-dist');
        }

        $this->call('vendor:publish');
        $this->call('migrate');

        $this->info('The task is completed! Thank you for using LarrockCMS');
    }
}