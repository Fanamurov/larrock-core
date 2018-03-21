<?php

namespace Larrock\Core\Helpers\FormBuilder;

use Illuminate\Database\Eloquent\Model;
use Larrock\Core\Exceptions\LarrockFormBuilderRowException;
use Larrock\Core\Models\Link;
use View;

class FormTags extends FBElement
{
    /** @var Model  Модель связываемого компонента */
    public $modelParent;

    /** @var Model  Модель компонента с которым связываем */
    public $modelChild;

    /** @var string Ключ поля для выборки в $modelChild */
    public $modelChildWhereKey;

    /** @var string Значение поля для выборки в $modelChild */
    public $modelChildWhereValue;

    /** @var integer    Сколько элементов максимум можно выбрать */
    public $maxItems;

    /** @var null|bool  Позволить пользователям создавать новые поля */
    public $allowCreate;

    /** @var null|bool  Удалять поле из $modelChild если связей к ней нет */
    public $deleteIfNoLink;

    /** @var null|bool  Задается автоматически при наличии сведения о разделе в modelParent */
    public $showCategory;

    /** @var null|bool  Поле может использоваться для создания модицикаций (для каталога) */
    public $costValue;

    /** @var string Какое поле использоваться в качестве заголовка в modelChild */
    public $titleRow = 'title';

    /**
     * Передача моделей для связывания
     *
     * @param Model $modelParent    Что связываем
     * @param Model $modelChild     С чем связываем
     * @return $this
     */
    public function setModels($modelParent, $modelChild)
    {
        $this->modelParent = $modelParent;
        $this->modelChild = $modelChild;
        return $this;
    }

    /**
     * Условие выборки возможных элементов для связывания
     * @param int   $key
     * @param int|array|string $value
     * @return $this
     */
    public function setModelChildWhere($key, $value)
    {
        $this->modelChildWhereKey = $key;
        $this->modelChildWhereValue = $value;
        return $this;
    }

    /**
     * Сколько элементов можно выбрать для связывания
     * @param int   $count
     * @return $this
     */
    public function setMaxItems($count)
    {
        $this->maxItems = $count;
        return $this;
    }

    /**
     * Разрешать создавать новые элементы
     * @return $this
     */
    public function setAllowCreate()
    {
        $this->allowCreate = TRUE;
        return $this;
    }

    /**
     * Удалять поле из $modelChild если связей к ней нет
     * @return $this
     */
    public function deleteIfNoLink()
    {
        $this->deleteIfNoLink = TRUE;
        return $this;
    }

    /**
     * Использовать поле для создания модицикаций (для каталога)
     * @return $this
     */
    public function setCostValue()
    {
        $this->costValue = TRUE;
        return $this;
    }

    /**
     * Какое поле использоваться в качестве заголовка в modelChild
     * @param $rowName
     * @return $this
     */
    public function setTitleRow($rowName)
    {
        $this->titleRow = $rowName;
        return $this;
    }

    /**
     * @param $row_settings
     * @param $data
     * @return \Illuminate\Contracts\Routing\ResponseFactory|string|\Symfony\Component\HttpFoundation\Response
     * @throws LarrockFormBuilderRowException
     */
    public function render($row_settings, $data)
    {
        if( !$row_settings->modelParent || !$row_settings->modelChild){
            throw LarrockFormBuilderRowException::withMessage('modelParent или modelChild поля '. $row_settings->name .' не задан');
        }

        $rows = ['id', $row_settings->titleRow];
        $model = new $row_settings->modelChild;

        if(method_exists($model, 'getConfig') && array_key_exists('category', $model->getConfig()->rows)
            && $model->getConfig()->rows['category']->fillable === TRUE){
            $rows[] = 'category';
            $this->showCategory = TRUE;
        }

        if($row_settings->modelChildWhereKey && $row_settings->modelChildWhereValue){
            if(\is_array($this->modelChildWhereValue)){
                $tags = $model->whereIn($row_settings->modelChildWhereKey, $row_settings->modelChildWhereValue)->get($rows);
            }else{
                $tags = $model->where($row_settings->modelChildWhereKey, '=', $row_settings->modelChildWhereValue)->get($rows);
            }
        }else{
            $tags = $model->get($rows);
        }

        $selected = NULL;
        if($data){
            if($row_settings->modelChild === 'Larrock\ComponentUsers\Roles\Models\Role'){
                $selected = $data->getLinkWithParams($row_settings->modelChild, 'role_user', 'role_id', 'user_id')->get();
            }else{
                $selected = $data->getLink($row_settings->modelChild)->get();
                if($row_settings->costValue){
                    foreach ($selected as $key => $value){
                        if($link = Link::whereModelParent($row_settings->modelParent)
                            ->whereModelChild($row_settings->modelChild)
                            ->whereIdParent($data->id)
                            ->whereIdChild($value->id)->first(['cost'])){
                            $selected[$key]->cost = $link->cost;
                        }
                    }
                }
            }
        }

        return View::make('larrock::admin.formbuilder.tags.tags', ['tags' => $tags, 'data' => $data,
            'row_key' => $row_settings->name, 'row_settings' => $row_settings, 'selected' => $selected])->render();
    }
}