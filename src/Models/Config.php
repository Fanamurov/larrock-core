<?php

namespace Larrock\Core\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * \Larrock\Core\Models\Config.
 *
 * @property int $id
 * @property string $key
 * @property string $value
 * @property string $type
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Config whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Config whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Config whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Config whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Config whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Config whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Config extends Model
{
    protected $table = 'config';

    protected $fillable = ['name', 'value', 'type'];

    public function getValueAttribute($value)
    {
        return unserialize($value);
    }
}
