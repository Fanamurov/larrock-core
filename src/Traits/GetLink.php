<?php
namespace Larrock\Core\Traits;

use Larrock\Core\Models\Link;

trait GetLink{

    /**
     * Get link query builder
     * @param $childModel
     * @return mixed
     */
    public function linkQuery($childModel)
    {
        return $this->hasMany(Link::class, 'id_parent')->whereModelParent($this->config->model)->whereModelChild($childModel);
    }

    /**
     * Get link data
     * @param $childModel
     * @return mixed
     */
    public function link($childModel)
    {
        return $this->hasMany(Link::class, 'id_parent')->whereModelParent($this->config->model)->whereModelChild($childModel)->get();
    }

    /**
     * Get Model Component + link in attrubute
     * @param $childModel
     * @return $this
     */
    public function linkAttribute($childModel)
    {
        $this->{$childModel} = $this->hasMany(Link::class, 'id_parent')->whereModelParent($this->config->model)->whereModelChild($childModel)->get();
        return $this;
    }

    /**
     * Метод для attach() и detach()
     * @param $childModel
     * @return mixed
     */
    public function getLink($childModel)
    {
        return $this->belongsToMany($childModel, 'link', 'id_parent', 'id_child')
            ->whereModelParent($this->config->getModelName())->whereModelChild($childModel);
    }
}