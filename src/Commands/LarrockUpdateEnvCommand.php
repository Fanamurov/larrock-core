<?php

namespace Larrock\Core\Commands;

use Illuminate\Console\Command;

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
     * Execute the console command.
     *
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {
        $this->line('=== Check Laravel .env ===');

        if (! env('LARROCK VARS')) {
            $this->info('=== Add LARROCK vars ===');
            \File::append(base_path('.env'), "\n\nLARROCK VARS=PLACED");
        }

        if (! env('MAIL_TEMPLATE_ADDRESS')) {
            \File::append(base_path('.env'), "\nMAIL_TEMPLATE_ADDRESS='company address'");
            $this->info('MAIL_FROM_ADDRESS not found. Add "company address"');
        }

        if (! env('MAIL_TEMPLATE_PHONE')) {
            \File::append(base_path('.env'), "\nMAIL_TEMPLATE_PHONE='company phone'");
            $this->info('MAIL_TEMPLATE_PHONE not found. Add "company phone"');
        }

        if (! env('MAIL_TEMPLATE_MAIL')) {
            \File::append(base_path('.env'), "\nMAIL_TEMPLATE_MAIL=admin@larrock-cms.ru");
            $this->info('MAIL_TEMPLATE_MAIL not found. Add "admin@larrock-cms.ru"');
        }

        if (! env('MAIL_FROM_ADDRESS')) {
            \File::append(base_path('.env'), "\nMAIL_FROM_ADDRESS=admin@larrock-cms.ru");
            $this->info('MAIL_FROM_ADDRESS not found. Add "admin@larrock-cms.ru"');
        }

        if (! env('MAIL_TO_ADMIN')) {
            \File::append(base_path('.env'), "\nMAIL_TO_ADMIN=admin@larrock-cms.ru");
            $this->info('MAIL_TO_ADMIN not found. Add "admin@larrock-cms.ru"');
        }

        if (! env('MAIL_FROM_NAME')) {
            \File::append(base_path('.env'), "\nMAIL_FROM_NAME='LARROCK'");
            $this->info('MAIL_FROM_NAME not found. Add "LARROCK"');
        }

        if (! env('SITE_NAME')) {
            \File::append(base_path('.env'), "\nSITE_NAME='LARROCK'");
            $this->info('SITE_NAME not found. Add "LARROCK"');
        }

        if (env('MAIL_STOP', false) !== false) {
            \File::append(base_path('.env'), "\nMAIL_STOP=false");
            $this->info('MAIL_STOP not found. Add "false"');
        }

        $this->info('.env vars currently installed');
    }
}
