<?php

namespace Larrock\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Larrock\ComponentAdminSeo\Facades\LarrockSeo;

/**
 * \Larrock\Core\Models\Seo.
 *
 * @property int $id
 * @property string $seo_title
 * @property string $seo_description
 * @property string $seo_keywords
 * @property int $seo_id_connect
 * @property string $seo_url_connect
 * @property string $seo_type_connect
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Seo whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Seo whereSeoTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Seo whereSeoDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Seo whereSeoKeywords($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Seo whereSeoIdConnect($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Seo whereSeoUrlConnect($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Seo whereSeoTypeConnect($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Seo whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Seo whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Larrock\Core\Models\Seo find($value)
 * @mixin \Eloquent
 */
class Seo extends Model
{
    /** @var $this Component */
    protected $config;

    protected $table = 'seo';

    protected $fillable = ['seo_title', 'seo_description', 'seo_keywords', 'seo_id_connect', 'seo_url_connect', 'seo_type_connect'];

    protected $casts = [
        'id_connect' => 'integer',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->fillable(LarrockSeo::addFillableUserRows([]));
        $this->config = LarrockSeo::getConfig();
        $this->table = LarrockSeo::getTable();
    }

    public function getConfig()
    {
        return $this->config;
    }
}
