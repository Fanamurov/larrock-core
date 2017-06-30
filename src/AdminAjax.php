<?php

namespace Larrock\Core;

use App\Components\CatalogComponent;
use App\Models\Cart;
use App\Models\Catalog;
use App\Models\Orders;
use EMT\EMTypograph;
use File;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Image;
use Cache;
use Larrock\Core\Helpers\Plugins\Typograf;

class AdminAjax extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function EditRow(Request $request)
	{
		$value_where = $request->get('value_where');
		$row_where = $request->get('row_where');
		$value = $request->get('value');
		$row = $request->get('row');
		$table = $request->get('table');

		//Получаем данные до изменения
		if( !$old_data = DB::table($table)->where($row_where, '=', $value_where)->first([$row])){
			return response()->json(['status' => 'error', 'message' => 'Данные не найдены']);
		}

		if($old_data->{$row} !== $value){
			if(DB::table($table)->where($row_where, '=', $value_where)->update([$row => $value])){
				Cache::flush();
				return response()->json(['status' => 'success', 'message' => 'Поле '. $row .' успешно изменено']);
			}
            return response()->json(['status' => 'error', 'message' => 'Поле не изменено']);
		}
        return response()->json(['status' => 'blank', 'message' => 'Передано текущее значение поля. Ничего не изменено']);
	}

    /**
     * @return \Illuminate\Http\JsonResponse
     */
	public function ClearCache()
	{
		Cache::flush();
		return response()->json(['status' => 'success', 'message' => 'Кеш очищен']);
	}

	/**
	 * Предзагрузка файлов для MediaLibrary
	 * Логика: загружаем файлы, выводим в форме в input[], при сохранении новости подключаем medialibrary
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function UploadImage(Request $request)
	{
		if( !file_exists(public_path() .'/image_cache')){
			/** @noinspection MkdirRaceConditionInspection */
            File::makeDirectory(public_path('image_cache'), 0755, true);
		}
		$images = $request->file('images');
		$model = $request->get('model_type');
		$model_name = class_basename($request->get('model_type'));
		$model_id = $request->get('model_id');
		$resize_original = $request->get('resize_original');
		foreach($images as $images_value){
			if($images_value->isValid()){
				$image_name = mb_strimwidth($model_name .'-'. $model_id .'-'.str_slug($images_value->getClientOriginalName()), 0, 150) .'.'. $images_value->getClientOriginalExtension();
				if($resize_original === '1'){
                    Image::make($images_value->getRealPath())
                        ->resize(800, NULL, function ($constraint) {
                            $constraint->aspectRatio();
                            $constraint->upsize();
                        })
                        ->save(public_path() .'/image_cache/'. $image_name);
                }else{
                    Image::make($images_value->getRealPath())
                        ->save(public_path() .'/image_cache/'. $image_name);
                }

				$content = $model::find($model_id);
				//Сохраняем фото под именем имямодели-idмодели-транслит(название картинки)
				$content->addMedia(public_path() .'/image_cache/'. $image_name)->withCustomProperties([
                    'alt' => 'photo', 'gallery' => $content->url
                ])->toMediaLibrary('images');
			}else{
				return response()->json(['status' => 'error', 'message' => $images_value->getClientOriginalName() .' не валиден'], 300);
			}
		}

		return response()->json(['status' => 'success', 'message' => 'Все фото успешно загружены']);
	}

	/**
	 * Изменение дополнительных параметров у прикрепленных фото
	 *
	 * @param Request $request
	 *
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function CustomProperties(Request $request)
	{
		$id = $request->get('id'); //ID в таблице media
		if(DB::table('media')
			->where('id', $id)
			->update(['order_column' => $request->get('position', 0),
				'custom_properties' => json_encode([
					'alt' => $request->get('alt'),
					'gallery' => $request->get('gallery')])])){
			return response()->json(['status' => 'success', 'message' => 'Дополнительные параметры сохранены']);
		}
        return response()->json(['status' => 'error', 'message' => 'Запрос к БД не выполенен'], 503);
	}

	/**
	 * Вывод списка загруженных/прикрепленных к материалу картинок
	 *
	 * @param Request $request
	 *
	 * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
	 */
	public function GetUploadedImage(Request $request)
	{
		if($request->get('model_id')){
			$model = $request->get('model_type');
			$content = $model::whereId($request->get('model_id'))->first();
			return view('larrock::admin.admin-builder.plugins.images.getUploadedImages', ['data' => $content->getMedia('images')->sortByDesc('order_column')]);
		}
        return response()->json(['status' => 'error', 'message' => 'Не передан model_id'], 300);
	}

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function DeleteUploadedImage(Request $request)
	{
		$model = $request->get('model');
		$model::find($request->get('model_id'))->deleteMedia($request->get('id'));
		return response()->json(['status' => 'success', 'message' => 'Файл удален']);
	}

    /**
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
	public function GetUploadedFile(Request $request)
	{
		if($request->get('model_id')){
            $model = $request->get('model_type');
            $content = $model::whereId($request->get('model_id'))->first();
			return view('larrock::admin.admin-builder.plugins.files.getUploadedFiles', ['data' => $content->getMedia('files')->sortByDesc('order_column')]);
		}
        return response()->json(['status' => 'error', 'message' => 'Не передан model_id'], 300);
	}

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function UploadFile(Request $request)
	{
		$files = $request->file('files');
        $model = $request->get('model_type');
        $model_name = class_basename($request->get('model_type'));
        $model_id = $request->get('model_id');
		foreach($files as $files_value){
			if($files_value->isValid()){
				$file_name = $model_name .'-'. $model_id .'-'.str_slug($files_value->getClientOriginalName()) .'.'. $files_value->getClientOriginalExtension();
				$files_value->move(public_path() .'/media/', $file_name);
				$content = $model::find($model_id);
                $content->addMedia(public_path() .'/media/'. $file_name)->withCustomProperties([
                    'alt' => 'file', 'gallery' => $content->url
                ])->toMediaLibrary('files');
			}else{
				return response()->json(['status' => 'error', 'message' => $files_value->getClientOriginalName() .' не валиден'], 300);
			}
		}

		return response()->json(['status' => 'success', 'message' => 'Все файлы успешно загружены']);
	}

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function DeleteUploadedFile(Request $request)
	{
        $model = $request->get('model');
        $model::find($request->get('model_id'))->deleteMedia($request->get('id'));
		return response()->json(['status' => 'success', 'message' => 'Файл удален']);
	}

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function Translit(Request $request)
	{
		$url = str_slug($request->get('text'));

		if($request->get('table', '') !== ''){
			$model = $request->get('table');
			if($model::whereUrl($url)->first(['url'])){
				$url = $url .'-'. random_int(2, 999);
			}

			if($model === 'Larrock\ComponentBlocks\Models\Blocks'){
			    $url = str_replace('-', '_', $url);
            }
		}

		return response()->json(['status' => 'success', 'message' => $url]);
	}

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function Typograph(Request $request)
	{
		return response()->json(['text' => EMTypograph::fast_apply($request->get('text'))]);
	}

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function TypographLight(Request $request)
	{
	    $typo = new Typograf();
	    return $typo->TypographLight($request->get('text'));
	}

}