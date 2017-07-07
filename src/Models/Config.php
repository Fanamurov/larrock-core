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
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Config imagePresets($key)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Config whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Config whereKey($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Config whereValue($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Config whereType($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Config whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Config whereUpdatedAt($value)
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
