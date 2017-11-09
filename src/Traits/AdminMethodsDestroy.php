<?php

namespace Larrock\Core\Traits;

use Illuminate\Http\Request;
use Lang;
use Larrock\Core\Component;
use Redirect;
use Session;

trait AdminMethodsDestroy{

    /**
     * @var Component
     */
    protected $config;

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param  int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $id)
    {
        if($request->has('ids') && is_array($request->get('ids'))){
            foreach ($request->get('ids') as $id_item){
                $this->destroyElement($request, $id_item);
            }
            Session::push('message.success', 'Удалено '. count($request->get('ids')) .' элементов');
            return back();
        }
        $this->destroyElement($request, $id);
        if($request->has('category_item')){
            return Redirect::to('/admin/'. $this->config->name .'/'. $request->get('category_item'));
        }
        if($request->get('place') === 'material'){
            return Redirect::to('/admin/'. $this->config->name);
        }
        return back();
    }

    /**
     * Remove id element
     * @param Request $request
     * @param $id
     */
    protected function destroyElement(Request $request, $id)
    {
        if($data = $this->config->getModel()::find($id)){
            if(method_exists($data, 'clearMediaCollection')){
                $data->clearMediaCollection();
            }
            $name = $data->title;
            $this->config->removeDataPlugins($this->config);

            if($data->delete()){
                $this->config->actionAttach($this->config, $data, $request);
                \Cache::flush();
                Session::push('message.success', Lang::get('larrock::apps.delete.success', ['name' => $name]));
            }else{
                Session::push('message.danger', Lang::get('larrock::apps.delete.error', ['name' => $name]));
            }
        }else{
            Session::push('message.danger', 'Такого материала больше нет');
        }
    }
}