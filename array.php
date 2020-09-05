<?php

class Solution
{
    /**
     * 原地旋转图像
     * 不难；序号、点位和循环次数，挺难理，易错调很久。。
     * @param Integer[][] $matrix
     * @return NULL
     */
    function rotate(&$matrix)
    {
        $n = count($matrix);
        for ($i = 0; $i < intval($n / 2); $i++) {//当心判断条件
            for ($j = $i; $j < $n - 1 - $i; $j++) {//当心，右角不要转两次
                $tmp = $matrix[$i][$j];
                $matrix[$i][$j] = $matrix[$n - 1 - $j][$i];
                $matrix[$n - 1 - $j][$i] = $matrix[$n - 1 - $i][$n - 1 - $j];
                $matrix[$n - 1 - $i][$n - 1 - $j] = $matrix[$j][$n - 1 - $i];
                $matrix[$j][$n - 1 - $i] = $tmp;
            }
        }
    }

    private $nums = [1, 2, 3];

    /**
     * 用rand实现shuffle
     * @return mixed
     */
    function shuffle()
    {
        # 交换法
        $len = count($this->nums);
        for ($i = 0; $i < $len; $i++) {
            $rand = mt_rand($i, $len - 1);
            if ($rand == $i) continue;
            $tmp = $this->nums[$i];
            $this->nums[$i] = $this->nums[$rand];
            $this->nums[$rand] = $tmp;
        }
        return $this->nums;
    }

    /**
     * 合并两个有序数组
     * 整理清楚思路，才一行代码！所以这思路才是最重要的一步。
     * @param Integer[] $nums1
     * @param Integer $m
     * @param Integer[] $nums2
     * @param Integer $n
     * @return NULL
     */
    function merge(&$nums1, $m, $nums2, $n)
    {
        while ($n) {
            //echo "$m, $n\n";
            $nums1[$m + $n - 1] = $m > 0 && $nums1[$m - 1] > $nums2[$n - 1]
                ? $nums1[--$m]
                : $nums2[--$n];
        }
    }

    /**
     * 15.三数之和
     * 暴力法倒是简单，但性能算法就不容易了，3个变量比2个复杂度上了一个台阶，思维容易乱。
     * 难点：流程一维化；去重
     * @param Integer[] $nums
     * @return Integer[][]
     */
    function threeSum($nums)
    {
        $nums = [0, 0, 0, 0];
        if (3 > $n = count($nums)) return [];
        sort($nums);
        $r = [];
        # 双指针法
        for ($i = 0; $i < $n - 2; $i++) {
            if ($i && $nums[$i] == $nums[$i - 1]) continue;
            $x = -$nums[$i];
            $start = $i + 1;
            $end = $n - 1;
            while ($start < $end) {
                $sum = $nums[$start] + $nums[$end];
                if ($sum > $x) {
                    $end--;
                } elseif ($sum == $x) {
                    $r[] = [$nums[$i], $nums[$start], $nums[$end]];
                    while ($start < $end && $nums[$start] === $nums[$start + 1]) $start++;
                    $start++;
                    while ($start < $end && $nums[$end] === $nums[$end - 1]) $end--;
                    $end--;
                } else {
                    $start++;
                }
            }
        }
        return $r;
        $map = [];
        # 暴力法优化，还需要去重(map费时。。)，估计效率差不多O(n^2)?
        for ($i = 0; $i < $n - 2; $i++) {
            //if ($i && $nums[$i] == $nums[$i - 1]) continue;
            for ($j = $i + 1, $k = $j + 1; $j < $n; $j++) {
                //if ($j>1 && $nums[$j] == $nums[$j - 1]) continue;
                $x = 0 - $nums[$i] - $nums[$j];
                for (; $k < $n; $k++) {
                    if ($nums[$k] == $x) {
                        $r[] = [$nums[$i], $nums[$j], $nums[$k]];
                        break;
                    } elseif ($nums[$k] > $x) {
                        $k > 1 && $k--;
                        break;
                    }
                }
            }
        }
        return $r;

        # 暴力法，超时
        foreach ($nums as $i => $a) {
            foreach ($nums as $j => $b) {
                foreach ($nums as $k => $c) {
                    if ($i < $j && $j < $k && !isset($map["$a.$b"]) && $a + $b + $c == 0) {
                        $r[] = [$a, $b, $c];
                        $map["$a.$b"] = 0;
                        break;
                    }
                }
            }
        }
        return $r;
    }
    /*
    $swap = function(&$arr, $i, $j){
        $tmp = $arr[$i];
        $arr[$i]=$arr[$j];
        $arr[$j]=$tmp;
    };
    */
    /**
     * 前面值选好了，后面的依次追加，这样递归
     * @param int $start 开始点
     * @param array $path 选上的value
     * @param array $nums 原参数
     * @param $n
     * @param $r
     * @param $paths
     */
    function dfs($start, $path, $nums, $n, &$r, &$paths)
    {
        if (count($path) > 1) {
            $_path = implode('-', $path);
            if (!isset($paths[$_path])) {
                $r[] = $path;
                $paths[$_path] = 0;
            }
        }
        for ($i = $start; $i < $n; $i++) {
            $prev = end($path);
            $cur = $nums[$i];
            if (!$path || $prev <= $cur) {
                $path[] = $cur;
                $this->dfs($i + 1, $path, $nums, $n, $r, $paths);
                array_pop($path);
            }
        }
    }


