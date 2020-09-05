<?php

/**
 * 二分法搜索大于$k的最小值
 * @param array $arr 索引连续0,1,2,3..的升序数组（不从0开始则需改代码）
 * @param $k
 * @return int index of arr item which > $k
 */
function bisect_right($arr, $k)
{
    if ($k < $arr[0]) return 0;
    $r = count($arr) - 1;
    if ($k >= $arr[$r]) return -1;
    $l = 0;
    while ($l < $r - 1) {
        $m = intval(($l + $r) / 2);
        if ($arr[$m] > $k) {
            $r = $m;
        } else {
            $l = $m;
        }
    }
    return $r;
}

//==================

/**
 * 快速排序
 * @param $arr
 * @param int $min
 * @param null $max
 */
function quickSort(&$arr, $min = 0, $max = null)
{
    is_null($max) && $max = count($arr);
    $position = $min;
    $middle = $arr[$position];
    for ($i = $min; $i < $max; $i++) {
        if ($arr[$i] < $middle) {
            $arr[$position] = $arr[$i];//小的前放
            $arr[$i] = $arr[++$position];
        }
    }
    $arr[$position] = $middle;
    if ($min < $position) quickSort($arr, $min, $position);
    if ($max > $position + 1) quickSort($arr, $position + 1, $max);
}

// $arr = [3, 7, 5, 4, 8, 1, 6, 9, 2];
// quickSort($arr);