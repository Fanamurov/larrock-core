# Laravel Larrock CMS

---

#### Core and main components:
  - fanamurov/larrock-menu
  - fanamurov/larrock-users
  - fanamurov/larrock-pages
  - fanamurov/larrock-blocks
  - fanamurov/larrock-contact
  - fanamurov/larrock-admin-seo
  - fanamurov/larrock-admin-search

## INSTALL

1. Install laravel:
```sh
$ composer create-project --prefer-dist laravel/laravel blog
```

2.Install Larrock core:
```sh
$ composer require fanamurov/larrock-core
$ composer update
```

3. Create DB

4. Disable mysql strict mode (config/database.php)
```php
'strict' => false
```

5.Modify config/app.php
providers:
```
//LARROCK CORE DEPENDS
Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
//https://github.com/barryvdh/laravel-debugbar
Barryvdh\Debugbar\ServiceProvider::class,
//http://laravel-breadcrumbs.davejamesmiller.com/en/latest/start.html#install-laravel-breadcrumbs
DaveJamesMiller\Breadcrumbs\ServiceProvider::class,
//https://github.com/proengsoft/laravel-jsvalidation/wiki/Installation
Proengsoft\JsValidation\JsValidationServiceProvider::class,
//https://github.com/RoumenDamianoff/laravel-sitemap
Roumen\Sitemap\SitemapServiceProvider::class,
//https://github.com/Intervention/image
Intervention\Image\ImageServiceProvider::class,
//https://github.com/spatie/laravel-medialibrary
Spatie\MediaLibrary\MediaLibraryServiceProvider::class,
//https://github.com/prologuephp/alerts
Prologue\Alerts\AlertsServiceProvider::class,
//https://github.com/RoumenDamianoff/laravel-feed
Roumen\Feed\FeedServiceProvider::class,

//LARROCK COMPONENT USERS DEPENDS
//https://github.com/ultraware/roles
Ultraware\Roles\RolesServiceProvider::class,

//LARROCK
\Larrock\Core\LarrockCoreServiceProvider::class,
\Larrock\ComponentPages\LarrockComponentPagesServiceProvider::class,
\Larrock\ComponentUsers\LarrockComponentUsersServiceProvider::class,
\Larrock\ComponentBlocks\LarrockComponentBlocksServiceProvider::class,
\Larrock\ComponentAdminSearch\LarrockAdminSearchServiceProvider::class,
\Larrock\ComponentAdminSeo\LarrockComponentAdminSeoServiceProvider::class,
\Larrock\ComponentMenu\LarrockComponentMenuServiceProvider::class,
\Larrock\ComponentContact\LarrockComponentContactServiceProvider::class
```

aliases:
```
//LARROCK CORE DEPENDS
'Debugbar' => Barryvdh\Debugbar\Facade::class,
'Breadcrumbs' => DaveJamesMiller\Breadcrumbs\Facade::class,
'JsValidator' => Proengsoft\JsValidation\Facades\JsValidatorFacade::class,
'Recaptcha' => Greggilbert\Recaptcha\Facades\Recaptcha::class,
'Image' => Intervention\Image\Facades\Image::class,
'Alert' => Prologue\Alerts\Facades\Alert::class,
'RSS' => Roumen\Feed\Feed::class,

//LARROCK COMPONENT USERS DEPENDS
'Socialite' => Laravel\Socialite\Facades\Socialite::class,
```

6. Publish larrock views, migrations etc.
```sh
$ php artisan vendor:publish --provider="Larrock\Core\LarrockCoreServiceProvider"
$ php artisan vendor:publish --provider="Larrock\ComponentUsers\LarrockComponentUsersServiceProvider"
$ php artisan vendor:publish --provider="Larrock\ComponentPages\LarrockComponentPagesServiceProvider"
$ php artisan vendor:publish --provider="Larrock\ComponentBlocks\LarrockComponentBlocksServiceProvider"
$ php artisan vendor:publish --provider="Larrock\ComponentMenu\LarrockComponentMenuServiceProvider"
$ php artisan vendor:publish --provider="Larrock\ComponentContact\LarrockComponentContactServiceProvider"
$ php artisan vendor:publish --provider="Larrock\ComponentAdminSeo\LarrockComponentAdminSeoServiceProvider"
$ php artisan vendor:publish --provider="Larrock\ComponentAdminSearch\LarrockComponentAdminSearchServiceProvider"
$ php artisan migrate
```
       
7. Install migration table
```sh
$ php artisan migrate:install
```
       
8. Run Larrock migrations
```sh
$ php artisan migrate
$ php artisan db:seed --class="Larrock\ComponentUsers\Database\Seeds\UsersTableSeeder"
```
       
9. Add to .ENV
```
MAIL_TO_ADMIN="your admin mail address"
MAIL_TO_ADMIN_NAME="sitename for emails"
MAIL_STOP=false
SITE_NAME="your sitename"
```
       
10. Change view for jsvalidation (config/jsvalidation.php)
```php
'view' => 'larrock::jsvalidation.uikit'
```

11. Change User model for Auth (config/auth.php)
```php
'model' => \Larrock\ComponentUsers\Models\User::class
```

12. Add SaveAdminPluginsData::class in $middlewareGroups (Http/Kernel.php)
```php
SaveAdminPluginsData::class
```


##START
http://yousite/admin
Login: admin@larrock-cms.ru
Password: password       
       

### OTHER
jsvalidation Laravel 5.4 Support
https://github.com/proengsoft/laravel-jsvalidation/issues/222
use https://github.com/reganjohnson/laravel-jsvalidation

.ENV for MAMP
```
DB_SOCKET=/Applications/MAMP/tmp/mysql/mysql.sock
```