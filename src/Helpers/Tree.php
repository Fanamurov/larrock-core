<?php

namespace Larrock\Core\Helpers;

use Illuminate\Support\Arr;

class Tree{

    protected $activeCategory;

	/**
	 * Построение дерева объектов по определенному полю(по-умолчанию parent)
	 *
	 * @link http://stackoverflow.com/a/10332361/2748662
	 * @param $data
	 * @param string $row_level     по какому полю ищем детей
	 * @return array
	 */
	public function build_tree($data, $row_level = 'parent')
	{
		$new = array();
		foreach ($data as $a){
			if(is_null($a->{$row_level})){
				$a->{$row_level} = 0;
			}
			$new[$a->{$row_level}][] = $a;
		}
		return $this->createTree($new, Arr::get($new, 0, []));
	}

	/**
	 * Вспомогательный метод для построения дерева
	 * Прикрпепляем информацию о вложенности элемента ->level
	 *
	 * @link http://stackoverflow.com/a/10332361/2748662
	 * @param $list
	 * @param array $parent
	 * @param int $level
	 * @return array
	 */
	public function createTree(&$list, $parent, $level = 1){
		$tree = array();
		foreach ($parent as $l){
			$l->level = $level;
			if(isset($list[$l->id])){
				$l->children = $this->createTree($list, $list[$l->id], ++$level);
				--$level;
			}
			$tree[] = $l;
		}
		return $tree;
	}

    /**
     * Вспомогательный метод для получения массива все опубликованных разделов уровнем переданных в $categories и выше
     *
     * @param $categories \Eloquent
     * @return mixed
     */
	public function listActiveCategories($categories){
	    $this->iterateActiveCategories($categories);
	    return $this->activeCategory;
    }

    /**
     * Выборка опубликованных разделов, включая связи
     *
     * @param $categories
     */
    protected function iterateActiveCategories($categories)
    {
        foreach ($categories as $category){
            $this->activeCategory[] = $category->id;
            if(isset($category->get_childActive)){
                $this->listActiveCategories($category->get_childActive);
            }
        }
    }
}