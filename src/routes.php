<?php

use Larrock\Core\AdminDashboardController;
use Larrock\Core\AdminAjax;

Route::get('/sitemap.xml', function() {
    return Response::view('larrock::front.sitemap', ['data' => config('larrock-sitemap')])->header('Content-Type', 'application/xml');
});

Route::group(['prefix' => 'admin', 'middleware'=> ['web', 'level:2', 'LarrockAdminMenu', 'SaveAdminPluginsData', 'SiteSearchAdmin']], function(){
    Route::get('/', [
        'as' => 'admin.home', 'uses' => AdminDashboardController::class .'@index'
    ]); //Роут главной страницы админки

    Route::post('ajax/EditRow', AdminAjax::class .'@EditRow');
    Route::post('ajax/ClearCache', AdminAjax::class .'@ClearCache');

    Route::post('ajax/UploadImage', AdminAjax::class .'@UploadImage');
    Route::post('ajax/GetUploadedImage', AdminAjax::class .'@GetUploadedImage');
    Route::post('ajax/DeleteUploadedImage', AdminAjax::class .'@DeleteUploadedImage');
    Route::post('ajax/DeleteAllImagesByMaterial', AdminAjax::class .'@DeleteAllImagesByMaterial');
    Route::post('ajax/CustomProperties', AdminAjax::class .'@CustomProperties');

    Route::post('ajax/UploadFile', AdminAjax::class .'@UploadFile');
    Route::post('ajax/GetUploadedFile', AdminAjax::class .'@GetUploadedFile');
    Route::post('ajax/getFileParams', AdminAjax::class .'@getFileParams');
    Route::post('ajax/DeleteUploadedFile', AdminAjax::class .'@DeleteUploadedFile');
    Route::post('ajax/DeleteAllFilesByMaterial', AdminAjax::class .'@DeleteAllFilesByMaterial');

    Route::post('ajax/Typograph', AdminAjax::class .'@Typograph');
    Route::post('ajax/TypographLight', AdminAjax::class .'@TypographLight');
    Route::post('ajax/Translit', AdminAjax::class .'@Translit');
});

Route::get('/sitemap.xml', function() {
    return Response::view('larrock::front.sitemap', ['data' => config('larrock-sitemap')])->header('Content-Type', 'application/xml');
});