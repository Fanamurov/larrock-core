<?php

namespace Larrock\Core\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Seo
 *
 * @property integer $id
 * @property string $seo_title
 * @property string $seo_description
 * @property string $seo_keywords
 * @property integer $id_connect
 * @property string $url_connect
 * @property string $type_connect
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Models\Seo whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Models\Seo whereSeoTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Models\Seo whereSeoDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Models\Seo whereSeoKeywords($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Models\Seo whereIdConnect($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Models\Seo whereUrlConnect($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Models\Seo whereTypeConnect($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Models\Seo whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Models\Seo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Models\Seo find($value)
 * @mixin \Eloquent
 */
class Seo extends Model
{
	protected $table = 'seo';

    protected $fillable = ['seo_title', 'seo_description', 'seo_keywords', 'id_connect', 'url_connect', 'type_connect'];

    protected $casts = [
        'id_connect' => 'integer'
    ];
}
