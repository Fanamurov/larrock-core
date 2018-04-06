<?php

namespace Larrock\Core\Traits;

use Lang;
use Session;
use Redirect;
use Larrock\Core\Component;
use Illuminate\Http\Request;
use Larrock\Core\Events\ComponentItemDestroyed;

trait AdminMethodsDestroy
{
    /** @var Component */
    protected $config;

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function destroy(Request $request, $id)
    {
        if ($request->has('ids') && \is_array($request->get('ids'))) {
            foreach ($request->get('ids') as $id_item) {
                $this->destroyElement($request, $id_item);
            }
            Session::push('message.success', 'Удалено '.\count($request->get('ids')).' элементов');

            return back();
        }
        $this->destroyElement($request, $id);
        if ($request->has('category_item')) {
            return Redirect::to('/admin/'.$this->config->name.'/'.$request->get('category_item'));
        }
        if ($request->get('place') === 'material') {
            return Redirect::to('/admin/'.$this->config->name);
        }

        return back();
    }

    /**
     * Remove id element.
     * @param Request $request
     * @param $id
     * @throws \Exception
     */
    protected function destroyElement(Request $request, $id)
    {
        if ($data = $this->config->getModel()::find($id)) {
            if (method_exists($data, 'clearMediaCollection')) {
                $data->clearMediaCollection();
            }
            $name = $data->title;

            if ($data->delete()) {
                event(new ComponentItemDestroyed($this->config, $data, $request));
                \Cache::flush();
                Session::push('message.success', Lang::get('larrock::apps.delete.success', ['name' => $name]));
            } else {
                Session::push('message.danger', Lang::get('larrock::apps.delete.error', ['name' => $name]));
            }
        } else {
            Session::push('message.danger', 'Такого материала уже не существует');
        }
    }
}
