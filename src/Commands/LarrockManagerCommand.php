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
            'larrock-catalog', 'larrock-cart', 'larrock-wizard', 'larrock-discount',
            'larrock-feed', 'larrock-category', 'larrock-reviews', 'larrock-smartbanners',
            'larrock-menu', 'larrock-users', 'larrock-pages', 'larrock-blocks', 'larrock-contact',
            'larrock-admin-seo', 'larrock-search', 'all'
        ];

        $name = $this->choice('What to install/update?', $packages);

        if($name === 'all'){
            $this->info('Install all packages LarrockCMS');
            foreach ($packages as $package){
                if($package !== 'all'){
                    $this->info('composer require fanamurov/'. $package);
                    echo shell_exec('composer require fanamurov/'. $package);
                }
            }
        }else{
            $this->info('composer require fanamurov/'. $name);
            echo shell_exec('composer require fanamurov/'. $name);
        }

        $this->info('The task is completed! Thank you for using LarrockCMS');
    }
}
