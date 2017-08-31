<?php

namespace Larrock\Core;

use Cache;
use Larrock\Core\AdminController;

class AdminDashboardController extends AdminController
{
    public function index()
    {
        //Cache::forget('coreVersion');
        $data['coreVersions'] = Cache::remember('coreVersion', 1440, function(){
            if($file = \File::get(base_path('composer.lock'))){
                $json = json_decode($file);
                $packages = collect($json->packages);
                $filtered = $packages->filter(function ($value, $key) {
                    return strpos($value->name, 'larrock');
                });
            }
            return $filtered;
        });
        return view('larrock::admin.dashboard.dashboard', $data);
    }
}
