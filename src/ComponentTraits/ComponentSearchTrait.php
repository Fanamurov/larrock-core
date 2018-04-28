<?php

namespace Larrock\Core\ComponentTraits;

use Larrock\Core\Helpers\FormBuilder\FormTags;

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
        if ($this->searchable !== true) {
            return [];
        }

        return \Cache::rememberForever('search'.$this->name.$admin, function () use ($admin) {
            $data = [];

            if ($this->name !== 'feed') {
                return [];
            }

            $model = new $this->model;

            $search_rows = ['id', $this->search_title];

            if (isset($this->rows['url'])) {
                $search_rows[] = 'url';
            }

            if (isset($this->rows['category']) && ! $this->rows['category'] instanceof FormTags) {
                $search_rows[] = 'category';
            }

            if ($admin) {
                if (isset($this->rows['category'])) {
                    $model = $model::with(['getCategory']);
                }
            } else {
                if (isset($this->rows['category'])) {
                    $model = $model::with(['getCategoryActive']);
                }
            }

            $items = $model->get($search_rows);

            foreach ($items as $item) {
                if (empty($item->{$this->search_title})) {
                    unset($data[$item->id]);
                } else {
                    $data[$item->id]['id'] = $item->id;
                    $data[$item->id]['title'] = $item->{$this->search_title};
                    $data[$item->id]['full_url'] = $item->full_url;
                    $data[$item->id]['component'] = $this->name;
                    $data[$item->id]['category'] = null;
                    $data[$item->id]['admin_url'] = $item->admin_url;
                    if ($admin) {
                        if ($item->getCategory) {
                            if (\count($item->getCategory) > 0) {
                                $data[$item->id]['category'] = $item->getCategory->first()->title;
                            } elseif (isset($item->getCategory->title)) {
                                $data[$item->id]['category'] = $item->getCategory->title;
                            } else {
                                unset($data[$item->id]);
                            }
                        }
                    } else {
                        if ($item->getCategoryActive) {
                            if (\count($item->getCategoryActive) > 0) {
                                $data[$item->id]['category'] = $item->getCategoryActive->first()->title;
                            } elseif (isset($item->getCategoryActive->title)) {
                                $data[$item->id]['category'] = $item->getCategoryActive->title;
                            } else {
                                unset($data[$item->id]);
                            }
                        }
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
