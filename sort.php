<?php
/*
比喻：整理一沓无序的纸张。

- 冒泡：依次找出最后一页，不断比较【交换】
- 选择：依次找出最后一页，比较找到但最后一次交换
- 插入：先把前面的排好
- 快速：以一个数为基准分成大小两堆再递归
- 归并：直接分两堆，再递归分下去，排好再上交合并。相当于一个公司下发任务来完成。
- 堆：做个大顶推（有点像32队足球比赛），从最下层扫，把大的扫上去（二分法？冒泡）。
- 希尔：插入排序每次只移动了一个位置，末尾的0到最前面很费劲。<=改进：跳跃式前后交换。实现上，一下分到底，两两一组，执行插入排序；然后4个一组，再插入排序。。这样小范围基本有序，减少了交换次数！（感觉不如快排高级）
- 计数:一桶一数
- 桶：一桶一范围
- 基数：每位数字

理论，很重要，算法复杂度==
自己理解只是一种方式，能够记得久一点。
代码写法有多种组织形式，自己必须记住一种。
边界处理问题，是coding中的难点。

算法需要详解：https://blog.csdn.net/l754539910/article/details/87635192
算法可视化有助于理解：https://www.cs.usfca.edu/~galles/visualization/RadixSort.html
*/

# 冒泡排序
function bubbleSort($arr)
{
    $n = count($arr);
    for ($i = 1; $i < $n; $i++) {
        for ($k = 0; $k < $n - $i; $k++) {
            if ($arr[$k] > $arr[$k + 1]) {
                $tmp = $arr[$k + 1];
                $arr[$k + 1] = $arr[$k];
                $arr[$k] = $tmp;
            }
        }
    }
    return $arr;
}

# 选择排序
function selectSort($arr)
{
    $len = count($arr);
    for ($i = 0; $i < $len - 1; $i++) {
        $min = $i;//找小的
        for ($j = $i + 1; $j < $len; $j++) {
            if ($arr[$j] < $arr[$min]) $min = $j;
        }
        if ($min != $i) {
            $tmp = $arr[$min];
            $arr[$min] = $arr[$i];
            $arr[$i] = $tmp;
        }
    }
    return $arr;
}

# 插入排序
function insertSort($arr)
{
    $count = count($arr);
    for ($i = 1; $i < $count; $i++) {
        $tmp = $arr[$i];
        $j = $i - 1;
        while ($j >= 0 && $tmp > $arr[$j]) {
            $arr[$j + 1] = $arr[$j--];
        }
        $arr[$j + 1] = $tmp;
    }
    return $arr;
}


# 快速排序
function quickSort($arr)
{
    //不想修改原数组则可用匿名函数
    quickSort0($arr, 0, count($arr));
    return $arr;
}

// 快速排序(原地)
function quickSort0(&$arr, $min = 0, $max = null)
{
    is_null($max) && $max = count($arr);
    $position = $min;//基准
    $middle = $arr[$position];
    for ($i = $min; $i < $max; $i++) {
        if ($arr[$i] < $middle) {
            $arr[$position] = $arr[$i];//小的前放
            $arr[$i] = $arr[++$position];//被占的数换到后面
        }
    }
    $arr[$position] = $middle;//基准到了最终位置
    if ($min < $position) quickSort0($arr, $min, $position);
    if ($max > $position + 1) quickSort0($arr, $position + 1, $max);
}

// 写法组织可以多样，这个很清晰：关键是双指针可以减少赋值操作！
function partition(&$arr, $leftIndex, $rightIndex)
{
    $pivot = $arr[($leftIndex + $rightIndex) / 2];
    while ($leftIndex <= $rightIndex) {
        while ($arr[$leftIndex] < $pivot) $leftIndex++;
        while ($arr[$rightIndex] > $pivot) $rightIndex--;
        if ($leftIndex <= $rightIndex) {
            list($arr[$leftIndex], $arr[$rightIndex]) = [$arr[$rightIndex], $arr[$leftIndex]];
            $leftIndex++;
            $rightIndex--;
        }
    }
    return $leftIndex;
}

function quickSort1(&$arr, $leftIndex, $rightIndex)
{
    if ($leftIndex < $rightIndex) {
        $index = partition($arr, $leftIndex, $rightIndex);

        quickSort1($arr, $leftIndex, $index - 1);
        quickSort1($arr, $index, $rightIndex);
    }
}

//用额外空间，倒是简单清晰
function quickSort2($arr)
{
    //先判断是否需要继续进行
    $length = count($arr);
    if ($length <= 1) return $arr;
    //选择第一个元素作为基准
    $base_num = $arr[0];
    //遍历除了标尺外的所有元素，按照大小关系放入两个数组内
    //初始化两个数组
    $left_array = array();  //小于基准的
    $right_array = array();  //大于基准的
    for ($i = 1; $i < $length; $i++) {
        if ($base_num > $arr[$i]) {
            $left_array[] = $arr[$i];
        } else {
            $right_array[] = $arr[$i];
        }
    }
    //再分别对左边和右边的数组进行相同的排序处理方式递归调用这个函数
    $left_array = quickSort2($left_array);
    $right_array = quickSort2($right_array);
    //合并
    return array_merge($left_array, array($base_num), $right_array);
}

