<?php

namespace Larrock\Core\Helpers;

use Illuminate\Routing\Controller;

class AdminMenuBuilder extends Controller
{
    public function topMenu()
    {
        $menu = [];
        $menu_other = [];

        $components = \Config::get('larrock-core-adminmenu.components');
        foreach ($components as $item){
            if($item->active === TRUE){
                $menu[] = $item->renderAdminMenu();
            }
        }

        $other_items = \Config::get('larrock-core-adminmenu.other_items');
        foreach ($other_items as $item){
            if($item->active === TRUE) {
                $menu_other[] = $item->renderAdminMenu();
            }
        }
        return ['menu' => $menu, 'menu_other' => $menu_other];
    }
}