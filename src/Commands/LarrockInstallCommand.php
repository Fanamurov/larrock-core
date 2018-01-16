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

        if ($this->confirm('Шаг 1/7. Обновить .env? (larrock:updateEnv)')) {
            $this->call('larrock:updateEnv');
        }

        if ($this->confirm('Шаг 2/7. Сменить директорию "public" на "public_html"? (larrock:renamePublicDirectory)')) {
            $this->call('larrock:renamePublicDirectory');
        }

        if ($this->confirm('Шаг 3/7. Обновить конфиги зависимостей? (larrock:updateVendorConfig)')) {
            $this->call('larrock:updateVendorConfig');
        }

        if ($this->confirm('Шаг 4/7. Хотите установить пакеты не входящие в ядро LarrockCMS? (larrock:manager)')) {
            $this->call('larrock:manager');
        }

        if ($this->confirm('Шаг 5/7. Опубликовать ресурсы (vendor:publish)?')) {
            $this->call('vendor:publish');
        }

        if ($this->confirm('Шаг 6/7. Опубликовать ресурсы (migrate)?')) {
            $this->call('migrate');
        }

        if ($this->confirm('Шаг 7/7. Добавить пользователя администратора? (larrock:addAdmin)')) {
            $this->call('larrock:addAdmin');
        }

        $this->call('larrock:check');
        $this->info('=== END ===');
    }
}