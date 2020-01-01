<?php
/*
 * @Description: 
 * @Author: freeair
 * @Date: 2020-01-01 20:00:26
 * @LastEditors  : freeair
 * @LastEditTime : 2020-01-01 20:06:39
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Class Common tools
 */
class Common_tools
{
	public function __construct()
	{
		// Check compat first

	}

	/**
     * 一维数据数组生成数据树
     * @param array $list 数据列表
     * @param string $id 父ID Key
     * @param string $pid ID Key
     * @param string $son 定义子数据Key
     * @return array
     */
    public static function arr2tree($list, $id = 'id', $pid = 'pid', $son = 'children')
    {
        list($tree, $map) = [[], []];
        foreach ($list as $item) {
            $map[$item[$id]] = $item;
        }
        foreach ($list as $item) {
            if (isset($item[$pid]) && isset($map[$item[$pid]])) {
                $map[$item[$pid]][$son][] = &$map[$item[$id]];
            } else {
                $tree[] = &$map[$item[$id]];
            }
        }
        unset($map);
        return $tree;
    }
}
