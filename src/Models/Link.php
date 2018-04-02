<?php

namespace Larrock\Core\Models;

use Cache;
use Illuminate\Database\Eloquent\Model;

/**
 * \Larrock\Core\Models\Link.
 *
 * @property int $id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property mixed model_child
 * @property mixed model_parent
 * @property int id_child
 * @property int id_parent
 * @property float cost
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Link whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Link whereIdParent($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Link whereIdChild($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Link whereModelParent($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Link whereModelChild($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Link whereCost($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Link whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Link whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Link extends Model
{
    protected $table = 'link';

    protected $fillable = ['id_parent', 'id_child', 'model_parent', 'model_child', 'cost'];

    protected $casts = [
        'cost' => 'float',
    ];

    protected $appends = [
        'cost',
    ];

    public function getFullDataChild()
    {
        $cache_key = sha1('getFullDataChild'.$this->model_child.$this->id_child);

        return Cache::rememberForever($cache_key, function () {
            $data = new $this->model_child;

            return $data->whereId($this->id_child)->first();
        });
    }
}
