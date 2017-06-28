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

2. Create DB
3. Publish larrock views, migrations etc.
```sh
$ php artisan vendor:publish
```
       
4. Install migration table
```sh
$ php artisan migrate:install
```
       
5. Run Larrock migrations
```sh
$ php artisan migrate
```
       
6. Add to .ENV
```
MAIL_TO_ADMIN="your admin mail address"
MAIL_TO_ADMIN_NAME="sitename for emails"
MAIL_STOP=false
SITE_NAME="your sitename"
```
       
7. Disable mysql strict mode (config/database.php)
```php
'strict' => false
```
       
8. Change view for jsvalidation (config/jsvalidation.php)
```php
'view' => 'larrock::jsvalidation.uikit'
```
       
       

### OTHER
jsvalidation Laravel 5.4 Support
https://github.com/proengsoft/laravel-jsvalidation/issues/222
use https://github.com/reganjohnson/laravel-jsvalidation

.ENV for MAMP
```
DB_SOCKET=/Applications/MAMP/tmp/mysql/mysql.sock
```