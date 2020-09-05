<?php

class Solution
{

    /**
     * 二进制数有多少1
     * @param Integer $n
     * @return Integer
     */
    function hammingWeight($n)
    {
        $count = 0;
        while ($n > 0) {
            $n = $n & ($n - 1);//与前一个数与操作，最后一个1消掉了，高级思路！
            $count++;//计数一次

            /*
            if ($n & 1) $count++;
            $n >>= 1;// 中间很多0都只能一步步判断，不高级
            */
        }
        return $count;

        return substr_count(decbin($n), '1');
    }
}
