<?php

namespace Larrock\Core\Events;

use Larrock\Core\Component;
use Illuminate\Http\Request;
use Illuminate\Queue\SerializesModels;
use Illuminate\Database\Eloquent\Model;

/**
 * Выбрасываемое событие на удаление материала из компонента
 * Class ComponentItemDestroyed.
 */
class ComponentItemDestroyed
{
    use SerializesModels;

    /** @var Component */
    public $component;

    /** @var Model */
    public $model;

    /** @var Request */
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
