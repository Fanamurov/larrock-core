<?php

namespace Larrock\Core\Commands;

use Illuminate\Console\Command;
use App\Exceptions\Handler;

class LarrockWriteCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larrock:write';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Laravel files to Larrock';

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
        $this->line('=== Check Laravel config ===');

        if(method_exists(Handler::class, 'renderExceptionWithWhoops')){
            $this->info('Whoops current installed');
        }else{
            //dd(copy(__DIR__.'/UpgradeLaravel/Handler.php', app_path('Exceptions')));
            copy(__DIR__.'/UpgradeLaravel/Handler.php', app_path('Exceptions//Handler.php'));
            $this->info('Whoops can be installed');
        }
    }
}