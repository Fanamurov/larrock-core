<?php

use Larrock\ComponentPages\AdminPageController;
use Larrock\Core\AdminAjax;

Route::group(['middleware' => ['web', 'AddMenuFront', 'GetSeo', 'AddBlocksTemplate']], function(){
    Route::get('/', [
        'as' => 'site.index', 'uses' => 'Larrock\ComponentPages\PageController@getItem'
    ]);
});

Route::group(['prefix' => 'admin', 'middleware'=> ['web', 'level:2', 'LarrockAdminMenu']], function(){
    Route::get('/', [
        'as' => 'admin.home', 'uses' => AdminPageController::class .'@index'
    ]); //Роут главной страницы админки

    Route::post('ajax/EditRow', AdminAjax::class .'@EditRow');
    Route::post('ajax/ClearCache', AdminAjax::class .'Larrock\Core\AdminAjax@ClearCache');

    Route::post('ajax/UploadImage', AdminAjax::class .'@UploadImage');
    Route::post('ajax/GetUploadedImage', AdminAjax::class .'@GetUploadedImage');
    Route::post('ajax/DeleteUploadedImage', AdminAjax::class .'@DeleteUploadedImage');
    Route::post('ajax/CustomProperties', AdminAjax::class .'@CustomProperties');


    Route::post('ajax/UploadFile', AdminAjax::class .'@UploadFile');
    Route::post('ajax/GetUploadedFile', AdminAjax::class .'@GetUploadedFile');
    Route::post('ajax/getFileParams', AdminAjax::class .'@getFileParams');
    Route::post('ajax/DeleteUploadedFile', AdminAjax::class .'@DeleteUploadedFile');

    Route::post('ajax/Typograph', AdminAjax::class .'@Typograph');
    Route::post('ajax/TypographLight', AdminAjax::class .'@TypographLight');
    Route::post('ajax/Translit', AdminAjax::class .'@Translit');
});