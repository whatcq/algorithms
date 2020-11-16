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

        # 字符串形式，反复处理，逻辑简单 160ms
        while ($k--) {
            $i = 1;
            while (isset($num[$i]) && $num[$i] >= $num[$i - 1]) $i++;
            $num = substr_replace($num, '', $i - 1, 1);
        }
        return ltrim($num, '0') ?: '0';
    }
}
