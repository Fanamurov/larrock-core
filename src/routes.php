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

    Route::post('ajax/UploadImage', 'Larrock\Core\AdminAjax@UploadImage');
    Route::post('ajax/GetUploadedImage', 'Larrock\Core\AdminAjax@GetUploadedImage');
    Route::post('ajax/DeleteUploadedImage', 'Larrock\Core\AdminAjax@DeleteUploadedImage');
    Route::post('ajax/DeleteAllImagesByMaterial', 'Larrock\Core\AdminAjax@DeleteAllImagesByMaterial');
    Route::post('ajax/CustomProperties', 'Larrock\Core\AdminAjax@CustomProperties');

    Route::post('ajax/UploadFile', 'Larrock\Core\AdminAjax@UploadFile');
    Route::post('ajax/GetUploadedFile', 'Larrock\Core\AdminAjax@GetUploadedFile');
    Route::post('ajax/getFileParams', 'Larrock\Core\AdminAjax@getFileParams');
    Route::post('ajax/DeleteUploadedFile', 'Larrock\Core\AdminAjax@DeleteUploadedFile');
    Route::post('ajax/DeleteAllFilesByMaterial', 'Larrock\Core\AdminAjax@DeleteAllFilesByMaterial');

    Route::post('ajax/Typograph', 'Larrock\Core\AdminAjax@Typograph');
    Route::post('ajax/TypographLight', 'Larrock\Core\AdminAjax@TypographLight');
    Route::post('ajax/Translit', 'Larrock\Core\AdminAjax@Translit');
});