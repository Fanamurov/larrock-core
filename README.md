

[LarrockCMS](https://github.com/Fanamurov/larrock-core) - это CMS основанная на php-фреймворке Laravel поставляемая в формате composer-пакетов.

[![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-core/v/stable)](https://packagist.org/packages/fanamurov/larrock-core) [![Total Downloads](https://poser.pugx.org/fanamurov/larrock-core/downloads)](https://packagist.org/packages/fanamurov/larrock-core) [![License](https://poser.pugx.org/fanamurov/larrock-core/license)](https://packagist.org/packages/fanamurov/larrock-core) [![Codacy Badge](https://api.codacy.com/project/badge/Grade/1a0fb19f2e024607a1d40260c8baa5e7)](https://www.codacy.com/app/Fanamurov/larrock-core?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Fanamurov/larrock-core&amp;utm_campaign=Badge_Grade)

Распространяется под лицензией LGPL. При использовании CMS вы обязаны указать ее название в своем проекте в виде копирайта в админ-панели.

В основе проекта ядро CMS (LarrockCore), устанавливаемое в дополнение к laravel версии >=5.5 и пакеты компонентов (дополнений к LarrockCore).

**Внимание! LarrockCMS версии >=0.2.x требует Mysql версии не ниже 5.7! Для работы с более старыми версиями используйте ветку 0.1.x.**

**Компоненты поставляемые вместе с главным пакетом larrock-core:**

* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-menu/v/stable)](https://packagist.org/packages/fanamurov/larrock-menu) [fanamurov/larrock-menu](https://github.com/Fanamurov/larrock-menu) - управление меню сайта
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-users/v/stable)](https://packagist.org/packages/fanamurov/larrock-users) [fanamurov/larrock-users](https://github.com/Fanamurov/larrock-users) - пользователи, права и роли
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-pages/v/stable)](https://packagist.org/packages/fanamurov/larrock-pages) [fanamurov/larrock-pages](https://github.com/Fanamurov/larrock-pages) - страницы
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-blocks/v/stable)](https://packagist.org/packages/fanamurov/larrock-blocks) [fanamurov/larrock-blocks](https://github.com/Fanamurov/larrock-blocks) - блоки для вывода в шаблоне
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-contact/v/stable)](https://packagist.org/packages/fanamurov/larrock-contact) [fanamurov/larrock-contact](https://github.com/Fanamurov/larrock-contact) - вывод, обработка, отправка форм
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-admin-seo/v/stable)](https://packagist.org/packages/fanamurov/larrock-admin-seo) [fanamurov/larrock-admin-seo](https://github.com/Fanamurov/larrock-admin-seo) - управление seo-данными компонентов
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-admin-search/v/stable)](https://packagist.org/packages/fanamurov/larrock-core) [fanamurov/larrock-admin-search](https://github.com/Fanamurov/larrock-admin-search) - поиск по материалам компонентов в админке

**Другие компоненты:**

* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-catalog/v/stable)](https://packagist.org/packages/fanamurov/larrock-catalog) [fanamurov/larrock-catalog](https://github.com/Fanamurov/larrock-catalog) - каталог товаров
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-cart/v/stable)](https://packagist.org/packages/fanamurov/larrock-cart) [fanamurov/larrock-cart](https://github.com/Fanamurov/larrock-cart) - корзина покупок, сохранение заказов, оплаты
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-discount/v/stable)](https://packagist.org/packages/fanamurov/larrock-discount) [fanamurov/larrock-discount](https://github.com/Fanamurov/larrock-discount) - скидочная система к каталогу
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-feed/v/stable)](https://packagist.org/packages/fanamurov/larrock-feed) [fanamurov/larrock-feed](https://github.com/Fanamurov/larrock-feed) - материалы в разделах
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-reviews/v/stable)](https://packagist.org/packages/fanamurov/larrock-reviews) [fanamurov/larrock-reviews](https://github.com/Fanamurov/larrock-reviews) - отзывы, комментарии
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-wizard/v/stable)](https://packagist.org/packages/fanamurov/larrock-wizard) [fanamurov/larrock-wizard](https://github.com/Fanamurov/larrock-wizard) - импорт товаров в каталог через .xlsx-прайс
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-smartbanners/v/stable)](https://packagist.org/packages/fanamurov/larrock-smartbanners) [fanamurov/larrock-smartbanners](https://github.com/Fanamurov/larrock-smartbanners) - клиент баннерообменной сети


***

#### Other depends
- "php": ">=7.0.0",
- "[barryvdh/laravel-debugbar](https://github.com/barryvdh/laravel-debugbar)": "~2.0",
- "[barryvdh/laravel-ide-helper](https://github.com/barryvdh/laravel-ide-helper)": "~2.1",
- "[proengsoft/laravel-jsvalidation](https://github.com/proengsoft/laravel-jsvalidation)": "^1.5",
- "[albertcht/invisible-recaptcha](https://github.com/albertcht/invisible-recaptcha)": "^1.7",
- "[davejamesmiller/laravel-breadcrumbs](https://github.com/davejamesmiller/laravel-breadcrumbs)": "^4.0",
- "[intervention/image](https://github.com/Intervention/image)": "^2.3",
- "[nicolaslopezj/searchable](https://github.com/nicolaslopezj/searchable)": "^1.9",
- "[spatie/laravel-medialibrary](https://github.com/spatie/laravel-medialibrary)": "^6.0.0",
- "[kix/mdash](https://github.com/kix/mdash-bundle)": "^0.5.4",
- "[ultraware/roles](https://github.com/ultraware/roles)": "^5.4"


# INSTALL LARROCK CMS

1. Install laravel:
  ```sh
  $ composer create-project --prefer-dist laravel/laravel=5.5.* larrock
  ```

2. Install Larrock core over Laravel:
  ```sh
  $ cd larrock
  $ composer require fanamurov/larrock-core
  ```

3. Add Alerts and Role package (/config/app.php)
  ```php
  'providers' => [
    ...
    Ultraware\Roles\RolesServiceProvider::class,
    ...
  ]
  ```

4. Publish views, migrations etc.
  ```sh
  $ php artisan vendor:publish
  ```
       
5. Run artisan command:
  ```sh
  $ php artisan larrock:updateEnv
  $ php artisan larrock:check
  ```
  
  
6. Run Larrock migrations
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
bower install fileapi
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


----------


## FAQ
Laravel 5.4: Specified key was too long error (https://laravel-news.com/laravel-5-4-key-too-long-error)

  **AppServiceProvider.php**
  
  ```php
  use Illuminate\Support\Facades\Schema;
  
  public function boot()
  {
      Schema::defaultStringLength(191);
  }
  ```