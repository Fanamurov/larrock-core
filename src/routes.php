<?php

Route::get('/sitemap.xml', function() {
    return Response::view('larrock::front.sitemap', ['data' => config('larrock-sitemap')])->header('Content-Type', 'application/xml');
});

Route::group(['prefix' => 'admin'], function(){
    Route::get('/', [
        'as' => 'admin.home', 'uses' => 'Larrock\Core\AdminDashboardController@index'
    ]); //Роут главной страницы админки

    Route::post('ajax/EditRow', 'Larrock\Core\AdminAjax@EditRow');
    Route::post('ajax/ClearCache', 'Larrock\Core\AdminAjax@ClearCache');
    Route::post('ajax/UploadFile', 'Larrock\Core\AdminAjax@UploadFile');
    Route::post('ajax/UploadImage', 'Larrock\Core\AdminAjax@UploadImage');
    Route::post('ajax/GetUploadedMedia', 'Larrock\Core\AdminAjax@GetUploadedMedia');
    Route::post('ajax/DeleteUploadedMedia', 'Larrock\Core\AdminAjax@DeleteUploadedMedia');
    Route::post('ajax/DeleteAllUploadedMediaByType', 'Larrock\Core\AdminAjax@DeleteAllUploadedMediaByType');
    Route::post('ajax/CustomProperties', 'Larrock\Core\AdminAjax@CustomProperties');
    Route::post('ajax/TypographLight', 'Larrock\Core\AdminAjax@TypographLight');
    Route::post('ajax/Translit', 'Larrock\Core\AdminAjax@Translit');
});