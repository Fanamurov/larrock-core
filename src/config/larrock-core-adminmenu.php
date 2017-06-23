<?php

use Larrock\ComponentPages\PageComponent;
use Larrock\ComponentBlocks\BlocksComponent;
use Larrock\ComponentFeed\FeedComponent;
use Larrock\ComponentAdminSeo\SeoComponent;
use Larrock\ComponentMenu\MenuComponent;
use Larrock\ComponentUsers\UsersComponent;
use Larrock\ComponentCatalog\CatalogComponent;
use Larrock\ComponentCart\CartComponent;

return [
    'components' => [
        new PageComponent(),
        new BlocksComponent(),
        new FeedComponent(),
        new CatalogComponent(),
        new CartComponent()
    ],

    'other_items' => [
        new MenuComponent(),
        new SeoComponent(),
        new UsersComponent()
    ]
];