<?php

namespace Larrock\Core\Helpers\FormBuilder;

use Larrock\Core\Exceptions\LarrockFormBuilderRowException;
use Larrock\Core\Models\Link;
use View;

class FormTagsLink extends FBElement {

    public $modelParent;
    public $modelChild;
    public $modelChildWhereKey;
    public $modelChildWhereValue;
    public $maxItems;

    /** @var null|bool  Задается автоматически при наличии сведения о разделе в modelParent */
    public $showCategory;

    /**
     * Указывание имени модели, которую следует связать
     * @param string    $modelName
     * @return $this
     */
    public function setModelParent($modelName)
    {
        $this->modelParent = $modelName;
        return $this;
    }

    /**
     * Указывание имени модели, к которой привязываем
     * @param string    $modelName
     * @return $this
     */
    public function setModelChild($modelName)
    {
        $this->modelChild = $modelName;
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

            if($model->config->rows && array_key_exists('category', $model->config->rows)){
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
                $selected = $data->hasMany(Link::class, 'id_parent')->whereModelParent($row_settings->modelParent)->whereModelChild($row_settings->modelChild)->get(['id_child']);
            }
        }else{
            throw LarrockFormBuilderRowException::withMessage('modelChild поля '. $row_settings->name .' не задан');
        }
        return View::make('larrock::admin.formbuilder.tags.link', ['tags' => $tags, 'data' => $data,
            'row_key' => $row_settings->name, 'row_settings' => $row_settings, 'selected' => $selected])->render();
    }
}