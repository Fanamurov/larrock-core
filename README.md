# Laravel Larrock CMS

---
CMS for Laravel framework v. >= 5.4

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
- "davejamesmiller/laravel-breadcrumbs": "3.*",
- "greggilbert/recaptcha": "^2.1",
- "intervention/image": "^2.3",
- "nicolaslopezj/searchable": "^1.9",
- "roumen/feed": "^2.10",
- "roumen/sitemap": "^2.6",
- "spatie/laravel-medialibrary": "4.*.*",
- "kix/mdash": "^0.5.4",
- "filp/whoops": "^2.1",
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
  $ composer create-project --prefer-dist laravel/laravel blog
  ```

2. Install Larrock core over Laravel:
  ```sh
  $ composer require fanamurov/larrock-core
  ```

3. Add the ServiceProvider to the providers array in app/config/app.php
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
  //https://packagist.org/packages/greggilbert/recaptcha
    Greggilbert\Recaptcha\RecaptchaServiceProvider::class,
  
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

4. Publish views, migrations etc.
  ```sh
  $ php artisan vendor:publish
  ```
  Or publish files for each component separately
  ```sh
  $ php artisan vendor:publish --provider="Larrock\Core\LarrockCoreServiceProvider"
  $ php artisan vendor:publish --provider="Larrock\ComponentUsers\LarrockComponentUsersServiceProvider"
  $ php artisan vendor:publish --provider="Larrock\ComponentPages\LarrockComponentPagesServiceProvider"
  $ php artisan vendor:publish --provider="Larrock\ComponentBlocks\LarrockComponentBlocksServiceProvider"
  $ php artisan vendor:publish --provider="Larrock\ComponentMenu\LarrockComponentMenuServiceProvider"
  $ php artisan vendor:publish --provider="Larrock\ComponentContact\LarrockComponentContactServiceProvider"
  $ php artisan vendor:publish --provider="Larrock\ComponentAdminSeo\LarrockComponentAdminSeoServiceProvider"
  $ php artisan vendor:publish --provider="Larrock\ComponentAdminSearch\LarrockComponentAdminSearchServiceProvider"
  ```
       
5. Run artisan command:
  ```sh
  $ php artisan larrock:check
  ```
  And follow the tips for setting third-party dependencies
  
  
6. Run Larrock migrations
  Laravel 5.4: Specified key was too long error (https://laravel-news.com/laravel-5-4-key-too-long-error)
  AppServiceProvider.php
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
bower install fileapi
bower install jquery
bower install jquery-validation
bower install jquery.cookie
bower install jquery.filer
bower install jquery.spinner
bower install microplugin
bower install noty
bower install pickadate
bower install selectize
bower install sifter
bower install tinymce
bower install uikit
bower install yohoho.flexy
```

##START
http://yousite/admin
Login: admin@larrock-cms.ru
Password: password       
       


----------


### OTHER
Run Whoops
```sh
$ php artisan larrock:write
```