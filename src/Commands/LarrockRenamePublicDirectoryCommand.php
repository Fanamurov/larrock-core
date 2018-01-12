<?php

namespace Larrock\Core\Commands;

use Illuminate\Console\Command;
use App\Exceptions\Handler;

class LarrockRenamePublicDirectoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larrock:renamePublicDirectory';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rename public directory to "public_html"';

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
        $this->line('=== Rename public directory to "public_html" ===');

        if( !\File::exists(base_path('public_html/index.php'))){
            \File::copyDirectory(base_path('public'), base_path('public_html'));
            \File::deleteDirectory(base_path('public'));
            $dir = str_replace('/Commands', '', __DIR__);
            \File::copy($dir. '/configVendor/larrock-index-public_html.php', base_path('public_html/index.php'));
            $this->info('Directory and index.php successfully updated');
        }else{
            $this->info('The command is not required, the directory has already changed.');
        }
    }
}