    /**
     * 189. 旋转数组(右移$_k个位置===把倒数_k个数放前面,)
     * 与moveArray完全一样，_k==>n-k
     * @param Integer[] $nums
     * @param Integer $k
     * @return NULL
     */
    function rotateArray(&$nums, $k)
    {
        # 环状替换
        $n = count($nums);
        $count = 0;
        for ($i = 0; $count < $n; $i++) {
            $mover = $nums[$n - $k];//移民
            $p = $i;//目的地
            do {
                $origin = $nums[$p];//原住民
                $nums[$p] = $mover;
                $mover = $origin;
                $p = $p < $n - $k ? $p + $k : $p - ($n - $k);
                $count++;
            } while ($p != $n - $k);
        }
        # 额外空间
        $_nums = [];
        $n = count($nums);
        foreach ($nums as $i => $num) {
            $_nums[($i + $k) % $n] = $num;
        }
        ksort($_nums);
        $nums = $_nums;
    }

    /**
     * 把数组$arr,$k后的移到数组前面
     * 逻辑很简单，循环移动，但画好图实现出来，1h+，还有bug不好解决。。
     * +2h...退出条件才理清，需要多次循环的测试才遇到
     * @param $arr
     * @param int $k 序号
     * @return bool|array
     */
    function moveArray(array $arr, $k)
    {
        # 环状替换
        $n = count($arr);
        if ($k < 0 || $k > $n - 1) return false;
        $l = $n - $k;
        # 两个指针，两个临时值，在k前就往后移
        if (0 == $n % $k) {
            $y = $k;
        } elseif (0 == $n % $l) {
            $y = $l;
        } else {
            $y = 1;//只需要循环一遍
        }
        for ($x = 0; $x < $y; $x++) {
            $i = $x;
            $j = $k + $x;
            $prev = $arr[$j];
            do {
                $hold = $arr[$i];
                $arr[$i] = $prev;
                $prev = $hold;
                $j = $i;
                $i = $i < $k ? $i + $l : $i - $k;
                #echo implode(' ', $arr), "\n";
            } while ($j != $k + $x);
            #echo implode(' ', $arr), "\n";
        }
        return $arr;
    }

    /**
     * 1488. 避免洪水泛滥
     * @param Integer[] $rains [1,2,3,4] 第i天=>第n个湖下雨
     * @return Integer[]
     *
     * [1,2,0,0,2,1] => [-1,-1,2,1,-1,-1]
     * [1,2,0,1,2] => []
     * [69,0,0,0,69] => [-1,69,1,1,-1]
     * [1,2,0,2,3,0,1] => [-1,-1,2,-1,-1,1,-1]
     * [1,0,2,3,0,1,2] => [-1,1,-1,-1,2,-1,-1]
     * 这个题有些复杂，后面这几种状况必须想好（保存好晴天，找到某湖上次下雨之后的晴天）
     * 二分法查找 反而超时了？？
     *
     * @start 2020/8/13 11:27
     * @end
     */
    function avoidFlood($rains)
    {
        $ans = [];
        $hus = [];
        $free = [];
        foreach ($rains as $i => $hu) {
            if ($hu > 0) {
                if (isset($hus[$hu])) {
                    if (!$free) return [];

                    $day = 0;
                    foreach ($free as $_ => $x) {
                        if ($x > $hus[$hu]) {
                            $day = $x;
                            unset($free[$_]);
                            break;
                        }
                    }
                    if (!$day) return [];
                    $ans[$day] = $hu;

                    /*
                    if (0 > $_ = $this->bisect_right($free, $hus[$hu])) return [];
                    $ans[$free[$_]] = $hu;
                    unset($free[$_]);
                    $free = array_values($free);
                    */
                }
                $ans[$i] = -1;
                $hus[$hu] = $i;
            } else {
                $free[] = $i;
                $ans[$i] = 1;
            }
        }
        return $ans;
    }

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
            $m = floor(($l + $r) / 2);
            if ($arr[$m] > $k) {
                $r = $m;
            } else {
                $l = $m;
            }
        }
        return $r;
    }
}
