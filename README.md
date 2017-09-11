# Laravel Larrock CMS
[![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-core/version)](https://packagist.org/packages/fanamurov/larrock-core) [![Total Downloads](https://poser.pugx.org/fanamurov/larrock-core/downloads)](https://packagist.org/packages/fanamurov/larrock-core) [![Latest Unstable Version](https://poser.pugx.org/fanamurov/larrock-core/v/unstable)](//packagist.org/packages/fanamurov/larrock-core) [![License](https://poser.pugx.org/fanamurov/larrock-core/license)](https://packagist.org/packages/fanamurov/larrock-core)

---
LarrockCMS for **Laravel framework >= 5.5** and **Mysql >=5.7**, **php >= 7.0**

Other versions: LarrockCMS v.0.1.x for Laravel 5.4, Mysql 5.6, php 7.0

#### Core and main components:
  - fanamurov/larrock-menu
  - fanamurov/larrock-users
  - fanamurov/larrock-pages
  - fanamurov/larrock-blocks
  - fanamurov/larrock-contact
  - fanamurov/larrock-admin-seo
  - fanamurov/larrock-admin-search

#### Other depends
- "php": ">=7.0.0",
- "barryvdh/laravel-debugbar": "~2.0",
- "barryvdh/laravel-ide-helper": "~2.1",
- "proengsoft/laravel-jsvalidation": "^1.5",
- "prologue/alerts": "^0.4.1",
- "davejamesmiller/laravel-breadcrumbs": "^4.0",
- "intervention/image": "^2.3",
- "nicolaslopezj/searchable": "^1.9",
- "spatie/laravel-medialibrary": "^6.0.0",
- "kix/mdash": "^0.5.4",
- "laravel/socialite": "^3.0",
- "jhaoda/socialite-odnoklassniki": "^3.0",
- "socialiteproviders/google": "^3.0",
- "socialiteproviders/instagram": "^3.0",
- "socialiteproviders/twitter": "^3.0",
- "socialiteproviders/vkontakte": "^3.0",
- "socialiteproviders/yandex": "^3.0",
- "ultraware/roles": "^5.4"

## INSTALL

1. Install laravel:
  ```sh
  $ composer create-project --prefer-dist laravel/laravel larrock
  ```

2. Install Larrock core over Laravel:
  ```sh
  $ composer require fanamurov/larrock-core
  ```

3. Add the ServiceProvider to the providers array in app/config/app.php
  ```php
 /**  LARROCK CORE DEPENDS */
/**  https://github.com/prologuephp/alerts */
 Prologue\Alerts\AlertsServiceProvider::class,
 Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class,
 /**  https://github.com/barryvdh/laravel-debugbar */
 Barryvdh\Debugbar\ServiceProvider::class,

 /**  LARROCK COMPONENT USERS DEPENDS */
 /**  https://github.com/ultraware/roles */
 Ultraware\Roles\RolesServiceProvider::class,
        
  ```

  aliases:
  ```php
  /**  LARROCK CORE DEPENDS */
  'Alert' => Prologue\Alerts\Facades\Alert::class,
  ```

4. Publish views, migrations etc.
  ```sh
  $ php artisan vendor:publish
  ```
       
5. Run artisan command:
  ```sh
  $ php artisan larrock:check
  ```
  And follow the tips for setting third-party dependencies
  
  
6. Run Larrock migrations
  Laravel 5.4: Specified key was too long error (https://laravel-news.com/laravel-5-4-key-too-long-error)
  **AppServiceProvider.php**
  
  ```php
  use Illuminate\Support\Facades\Schema;
  
  public function boot()
  {
      Schema::defaultStringLength(191);
  }
  ```
  
  ```sh
  $ php artisan migrate
  ```
  Add admin user
  ```sh
  $ php artisan db:seed --class="Larrock\ComponentUsers\Database\Seeds\UsersTableSeeder"
  ```

7. Medialibrary files deleting (Корректное удаление файлов с нашей системой хранения)
  /vendor/spatie/laravel-medialibrary/src/Filesystem.php
  ```php
  public function removeFiles(Media $media)
  {
    $pathGenerator = PathGeneratorFactory::create();
    $this->filesystem->disk($media->disk)->deleteDirectory($pathGenerator->getPathForConversions($media));
    $this->filesystem->disk($media->disk)->delete($pathGenerator->getPath($media) .'/'. $media->file_name);
  }
  ```
  
##BOWER COMPONENTS FOR TEMPLATE
```sh
cd /public_html/_assets
bower install fancybox
bower install jquery-validation
bower install jquery.cookie
bower install jquery.filer
bower install jquery.spinner
bower install microplugin
bower install pickadate
bower install selectize
bower install sifter
bower install tinymce
bower install uikit
```

##START
http://yousite/admin
Login: admin@larrock-cms.ru
Password: password