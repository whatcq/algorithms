<?php

class Solution
{

    /**
     * 78. 子集
     * @param Integer[] $nums
     * @return Integer[][]
     */
    function subsets($nums)
    {
        #迭代法
        $result = [[]];
        foreach ($nums as $num) {
            foreach ($result as $item) {
                $result[] = array_merge($item, [$num]);
            }
        }
        return $result;

        #位运算 这么简单一个题，我想了这么个高级方法，服了自己。。
        $r = [[]];
        $n = count($nums);
        for ($i = 1, $l = 1 << $n; $i < $l; $i++) {
            $sub = [];
            $bit = $i;
            $j = 1;
            do {
                if ($bit % 2) array_unshift($sub, $nums[$n - $j]);
                $bit >>= 1;
                $j++;
            } while ($bit);
            $r[] = $sub;
        }
        return $r;
    }

    /**
     * 17.电话号码的字母组合
     * 记住这个套路，以前这么写，后来又忘了。。
     * @param String $digits
     * @return String[]
     */
    function letterCombinations($digits)
    {
        $map = [
            '2' => ['a', 'b', 'c'],
            '3' => ['d', 'e', 'f'],
            '4' => ['g', 'h', 'i'],
            '5' => ['j', 'k', 'l'],
            '6' => ['m', 'n', 'o'],
            '7' => ['p', 'q', 'r', 's'],
            '8' => ['t', 'u', 'v'],
            '9' => ['w', 'x', 'y', 'z']
        ];
        if (!isset($map[$digits[0]])) return [];
        $r = $map[$digits[0]];
        for ($i = 1, $n = strlen($digits); $i < $n; $i++) {
            $_r = [];
            foreach ($r as $item) {
                foreach ($map[$digits[$i]] as $char) {
                    $_r[] = $item . $char;
                }
            }
            $r = $_r;
        }
        return $r;
    }

    function findPeakElement($nums)
    {
        // 二分法找峰值，正规操作
        $l = 0;
        $r = count($nums) - 1;
        while ($l < $r) {
            $m = ($l + $r) >> 1;
            if ($nums[$m] < $nums[$m + 1]) $l = $m + 1;
            else $r = $m;
        }
        return $l;
        // 语言函数，取巧了..
        $max = max($nums);
        return array_search($max, $nums);
    }

    /**
     * 递增的三元子序列
     * @param Integer[] $nums [1,0,-1,0,2, -3]
     * @return Boolean true
     */
    function increasingTriplet($nums)
    {
        # 简单来源于巧妙，只能这样？
        if (count($nums) < 3) return false;
        $min = $second = PHP_INT_MAX;
        foreach ($nums as $num) {
            if ($num <= $min) $min = $num;
            elseif ($num <= $second) $second = $num;
            else return true;
        }
        return false;

        # 题意：没说连续的3个数，问题来了，比较的基准在哪？k-v一起比较，很难
        asort($nums);
        print_r($nums);
        $c = 1;
        $prevK = key($nums);
        next($nums);
        foreach ($nums as $k => $v) {
            if ($k > $prevK) {
                if ($v > $nums[$prevK]) {
                    echo "- $k $c\n";
                    if ($c++ == 2) return true;
                    $prevK = $k;
                }
            } else $prevK = $k;
        }
        return false;
    }

    /**
     * 炸弹人
     * @param Integer[][] $matrix
     * @return NULL
     */
    function setZeroes(&$matrix)
    {
        if (!$matrix) return;
        $emptyRow = array_fill(0, count($matrix[0]), 0);
        $emptyCols = [];
        $i = 0;
        foreach ($matrix as $i => &$row) {
            $empty = false;
            foreach ($row as $j => $num) {
                if (!$num) {
                    $emptyCols[] = $j;
                    $empty = true;
                }
            }
            if ($empty) $row = $emptyRow;
        }
        foreach ($emptyCols as $emptyCol) {
            for ($j = 0; $j <= $i; $j++) {
                $matrix[$j][$emptyCol] = 0;
            }
        }
    }

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

