<?php

namespace Larrock\Core;

use Larrock\ComponentFeed\Models\Feed;
use Larrock\Core\Helpers\FormBuilder\FormCheckbox;
use Larrock\Core\Helpers\FormBuilder\FormInput;
use Larrock\Core\Helpers\FormBuilder\FormTextarea;
use Larrock\Core\Models\Seo;
use Illuminate\Database\Eloquent\Model;
use JsValidator;
use View;

class Component
{
    public $name;
    public $title;
    public $description;
    public $table;
    public $rows;

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

    protected function addPlugins()
    {
        if(empty($this->name)){
            return abort('Конфиг компонента не заполнен');
        }
        return $this;
    }

    public function addPositionAndActive()
    {
        $row = new FormInput('position', 'Вес');
        $this->rows['position'] = $row->setTab('other', 'Дата, вес, активность')->setValid('integer')
            ->setDefaultValue(0)->setInTableAdminAjaxEditable();

        $row = new FormCheckbox('active', 'Опубликован');
        $this->rows['active'] = $row->setTab('other', 'Дата, вес, активность')->setChecked()
            ->setValid('integer|max:1')->setDefaultValue(1)->setInTableAdminAjaxEditable();
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
        $this->rows['url'] = $row->setTab('seo', 'SEO')->setValid('max:155|required|unique:'. $this->table .',url,:id')->setCssClass('uk-width-1-1');

        if( !\Request::has('_jsvalidation') && \Request::has('seo_title')){
            if( !$seo = Seo::whereIdConnect(\Request::input('id_connect'))->whereTypeConnect(\Request::input('type_connect'))->first()){
                $seo = new Seo();
            }
            if(\Request::get('seo_title', '') !== ''){
                if($seo->fill(\Request::all())->save()){
                    \Alert::add('successAdmin', 'SEO обновлено')->flash();
                }
            }else{
                $seo->delete($seo->id);
                \Alert::add('successAdmin', 'SEO удалено')->flash();
            }
        }

        return $this;
    }

    public function addPluginImages()
    {
        $this->plugins_backend['images'] = 'images';
        return $this;
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

        $this->plugins_backend['anons']['rows'] = $rows_plugin;

        if( !\Request::has('_jsvalidation') && (\Request::has('anons_merge') || !empty(\Request::has('anons_description')))){
            $anons = new Feed();
            $anons->title = \Request::get('title');
            $anons->url = 'anons_'. \Request::get('id_connect') .''. random_int(1,9999);
            $anons->category = $categoryAnons;
            $anons->user_id = 1; //TODO: Временно, переписать
            $anons->active = 1;
            $anons->position = Feed::whereCategory($categoryAnons)->max('position') +1;

            if(\Request::has('anons_merge')){
                $original = Feed::whereId(\Request::get('id_connect'))->first();
                $anons->short = '<a href="'. $original->full_url .'">'. \Request::get('title') .'</a>';
            }else{
                $anons->short = \Request::get('anons_description');
            }

            if($anons->save()){
                \Alert::add('successAdmin', 'Анонс добавлен')->flash();
            }else{
                \Alert::add('errorAdmin', 'Анонс не добавлен')->flash();
            }
        }

        return $this;
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
            foreach($this->rows as $row_key => $row_value){
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

    public function removeDataPlugins($config)
    {
        if($config->plugins_backend){
            foreach ($config->plugins_backend as $key_plugin => $value_plugin){
                if($key_plugin === 'seo'){
                    if($seo = Seo::whereIdConnect(\Request::input('id_connect'))->whereTypeConnect(\Request::input('type_connect'))->first()){
                        $seo->delete();
                    }
                }
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
                            $data->{$row->connect->relation_name}()->attach($param);
                        }
                    }else{
                        $data->{$row->connect->relation_name}()->attach($request->get($row->name));
                    }
                }
            }
        }
    }

    public function isSearchable()
    {
        $this->searchable = TRUE;
        return $this;
    }

    public function renderAdminMenu()
    {
        return '';
    }

    public function createSitemap()
    {
        return NULL;
    }

    public function createRSS()
    {
        return NULL;
    }
}