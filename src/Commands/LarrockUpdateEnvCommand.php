<?php

namespace Larrock\Core\Commands;

use Illuminate\Console\Command;
use App\Exceptions\Handler;

class LarrockUpdateEnvCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larrock:updateEnv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Laravel .env to Larrock';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('=== Check Laravel .env ===');

        if( !env('LARROCK VARS')){
            $this->info('=== Add LARROCK vars ===');
            $current_env = \File::get(base_path('.env'));
            \File::put(base_path('.env'), $current_env ."\n\nLARROCK VARS=PLACED");
        }

        if( !env('MAIL_FROM_ADDRESS')){
            $current_env = \File::get(base_path('.env'));
            \File::put(base_path('.env'), $current_env ."\nMAIL_FROM_ADDRESS=admin@larrock-cms.ru");
            $this->info('MAIL_FROM_ADDRESS not found. Add "admin@larrock-cms.ru"');
        }

        if( !env('MAIL_TO_ADMIN')){
            $current_env = \File::get(base_path('.env'));
            \File::put(base_path('.env'), $current_env ."\nMAIL_TO_ADMIN=admin@larrock-cms.ru");
            $this->info('MAIL_TO_ADMIN not found. Add "admin@larrock-cms.ru"');
        }

        if( !env('MAIL_FROM_NAME')){
            $current_env = \File::get(base_path('.env'));
            \File::put(base_path('.env'), $current_env ."\nMAIL_FROM_NAME='LARROCK'");
            $this->info('MAIL_FROM_NAME not found. Add "LARROCK"');
        }

        if( !env('SITE_NAME')){
            $current_env = \File::get(base_path('.env'));
            \File::put(base_path('.env'), $current_env ."\nSITE_NAME='LARROCK'");
            $this->info('SITE_NAME not found. Add "LARROCK"');
        }

        if( !env('MAIL_STOP')){
            $current_env = \File::get(base_path('.env'));
            \File::put(base_path('.env'), $current_env ."\nMAIL_STOP=false");
            $this->info('MAIL_STOP not found. Add "false"');
        }

        $this->info('.env vars currently installed');
    }
}