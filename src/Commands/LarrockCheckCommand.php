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
    protected $description = 'Check Check LarrockCMS configs';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('=== Check LarrockCMS configs ===');
        $errors = [];

        if (config('database.connections.mysql.strict') === true) {
            $errors[] = 'Mysql Strict: ON. Please change to false';
        }

        if (config('auth.providers.users.model') !== \Larrock\ComponentUsers\Models\User::class) {
            $errors[] = 'User Model: '.config('auth.providers.users.model').'. Please change auth.providers.users.model to \Larrock\ComponentUsers\Models\User::class';
        }

        if (config('breadcrumbs.view') !== 'larrock::front.modules.breadcrumbs.breadcrumbs') {
            $errors[] = 'Breadcrumbs.view: '.config('breadcrumbs.view').'. Please change config/breadcrumbs.php to larrock::front.modules.breadcrumbs.breadcrumbs';
        }

        if (config('jsvalidation.view') !== 'larrock::jsvalidation.uikit') {
            $errors[] = 'Jsvalidation.view: '.config('jsvalidation.view').'. Please change config/jsvalidation.php to larrock::jsvalidation.uikit';
        }

        if (config('jsvalidation.disable_remote_validation') !== true) {
            $errors[] = 'Jsvalidation.disable_remote_validation not true. Please change config/jsvalidation.php to true';
        }

        if (config('medialibrary.disk_name') !== 'media') {
            $errors[] = 'Please change medialibrary.disk_name to "media"';
        }

        if (config('medialibrary.path_generator') !== \Larrock\Core\Helpers\CustomPathGenerator::class) {
            $errors[] = 'Medialibrary path_generator '.config('laravel-medialibrary.custom_path_generator_class')
                .'. Please change config/medialibrary.php to path_generator => \Larrock\Core\Helpers\CustomPathGenerator::class';
        }

        if (config('filesystems.disks.media.root') !== base_path().'/public_html/media') {
            $errors[] = 'Medialibrary filesystems.disks.media.root '.config('filesystems.disks.media.root')
                .'. Please change config/filesystems.php to disks.media.root => base_path() .\'/public_html/media\'';
        }

        if (! env('MAIL_TO_ADMIN')) {
            $errors[] = '.env MAIL_TO_ADMIN not found (email address)';
        }
        if (! env('MAIL_FROM_NAME')) {
            $errors[] = '.env MAIL_FROM_NAME not found (sitename for emails)';
        }
        if (env('MAIL_STOP', false) !== false) {
            $errors[] = '.env MAIL_STOP='.env('MAIL_STOP').'. Mails not be send';
        }
        if (! env('SITE_NAME')) {
            $errors[] = '.env SITE_NAME not found';
        }

        if (\count($errors) > 0) {
            $this->error('Find errors:'.\count($errors));
            foreach ($errors as $key => $error) {
                $this->error(++$key.'.'.$error);
            }
        } else {
            $this->info('All OK');
        }
    }
}
