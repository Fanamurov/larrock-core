<?php

/**
 * Компоненты, которые добавляются на вывод в меню админки
 */

/** @var  $components Основные пункты */
$components = [];
if(file_exists(base_path(). '/vendor/fanamurov/larrock-pages')){
    $components[] = new \Larrock\ComponentPages\PageComponent();
}
if(file_exists(base_path(). '/vendor/fanamurov/larrock-blocks')){
    $components[] = new \Larrock\ComponentBlocks\BlocksComponent();
}
if(file_exists(base_path(). '/vendor/fanamurov/larrock-feed')){
    $components[] = new \Larrock\ComponentFeed\FeedComponent();
}
if(file_exists(base_path(). '/vendor/fanamurov/larrock-catalog')){
    $components[] = new \Larrock\ComponentCatalog\CatalogComponent();
}
if(file_exists(base_path(). '/vendor/fanamurov/larrock-cart')){
    $components[] = new \Larrock\ComponentCart\CartComponent();
}
if(file_exists(base_path(). '/vendor/fanamurov/larrock-reviews')){
    $components[] = new \Larrock\ComponentReviews\ReviewsComponent();
}
if(file_exists(base_path(). '/vendor/fanamurov/larrock-contact')){
    $components[] = new \Larrock\ComponentContact\ContactComponent();
}

/** @var  $other_items Второстепенные пункты, свернутые в выпадающий список */
$other_items = [];
if(file_exists(base_path(). '/vendor/fanamurov/larrock-menu')){
    $other_items[] = new \Larrock\ComponentMenu\MenuComponent();
}
if(file_exists(base_path(). '/vendor/fanamurov/larrock-admin-seo')){
    $other_items[] = new \Larrock\ComponentAdminSeo\SeoComponent();
}
if(file_exists(base_path(). '/vendor/fanamurov/larrock-users')){
    $other_items[] = new \Larrock\ComponentUsers\UsersComponent();
}

return [
    'components' => $components,
    'other_items' => $other_items
];