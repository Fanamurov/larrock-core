<?php

namespace Larrock\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;

/**
 * \Larrock\Core\Models\Link
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 * @property string $type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property mixed model_child
 * @property mixed model_parent
 * @property mixed id_child
 * @property mixed id_parent
 * @property mixed cost
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
        'cost' => 'float'
    ];

    protected $appends = [
        'cost',
    ];

	public function getFullDataChild()
    {
        $cache_key = sha1('getFullDataChild'. $this->model_child . $this->id_child);
        return Cache::rememberForever($cache_key, function () {
            $data = new $this->model_child;
            return $data->whereId($this->id_child)->first();
        });
    }
}