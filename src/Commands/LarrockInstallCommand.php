<?php

namespace Larrock\Core\Commands;

use Illuminate\Console\Command;

class LarrockInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'larrock:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Install LarrockCMS';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->line('=== Install LarrockCMS ===');

        if ($this->confirm('Обновить .env? (larrock:updateEnv)')) {
            $this->call('larrock:updateEnv');
        }

        if ($this->confirm('Сменить директорию "public" на "public_html"? (larrock:renamePublicDirectory)')) {
            $this->call('larrock:renamePublicDirectory');
        }

        if ($this->confirm('Обновить конфиги зависимостей? (larrock:updateVendorConfig)')) {
            $this->call('larrock:updateVendorConfig');
        }

        if ($this->confirm('Хотите установить пакеты не входящие в ядро LarrockCMS? (larrock:manager)')) {
            $this->call('larrock:manager');
        }

        if ($this->confirm('Опубликовать ресурсы (vendor:publish)?')) {
            $this->call('vendor:publish');
        }

        if ($this->confirm('Добавить пользователя администратора? (larrock:addAdmin)')) {
            $this->call('larrock:addAdmin');
        }

        $this->call('larrock:check');
        $this->info('=== END ===');
    }
}