<?php

class Solution
{
    /**
     * 两数相除
     * -2147483648/-1 = 2147483647
     * 没想到挺复杂
     * @param Integer $dividend
     * @param Integer $divisor
     * @return Integer
     */
    function divide($dividend, $divisor)
    {
        $INT_MAX = 0x7FFFFFFF;
        //$MIN_INT = -($INT_MAX + 1);
        if ($divisor == 1) return $dividend;//特殊情况
        if ($divisor == -1) {
            if ($dividend > -($INT_MAX + 1)) return -$dividend;// 整数范围内
            return $INT_MAX;
        }

        $fu = ($dividend < 0 xor $divisor < 0);//xor运算符优先级比=低，必须加括号
        $dividend < 0 && $dividend = -$dividend;
        $divisor < 0 && $divisor = -$divisor;
        $r = $this->div($dividend, $divisor);
        return $fu ? -$r : ($r > $INT_MAX ? $INT_MAX : $r);

        # 缓存各倍数，循环搞定
        for ($i = $divisor; $i <= $dividend; $i = $i + $i) $exp[] = $i;
        $res = 0;
        $count = count($exp);
        for ($i = $count - 1; $i >= 0; $i--) {
            if ($dividend >= $exp[$i]) {
                $dividend -= $exp[$i];
                $res += 1 << $i;
            }
        }
    }

    // 分出一个函数更好，没有了负数判断
    function div($dividend, $divisor)
    {
        if ($dividend < $divisor) return 0;
        $r = 1;
        $ji = $divisor;
        while (($jiX2 = $ji + $ji) <= $dividend) {
            $r += $r;
            $ji = $jiX2;//不断翻倍
        }
        return $r + $this->divide($dividend - $ji, $divisor);
    }

    /**
     * x 的平方根
     * @param Integer $x
     * @return Integer
     */
    function mySqrt($x)
    {
        # 二分法搜索，快些
        if ($x == 0 || $x == 1) return $x;
        $left = 1;
        $right = $x / 2;
        while ($left <= $right) {
            //$mid = intval(($right + $left) / 2);
            $mid = ($right + $left) >> 1;
            if ($mid * $mid > $x) {
                $right = $mid - 1;
            } else {
                $left = $mid + 1;
            }
        }
        return $right;

        # 顺序搜索，平方很快的，这方式慢不了多少，简单快捷
        $y = 1;
        while ($x >= $y * $y) $y++;
        return $y - 1;
    }

    /**
     * 50. Pow(x, n)
     * 这么简单的一个做过的题目，咋不会了？
     * @param Float $x
     * @param Integer $n
     * @return Float
     */
    function myPow($x, $n)
    {
        if (!$n || $x == 1) return 1;//优化一下特殊情况，对于测试用例可以节约很多时间
        $fu = $n < 0;
        if ($fu) $n = -$n;
        $r = 1;
        $p = $x;
        while ($n > 1) {
            $yu = $n % 2;//余数 英文?
            if ($yu) $r *= $p;//多余的先收集(乘)起来
            $n = ($n - $yu) / 2;
            $p *= $p;//不断折叠
        }
        $r *= $p;
        return $fu ? 1 / $r : $r;
    }

    /**
     * 60. 第k个排列
     * 数学法
     * 12345排列，以3开头的有(n-1)!个，在第2*(n-1)!之后。。
     * @param Integer $n
     * @param Integer $k
     * @return String
     */
    function getPermutation($n, $k)
    {
        $i = 1;
        $A = [1, 1];//排列
        while ($i++ < $n - 1) {//①排列总数不要
            $A[$i] = $A[$i - 1] * $i;
        }
        //print_r($A);
        $k--;//②退一步，不能等于n!
        $nums = range(1, $n);//用来挑选
        $num = '';
        while ($n--) {
            $x = intval($k / $A[$n]);//③第几组(n-1)!
            $num .= $nums[$x];//第$x+1组
            array_splice($nums, $x, 1);//删除挑出的数
            $k -= $x * $A[$n];
            if ($k == 1) break;
        }
        return $num . implode('', $nums);
    }
}
