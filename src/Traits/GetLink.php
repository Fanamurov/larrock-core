<?php

namespace Larrock\Core\Traits;

use Cache;
use Larrock\Core\Models\Link;

trait GetLink
{
    /**
     * Get link query builder.
     * @param $childModel
     * @return mixed
     */
    public function linkQuery($childModel)
    {
        return $this->hasMany(Link::class, 'id_parent')->whereModelParent($this->config->model)->whereModelChild($childModel);
    }

    /**
     * Get link data.
     * @param $childModel
     * @return mixed
     */
    public function link($childModel)
    {
        return $this->hasMany(Link::class, 'id_parent')->whereModelParent($this->config->model)->whereModelChild($childModel)->get();
    }

    /**
     * Get link data.
     * @return mixed
     */
    public function linkParam()
    {
        return $this->hasMany(Link::class, 'id_parent')->whereModelParent($this->config->model)
            ->whereModelChild('Larrock\ComponentCatalog\Models\Param');
    }

    /**
     * Get Model Component + link in attrubute.
     * @param $childModel
     * @return $this
     */
    public function linkAttribute($childModel)
    {
        $this->{$childModel} = $this->hasMany(Link::class, 'id_parent')->whereModelParent($this->config->model)->whereModelChild($childModel)->get();

        return $this;
    }

    /**
     * Метод для attach() и detach().
     * @param string $childModel
     * @return mixed
     */
    public function getLink($childModel)
    {
        return $this->belongsToMany($childModel, 'link', 'id_parent', 'id_child')
            ->whereModelParent($this->config->getModelName())->whereModelChild($childModel);
    }

    /**
     * Метод для совместимости привязки сторонних расширений (роли у пользователей)
     * Например: $data->getLinkWithParams($row_settings->modelChild, 'role_user', 'role_id', 'user_id')->get()
     * для получения прилинкованных ролей пользователя.
     * @param $childModel
     * @param $tableLink
     * @param $parentRow
     * @param $childRow
     * @return mixed
     */
    public function getLinkWithParams($childModel, $tableLink, $parentRow, $childRow)
    {
        return $this->belongsToMany($childModel, $tableLink, $parentRow, $childRow);
    }

    /**
     * Получение всех связей.
     * @return mixed
     */
    public function getAllLinks()
    {
        return $this->hasMany(Link::class, 'id_parent')->whereModelParent($this->config->model);
    }

    /**
     * Получение связи модификации цены.
     * @param $childModel
     * @return null
     */
    public function getCostLink($childModel)
    {
        $cache_key = sha1('getCostLink'.$this->config->model.$childModel.$this->id);

        return Cache::rememberForever($cache_key, function () use ($childModel) {
            $query = $this->hasMany(Link::class, 'id_parent')->whereModelParent($this->config->model)->whereModelChild($childModel);
            if ($getLink = $query->get()) {
                foreach ($getLink as $key => $item) {
                    $class = new $childModel();
                    if ($param = $class->whereId($item->id_child)->first()) {
                        $getLink[$key]->title = $param->title;
                    }
                }

                return $getLink;
            }

            return null;
        });
    }

    /**
     * Получение модификаций товаров
     * Товар->cost_value.
     * @return mixed|null
     */
    public function getCostValuesAttribute()
    {
        $cache_key = sha1('cost_value'.$this->id.$this->getConfig()->name);

        return Cache::rememberForever($cache_key, function () {
            $values = null;
            foreach ($this->config->rows as $row) {
                if (isset($row->costValue) && $row->costValue) {
                    if (null === $values) {
                        $values = $this->getCostLink($row->modelChild, $row->modelChildWhereKey, $row->modelChildWhereValue);
                    } else {
                        $values = $values->merge($this->getCostLink($row->modelChild, $row->modelChildWhereKey, $row->modelChildWhereValue));
                    }
                }
            }

            return $values;
        });
    }

    /**
     * Получение первой цены модификации товара.
     * @return mixed
     */
    public function getFirstCostValueAttribute()
    {
        if (($costValues = $this->getCostValuesAttribute()) && $costValues->count() > 0) {
            return $costValues->first()->cost;
        }

        return $this->cost;
    }

    /**
     * Получение ID первой цены модификации товара.
     * @return mixed
     */
    public function getFirstCostValueIdAttribute()
    {
        if ($costValues = $this->getCostValuesAttribute()) {
            if ($costValues->count() > 0) {
                return $costValues->first()->id;
            }
        }

        return null;
    }

    /**
     * Получение title первой цены модификации товара.
     * @return mixed
     */
    public function getFirstCostValueTitleAttribute()
    {
        if ($costValues = $this->getCostValuesAttribute()) {
            if ($costValues->count() > 0) {
                return $costValues->first()->title;
            }
        }

        return null;
    }
}
