<?php

namespace Larrock\Core\Events;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Larrock\Core\Component;

/**
 * Выбрасываемое событие на удаление материала из компонента
 * Class ComponentItemDestroyed
 * @package Larrock\Core\Events
 */
class ComponentItemDestroyed
{
    use SerializesModels;

    /** @var Component  */
    public $component;

    /** @var Model  */
    public $model;

    /** @var Request  */
    public $request;

    /**
     * Create a new event instance.
     *
     * @param Component $component
     * @param Model $data
     * @param Request $request
     */
    public function __construct(Component $component, Model $data, Request $request)
    {
        $this->component = $component;
        $this->model = $data;
        $this->request = $request;
    }
}
