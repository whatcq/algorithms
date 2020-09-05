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
        if (!$n) return 1;
        $fu = $n < 0;
        if ($fu) $n = -$n;
        $r = 1;
        $p = $x;
        while ($n > 1) {
            $yu = $n % 2;//余数 英文
            if ($yu) $r *= $p;
            $n = ($n - $yu) / 2;
            $p *= $p;//不断折叠
        }
        $r *= $p;
        return $fu ? 1 / $r : $r;
    }
}
