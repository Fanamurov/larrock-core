<?php

namespace Larrock\Core\Middleware;

use Larrock\Core\Models\Seo;
use Cache;
use Closure;
use View;

class GetSeo
{
	protected $seo;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		$get_seo = Cache::remember('SEO', 1440, function() {
			if($get_seo = Seo::whereTypeConnect('custom_main')->first()){
				$seo['postfix_global'] = '. '. $get_seo->seo_title;
			}else{
				$seo['postfix_global'] = '';
			}
			$seo['postfix_global'] = ''; // Только для техностроя

			if($get_seo = Seo::whereTypeConnect('catalog_category_postfix')->first()){
				$seo['catalog_category_postfix'] = ' '. $get_seo->seo_title;
			}else{
				$seo['catalog_category_postfix'] = '';
			}

			if($get_seo = Seo::whereTypeConnect('catalog_category_prefix')->first()){
				$seo['catalog_category_prefix'] = $get_seo->seo_title .' ';
			}else{
				$seo['catalog_category_prefix'] = '';
			}
			return $seo;
		});
		$this->seo = $get_seo;

		View::share('seo_midd', $this->seo);
        return $next($request);
    }
}
