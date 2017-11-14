<?php
namespace Larrock\Core\Traits;

use Cache;
use Spatie\MediaLibrary\Media;

trait GetFilesAndImages{

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('110x110')
            ->height(110)->width(110)
            ->performOnCollections('images');

        $this->addMediaConversion('140x140')
            ->height(140)->width(140)
            ->performOnCollections('images');

        if($this->config->customMediaConversions){
            foreach ($this->config->customMediaConversions as $conversion){
                $explode = explode('x', $conversion);
                $this->addMediaConversion($conversion)
                    ->height($explode[0])->width($explode[1])
                    ->performOnCollections('images');
            }
        }
    }

    public function getFiles()
    {
        return $this->hasMany('Spatie\MediaLibrary\Media', 'model_id', 'id')->where([['model_type', '=', $this->config->model], ['collection_name', '=', 'files']])->orderBy('order_column', 'DESC');
    }

    public function getImages()
    {
        return $this->hasMany('Spatie\MediaLibrary\Media', 'model_id', 'id')->where([['model_type', '=', $this->config->model], ['collection_name', '=', 'images']])->orderBy('order_column', 'DESC');
    }

    public function getFirstImage()
    {
        return $this->hasOne('Spatie\MediaLibrary\Media', 'model_id', 'id')->where([['model_type', '=', $this->config->model], ['collection_name', '=', 'images']])->orderBy('order_column', 'DESC');
    }

    public function getFirstImageAttribute()
    {
        $value = Cache::remember(sha1('image_f_category'. $this->id .'_'. $this->config->model), 1440, function() {
            if($get_image = $this->getMedia('images')->sortByDesc('order_column')->first()){
                return $get_image->getUrl();
            }
            return '/_assets/_front/_images/empty_big.png';
        });
        return $value;
    }

    public function getFirstImage110Attribute()
    {
        $value = Cache::remember(sha1('image_f110_category'. $this->id .'_'. $this->config->model), 1440, function() {
            if($get_image = $this->getMedia('images')->sortByDesc('order_column')->first()){
                return $get_image->getUrl('110x110');
            }
            return '/_assets/_front/_images/empty_big.png';
        });
        return $value;
    }

    public function getFirstImage140Attribute()
    {
        $value = Cache::remember(sha1('image_f140_category'. $this->id .'_'. $this->config->model), 1440, function() {
            if($get_image = $this->getMedia('images')->sortByDesc('order_column')->first()){
                return $get_image->getUrl('140x140');
            }
            return '/_assets/_front/_images/empty_big.png';
        });
        return $value;
    }
}