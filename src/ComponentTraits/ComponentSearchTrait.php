<?php

namespace Larrock\Core\ComponentTraits;

trait ComponentSearchTrait
{
    /** @var null|bool Осуществляется ли поиск по компоненту */
    public $searchable;

    /** @var string Какое поле модели показывать для поиска */
    public $search_title;

    /**
     * Разрешить поиск по материалам компонента.
     *
     * @param string $search_title Какое поле модели показывать для поиска
     * @return $this
     */
    public function isSearchable($title = 'title')
    {
        $this->searchable = true;
        $this->search_title = $title;

        return $this;
    }

    /**
     * Данные для поиска по материалам компонента.
     * @param null|bool $admin Если TRUE - для поиска будут доступны вообще все элементы (не только опубликованные)
     * @return null
     */
    public function search($admin = null)
    {
        return \Cache::rememberForever('search'.$this->name.$admin, function () use ($admin) {
            $data = [];

            $search_rows = ['id', $this->search_title];

            if ($admin) {
                if (\in_array('category', array_keys($this->rows))) {
                    $search_rows[] = 'category';
                    $items = $this->model::with(['getCategory'])->get($search_rows);
                } else {
                    $items = $this->model::get($search_rows);
                }
            } else {
                if (\in_array('category', array_keys($this->rows))) {
                    $search_rows[] = 'category';
                    $items = $this->model::whereActive(1)->with(['getCategoryActive'])->get($search_rows);
                } else {
                    $items = $this->model::whereActive(1)->get($search_rows);
                }
            }
            foreach ($items as $item) {
                $data[$item->id]['id'] = $item->id;
                $data[$item->id]['title'] = $item->{$this->search_title};
                $data[$item->id]['full_url'] = $item->full_url;
                $data[$item->id]['component'] = $this->name;
                $data[$item->id]['category'] = null;
                $data[$item->id]['admin_url'] = $item->admin_url;
                if ($admin) {
                    if ($item->getCategory) {
                        $data[$item->id]['category'] = $item->getCategory->title;
                    }
                } else {
                    if ($item->getCategoryActive) {
                        $data[$item->id]['category'] = $item->getCategoryActive->title;
                    }
                }
            }
            if (\count($data) === 0) {
                return null;
            }

            return $data;
        });
    }
}