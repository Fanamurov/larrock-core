<?php

$components = [];

if(file_exists(base_path(). '/vendor/fanamurov/larrock-pages')){
    $components[] = new \Larrock\ComponentPages\PageComponent();
}
if(file_exists(base_path(). '/vendor/fanamurov/larrock-feed')){
    $components[] = new \Larrock\ComponentFeed\FeedComponent();
}
if(file_exists(base_path(). '/vendor/fanamurov/larrock-catalog')){
    $components[] = new \Larrock\ComponentCatalog\CatalogComponent();
}
if(file_exists(base_path(). '/vendor/fanamurov/larrock-category')){
    $components[] = new \Larrock\ComponentCategory\CategoryComponent();
}

return [
    'components' => $components
];