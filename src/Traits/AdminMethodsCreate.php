<?php

namespace Larrock\Core\Traits;

use Larrock\Core\Component;
use Illuminate\Http\Request;
use Larrock\Core\Helpers\MessageLarrock;
use Larrock\Core\Helpers\FormBuilder\FormCategory;

trait AdminMethodsCreate
{
    /** @var Component */
    protected $config;

    /**
     * Creating a new resource.
     * @param Request $request
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function create(Request $request)
    {
        $post_rows = [
            'title' => $request->get('title', 'Новый материал'),
            'url' => str_slug($request->get('title', 'Новый материал')),
        ];

        foreach ($this->config->rows as $row) {
            if ($row->fillable && $row instanceof FormCategory) {
                if (! empty($request->get($row->name))) {
                    if ($findCategory = \LarrockCategory::getModel()->whereComponent($this->config->name)->whereId($request->get($row->name))->first()) {
                        $post_rows[$row->name] = $findCategory->id;
                    } else {
                        MessageLarrock::danger('Раздела с переданным id:'.$request->get($row->name).' не существует');

                        return back()->withInput();
                    }
                }

                if ($findCategory = \LarrockCategory::getModel()->whereComponent($this->config->name)->first()) {
                    $post_rows[$row->name] = $findCategory->id;
                } else {
                    MessageLarrock::danger('Создать материал пока нельзя. Сначала создайте для него раздел');

                    return back()->withInput();
                }
            }
        }

        $test = Request::create('/admin/'.$this->config->name, 'POST', $post_rows);

        if (! method_exists($this, 'store')) {
            $trait = new class {
                use AdminMethodsStore;
            };

            return $trait->store($test);
        }

        return $this->store($test);
    }
}