//$arr = [3, 7, 5, 4, 8, 1, 6, 9, 2];
//$arr = quickSort0($arr);
//print_r($arr);

/**
 * 归并排序
 *
 * @param array $arr
 * @return array
 */
function mergeSort(array $arr)
{
    $n = count($arr);
    if ($n <= 1) return $arr;

    $mid = $n >> 1;//floor/intval($n/2)
    $left = mergeSort(array_slice($arr, 0, $mid));
    $right = mergeSort(array_slice($arr, $mid));
    $arr = merge($left, $right);
    return $arr;
}

function merge(array $left, array $right)
{
    $lists = [];
    $i = $j = 0;
    while ($i < count($left) && $j < count($right)) {
        $lists[] = $left[$i] < $right[$j] ? $left[$i++] : $right[$j++];
        //if ($left[$i] < $right[$j]) {
        //    $lists[] = $left[$i];
        //    $i++;
        //} else {
        //    $lists[] = $right[$j];
        //    $j++;
        //}
    }
    $lists = array_merge($lists, array_slice($left, $i));
    $lists = array_merge($lists, array_slice($right, $j));
    return $lists;
}

/**
 * 堆排序
 * 堆排序是指利用堆这种数据结构所设计的一种排序算法。
 * 堆积是一个近似完全二叉树的结构，并同时满足堆积的性质：即子结点的键值或索引总是小于（或者大于）它的父节点。
 * 堆排序的平均时间复杂度为Ο(nlogn) 。
 *
 * 算法步骤：
 * 创建一个堆H[0..n-1]；
 * 把堆首（最大值）和堆尾互换；
 * 把堆的尺寸缩小1，并调用shift_down(0)，目的是把新的数组顶端数据调整到相应位置；
 * 重复步骤2，直到堆的尺寸为1。
 * @param array $lists
 * @return array
 */
function heap_sort(array $lists)
{
    $n = count($lists);
    build_heap($lists);
    while (--$n) {
        $val = $lists[0];
        $lists[0] = $lists[$n];
        $lists[$n] = $val;
        heap_adjust($lists, 0, $n);
        //echo "sort: " . $n . "\t" . implode(', ', $lists) . PHP_EOL;
    }
    return $lists;
}

function build_heap(array &$lists)
{
    $n = count($lists) - 1;
    for ($i = floor(($n - 1) / 2); $i >= 0; $i--) {
        heap_adjust($lists, $i, $n + 1);
        //echo "build: " . $i . "\t" . implode(', ', $lists) . PHP_EOL;
    }
    //echo "build ok: " . implode(', ', $lists) . PHP_EOL;
}

function heap_adjust(array &$lists, $i, $num)
{
    if ($i > $num / 2) {
        return;
    }
    $key = $i;
    $leftChild = $i * 2 + 1;
    $rightChild = $i * 2 + 2;

    if ($leftChild < $num && $lists[$leftChild] > $lists[$key]) {
        $key = $leftChild;
    }
    if ($rightChild < $num && $lists[$rightChild] > $lists[$key]) {
        $key = $rightChild;
    }
    if ($key != $i) {
        $val = $lists[$i];
        $lists[$i] = $lists[$key];
        $lists[$key] = $val;
        heap_adjust($lists, $key, $num);
    }
}


/**
 * 希尔排序 标准
 * 希尔排序，也称递减增量排序算法，是插入排序的一种更高效的改进版本。
 * 但希尔排序是非稳定排序算法。
 *
 * 希尔排序是基于插入排序的以下两点性质而提出改进方法的：
 *
 * 插入排序在对几乎已经排好序的数据操作时， 效率高， 即可以达到线性排序的效率
 * 但插入排序一般来说是低效的， 因为插入排序每次只能将数据移动一位
 *
 * 算法步骤：
 * 先将整个待排序的记录序列分割成为若干子序列，分别进行直接插入排序
 * 待整个序列中的记录“基本有序”时，再对全体记录进行依次直接插入排序。
 * @param array $lists
 * @return array
 */
function shellSort(array $lists)
{
    $n = count($lists);
    $step = 2;
    $gap = intval($n / $step);
    while ($gap > 0) {
        for ($gi = 0; $gi < $gap; $gi++) {
            for ($i = $gi; $i < $n; $i += $gap) {
                $key = $lists[$i];
                for ($j = $i - $gap; $j >= 0 && $lists[$j] > $key; $j -= $gap) {
                    $lists[$j + $gap] = $lists[$j];
                    $lists[$j] = $key;
                }
            }
        }
        $gap = intval($gap / $step);
    }
    return $lists;
}

# 基数排序
function radixSort(array $lists)
{
    $radix = 10;
    $max = max($lists);
    $k = ceil(log($max, $radix));
    if ($max == pow($radix, $k)) {
        $k++;
    }
    for ($i = 1; $i <= $k; $i++) {
        $newLists = array_fill(0, $radix, []);
        for ($j = 0; $j < count($lists); $j++) {
            $key = $lists[$j] / pow($radix, $i - 1) % $radix;
            $newLists[$key][] = $lists[$j];
        }
        $lists = [];
        for ($j = 0; $j < $radix; $j++) {
            $lists = array_merge($lists, $newLists[$j]);
        }
    }
    return $lists;
}


//===查找===============

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
