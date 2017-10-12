# Laravel Larrock CMS
[![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-core/version)](https://packagist.org/packages/fanamurov/larrock-core) [![Total Downloads](https://poser.pugx.org/fanamurov/larrock-core/downloads)](https://packagist.org/packages/fanamurov/larrock-core) [![License](https://poser.pugx.org/fanamurov/larrock-core/license)](https://packagist.org/packages/fanamurov/larrock-core) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/1a0fb19f2e024607a1d40260c8baa5e7)](https://www.codacy.com/app/Fanamurov/larrock-core?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Fanamurov/larrock-core&amp;utm_campaign=Badge_Grade)

---
LarrockCMS for **Laravel framework >= 5.5** and **Mysql >=5.7**, **php >= 7.0**

*Other versions: LarrockCMS v.0.1.x for Laravel 5.4, Mysql 5.6, php 7.0*

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
- "albertcht/invisible-recaptcha": "^1.7",
- "prologue/alerts": "^0.4.1",
- "davejamesmiller/laravel-breadcrumbs": "^4.0",
- "intervention/image": "^2.3",
- "nicolaslopezj/searchable": "^1.9",
- "spatie/laravel-medialibrary": "^6.0.0",
- "kix/mdash": "^0.5.4",
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

3. Publish views, migrations etc.
  ```sh
  $ php artisan vendor:publish
  ```
       
4. Run artisan command:
  ```sh
  $ php artisan larrock:check
  ```
  And follow the tips for setting third-party dependencies
  
  
5. Run Larrock migrations
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
  
### ASSETS: BOWER COMPONENTS FOR TEMPLATES
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

## START
http://yousite/admin
Login: admin@larrock-cms.ru
Password: password