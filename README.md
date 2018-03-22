[LarrockCMS](https://github.com/Fanamurov/larrock-core) - это CMS основанная на php-фреймворке Laravel 5.6 поставляемая в формате composer-пакетов.

Сайт с официальной технической и пользовательской документацией: [http://larrock-cms.ru](http://larrock-cms.ru)

[![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-core/v/stable)](https://packagist.org/packages/fanamurov/larrock-core) [![Total Downloads](https://poser.pugx.org/fanamurov/larrock-core/downloads)](https://packagist.org/packages/fanamurov/larrock-core) [![License](https://poser.pugx.org/fanamurov/larrock-core/license)](https://packagist.org/packages/fanamurov/larrock-core) [![Scrutinizer Badge](https://scrutinizer-ci.com/g/Fanamurov/larrock-core/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Fanamurov/larrock-core/?branch=master) [![Build Status](https://travis-ci.org/Fanamurov/larrock-core.svg?branch=master)](https://travis-ci.org/Fanamurov/larrock-core) [![Coverage Status](https://coveralls.io/repos/github/Fanamurov/larrock-core/badge.svg?branch=master)](https://coveralls.io/github/Fanamurov/larrock-core?branch=master)


Распространяется под лицензией CC-BY-4.0. При использовании CMS вы обязаны указать ее название в своем проекте в виде копирайта в админ-панели.

В основе проекта ядро CMS (LarrockCore), устанавливаемое в дополнение к laravel версии >=5.6 и пакеты компонентов (дополнений к LarrockCore).

***

### Компоненты поставляемые вместе с главным пакетом larrock-core:

* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-menu/v/stable)](https://packagist.org/packages/fanamurov/larrock-menu) [fanamurov/larrock-menu](https://github.com/Fanamurov/larrock-menu) - управление меню сайта
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-users/v/stable)](https://packagist.org/packages/fanamurov/larrock-users) [fanamurov/larrock-users](https://github.com/Fanamurov/larrock-users) - пользователи, права и роли
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-pages/v/stable)](https://packagist.org/packages/fanamurov/larrock-pages) [fanamurov/larrock-pages](https://github.com/Fanamurov/larrock-pages) - страницы
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-blocks/v/stable)](https://packagist.org/packages/fanamurov/larrock-blocks) [fanamurov/larrock-blocks](https://github.com/Fanamurov/larrock-blocks) - блоки для вывода в шаблоне
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-contact/v/stable)](https://packagist.org/packages/fanamurov/larrock-contact) [fanamurov/larrock-contact](https://github.com/Fanamurov/larrock-contact) - вывод, обработка, отправка форм
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-admin-seo/v/stable)](https://packagist.org/packages/fanamurov/larrock-admin-seo) [fanamurov/larrock-admin-seo](https://github.com/Fanamurov/larrock-admin-seo) - управление seo-данными компонентов
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-search/v/stable)](https://packagist.org/packages/fanamurov/larrock-search) [fanamurov/larrock-search](https://github.com/Fanamurov/larrock-search) - поиск по материалам компонентов

### Другие компоненты:

* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-catalog/v/stable)](https://packagist.org/packages/fanamurov/larrock-catalog) [fanamurov/larrock-catalog](https://github.com/Fanamurov/larrock-catalog) - каталог товаров
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-cart/v/stable)](https://packagist.org/packages/fanamurov/larrock-cart) [fanamurov/larrock-cart](https://github.com/Fanamurov/larrock-cart) - корзина покупок, сохранение заказов, оплаты
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-discount/v/stable)](https://packagist.org/packages/fanamurov/larrock-discount) [fanamurov/larrock-discount](https://github.com/Fanamurov/larrock-discount) - скидочная система к каталогу
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-feed/v/stable)](https://packagist.org/packages/fanamurov/larrock-feed) [fanamurov/larrock-feed](https://github.com/Fanamurov/larrock-feed) - материалы в разделах
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-reviews/v/stable)](https://packagist.org/packages/fanamurov/larrock-reviews) [fanamurov/larrock-reviews](https://github.com/Fanamurov/larrock-reviews) - отзывы, комментарии
* [![Latest Stable Version](https://poser.pugx.org/fanamurov/larrock-wizard/v/stable)](https://packagist.org/packages/fanamurov/larrock-wizard) [fanamurov/larrock-wizard](https://github.com/Fanamurov/larrock-wizard) - импорт товаров в каталог через .xlsx-прайс


***

### Зависимости компонентов
- "php": ">=7.1.3",
- "mysql": ">=5.7",
- "[proengsoft/laravel-jsvalidation](https://github.com/proengsoft/laravel-jsvalidation)": "^1.5", (js-валидация форм)
- "[albertcht/invisible-recaptcha](https://github.com/albertcht/invisible-recaptcha)": "^1.7", (каптча для форм)
- "[davejamesmiller/laravel-breadcrumbs](https://github.com/davejamesmiller/laravel-breadcrumbs)": "^5.0", ("хлебные крошки")
- "[nicolaslopezj/searchable](https://github.com/nicolaslopezj/searchable)": "^1.9", (расширенный поиск)
- "[spatie/laravel-medialibrary](https://github.com/spatie/laravel-medialibrary)": "^6.0.0", (управление файлами в материалах)
- "[fanamurov/mdash](https://github.com/fanamurov/mdash)": "^1.0" (типограф)



# INSTALL LARROCK CMS

1. **Install laravel**
  ```sh
  $ composer create-project --prefer-dist laravel/laravel=5.6.* larrock
  ```

2. **Install LarrockСore**
  ```sh
  $ cd larrock
  $ composer require fanamurov/larrock-core --prefer-dist
  ```

3. **Install LarrockCMS**
  ```sh
  $ php artisan larrock:installcorepackages
  $ php artisan larrock:install
  ```
  
# START
yoursite/```admin```

Default login: ```admin@larrock-cms.ru```

Default password: ```password```


### Manual installation of other components LarrockCMS (composer required!)
  ```sh
  $ php artisan larrock:manager
  ```
  or use composer
  ```sh
  $ composer require fanamurov/larrock-*name*  --prefer-dist
  ```
  
### Manual installation of assets (bower required!)
```sh
cd public_html/_assets
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