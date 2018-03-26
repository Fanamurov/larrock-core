<?php

namespace Larrock\Core;

use Cache;
use Illuminate\Routing\Controller;

class AdminDashboardController extends Controller
{
    public function __construct()
    {
        $component = new Component;
        $this->middleware($component->combineAdminMiddlewares());
    }

    public function index()
    {
        $data['coreVersions'] = Cache::rememberForever('coreVersion', function(){
            $filtered = [];
            if($file = \File::get(base_path('composer.lock'))){
                $json = json_decode($file);
                $packages = collect($json->packages);
                $filtered = $packages->filter(function ($value, $key) {
                    return strpos($value->name, 'larrock');
                });
            }
            return $filtered;
        });

        Cache::rememberForever('coreVersionInstall', function() use ($data){
            foreach ($data['coreVersions'] as $item){
                if($item->name === 'fanamurov/larrock-core'){
                    return $item->version;
                }
            }
        });

        $data['full_packages_list'] = [
            'fanamurov/larrock-search' => 'Search content for larrockCMS',
            'fanamurov/larrock-admin-seo' => 'SEO component for larrockCMS',
            'fanamurov/larrock-blocks' => 'Template blocks component for larrockCMS',
            'fanamurov/larrock-cart' => 'Cart to catalog component for larrockCMS',
            'fanamurov/larrock-catalog' => 'Catalog component for larrockCMS',
            'fanamurov/larrock-category' => 'Category component for larrockCMS',
            'fanamurov/larrock-contact' => 'Send forms component for larrockCMS',
            'fanamurov/larrock-core' => 'Core components for LarrockCMS',
            'fanamurov/larrock-discount' => 'Discounts in catalog component for larrockCMS',
            'fanamurov/larrock-feed' => 'Feeds items component for larrockCMS',
            'fanamurov/larrock-menu' => 'Menu component for larrockCMS',
            'fanamurov/larrock-pages' => 'Static pages component for larrockCMS',
            'fanamurov/larrock-reviews' => 'Reviews component for larrockCMS',
            'fanamurov/larrock-smartbanners' => 'Smartbanners component for larrockCMS',
            'fanamurov/larrock-users' => 'Users component for larrockCMS',
            'fanamurov/larrock-wizard' => 'Import .xlsx price to catalog component for larrockCMS',
            'fanamurov/larrock-yandex-kassa' => 'Yandex.Kassa SDK bridge for larrockCMS',
            'fanamurov/larrock-vscale' => 'Vscale API bridge for larrockCMS'
        ];
        foreach ($data['coreVersions'] as $item){
            unset($data['full_packages_list'][$item->name]);
        }

        $data['toDashboard'] = $this->componentToDashboard();
        return view('larrock::admin.dashboard.dashboard', $data);
    }

    protected function componentToDashboard()
    {
        $data = [];
        $components = config('larrock-to-dashboard.components');
        if(\is_array($components)){
            foreach ($components as $item){
                $data[] = $item->toDashboard();
            }
        }
        return $data;
    }
}