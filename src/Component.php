<?php

namespace Larrock\Core;

use Larrock\ComponentAdminSeo\Facades\LarrockSeo;
use Larrock\ComponentFeed\Facades\LarrockFeed;
use Larrock\Core\Helpers\FormBuilder\FormCheckbox;
use Larrock\Core\Helpers\FormBuilder\FormInput;
use Larrock\Core\Helpers\FormBuilder\FormTextarea;
use Illuminate\Database\Eloquent\Model;
use JsValidator;
use Larrock\Core\Models\Link;
use View;

class Component
{
    public $name;
    public $title;
    public $description;
    public $table;
    public $rows;
    public $customMediaConversions;

    /** @var Model */
    public $model;
    public $active = TRUE;
    public $plugins_backend;
    public $plugins_front;
    public $settings;
    public $searchable;

    public $tabs;
    public $tabs_data;

    public $valid;

    public function getConfig()
    {
        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getModel()
    {
        return new $this->model;
    }

    public function getModelName()
    {
        return $this->model;
    }

    public function getTable()
    {
        return $this->table;
    }

    public function getRows()
    {
        return $this->rows;
    }

    public function getValid()
    {
        return Component::_valid_construct($this);
    }

    public function getFillableRows()
    {
        $fillable_rows = [];
        foreach ($this->rows as $key => $row){
            if($row->fillable){
                $fillable_rows[] = $key;
            }
        }
        return $fillable_rows;
    }

    public function addFillableUserRows($rows)
    {
        $fillable_rows = $rows;
        foreach ($this->rows as $key => $row){
            if($row->fillable){
                $fillable_rows[] = $key;
            }
        }
        return $fillable_rows;
    }

    protected function addPlugins()
    {
        if(empty($this->name)){
            return abort('Конфиг компонента не заполнен');
        }
        return $this;
    }

    public function addPositionAndActive()
    {
        $this->addActive();
        $this->addPosition();
        return $this;
    }

    public function addPosition()
    {
        $row = new FormInput('position', 'Вес');
        $this->rows['position'] = $row->setTab('other', 'Дата, вес, активность')->setValid('integer')
            ->setDefaultValue(0)->setInTableAdminAjaxEditable()->setFillable();
        return $this;
    }

    public function addActive()
    {
        $row = new FormCheckbox('active', 'Опубликован');
        $this->rows['active'] = $row->setTab('other', 'Дата, вес, активность')->setChecked()
            ->setValid('integer|max:1')->setDefaultValue(1)->setInTableAdminAjaxEditable()->setFillable();
        return $this;
    }

    protected function addRows()
    {
        return $this;
    }

    public function shareConfig()
    {
        $this->tabs = collect();
        $this->tabs_data = collect();
        View::share('app', $this);
        $this->valid = Component::_valid_construct($this);
        View::share('validator', JsValidator::make($this->valid));
        return $this;
    }

    /**
     * Метод объявления использования middleware для компонентов.
     * Вызывается из конструктора класса контроллера компонента через $this->middleware(Компонент::combineFrontMiddlewares());
     * @param null $user_middlewares
     * @return array
     */
    public function combineFrontMiddlewares($user_middlewares = NULL)
    {
        $middleware = ['web', 'GetSeo'];
        if($config = config('larrock.middlewares.front')){
            array_merge($middleware, $config);
        }
        if(file_exists(base_path(). '/vendor/fanamurov/larrock-menu')){
            $middleware[] = 'AddMenuFront';
        }
        if(file_exists(base_path(). '/vendor/fanamurov/larrock-blocks')){
            $middleware[] = 'AddBlocksTemplate';
        }
        if(file_exists(base_path(). '/vendor/fanamurov/larrock-discount')){
            $middleware[] = 'DiscountsShare';
        }
        if($user_middlewares){
            array_merge($middleware, $user_middlewares);
        }
        return array_unique($middleware);
    }

    /**
     * Метод объявления использования middleware для компонентов.
     * Вызывается из конструктора класса контроллера компонента через $this->middleware(Компонент::combineAdminMiddlewares());
     * @param null $user_middlewares
     * @return array
     */
    public function combineAdminMiddlewares($user_middlewares = NULL)
    {
        $middleware = ['web', 'level:2', 'LarrockAdminMenu', 'SaveAdminPluginsData', 'SiteSearchAdmin'];
        if($config = config('larrock.middlewares.admin')){
            array_merge($middleware, $config);
        }
        if($user_middlewares){
            array_merge($middleware, $user_middlewares);
        }
        return array_unique($middleware);
    }

    /**
     * Используется через SaveAdminPluginsData Middleware (Core)
     * @param $request
     */
    public function savePluginsData($request)
    {
        $this->savePluginSeoData($request);
        $this->savePluginAnonsToModuleData($request);
        $this->saveLinkData($request);
    }

    /**
     * Подключение плагина SEO
     * 
     * @return $this
     */
    public function addPluginSeo()
    {
        $row = new FormInput('seo_title', 'Title материала');
        $this->rows['seo_title'] = $rows_plugin[] = $row->setTab('seo', 'Seo')->setValid('max:255')
            ->setTypo()->setHelp('По-умолчанию равно заголовку материала');

        $row = new FormInput('seo_description', 'Description материала');
        $this->rows['seo_description'] = $rows_plugin[] = $row->setTab('seo', 'Seo')->setValid('max:255')
            ->setTypo()->setHelp('По-умолчанию равно заголовку материала');

        $row = new FormTextarea('seo_keywords', 'Keywords материала');
        $this->rows['seo_keywords'] = $rows_plugin[] = $row->setTab('seo', 'Seo')->setValid('max:255')->setCssClass('not-editor uk-width-1-1');

        $this->plugins_backend['seo']['rows'] = $rows_plugin;

        $row = new FormInput('url', 'URL материала');
        $this->rows['url'] = $row->setTab('seo', 'SEO')
            ->setValid('max:155|required|unique:'. $this->table .',url,:id')->setCssClass('uk-width-1-1')->setFillable();

        return $this;
    }

    public function savePluginSeoData($request)
    {
        if( !$request->has('_jsvalidation') && ($request->has('seo_title') || $request->get('seo_description') || $request->get('seo_seo_keywords'))){
            $seo = LarrockSeo::getModel()->whereSeoIdConnect($request->get('id_connect'))->whereSeoTypeConnect($request->get('type_connect'))->first();
            if( !empty($request->get('seo_title')) || !empty($request->get('seo_description')) || !empty($request->get('seo_keywords'))){
                if( !$seo){
                    $seo = LarrockSeo::getModel();
                }
                $seo->seo_id_connect = $request->get('id_connect');
                $seo->seo_title = $request->get('seo_title');
                $seo->seo_description = $request->get('seo_description');
                $seo->seo_keywords = $request->get('seo_keywords');
                $seo->seo_type_connect = $request->get('type_connect');
                if($seo->save()){
                    \Session::push('message.success', 'SEO обновлено');
                }
            }else{
                if($seo){
                    $seo->delete($seo->id);
                    \Session::push('message.success', 'SEO удалено');
                }
            }
        }
        return TRUE;
    }

    /**
     * Сохранение связей данных компонента (FormTagsLink)
     * @param $request
     * @return bool
     */
    public function saveLinkData($request): bool
    {
        if( !$request->has('_jsvalidation') && $request->get('link')){
            $modelParent = $request->get('modelParent');
            $modelParentId = $request->get('modelParentId');
            $modelChild = $request->get('modelChild');
            $link = $request->get('link');

            //Удаляем старые связи
            $model = new Link();
            $model->whereIdParent($modelParentId)->whereModelParent($modelParent)->whereModelChild($modelChild)->delete();

            if($modelParent && $modelParentId && $modelChild && $link && \is_array($link) ){
                $link = $request->get('link');
                foreach ($link as $value){
                    $model = new Link();
                    $model->id_parent = $request->get('modelParentId');
                    $model->model_parent = $request->get('modelParent');
                    $model->model_child = $request->get('modelChild');
                    $model->id_child = $value;
                    $model->save();
                }
                \Session::push('message.success', 'Связи обновлены');
            }
        }
        return TRUE;
    }

    public function addPluginImages()
    {
        $this->plugins_backend['images'] = 'images';
        return $this;
    }

    /**
     * Метод для добавления в модель новых пресетов картинок для Medialibrary
     * @param $conversions
     */
    public function addCustomMediaConversions($conversions)
    {
        if( !is_array($conversions)){
            return abort(403, 'addCustomMediaConversions должно быть массивом');
        }
        $this->customMediaConversions = $conversions;
    }

    public function addPluginFiles()
    {
        $this->plugins_backend['files'] = 'files';
        return $this;
    }

    /**
     * Плагин для генерации анонса новости для блока анонс новости
     * @param int $categoryAnons    ID категории с анонсами
     * @return $this
     */
    public function addAnonsToModule($categoryAnons)
    {
        $row = new FormCheckbox('anons_merge', 'Сгенерировать для анонса заголовок и ссылку на новость');
        $this->rows['anons_merge'] = $rows_plugin[] = $row->setTab('anons', 'Анонс');

        $row = new FormTextarea('anons_description', 'Текст для анонса новости в модуле');
        $this->rows['anons_description'] = $rows_plugin[] = $row->setTab('anons', 'Анонс')->setTypo();

        $this->settings['anons_category'] = $categoryAnons;

        $this->plugins_backend['anons']['rows'] = $rows_plugin;

        return $this;
    }

    /**
     * Создание анонса новости
     *
     * @param $request
     * @return bool
     */
    public function savePluginAnonsToModuleData($request)
    {
        if( !\Request::has('_jsvalidation') && (\Request::has('anons_merge') || !empty(\Request::get('anons_description')))){
            if( !config('larrock.feed.anonsCategory')){
                \Session::push('message.danger', 'larrock.feed.anonsCategory не задан. Анонс создан не будет');
                return TRUE;
            }
            $anons = LarrockFeed::getModel();
            $anons->title = \Request::get('title');
            $anons->url = 'anons_'. \Request::get('id_connect') .''. random_int(1,9999);
            $anons->category = LarrockFeed::getConfig()->settings['anons_category'];
            $anons->user_id = \Auth::id();
            $anons->active = 1;
            $anons->position = LarrockFeed::getModel()->whereCategory(LarrockFeed::getConfig()->settings['anons_category'])->max('position') +1;

            if(\Request::has('anons_merge')){
                $original = LarrockFeed::getModel()->whereId(\Request::get('id_connect'))->first();
                $anons->short = '<a href="'. $original->full_url .'">'. \Request::get('title') .'</a>';
            }else{
                $anons->short = \Request::get('anons_description');
            }

            if($anons->save()){
                \Session::push('message.success', 'Анонс добавлен');
            }else{
                \Session::push('message.danger', 'Анонс не добавлен');
            }
        }
        return TRUE;
    }

    /**
     * Вспомогательный метод построения правил валидации из конфига полей компонента
     *
     * @param $config
     * @param string $action create|update
     * @param null   $id
     *
     * @return array
     */
    public static function _valid_construct($config, $action = 'create', $id = NULL)
    {
        $rules = array();
        if(isset($config->rows)){
            foreach($config->rows as $rows_value){
                if( !empty($rows_value->valid)){
                    $rules[$rows_value->name] = $rows_value->valid;
                    if($action === 'update'){
                        $rules[$rows_value->name] = str_replace(':id', $id, $rules[$rows_value->name]);
                    }else{
                        $rules[$rows_value->name] = str_replace(',:id', '', $rules[$rows_value->name]);
                    }
                }
            }
        }
        return $rules;
    }

    /**
     * Вывод данные полей компонента для табов
     * @param $data
     * @return $this
     */
    public function tabbable($data)
    {
        if($this->plugins_backend){
            $this->addDataPlugins($data);
        }

        foreach($this->rows as $row_value){
            $this->tabs->put(key($row_value->tab), current($row_value->tab));
        }
        foreach($this->tabs as $tab_key => $tab_value){
            $render = '';
            foreach($this->rows as $row_value){
                $class_name = get_class($row_value);
                $load_class = new $class_name(NULL, NULL);

                if(key($row_value->tab) === $tab_key){
                    if(method_exists($load_class, 'render')){
                        $render .= $load_class->render($row_value, $data);
                    }else{
                        $this->tabs_data->put($tab_key, '<p>'. $class_name .' не определен для отрисовки</p>');
                    }
                }
            }
            $this->tabs_data->put($tab_key, $render);
        }

        return $this;
    }

    /**
     * Присоединяем данные от плагинов
     * @param $data
     */
    public function addDataPlugins($data)
    {
        foreach ($this->plugins_backend as $key_plugin => $value_plugin){
            if($key_plugin === 'seo'){
                if($plugin_data = $data->get_seo){
                    foreach ($this->rows as $key => $value){
                        if($value->name === 'seo_title' || $value->name === 'seo_description' || $value->name === 'seo_keywords'){
                            $this->rows[$key]->default = $plugin_data->{$value->name};
                        }
                    }
                }
            }
        }
    }

    /**
     * Удаление данных плагинов
     *
     * @param $config
     */
    public function removeDataPlugins($config)
    {
        if($config->plugins_backend){
            foreach ($config->plugins_backend as $key_plugin => $value_plugin){
                if($key_plugin === 'seo'){
                    if($seo = LarrockSeo::getModel()->whereSeoIdConnect(\Request::input('id_connect'))->whereSeoTypeConnect(\Request::input('type_connect'))->first()){
                        $seo->delete();
                    }
                }
            }
        }
        $this->removeLinkData($config);
    }

    /**
     * Удаление данных связей при удалении материала
     * @param $config
     */
    public function removeLinkData($config)
    {
        foreach($config->rows as $row){
            if(get_class($row) === 'Larrock\Core\Helpers\FormBuilder\FormTagsLink'){
                //Удаляем старые связи
                $model = new Link();
                $model->whereIdParent(\Request::input('id_connect'))->whereModelParent($row->modelParent)->whereModelChild($row->modelChild)->delete();
            }
        }
    }

    /**
     * Метод изменяет данные прикрепляемых полей при изменении/удалении/добавлении материала
     * ИСПОЛЬЗОВАНИЕ: в экшенах сохранения/удаления материалов после data->save()
     *
     * @param mixed $config     Предсгенерированный конфиг компонента
     * @param mixed $data       Данные материала после сохранения ($data->save())
     * @param mixed $request    Параметры переданные в качестве запроса. Значение Request $request
     */
    public function actionAttach($config, $data, $request)
    {
        foreach ($config->rows as $row){
            if($row->attached && ($request->getMethod() !== 'GET')){
                foreach($data->{$row->connect->relation_name}()->get() as $category){
                    $data->{$row->connect->relation_name}()->detach($category);
                }

                if($request->has($row->name)){
                    if(is_array($request->get($row->name))){
                        foreach ($request->get($row->name) as $param){
                            if(class_basename($row) === 'FormTagsCreate'){
                                if( !$row->connect->model::find($param)){
                                    if($find_param = $row->connect->model::whereTitle($param)->first()){
                                        $param = $find_param->id;
                                    }else{
                                        $add_param = new $row->connect->model();
                                        $add_param['title'] = $param;
                                        $add_param->save();
                                        $param = $add_param->id;
                                    }
                                }
                            }
                            $data->{$row->connect->relation_name}()->attach($param);
                        }
                    }else{
                        $data->{$row->connect->relation_name}()->attach($request->get($row->name));
                    }
                }
            }
        }
    }

    /**
     * Перезапись конфига компонента (например внутри контроллера)
     *
     * @param $option
     * @param $config
     * @return $this
     */
    public function overrideComponent($option, $config)
    {
        $this->{$option} = $config;
        return $this;
    }

    /**
     * Разрешить поиск по материалам компонента
     *
     * @return $this
     */
    public function isSearchable()
    {
        $this->searchable = TRUE;
        return $this;
    }

    /**
     * Формирование пунктов меню компонента в админке
     *
     * @return string
     */
    public function renderAdminMenu()
    {
        return '';
    }

    /**
     * Метод встаивания данных компонента в карту сайта sitemap.xml
     *
     * @return null
     */
    public function createSitemap()
    {
        return NULL;
    }

    /**
     * Метод встаивания данных компонента в rss-feed
     *
     * @return null
     */
    public function createRSS()
    {
        return NULL;
    }

    /**
     * Данные для поиска по материалам компонента
     *
     * @param null|bool $admin Если TRUE - дял поиска будут доступны вообще все элементы (не только опубликованные)
     * @return null
     */
    public function search($admin)
    {
        return NULL;
    }
}