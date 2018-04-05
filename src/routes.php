<?php

Route::get('/sitemap.xml', function () {
    return Response::view('larrock::front.sitemap', ['data' => config('larrock-sitemap')])->header('Content-Type', 'application/xml');
});

Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [
        'as' => 'admin.home', 'uses' => 'Larrock\Core\AdminDashboardController@index',
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

Breadcrumbs::register('admin.edit', function ($breadcrumbs, $data) {
    $current_level = null;
    $breadcrumbs->parent('admin.'.$data->getConfig()->name.'.index');
    if ($data->getCategory) {
        if (isset($data->getCategory->id)) {
            foreach ($data->getCategory->parent_tree as $item) {
                $active = ' [Не опубликован!]';
                if ($item->active === 1) {
                    $active = '';
                }
                $breadcrumbs->push($item->title.$active, '/admin/'.$data->getConfig()->name.'/'.$item->id);
            }
            $current_level = $data->getConfig()->getModel()->whereCategory($data->getCategory->id)->orderBy('updated_at', 'DESC')->take('15')->get();
        } else {
            if (\count($data->getCategory) > 0) {
                foreach ($data->getCategory->first()->parent_tree as $item) {
                    $active = ' [Не опубликован!]';
                    if ($item->active === 1) {
                        $active = '';
                    }
                    $breadcrumbs->push($item->title.$active, '/admin/'.$data->getConfig()->name.'/'.$item->id);
                }
            } else {
                $breadcrumbs->push('[Раздел не найден]');
            }
        }
    } else {
        if ($data->parent) {
            $breadcrumbs->push($data->getConfig()->title, '/admin/'.$data->getConfig()->name.'/'.$data->parent);
        }
    }
    if ($data->title) {
        $active = ' [Не опубликован!]';
        if ($data->active === 1) {
            $active = '';
        }
        $breadcrumbs->push($data->title.$active, '/admin/'.$data->getConfig()->getName().'/'.$data->id, ['current_level' => $current_level]);
    } else {
        $breadcrumbs->push('Элемент');
    }
});
