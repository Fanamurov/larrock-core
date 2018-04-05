<?php

namespace Larrock\Core\Traits;

use Larrock\Core\Component;
use Illuminate\Http\Request;
use Larrock\Core\Helpers\MessageLarrock;

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
        if (! method_exists($this, 'store')) {
            throw new \BadMethodCallException('AdminMethodsStore not found in this Controller', 400);
        }
        $post_rows = [
            'title' => 'Новый материал',
            'url' => 'novyy-material',
        ];

        if ($request->has('category')) {
            $post_rows['category'] = $request->get('category');
        } else {
            if (array_key_exists('category', $this->config->rows)) {
                if ($findCategory = \LarrockCategory::getModel()->whereComponent($this->config->name)->first()) {
                    $post_rows['category'] = $findCategory->id;
                } else {
                    MessageLarrock::danger('Создать материал пока нельзя. Сначала создайте для него раздел');

                    return back()->withInput();
                }
            }
        }

        foreach ($this->config->rows as $row) {
            if ($row->default) {
                $post_rows[$row->name] = $row->default;
            }
            if ($row->name === 'user_id') {
                $post_rows[$row->name] = \Auth::id();
            }
        }

        if (array_key_exists('position', $this->config->rows)) {
            $post_rows['active'] = 0;
        }
        $test = Request::create('/admin/'.$this->config->name, 'POST', $post_rows);

        return $this->store($test);
    }
}
