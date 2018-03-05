<?php

namespace Larrock\Core\Commands;

use Illuminate\Console\Command;

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('=== Install assets via bower ===');

        $libraries = ['fancybox', 'jquery-validation', 'jquery.cookie', 'fileapi', 'jquery.spinner', 'microplugin', 'pickadate',
            'selectize', 'sifter', 'tinymce', 'uikit'];

        foreach ($libraries as $library){
            if( !\File::exists(base_path('public_html/_assets/bower_components/'. $library))){
                echo shell_exec('bower install '. $library);
                if(\File::exists(base_path('public_html/_assets/bower_components/'. $library))){
                    $this->info('=== '. $library. ' successfully installed ===');
                }else{
                    $this->alert('=== ERROR! '. $library. ' not installed ===');
                }
            }else{
                $this->info('=== '. $library. ' already installed ===');
            }
        }
        $this->info('=== END of assets ===');
    }
}