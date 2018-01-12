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

        if ($this->confirm('Обновить .env?')) {
            $this->call('larrock:updateEnv');
        }

        if ($this->confirm('Обновить конфиги зависимостей?')) {
            $this->call('larrock:updateVendorConfig');
        }

        if ($this->confirm('Добавить пользователя администратора?')) {
            $this->call('larrock:addAdmin');
        }

        if ($this->confirm('Хотите установить пакеты не входящие в ядро LarrockCMS?')) {
            $this->call('larrock:manager');
        }

        $this->call('larrock:check');
    }
}