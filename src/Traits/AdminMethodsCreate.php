<?php

namespace Larrock\Core\Traits;

use Larrock\Core\Component;
use Illuminate\Http\Request;
use Larrock\Core\Helpers\MessageLarrock;
use Larrock\Core\Helpers\FormBuilder\FormCategory;
use Larrock\ComponentCategory\Models\Category;

trait AdminMethodsCreate
{
    /** @var Component */
    protected $config;

    /**
     * Creating a new resource.
     * @param Request $request
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $post_rows = [
            'title' => $request->get('title', 'Новый материал'),
            'url' => str_slug($request->get('title', 'Новый материал')),
        ];

        if ($request->has('category')) {
            $post_rows['category'] = $request->get('category');
        }

        foreach ($this->config->rows as $row) {
            if (isset($row->modelChild) && $row->modelChild === \config('larrock.models.category', Category::class)) {
                if (! empty($request->get($row->name))) {
                    if ($findCategory = \LarrockCategory::getModel()->whereComponent($this->config->name)->whereId($request->get($row->name))->first()) {
                        $post_rows[$row->name] = $findCategory->id;
                    } else {
                        MessageLarrock::danger('Раздела с переданным id:'.$request->get($row->name).' не существует');

                        return back()->withInput();
                    }
                } else {
                    if ($findCategory = \LarrockCategory::getModel()->whereComponent($this->config->name)->first()) {
                        $post_rows[$row->name] = $findCategory->id;
                    } else {
                        MessageLarrock::danger('Создать материал пока нельзя. Сначала создайте для него раздел');

                        return back()->withInput();
                    }
                }
            }
        }

        $store = Request::create('/admin/'.$this->config->name, 'POST', $post_rows);

        if (! method_exists($this, 'store')) {
            $trait = new class {
                use AdminMethodsStore;
            };

            return $trait->updateConfig($this->config)->store($store);
        }

        return $this->store($store);
    }
}
