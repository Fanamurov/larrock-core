<?php
namespace Larrock\Core\Traits;

use Cache;
use Spatie\MediaLibrary\Media;

trait GetFilesAndImages{

    public $modelName;

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('110x110')
            ->height(110)->width(110)
            ->performOnCollections('images');

        $this->addMediaConversion('140x140')
            ->height(140)->width(140)
            ->performOnCollections('images');
    }

    public function getFiles()
    {
        return $this->hasMany('Spatie\MediaLibrary\Media', 'model_id', 'id')->where([['model_type', '=', $this->modelName], ['collection_name', '=', 'files']])->orderBy('order_column', 'DESC');
    }

    public function getImages()
    {
        return $this->hasMany('Spatie\MediaLibrary\Media', 'model_id', 'id')->where([['model_type', '=', $this->modelName], ['collection_name', '=', 'images']])->orderBy('order_column', 'DESC');
    }

    public function getFirstImage()
    {
        return $this->hasOne('Spatie\MediaLibrary\Media', 'model_id', 'id')->where([['model_type', '=', $this->modelName], ['collection_name', '=', 'images']])->orderBy('order_column', 'DESC');
    }

    public function getFirstImageAttribute()
    {
        $value = Cache::remember(sha1('image_f_category'. $this->id .'_'. $this->modelName), 1440, function() {
            if($get_image = $this->getMedia('images')->sortByDesc('order_column')->first()){
                return $get_image->getUrl();
            }
            return '/_assets/_front/_images/empty_big.png';
        });
        return $value;
    }
}