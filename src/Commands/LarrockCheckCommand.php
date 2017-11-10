<?php

namespace Larrock\Core\Commands;

use Illuminate\Console\Command;

class LarrockCheckCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larrock:check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check current Larrock core install';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('=== Check Laravel config ===');

        if(config('database.connections.mysql.strict') !== true){
            $this->info('Mysql Strict: OFF (OK)');
        }else{
            $this->error('Mysql Strict: ON. Please change to false');
        }

        if(config('auth.providers.users.model') === \Larrock\ComponentUsers\Models\User::class){
            $this->info('User Model: ComponentUsers (OK)');
        }else{
            $this->error('User Model: '. config('auth.providers.users.model') .'. Please change auth.providers.users.model to \Larrock\ComponentUsers\Models\User::class');
        }

        $this->line('=== Check depends configs ===');

        if(config('breadcrumbs.view') === 'larrock::front.modules.breadcrumbs.breadcrumbs'){
            $this->info('Breadcrumbs.view: larrock (OK)');
        }else{
            $this->error('Breadcrumbs.view: '. config('breadcrumbs.view') .'. Please change config/breadcrumbs.php to larrock::front.modules.breadcrumbs.breadcrumbs');
        }

        if(config('jsvalidation.view') === 'larrock::jsvalidation.uikit'){
            $this->info('Jsvalidation.view: uikit (OK)');
        }else{
            $this->error('Jsvalidation.view: '. config('jsvalidation.view') .'. Please change config/jsvalidation.php to larrock::jsvalidation.uikit');
        }

        if(config('jsvalidation.disable_remote_validation') === true){
            $this->info('Jsvalidation.disable_remote_validation: (OK)');
        }else{
            $this->error('Jsvalidation.disable_remote_validation not true. Please change config/jsvalidation.php to true');
        }

        /* Medialibrary */
        $this->line('=== Check Medialibrary config ===');
        if(config('medialibrary.default_filesystem') === 'media'){
            $this->info('Medialibrary: disk media driver (OK)');
        }else{
            $this->error('Please change medialibrary.default_filesystem to "media"');
        }
        if(config('medialibrary.custom_url_generator_class') === \Larrock\Core\Helpers\MediaUrlGenerator::class){
            $this->info('Medialibrary: custom url (OK)');
        }else{
            $this->error('Medialibrary custom_url_generator_class '. config('medialibrary.custom_url_generator_class')
                .'. Please change config/medialibrary.php to custom_url_generator_class => Larrock\Core\Helpers\MediaUrlGenerator::class');
        }

        if(config('medialibrary.custom_path_generator_class') === \Larrock\Core\Helpers\CustomPathGenerator::class){
            $this->info('Medialibrary: custom path (OK)');
        }else{
            $this->error('Medialibrary custom_path_generator_class '. config('laravel-medialibrary.custom_path_generator_class')
                .'. Please change config/medialibrary.php to custom_path_generator_class => Larrock\Core\Helpers\CustomPathGenerator::class');
        }

        if(config('filesystems.disks.media')){
            $this->info('Medialibrary: disk media driver (OK)');
        }else{
            $this->error('Please add Medialibrary filesystems.disks.media');
        }
        if(config('filesystems.disks.media.root') === base_path() .'/public_html/media'){
            $this->info('Medialibrary: disk media root (OK)');
        }else{
            $this->error('Medialibrary filesystems.disks.media.root '. config('filesystems.disks.media.root')
                .'. Please change config/filesystems.php to disks.media.root => base_path() .\'/public_html/media\'');
        }

        $this->line('=== Check .env file ===');

        if(env('MAIL_TO_ADMIN')){
            $this->info('MAIL_TO_ADMIN: '. env('MAIL_TO_ADMIN') .' (OK)');
        }else{
            $this->error('.env MAIL_TO_ADMIN not found (email address)');
        }
        if(env('MAIL_DRIVER') === 'mail'){
            $this->info('MAIL_DRIVER: '. env('MAIL_DRIVER') .' (OK)');
        }else{
            $this->error('.env MAIL_DRIVER: '. env('MAIL_DRIVER') .'. Maybe mail?');
        }
        if(env('MAIL_FROM_NAME')){
            $this->info('MAIL_FROM_NAME: '. env('MAIL_FROM_NAME') .' (OK)');
        }else{
            $this->error('.env MAIL_FROM_NAME not found (sitename for emails)');
        }
        if(env('MAIL_STOP', false) === false){
            $this->info('MAIL_STOP: '. env('MAIL_STOP') .'. Mails may be send (OK)');
        }else{
            $this->info('.env MAIL_STOP='. env('MAIL_STOP') .'. Mails not be send');
        }
        if(env('SITE_NAME')){
            $this->info('SITE_NAME: '. env('SITE_NAME') .' (OK)');
        }else{
            $this->error('.env SITE_NAME not found');
        }
    }
}