<?php

namespace Larrock\Core;

use Illuminate\Support\ServiceProvider;
use Larrock\Core\Commands\LarrockCheckCommand;
use Larrock\Core\Commands\LarrockWriteCommand;
use Larrock\Core\Middleware\AdminMenu;
use Larrock\Core\Middleware\GetSeo;
use Larrock\Core\Middleware\VerifyLevel;
use Larrock\Core\Middleware\SaveAdminPluginsData;

class LarrockCoreServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');
        $this->loadViewsFrom(__DIR__.'/views', 'larrock');
        $this->loadTranslationsFrom(__DIR__.'/lang', 'larrock');

        $this->publishes([
            __DIR__.'/lang' => resource_path('vendor/lang/larrock')
        ], 'lang');
        $this->publishes([
            __DIR__.'/views' => base_path('resources/views/vendor/larrock')
        ], 'views');
        $this->publishes([
            __DIR__.'/assets/resources' => resource_path('assets')
        ], 'assets');
        $this->publishes([
            __DIR__.'/assets/public_html' => public_path('_assets')
        ], 'assets');
        $this->publishes([
            __DIR__.'/assets/gulpfile.js' => base_path('')
        ], 'assets');
        $this->publishes([
            __DIR__.'/config/larrock-core-adminmenu.php' => config_path('larrock-core-adminmenu.php')
        ], 'config');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app['router']->aliasMiddleware('level', VerifyLevel::class);
        $this->app['router']->aliasMiddleware('LarrockAdminMenu', AdminMenu::class);
        $this->app['router']->aliasMiddleware('GetSeo', GetSeo::class);
        $this->app['router']->aliasMiddleware('SaveAdminPluginsData', SaveAdminPluginsData::class);

        $this->mergeConfigFrom( __DIR__.'/config/larrock-core-adminmenu.php', 'larrock-core-adminmenu');

        if ( !class_exists('CreateConfigTable')){
            // Publish the migration
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/database/migrations/0000_00_00_000000_create_config_table.php' => database_path('migrations/'.$timestamp.'_create_config_table.php')
            ], 'migrations');
        }
        if ( !class_exists('CreateSeoTable')){
            // Publish the migration
            $timestamp = date('Y_m_d_His', time());

            $this->publishes([
                __DIR__.'/database/migrations/0000_00_00_000000_create_seo_table.php' => database_path('migrations/'.$timestamp.'_create_seo_table.php')
            ], 'migrations');
        }

        $this->app->bind('command.larrock:check', LarrockCheckCommand::class);
        $this->app->bind('command.larrock:write', LarrockWriteCommand::class);
        $this->commands([
            'command.larrock:check',
            'command.larrock:write',
        ]);
    }
}