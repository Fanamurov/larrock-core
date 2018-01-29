<?php

namespace Larrock\Core\Commands;

use Illuminate\Console\Command;
use App\Exceptions\Handler;

class LarrockUpdateVendorConfigCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larrock:updateVendorConfig';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update vendor configs';

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
        $this->line('=== Update vendor configs ===');

        $dir = str_replace('/Commands', '', __DIR__);

        \File::copy($dir. '/configVendor/auth.php', base_path('/config/auth.php'));
        \File::copy($dir. '/configVendor/breadcrumbs.php', base_path('/config/breadcrumbs.php'));
        \File::copy($dir. '/configVendor/filesystems.php', base_path('/config/filesystems.php'));
        \File::copy($dir. '/configVendor/jsvalidation.php', base_path('/config/jsvalidation.php'));
        \File::copy($dir. '/configVendor/medialibrary.php', base_path('/config/medialibrary.php'));
        \File::copy($dir. '/configVendor/cart.php', base_path('/config/cart.php'));

        $this->info('Configs successfully updated');
    }
}