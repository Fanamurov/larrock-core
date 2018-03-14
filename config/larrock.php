<?php

return [
    /*
    'components' => [
        'catalog' => \App\Components\OverrideCatalog::class, //Переназначенные компоненты
        'cart' => \App\Components\OverrideCart::class,
    ],

    'models' => [
        'users' => \App\Models\User::class //Переназначенная модель LarrockUSers
    ],

    'feed' => [
        'anonsCategory' => 12, //ID раздела для анонсов
        'seofish_category_id' => 10, //ID раздела seofish
        'anonsCategoryLimit' => 10, //Сколько материалов анонсов выводить через middleware
    ],

    'views' => [
        'cart' => [
            'getIndex' => 'test.cart.table', //Страница корзины
            'emailOrderFull' => 'test.emails.orderFull', //Шаблон письма оформленного заказа
            'oferta' => 'larrock::front.cart.oferta', //Шаблон оферты
        ],
        'auth' => [
            'showLoginForm' => 'larrock::front.auth.login-register', //Форма авторизации/регистрации
            'showPasswordRequestForm' => 'larrock::front.auth.passwords.email', //Форма запроса на сброс пароля
            'showResetForm' => 'larrock::front.auth.passwords.reset', //Форма сброса пароля
        ],
        'user' => [
            'cabinet' => 'test.user.cabinet', //Личный кабинет, профиль
        ],
        'reviews' => [
            'index' => 'larrock::front.reviews.list'
        ],
        'feed' => [
            'index' => 'larrock::front.feed.index', //Страница списка разделов
            'category' => 'larrock::front.feed.category', //Страница раздела с выводом списка материалов
            'item' => 'larrock::front.feed.item', //Страница материала
            'itemUniq' => [
                'kontakty' => 'test.feed.item' //Уникальные шаблоны страниц материалов по url
            ],
            'categoryUniq' => [
                'kontakty' => 'test.category.item' //Уникальные шаблоны страниц разделов по url
            ]
        ],
        'pages' => [
            'item' => 'larrock::front.pages.item',
            'itemUniq' => [
                'kontakty' => 'test.page.item' //Уникальные шаблоны страниц материалов по url
            ],
        ],
        'catalog' => [
            'root' => 'larrock::front.catalog.categories', //Страница корневых разделов
            'categories' => 'larrock::front.catalog.categories', //Страница вывода списка разделов
            'categoriesTable' => 'larrock::front.catalog.items-table', //Вывод товаров в шаблоне таблицы
            'categoriesBlocks' => 'larrock::front.catalog.items-4-3', //Вывод товаров в шаблоне блоков
            'item' => 'larrock::front.catalog.item', //Шаблон товара
            'itemUniq' => [
                'kontakty' => 'test.page.item' //Уникальные шаблоны страниц товаров по url
            ],
            'categoryUniq' => [
                'kalendari' => 'test.category.item' //Уникальные шаблоны страниц разделов товаров по url
            ],
            'search' => 'larrock::front.catalog.items-search-result', //Шаблон страницы поиска по каталогу
            'modal' => 'larrock::front.modals.addToCart', //Шаблон всплывающего окна для добавления товара в корзину
        ]
    ],

    'middlewares' => [
        'front' => ['AddFeedAnons'], //Добавление своих middleware на фронт
    ],

    'catalog' => [
        'categoriesView' => 'blocks' //Вид каталога по-умолчанию (blocks или table) 
        'DefaultItemsOnPage' => 36, //Кол-во товаров на странице раздела по-умолчанию
    
        'ShowItemPage' => true, //Если true - показывать ссылки на страницу товара
        'DescriptionCatalogItemLink' => TRUE, //Если true - показывать прилинкованные описания для страницы товара
        'DescriptionCatalogCategoryLink' => TRUE, //Если true - показывать прилинкованные описания для страницы раздела каталога
    
        'modules' => [
            'sortCost' => TRUE, //Показывать модуль сортировки
            'lilu' => TRUE, //Показывать модуль фильтров товаров
            'vid' => TRUE, //Показывать модуль выбора шаблона
            'itemsOnPage' => TRUE, //Показывать модуль кол-ва товаров на страницу
        ]
    ]
    */
];