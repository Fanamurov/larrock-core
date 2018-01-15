<?php

namespace Larrock\Core;

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
    public function __construct()
    {
        $this->middleware(\LarrockPages::combineAdminMiddlewares());
    }

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
            return response()->json(['status' => 'error', 'message' => trans('larrock::apps.404') .' '. trans('larrock::apps.data.error')]);
        }
        if(is_integer($old_data->{$row})){
            $value = (integer)$value;
        }
        if(is_float($old_data->{$row})){
            $value = (float)$value;
        }
        if($old_data->{$row} !== $value){
            if(DB::table($table)->where($row_where, '=', $value_where)->update([$row => $value])){
                Cache::flush();
                return response()->json(['status' => 'success', 'message' => trans('larrock::apps.row.update', ['name' => $row])]);
            }
            return response()->json(['status' => 'error', 'message' => trans('larrock::apps.row.error', ['name' => $row])]);
        }
        return response()->json(['status' => 'blank', 'message' => trans('larrock::apps.row.blank', ['name' => $row])]);
	}

    /**
     * @return \Illuminate\Http\JsonResponse
     */
	public function ClearCache()
	{
		Cache::flush();
		return response()->json(['status' => 'success', 'message' => trans('larrock::apps.cache.clear')]);
	}

	/**
	 * Предзагрузка файлов для MediaLibrary
	 * Логика: загружаем файлы, выводим в форме в input[], при сохранении новости подключаем medialibrary
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function UploadImage(Request $request)
	{
		if( !file_exists(public_path() .'/image_cache')){
			/** @noinspection MkdirRaceConditionInspection */
            File::makeDirectory(public_path('image_cache'), 0755, true);
		}
		$images_value = $request->file('images');
		$model = $request->get('model_type');
		$model_name = class_basename($request->get('model_type'));
		$model_id = $request->get('model_id');
		$resize_original = $request->get('resize_original');
		$resize_original_px = $request->get('resize_original_px');
        if($images_value->isValid()){
            $image_name = mb_strimwidth($model_name .'-'. $model_id .'-'.str_slug($images_value->getClientOriginalName()), 0, 150) .'.'. $images_value->getClientOriginalExtension();
            if($resize_original === '1'){
                Image::make($images_value->getRealPath())
                    ->resize((integer)$resize_original_px, NULL, function ($constraint) {
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
            ])->toMediaCollection('images');
            return response()->json(['status' => 'success', 'message' => trans('larrock::apps.upload.success', ['name' => $images_value->getClientOriginalName()])]);
        }
        return response()->json(['status' => 'error', 'message' => trans('larrock::apps.upload.not_valid', ['name' => $images_value->getClientOriginalName()])], 300);
	}

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function UploadFile(Request $request)
    {
        $files_value = $request->file('files');
        $model = $request->get('model_type');
        $model_name = class_basename($request->get('model_type'));
        $model_id = $request->get('model_id');
        if($files_value->isValid()){
            $file_name = $model_name .'-'. $model_id .'-'.str_slug($files_value->getClientOriginalName()) .'.'. $files_value->getClientOriginalExtension();
            $files_value->move(public_path() .'/media/', $file_name);
            $content = $model::find($model_id);
            $content->addMedia(public_path() .'/media/'. $file_name)->withCustomProperties([
                'alt' => 'file', 'gallery' => $content->url
            ])->toMediaCollection('files');
            return response()->json(['status' => 'success', 'message' => trans('larrock::apps.upload.success', ['name' => $files_value->getClientOriginalName()])]);
        }
        return response()->json(['status' => 'error', 'message' => trans('larrock::apps.upload.not_valid', ['name' => $files_value->getClientOriginalName()])], 300);
    }

	/**
	 * Изменение дополнительных параметров у прикрепленных фото
	 *
	 * @param Request $request
	 * @return \Illuminate\Http\JsonResponse
	 */
	public function CustomProperties(Request $request)
	{
	    if( !$request->has('id')){
            return response()->json(['status' => 'error', 'message' => trans('larrock::apps.param.404', ['name' => 'id'])], 500);
        }
		$id = $request->get('id'); //ID в таблице media
		if(DB::table('media')
			->where('id', $id)
			->update(['order_column' => $request->get('position', 0),
				'custom_properties' => json_encode([
					'alt' => $request->get('alt'),
					'gallery' => $request->get('gallery')])])){
			return response()->json(['status' => 'success', 'message' => trans('larrock::apps.data.update', ['name' => 'параметров'])]);
		}
        return response()->json(['status' => 'error', 'message' => trans('larrock::apps.row.error')], 500);
	}


    /**
     * Вывод списка загруженных/прикрепленных к материалу файлов
     *
     * @param Request $request
     * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Http\JsonResponse|\Illuminate\View\View
     */
	public function GetUploadedMedia(Request $request)
    {
        if( !$request->has('type')){
            return response()->json(['status' => 'error', 'message' => trans('larrock::apps.param.404', ['name' => 'type'])], 500);
        }
        $type = $request->get('type'); //Тип файла (images, files etc...)
        if($request->get('model_id')){
            $model = $request->get('model_type');
            $content = $model::whereId($request->get('model_id'))->first();
            if($type === 'images'){
                return view('larrock::admin.admin-builder.plugins.images.getUploadedImages', ['data' => $content->getMedia($type)->sortByDesc('order_column')]);
            }else{
                return view('larrock::admin.admin-builder.plugins.files.getUploadedFiles', ['data' => $content->getMedia($type)->sortByDesc('order_column')]);
            }
        }
        return response()->json(['status' => 'error', 'message' => rans('larrock::apps.param.404', ['name' => 'model_id'])], 400);
    }

    /**
     * Удаление медиа-файла из материала
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function DeleteUploadedMedia(Request $request)
    {
        if( !$request->has('model')){
            return response()->json(['status' => 'error', 'message' => trans('larrock::apps.param.404', ['name' => 'model'])], 500);
        }
        $model = $request->get('model');
        $model::find($request->get('model_id'))->deleteMedia($request->get('id'));
        Cache::clear();
        return response()->json(['status' => 'success', 'message' => trans('larrock::apps.delete.file')]);
    }

    /**
     * Удаление всех медиафайлов из материала по типу медиа
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function DeleteAllUploadedMediaByType(Request $request)
    {
        if( !$request->has('model')){
            return response()->json(['status' => 'error', 'message' => trans('larrock::apps.param.404', ['name' => 'model'])], 500);
        }
        if( !$request->has('type')){
            return response()->json(['status' => 'error', 'message' => trans('larrock::apps.param.404', ['name' => 'type'])], 500);
        }
        $model = $request->get('model');
        $type = $request->get('type');
        $model::find($request->get('model_id'))->clearMediaCollection($type);
        Cache::clear();
        return response()->json(['status' => 'success', 'message' => trans('larrock::apps.delete.files')]);
    }

    /**
     * Транслитерация для создания url
     *
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
     * Типограф
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function Typograph(Request $request)
	{
		return response()->json(['text' => EMTypograph::fast_apply($request->get('text'))]);
	}

    /**
     * Типограф
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
	public function TypographLight(Request $request)
	{
	    $json = $request->get('to_json', TRUE);
        $rules = array(
            'Etc.unicode_convert' => 'on',
            'OptAlign.all' => 'off',
            'OptAlign.oa_oquote' => 'off',
            'OptAlign.oa_obracket_coma' => 'off',
            'OptAlign.oa_oquote_extra' => 'off',
            'Text.paragraphs' => 'off',
            'Text.auto_links' => 'off',
            'Text.email' => 'off',
            'Text.breakline' => 'off',
            'Text.no_repeat_words' => 'off',
            'Abbr.nbsp_money_abbr' => 'off',
            'Abbr.nobr_vtch_itd_itp' => 'off',
            'Abbr.nobr_sm_im' => 'off',
            'Abbr.nobr_acronym' => 'off',
            'Abbr.nobr_locations' => 'off',
            'Abbr.nobr_abbreviation' => 'off',
            'Abbr.ps_pps' => 'off',
            'Abbr.nbsp_org_abbr' => 'off',
            'Abbr.nobr_gost' => 'off',
            'Abbr.nobr_before_unit_volt' => 'off',
            'Abbr.nbsp_before_unit' => 'off',
        );

        if($json === TRUE){
            return response()->json(['text' => EMTypograph::fast_apply($request->get('text'), $rules)]);
        }
        return response(EMTypograph::fast_apply($request->get('text'), $rules));
	}
}