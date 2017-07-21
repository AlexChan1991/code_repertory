<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/27
 * Time: 9:29
 */

class Tree {
	//获取某分类的直接子分类
	function getSons($categorys, $catId = 0) {
		$sons = array();
		foreach ($categorys as $item) {
			if ($item['parent_id'] == $catId) {
				$sons[] = $item;
			}

		}
		return $sons;
	}

	//获取某个分类的所有子分类
	function getSubs($categorys, $catId = 0) {
		$subs = array();
		foreach ($categorys as $item) {
			if ($item['parent_id'] == $catId) {
//                $item['level'] = $level;
				$subs[] = $item;
				$subs = array_merge($subs, $this->getSubs($categorys, $item['cate_id']));

			}

		}
		return $subs;
	}

	//获取某个分类的所有父分类
	//方法一，递归
	function getParents($categorys, $catId) {
		$tree = array();
		foreach ($categorys as $item) {
			if ($item['cate_id'] == $catId) {
				if ($item['parent_id'] > 0) {
					$tree = array_merge($tree, $this->getParents($categorys, $item['parent_id']));
				}

				$tree[] = $item;
				break;
			}
		}
		return $tree;
	}

	//方法二,迭代
	function getParents2($categorys, $catId) {
		$tree = array();
		while ($catId != 0) {
			foreach ($categorys as $item) {
				if ($item['cate_id'] == $catId) {
					$tree[] = $item;
					$catId = $item['parent_id'];
					break;
				}
			}
		}
		return $tree;
	}

	/**
	 * 数组转化为树状结构
	 * @param  [type]  $list  [description]
	 * @param  string  $pk    [主键]
	 * @param  string  $pid   [父级]
	 * @param  string  $child [节点名称]
	 * @param  integer $root  [description]
	 * @return [type]         [description]
	 */
	public function array_to_tree($list, $pk = 'cate_id', $pid = 'parent_id', $child = 'node', $root = 0) {
		$tree = array();
		if (is_array($list)) {
			$refer = array();
			foreach ($list as $key => $data) {
				$refer[$data[$pk]] = &$list[$key];
			}
			foreach ($list as $key => $data) {
				$parantId = $data[$pid];
				if ($root == $parantId) {
					$tree[] = &$list[$key];
				} else {
					if (isset($refer[$parantId])) {
						// dump($refer);
						$parent = &$refer[$parantId];
						$parent[$child][] = &$list[$key];
					}
				}
			}
		}
		return $tree;
	}

	/**
	 * 将数组转换为树
	 * @param array $elements
	 * @param int $parentId
	 * @return array
	 */
	function buildTree(array &$elements, $parentId = 0) {
		$branch = array();

		foreach ($elements as $element) {
			if ($element['parent_id'] == $parentId) {
				$children = $this->buildTree($elements, $element['cate_id']);
				if ($children) {
					$element['children'] = $children;
				}
				$branch[$element['cate_id']] = $element;
//                unset($elements[$element['cate_id']]);
			}
		}
		return $branch;
	}

}