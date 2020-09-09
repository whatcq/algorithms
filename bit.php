<?php

/*
& - and
| - or
^ - xor 有且仅有一个为1
~ - not
<< - 左移n位==2的n次方
>>
 */

class Solution
{

    /**
     * 实现不用+的加法
     * @param Integer $a
     * @param Integer $b
     * @return Integer
     */
    function getSum($a, $b)
    {
        # 2^32
        $MASK = 0x100000000;
        # 整型最大值
        $MAX_INT = 0x7FFFFFFF;
        $MIN_INT = $MAX_INT + 1;

        while ($b != 0) {
            $temp = ($a & $b) << 1;//手动进位
            $a = $a ^ $b;//%MASK 留下没有进位的
            $b = $temp;//%MASK
        }
        return $a;//a if a <= MAX_INT else ~((a % MIN_INT) ^ MAX_INT)
        return array_sum([$a, $b]);
    }

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
