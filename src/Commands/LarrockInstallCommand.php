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

        $this->call('larrock:installcorepackages');

        if(env('DB_DATABASE') === 'homestead' || env('DB_USERNAME') === 'homestead' || env('DB_PASSWORD') === 'secret') {
            $this->line('Параметры подключения к БД в .env-файле:');
            $this->line('DB_DATABASE=' . env('DB_DATABASE'));
            $this->line('DB_USERNAME=' . env('DB_USERNAME'));
            $this->line('DB_PASSWORD=' . env('DB_PASSWORD'));
            if ( !$this->confirm('Данные для доступа к БД верны?')) {
                $this->error('Установка завершена некорректно. Пожалуйста, установите правильные данные для доступа к БД и повторите попытку');
                return FALSE;
            }
        }

        if ($this->confirm('Шаг 1/7. Обновить .env? (larrock:updateEnv)')) {
            $this->call('larrock:updateEnv');
        }

        if ($this->confirm('Шаг 2/7. Сменить директорию "public" на "public_html"? (larrock:renamePublicDirectory)')) {
            $this->call('larrock:renamePublicDirectory');
        }

        if ($this->confirm('Шаг 3/7. Обновить конфиги зависимостей? (larrock:updateVendorConfig)')) {
            $this->call('larrock:updateVendorConfig');
        }

        if ($this->confirm('Шаг 4/7. Опубликовать ресурсы (vendor:publish)?')) {
            $this->call('vendor:publish');
        }

        if ($this->confirm('Шаг 5/7. Выполнить миграции БД (migrate)?')) {
            $this->call('migrate');
        }

        if ($this->confirm('Шаг 6/7. Добавить пользователя администратора? (larrock:addAdmin)')) {
            $this->call('larrock:addAdmin');
        }

        if ($this->confirm('Шаг 7/7. Установить пакеты ресурсов для шаблонов? (larrock:assets)')) {
            $this->call('larrock:assets');
        }

        //$this->call('larrock:check');
        $this->info('=== Install LarrockCMS successfully ended ===');
        $this->info('Проверка корректности установки LarrockCMS - php artisan larrock:check');
        $this->info('Если вы хотите установить пакеты не входящие в ядро LarrockCMS, выполните команду php artisan larrock:manager');
    }
}