<?php

// 二分法搜索大于$value的最小值
// sorted array must be a 0 indexed
// left most index, right most index all inclusive
// finds all of the elements coming from the left to the right that is less or equal to the key
function bisect_right($sorted_array, $value, $left = null, $right = null)
{
    if (is_null($left)) {
        reset($sorted_array);
        $left = key($sorted_array);
    }

    if (is_null($right)) {
        end($sorted_array);
        $right = key($sorted_array);
        reset($sorted_array);
    }

    if ($value < $sorted_array[$left]) {
        return [0, $sorted_array[0]];
    } elseif ($value >= $sorted_array[$right]) {
        return [-1, null];//count($sorted_array);
    }

    // this section only works for keys that are within the range and exclusive of the last element

    // converging upon compact range L,R where R-L = 1, where L can potentially equal the key
    while ($right - $left > 1) {

        // the middle when converted to an integer is biased to the left
        $middle = intval(($left + $right) / 2);

        // the left can potentially equal the key's position
        if ($value >= $sorted_array[$middle]) {
            $left = $middle;
        } else {
            $right = $middle;
        }
    }

    // right will always be to the right of the rightmost key (which is the left), left + 1 = right, as left and right has converged
    // therefore right is the number of elements less or equal to the key
    return $right;

}

// sorted array must be a 0 indexed
// left most index, right most index all inclusive
// finds all of the elements coming from the left to the right that is less than the key
function bisect_left($sorted_array, $value, $left = null, $right = null)
{
    if (is_null($left)) {
        reset($sorted_array);
        $left = key($sorted_array);
    }

    if (is_null($right)) {
        end($sorted_array);
        $right = key($sorted_array);
        reset($sorted_array);
    }

    if ($value < $sorted_array[$left]) {
        return 0;
    } elseif ($value >= $sorted_array[$right]) {
        return count($sorted_array);
    }

    // this section only works for keys that are within the range and exclusive of the last element

    // converging upon compact range L,R where R-L = 1, where R can potentially equal the key
    while ($right - $left > 1) {
        // the middle when converted to an integer is biased to the left
        $middle = intval(($left + $right) / 2);
        echo "$middle <= MIDDLE\n";
        // the right can potentially equal the key's position
        if ($value <= $sorted_array[$middle]) {
            $right = $middle;
        } else {
            $left = $middle;
        }
    }

    // left will always be to the left of the leftmost key (which is the right), left + 1 = right, as left and right has converged
    // therefore right is the number of elements less than the key
    return $right;
}

/**
 * 二分法搜索大于$v的最小值
 * @param array $arr 索引连续0,1,2,3..的升序数组（不从0开始则需改代码）
 * @param $v
 * @return int index of arr item which > $v
 */
function simple_bisect_right($arr, $v)
{
    if ($v < $arr[0]) return 0;
    $r = count($arr) - 1;
    if ($v >= $arr[$r]) return -1;
    $l = 0;
    while ($l < $r - 1) {
        $m = intval(($l + $r) / 2);
        if ($arr[$m] > $v) {
            $r = $m;
        } else {
            $l = $m;
        }
        echo "$l $r\n";
    }
    return $r;
}

/**
 * 二分法搜索小于$k的最大值
 * @param array $arr 索引连续0,1,2,3..的升序数组（不从0开始则需改代码）
 * @param $k
 * @return int index of arr item which > $k
 */
function simple_bisect_left($arr, $k)
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
    return $l;
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