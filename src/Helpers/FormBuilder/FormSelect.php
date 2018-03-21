<?php

namespace Larrock\Core\Helpers\FormBuilder;

use Illuminate\Database\Eloquent\Model;
use Larrock\Core\Exceptions\LarrockFormBuilderRowException;
use View;

class FormSelect extends FBElement
{
    /** @var array|null */
    public $options;

    /** @var string */
    public $option_title;

    /** @var null|bool */
    public $allowCreate;

    /** @var null|mixed */
    public $connect;

    /**
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * @param string $row
     * @return $this
     */
    public function setOptionsTitle($row)
    {
        $this->option_title = $row;
        return $this;
    }

    /**
     * @return $this
     */
    public function setAllowCreate()
    {
        $this->allowCreate = TRUE;
        return $this;
    }

    /**
     * Установка связи поля с какой-либо моделью
     * Сейчас применяется в FormSelect, FormCategory
     * @param Model $model
     * @param null $relation_name
     * @param null $group_by
     * @return $this
     */
    public function setConnect($model, $relation_name = NULL, $group_by = NULL)
    {
        $this->connect = collect();
        $this->connect->model = $model;
        $this->connect->relation_name = $relation_name;
        $this->connect->group_by = $group_by;
        return $this;
    }

    /**
     * Установка опции выборки значений для setConnect()
     * @param string $key
     * @param string $value
     * @return $this
     * @throws LarrockFormBuilderRowException
     */
    public function setWhereConnect(string $key, string $value)
    {
        if( !isset($this->connect->model)){
            throw new LarrockFormBuilderRowException('У поля '. $this->name .' сначала нужно определить setConnect');
        }
        $this->connect->where_key = $key;
        $this->connect->where_value = $value;
        return $this;
    }

    /**
     * @param $row_settings
     * @param $data
     * @return mixed
     */
    public function render($row_settings, $data)
    {
        if( !isset($data->{$row_settings->name}) && $row_settings->default && $row_settings->default !== NULL){
            $data->{$row_settings->name} = $row_settings->default;
        }

        if($row_settings->connect){
            if( !$row_settings->options){
                $row_settings->options = collect();
            }else{
                $row_settings->options = collect($row_settings->options);
            }
            $model = new $row_settings->connect->model;
            $get_options_query = $model;
            if(isset($row_settings->connect->where_key) && $row_settings->connect->where_key){
                $get_options_query = $get_options_query->where($row_settings->connect->where_key, '=', $row_settings->connect->where_value);
            }

            if(isset($row_settings->connect->group_by) && $row_settings->connect->group_by){
                $get_options_query = $get_options_query->groupBy([$row_settings->connect->group_by]);
            }

            if($get_options = $get_options_query->get()){
                foreach($get_options as $get_options_value){
                    $row_settings->options->push($get_options_value);
                }
            }
        }else{
            $row_settings->options = collect($row_settings->options);
        }

        $selected = [];
        if(\Request::input($row_settings->name)){
            $selected[] = \Request::input($row_settings->name);
        }

        return View::make('larrock::admin.formbuilder.select.value', ['row_key' => $row_settings->name,
            'row_settings' => $row_settings, 'data' => $data, 'selected' => $selected])->render();
    }
}