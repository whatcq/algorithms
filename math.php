<?php

class Solution
{
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
