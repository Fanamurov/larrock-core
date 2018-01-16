<?php

namespace Larrock\Core\Commands;

use Illuminate\Console\Command;
use App\Exceptions\Handler;

class LarrockAssetsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larrock:assets';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install assets (via bower)';

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
        $this->line('=== Install assets via bower ===');

        echo shell_exec('cd public_html/_assets');
        echo shell_exec('bower install fancybox');
        echo shell_exec('bower install jquery-validation');
        echo shell_exec('bower install jquery.cookie');
        echo shell_exec('bower install fileapi');
        echo shell_exec('bower install jquery.spinner');
        echo shell_exec('bower install microplugin');
        echo shell_exec('bower install pickadate');
        echo shell_exec('bower install selectize');
        echo shell_exec('bower install sifter');
        echo shell_exec('bower install tinymce');
        echo shell_exec('bower install uikit');
        echo shell_exec('cd ../..');
        $this->info('=== END ===');
    }
}