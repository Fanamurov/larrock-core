<?php

namespace Larrock\Core\Helpers;

use Larrock\ComponentPages\PageComponent;
use App\Http\Controllers\Controller;

class AdminMenuBuilder extends Controller
{
    public function top_menu()
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
        /*$other_items = [
            //new MenuComponent(),
            //new SeoComponent(),
            //new UsersComponent()
        ];*/
        foreach ($other_items as $item){
            if($item->active === TRUE) {
                $menu_other[] = $item->renderAdminMenu();
            }
        }

        return ['menu' => $menu, 'menu_other' => $menu_other];
    }
}