    /**
     * 18. 四数之和
     * 根据3数和思路解出来了，但错了多次，这样7788搞了2h。。
     * @param Integer[] $nums
     * @param Integer $target
     * @return Integer[][]
     */
    function fourSum($nums, $target)
    {
        $n = count($nums);
        if ($n < 4) return [];
        sort($nums);
        $r = [];
        for ($i = 0; $i < $n - 3; $i++) {
            if ($i > 0 && $nums[$i - 1] == $nums[$i]) continue;
            $x = $target - $nums[$i];
            if ($x > $nums[$n - 2] + $nums[$n - 1] + $nums[$n - 3]) continue;
            if ($x < $nums[$i + 1] + $nums[$i + 2] + $nums[$i + 3]) break;
            for ($j = $i + 1; $j < $n - 2; $j++) {
                if ($j > $i + 1 && $nums[$j - 1] == $nums[$j]) continue;
                $y = $x - $nums[$j];
                $start = $j + 1;
                $end = $n - 1;
                // echo "==>$start, $end\n";
                //没想到这两行优化10倍
                if ($y > $nums[$end - 1] + $nums[$end]) continue;
                if ($y < $nums[$start] + $nums[$start + 1]) break;
                while ($start < $end) {
                    // echo "$num, $nums[$j], $nums[$start], $nums[$end]\n";
                    $z = $nums[$start] + $nums[$end];
                    if ($y == $z) {
                        // echo "===\n";
                        // echo "$y $nums[$start] $nums[$end]\n";
                        $r[] = [$nums[$i], $nums[$j], $nums[$start], $nums[$end]];
                        //这里错了几次，写在判断式里是多执行一次
                        // while($end>$start && $nums[$end-1]==$nums[$end--]);//$end--;
                        // while($end>$start && $nums[$start+1]==$nums[$start++]);//$start++;
                    }
                    if ($y >= $z) {
                        while ($end > $start && $nums[$start + 1] == $nums[$start++]) ;
                    }
                    if ($y <= $z) {
                        while ($end > $start && $nums[$end - 1] == $nums[$end--]) ;
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
     * 避免重复字母的最小删除成本
     * 很简单一个题，判断边界得整理好套路!
     * @param String $s
     * @param Integer[] $cost 字符串s每个字符对应的删除成本
     * @return Integer
     */
    function minCost($s, $cost)
    {
        $c = 0;
        $max = 0;
        foreach ($cost as $i => $t) {
            if ($i && $s[$i] == $s[$i - 1]) {
                if (!$max) {
                    $c += $cost[$i - 1];
                    $max = $cost[$i - 1];
                }
                $c += $t;
                if ($t > $max) $max = $t;
            } elseif ($max > 0) {
                $c -= $max;
                $max = 0;
            }
            //echo "$i => $t $s[$i] ==> $c, $max\n";
        }
        return $c - $max;

        $c = $start = $end = 0;
        for ($i = 1, $n = strlen($s); $i < $n; $i++) {
            if ($s[$i] == $s[$start]) $end = $i;
            else {
                if ($start < $end) $c += $this->cost($cost, $start, $end);
                $start = $i;
            }
            //echo "\n-- $s[$i] == $start";
        }
        if ($start < $end) $c += $this->cost($cost, $start, $end);
        return $c;
    }

    function cost($cost, $start, $end)
    {
        //其实就是留下max
        $_cost = array_slice($cost, $start, $end - $start + 1);
        print_r($_cost);
        return array_sum($_cost) - max($_cost);

        //又想错了，删了还会连一起
        //echo "$start,$end\n";
        $c = min($cost[$start], $cost[$end]);
        for ($i = $start + 1; $i < $end; $i++) {
            $c += $cost[$i];
        }
        return $c;
        // 根本想错了。。不是rob题目
        $c[$start] = $cost[$start];
        $c[$start + 1] = $cost[$start + 1];
        for ($i = $start + 2; $i <= $end; $i++) {
            $c[$i] = min($c[$i - 2] + $cost[$i], $c[$i - 1]);
        }
        print_r($c);
        return $c[$i - 1];
    }

    /**
     * 75. 颜色分类
     * @param Integer[] $nums
     * @return NULL
     */
    function sortColors(&$nums)
    {
        # 逐个交换，高级
        $l = 0;
        $r = count($nums) - 1;
        $i = 0;
        while ($i <= $r) {
            if ($nums[$i] === 0) {
                //0交换到左边指针处
                [$nums[$i], $nums[$l]] = [$nums[$l], $nums[$i]];//php7.1+
                //list($nums[$i], $nums[$l]) = [$nums[$l], $nums[$i]];//php7.1-
                $i++;
                $l++;
            } elseif ($nums[$i] === 1) {
                $i++;
            } else {
                //2交换到后面去
                [$nums[$i], $nums[$r]] = [$nums[$r], $nums[$i]];
                $r--;
            }
        }
        return;
        # 一次性统计
        $stat = array_count_values($nums);
        $c = $stat[0] + $stat[1];
        foreach ($nums as $i => &$num) {
            if ($i < $stat[0]) $num = 0;
            elseif ($i < $c) $num = 1;
            else $num = 2;
        }
    }

    /**
     * 941. 有效的山脉数组
     * @param Integer[] $A
     * @return Boolean
     */
    function validMountainArray($A)
    {
        $n = count($A);
        if ($n < 3) return false;//这句测试用例多？

        # 业务中间判断
        for ($i = 1; $i < $n && $A[$i - 1] < $A[$i]; $i++) ;
        if ($i == 1 || $i == $n) return false;
        for (; $i < $n && $A[$i - 1] > $A[$i]; $i++) ;
        return $i == $n;

        # 双指针法，思路没理清，还调试几次
        $left = 0;
        $right = $n - 1;
        while ($left < $n - 2 && $A[$left + 1] > $A[$left]) $left++;
        while (1 < $right && $A[$right - 1] > $A[$right]) $right--;
        return $right == $left;
    }

    /**
     * 845. 数组中的最长山脉
     * 看似很简单一个题，思路高级代码才干净
     * @param Integer[] $A
     * @return Integer
     */
    function longestMountain($A)
    {
        # 这个思路太棒了,干净利落
        $max = 0;
        $i = 1;
        $n = count($A);
        while ($i < $n) {
            // 一次循环翻一条山，先上再下，最后走完平原，$i++语句重复了，但其他简单了
            $up = $down = 0;
            while ($A[$i] > $A[$i - 1] && $i < $n) {
                $i++;
                $up++;
            }
            while ($A[$i] < $A[$i - 1] && $i < $n) {
                $i++;
                $down++;
            }
            if ($up > 0 && $down > 0) $max = max($max, 1 + $up + $down);
            while ($A[$i] == $A[$i - 1] && $i < $n) $i++;
        }
        return $max;

        # 自己写的，根据业务逻辑写if-else调试多次太累，就是因为没有整理出来
        // 官方解法还有 遍历找峰、找谷。。
        // ><不包含==的情况，所以这怎么写？==的情况只能放最前面处理
        $mt = 0;
        $direct = 1;
        $prev = $A[0];
        $m = 1;
        for ($i = 1, $n = count($A); $i < $n; $i++) {
            echo "$direct * ( {$A[$i]}-$prev $m \n";
            if ($A[$i] == $prev) {
                if ($direct < 0 && $m > 2 && $mt < $m) $mt = $m;
                $m = 1;
                $direct = 1;
            } else {
                if ($direct * ($A[$i] - $prev) > 0) {
                    $m++;
                } elseif ($m > 1) {
                    // $mt = max($mt, $m);
                    if ($direct == -1) {
                        if ($m > $mt) $mt = $m;
                        $m = 2;
                    } else {
                        $m++;
                    }
                    $direct *= -1;
                }
            }
            $prev = $A[$i];
        }
        if ($direct < 0 && $m > $mt) $mt = $m;
        return $mt;
    }

    /**
     * 463. 岛屿的周长
     * 就这么一个简单题，我不看答案不知还要试多久
     * 完全想错了思路，还explore四周！
     * @param Integer[][] $grid
     * @return Integer
     */
    function islandPerimeter($grid)
    {
        $sum = 0;
        $x = count($grid);
        $y = count($grid[0]);
        for ($i = 0; $i < $x; $i++) {
            for ($j = 0; $j < $y; $j++) {
                if ($grid[$i][$j] == 1) {
                    $sum += 4;
                    if (!empty($grid[$i][$j - 1])) $sum -= 2;
                    if (!empty($grid[$i - 1][$j])) $sum -= 2;
                }
            }
        }
        return $sum;
    }

    /**
     * 402. 移掉K位数字
     * 看似简单一道题，倒回n步的问题=>贪心待学
     * @param String $num
     * @param Integer $k
     * @return String
     */
    function removeKdigits($num, $k)
    {
        if (strlen($num) <= $k) return '0';

        # 额外空间 8ms！
        // 贪心:让高位尽可能低 从高位向低位遍历 若高位可被更小的代替 则直接代替 但最多只能代替k次
        // 故前几位会形成一个递增序列 可利用单调栈 最多弹出k次
        $s = [];
        $t = 0;
        foreach (str_split($num) as $n) {
            while ($s && $n < end($s) && $t < $k) {
                array_pop($s);
                $t++;
            }
            $s[] = $n;
        }
        if ($t < $k) {
            $s = array_slice($s, 0, -$k + $t);
        }
        foreach ($s as $i => $x) {
            if ($x) break;
            unset($s[$i]);
        }
        return $s ? implode('', $s) : '0';

        # 数组形式 350ms
        $num = str_split($num);
        $i = 0;
        while ($k--) {
            while (isset($num[$i + 1]) && $num[$i + 1] >= $num[$i]) $i++;
            // 到结尾是特殊情况，一次搞定
            if (!isset($num[$i + 1])) {
                return implode('', array_slice($num, 0, -$k - 1));
            }
            unset($num[$i--]);
            $num = array_values($num);//re-index,<=应该是这一步费时间
        }
        foreach ($num as $i => $x) {
            if ($x) break;
            unset($num[$i]);
        }
        return $num ? implode('', $num) : '0';

        # 字符串形式，反复处理，逻辑简单 160ms=>优化成1遍处理，8ms！
        $i = 0;
        while ($k--) {
            while (isset($num[$i + 1]) && $num[$i + 1] >= $num[$i]) $i++;
            $num = substr_replace($num, '', $i, 1);//这函数性能可以:)
            $i && $i--;
        }
        return ltrim($num, '0') ?: '0';
    }

    /**
     * 406. 根据身高重建队列
     * 输入：[[7,0], [4,4], [7,1], [5,0], [6,1], [5,2]]
     * 输出：[[5,0], [7,0], [5,2], [6,1], [4,4], [7,1]]
     * @param Integer[][] $people
     * @return Integer[][]
     */
    function reconstructQueue($people)
    {
        // 高个子先站好位
        usort($people, function ($a, $b) {
            return $a[0] > $b[0] || ($a[0] == $b[0] && $a[1] < $b[1]) ? -1 : 1;
        });
        $r = [];
        // 没想到真的可以动态排队！
        foreach ($people as $v) {
            array_splice($r, $v[1], 0, [$v]);
        }
        return $r;

        // 还可以有高级排队 数据结构！
        $list = new SplDoublyLinkedList();
        foreach ($people as $p) {
            // 把p加到p[1]下标为右边数的位置
            $list->add($p[1], $p);
        }
        foreach ($list as $val) {
            $r[] = $val;
        }
        return $r;

        // 矮的先,辛苦做出来，结果还是落后的！
        usort($people, function ($a, $b) {
            return $a[0] > $b[0] || ($a[0] == $b[0] && $a[1] > $b[1]) ? 1 : -1;
        });
        $r = range(1, count($people));
        $prev = -1;
        $_p = $c = 0;
        foreach ($people as list($h, $k)) {
            $h > $prev ? $c = 0 : $c++;
            $prev = $h;
            $empty = -1;
            $p = $_p;
            while ($empty < $k - $c) {
                is_array($r[$p++]) or $empty++;
                $empty < 0 && $_p++;
            }
            $r[$p - 1] = [$h, $k];
        }
        foreach ($r as $i => $v) echo "\n$i: ", is_array($v) ? implode(' ', $v) : "($v)";
        return $r;
    }

    /**
     * 973. 最接近原点的 K 个点
     * @param Integer[][] $points
     * @param Integer $K
     * @return Integer[][]
     */
    function kClosest($points, $K)
    {
        # 大顶堆 @see https://www.php.net/manual/en/class.splheap.php
        $heap = new SplMaxHeap();
        $r = [];
        foreach ($points as list($a, $b)) {
            $c = $a * $a + $b * $b;
            $heap->insert([$c, [$a, $b]]);//原来还可以这样玩
            if ($heap->count() > $K) {
                $heap->extract();
            }
        }
        while (!$heap->isEmpty()) {
            $r[] = $heap->extract()[1];
        }
        return $r;

        # my
        $heap = new SplMaxHeap();
        $r = [];
        foreach ($points as list($a, $b)) {
            $c = $a * $a + $b * $b;
            if ($heap->count() < $K) {
                $heap->insert($c);
                isset($r[$c]) ? $r[$c][] = [$a, $b] : $r[$c] = [[$a, $b]];
            } elseif ($c < $heap->top()) {
                $heap->insert($c);
                isset($r[$c]) ? $r[$c][] = [$a, $b] : $r[$c] = [[$a, $b]];
                $x = $heap->extract();
                array_pop($r[$x]);
            }
        }
        // 利用好现成的函数
        return array_reduce($r, 'array_merge', []);
        $res = [];
        foreach ($r as $v) {
            $res = array_merge($res, $v);
        }
        return $res;
    }

    /**
     * 1030. 距离顺序排列矩阵单元格
     * @param Integer $R
     * @param Integer $C
     * @param Integer $r0
     * @param Integer $c0
     * @return Integer[][]
     */
    function allCellsDistOrder($R, $C, $r0, $c0)
    {
        # 桶排序
        $map = [];
        for ($i = 0; $i < $R; ++$i) {
            for ($j = 0; $j < $C; ++$j) {
                $distance = abs($r0 - $i) + abs($c0 - $j);
                $map[$distance][] = [$i, $j];
            }
        }
        $answer = [];
        // 这样避免排序，高！
        for ($k = 0; $k < $R + $C; ++$k) {
            if (isset($map[$k])) {
                foreach ($map[$k] as $value) {
                    $answer[] = $value;
                }
            }
        }
        return $answer;

        # 排序
        for ($i = 0; $i < $R; $i++) {
            for ($j = 0; $j < $C; $j++) {
                $d = abs($i - $r0) + abs($j - $c0);
                $l[$d][] = [$i, $j];
            }
        }
        ksort($l);
        return array_reduce($l, 'array_merge', []);
        $r = [];
        foreach ($l as $v) {
            $r = array_merge($r, $v);
        }
        return $r;

        #　高级数据结构 还不够快？--比桶排序多余了些排序步骤
        $heap = new SplMinHeap;
        for ($i = 0; $i < $R; $i++) {
            for ($j = 0; $j < $C; $j++) {
                $heap->insert([abs($r0 - $i) + abs($c0 - $j), [$i, $j]]);
            }
        }
        $r = [];
        while (!$heap->isEmpty()) {
            $r[] = $heap->extract()[1];
        }
        return $r;

        # 更复杂的排序函数
        $list = [];
        $long = [];
        for ($i = 0; $i < $R; $i++) {
            for ($j = 0; $j < $C; $j++) {
                $list[] = [$i, $j];
                $tmp = abs($r0 - $i) + abs($c0 - $j);
                $long[] = [$tmp, [$i, $j]];
            }
        }
        array_multisort(array_column($long, '0'), SORT_ASC, $long);
        return array_column($long, 1);
        $res = [];
        foreach ($long as $v) {
            $res[] = $v[1];
        }
        return $res;
    }

    /**
     * 283. 移动零
     * 如此简单的题!还是要小心才能对。。
     * @param Integer[] $nums
     * @return NULL
     */
    function moveZeroes(&$nums)
    {
        $p = 0;
        for ($i = 0, $n = count($nums); $i < $n; $i++) {
            if ($nums[$i]) {
                $nums[$p++] = $nums[$i];
                //细化判断反而浪费时间
                //$i>$p && $nums[$p] = $nums[$i];
                //$p++;
                //题目要求尽量减少操作次数,否则这样一遍过
                //$nums[$i] = 0;
            }
        }
        for ($i = $p; $i < $n; $i++) {
            $nums[$i] = 0;
        }
        return;
        // 之前做过的题目，这回还是原来的思路，但对题目本身却没印象
        $empty = [];
        for ($i = 0, $n = count($nums); $i < $n; $i++) {
            if ($nums[$i]) {
                if ($empty) {
                    $nums[array_shift($empty)] = $nums[$i];
                    $nums[$i] = 0;
                    $empty[] = $i;
                }
            } else {
                $empty[] = $i;
            }
        }
    }

    /**
     * nums1里两数乘积=nums2里一数的平方 （含重复），求个数
     * 很简单一个题，还可怎么优化？
     * @param Integer[] $nums1
     * @param Integer[] $nums2
     * @return Integer
     */
    function numTriplets($nums1, $nums2)
    {
        $count = 0;
        $pow = $x = [];
        foreach ($nums1 as $i => $num) {
            isset($pow[$num * $num]) ? $pow[$num * $num]++ : $pow[$num * $num] = 1;
            foreach ($nums1 as $j => $_num) {
                if ($i < $j) {
                    $ji = $num * $_num;
                    isset($x[$ji]) ? $x[$ji]++ : $x[$ji] = 1;
                }
            }
        }
        foreach ($nums2 as $i => $num) {
            isset($x[$num * $num]) && $count += $x[$num * $num];
            foreach ($nums2 as $j => $_num) {
                if ($i < $j && isset($pow[$num * $_num])) $count += $pow[$num * $_num];
            }
        }
        return $count;
    }

    /**
     * 5642. 大餐计数
     * @param Integer[] $deliciousness
     * @return Integer
     */
    function countPairs($deliciousness)
    {
        $count = 0;
        $stat = array_count_values($deliciousness);
        ksort($stat);
        foreach ($stat as $a => $x) {
            for ($k = 0; $k <= 21; $k++) {
                $b = (1 << $k) - $a;
                if ($b < 0) continue;
                if ($b == $a) $count = ($count + $x * ($x - 1) / 2) % 1000000007;
                elseif ($b > $a && isset($stat[$b])) $count = ($count + $x * $stat[$b]) % 1000000007;
            }
        }
        return $count;

        // 改成字典判断，但还是O(n^2)，在利用一下字典思路，其实就是答案
        $set = [];
        $i = 0;
        while ($i < 31) {
            $set[1 << $i++] = 0;
        }
        $stat = array_count_values($deliciousness);
        ksort($stat);
        $count = 0;
        foreach ($stat as $a => $x) {
            if ($x > 1 && isset($set[$a + $a])) $count = ($count + $x * ($x - 1) / 2) % 1000000007;
            foreach ($stat as $b => $y) {
                if ($a >= $b) continue;
                if (isset($set[$a + $b])) $count = ($count + $x * $y) % 1000000007;
            }
        }
        return $count;

        // 每个都判断，确实超时
        $is2 = function ($n) {
            while ($n && $n % 2 == 0) $n >>= 1;
            return $n == 1;
        };
        $stat = array_count_values($deliciousness);
        $count = 0;
        foreach ($stat as $a => $x) {
            if ($x > 1 && $is2($a + $a)) $count = ($count + $x * ($x - 1) / 2) % 1000000007;
            foreach ($stat as $b => $y) {
                if ($a >= $b) continue;
                if ($is2($a + $b)) $count = ($count + $x * $y) % 1000000007;
            }
        }
        return $count;
    }

    /**
     * 5243. 同积元组
     * @param Integer[] $nums
     * @return Integer
     */
    function tupleSameProduct($nums)
    {

        $n = count($nums);
        if ($n < 4) return 0;

        # 空间，没想到这么简单，完全是因为想复杂了走岔了。。
        $set = [];
        $count = 0;
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $i; $j++) {
                $ji = $nums[$i] * $nums[$j];
                if (isset($set[$ji])) {
                    $count += $set[$ji];
                    $set[$ji]++;
                } else {
                    $set[$ji] = 1;
                }
            }
        }
        return 8 * $count;

        # 时间复杂度上升了一个维度，
        sort($nums);
        $count = 0;
        $map = array_flip($nums);#map 比 双指针 少一半扫描量
        $a = 0;
        while ($a < $n - 3) {
            $d = $n - 1;
            while ($d > $a + 2) {
                $c = $a + 1;
                while ($c < $d - 1) {
                    $b = $nums[$a] * $nums[$d] / $nums[$c];
                    if ($b < $nums[$c]) break;
                    if (is_int($b) && $b > $nums[$c] && isset($map[$b])) {
                        $count++;
                    }
                    $c++;
                }
                $d--;
            }
            $a++;
        }
        return 8 * $count;
    }
}
