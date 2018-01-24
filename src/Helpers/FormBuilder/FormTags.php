<?php

namespace Larrock\Core\Helpers\FormBuilder;

use Illuminate\Database\Eloquent\Model;
use Larrock\Core\Exceptions\LarrockFormBuilderRowException;
use Larrock\Core\Models\Link;
use View;

class FormTags extends FBElement {

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
    public $allowCreate = NULL;

    /** @var null|bool  Удалять поле из $modelChild если связей к ней нет */
    public $deleteIfNoLink = NULL;

    /** @var null|bool  Задается автоматически при наличии сведения о разделе в modelParent */
    public $showCategory;

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
        $this->attached = TRUE;
        return $this;
    }

    /**
     * Условие выборки возможных элементов для связывания
     * @param int   $key
     * @param int|array $value
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
    }

    /**
     * @param $row_settings
     * @param $data
     * @return \Illuminate\Contracts\Routing\ResponseFactory|string|\Symfony\Component\HttpFoundation\Response
     * @throws LarrockFormBuilderRowException
     */
    public function render($row_settings, $data)
    {
        if( !$row_settings->modelParent){
            throw LarrockFormBuilderRowException::withMessage('modelParent поля '. $row_settings->name .' не задан');
        }
        if($row_settings->modelChild){
            $rows = ['id', 'title'];
            $model = new $row_settings->modelChild;

            if(method_exists($model, 'getConfig') && $model->getConfig()->rows && array_key_exists('category', $model->getConfig()->rows)){
                $rows[] = 'category';
                $this->showCategory = TRUE;
            }

            if($row_settings->modelChildWhereKey && $row_settings->modelChildWhereValue){
                if(is_array($this->modelChildWhereValue)){
                    $tags = $model->whereIn($row_settings->modelChildWhereKey, $row_settings->modelChildWhereValue)->get($rows);
                }else{
                    $tags = $model->where($row_settings->modelChildWhereKey, '=', $row_settings->modelChildWhereValue)->get($rows);
                }
            }else{
                $tags = $model->get($rows);
            }

            $selected = NULL;
            if($data){
                $selected = $data->getLink($row_settings->modelChild)->get();
            }
        }else{
            throw LarrockFormBuilderRowException::withMessage('modelChild поля '. $row_settings->name .' не задан');
        }

        if($row_settings->allowCreate){
            return View::make('larrock::admin.formbuilder.tags.tagsCreate', ['tags' => $tags, 'data' => $data,
                'row_key' => $row_settings->name, 'row_settings' => $row_settings, 'selected' => $selected])->render();
        }
        return View::make('larrock::admin.formbuilder.tags.link', ['tags' => $tags, 'data' => $data,
            'row_key' => $row_settings->name, 'row_settings' => $row_settings, 'selected' => $selected])->render();
    }
}