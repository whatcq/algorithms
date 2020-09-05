<?php

class Solution
{
    
    /**
     * 剑指 Offer 20. 表示数值的字符串
     * return is_numeric(trim($s));
     * 判断字符串是否表示数值（包括整数和小数）。
     * 例如，字符串"+100"、"5e2"、"-123"、"3.1416"、"-1E-16"、"0123"都表示数值，
     * 但"12e"、"1a3.14"、"1.2.3"、"+-5"及"12e+5.4"都不是。
     * 原来这叫【有限状态自动机】，就是一个流程判断，到了哪里下一步可以走哪里，否则报错。。
     * 代码抄的python，赖得理。。
     * @param $s
     * @return bool
     */
    function isNumber($s)
    {
        $states = [
            [' ' => 0, 's' => 1, 'd' => 2, '.' => 4], # 0. start with 'blank'
            ['d' => 2, '.' => 4],                     # 1. 'sign' before 'e'
            ['d' => 2, '.' => 3, 'e' => 5, ' ' => 8], # 2. 'digit' before 'dot'
            ['d' => 3, 'e' => 5, ' ' => 8],           # 3. 'digit' after 'dot'
            ['d' => 3],                               # 4. 'digit' after 'dot' (‘blank’ before 'dot')
            ['s' => 6, 'd' => 7],                     # 5. 'e'
            ['d' => 7],                               # 6. 'sign' after 'e'
            ['d' => 7, ' ' => 8],                     # 7. 'digit' after 'e'
            [' ' => 8]                                # 8. end with 'blank'
        ];
        $p = 0;// start with state 0
        for ($i = 0, $n = strlen($s); $i < $n; $i++) {
            $c = $s[$i];
            if ('0' <= $c && $c <= '9') $t = 'd';         # digit
            elseif ($c == '+' || $c == '-') $t = 's';     # sign
            elseif ($c == 'e' || $c == 'E') $t = 'e';     # e or E
            elseif ($c == '.' || $c == ' ') $t = $c;      # dot, blank
            else $t = '?';                                # unknown
            if (!in_array($t, $states[$p])) return false;
            if (isset($states[$p][$t])) return false;
            $p = $states[$p][$t];
        }
        return in_array($p, [2, 3, 7, 8]);

        # 别人代码，我思路也这样
        $s = strtolower(trim($s));
        $len = strlen($s);
        if (!$len) {
            return false;
        }
        $count = $point = $exp = 0;
        for ($i = 0; $i < $len; $i++) {
            if ($s[$i] == ' ') { // 中间有空格
                return false;
            } elseif ($s[$i] == '+' || $s[$i] == '-') { // 非开始部分或非e之后有+-符号
                if ($i != 0 && $s[$i - 1] != 'e') {
                    return false;
                }
            } elseif ($s[$i] == '.') {
                if ($point > 0 || $exp > 0) { // 小数点大于1个，或者指数大于1个
                    return false;
                }
                $point++;
            } elseif ($s[$i] == 'e') {
                if ($count == 0 || $exp > 0) { // 如果没有数字，或者已经有指数的情况下
                    return false;
                }
                $exp++;
            } elseif (ord($s[$i]) >= 48 && ord($s[$i]) <= 57) { // 为数字
                $count++;
            } else {
                return false;
            }
        }
        if ($count == 0 || $s[$len - 1] == 'e' || $s[$len - 1] == '+' || $s[$len - 1] == '-') { // 无数字或最后一位为e+-
            return false;
        }

        return true;
    }
}