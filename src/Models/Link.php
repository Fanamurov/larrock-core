<?php

namespace Larrock\Core\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Config
 *
 * @property integer $id
 * @property string $key
 * @property string $value
 * @property string $type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Config imagePresets($key)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Config whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Config whereKey($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Config whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Config whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Config whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Config whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Link extends Model
{
    protected $table = 'link';

	protected $fillable = ['id_parent', 'id_child', 'model_parent', 'model_child'];

	public function getFullDataChild()
    {
        $data = new $this->model_child;
        return $data->whereId($this->id_child)->first();
    }
}