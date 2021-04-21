<?php

class Solution
{

    /**
     * 647. 回文子串
     * @param String $s
     * @return Integer
     */
    function countSubstrings($s)
    {
        # 中心扩展法
        $total = 0;
        for ($i = 0, $n = strlen($s); $i < $n; $i++) {
            $start = $i;
            $end = $i;
            while ($start >= 0 && $end < $n && $s[$start--] == $s[$end++]) $total++;
            $start = $i;
            $end = $i + 1;
            while ($start >= 0 && $end < $n && $s[$start--] == $s[$end++]) $total++;
        }
        return $total;

        # 暴力法
        for ($i = 2, $total = $n = strlen($s); $i <= $n; $i++) {//step
            for ($j = 0; $j <= $n - $i; $j++) {
                if ($s[$j] != $s[$j + $i - 1]) continue;
                $_s = substr($s, $j, $i);
                //echo "$_s\n";
                if ($_s == strrev($_s)) {
                    $total++;
                }
            }
        }
        return $total;
    }

    private $len, $s;

    /**
     * 中心扩展法
     * @param $left
     * @param $right
     * @return int
     */
    private function centerExpand($left, $right)
    {
        while ($left >= 0 && $right < $this->len && $this->s[$left] == $this->s[$right]) {
            $left--;
            $right++;
        }
        //当不满足条件是，左右都再进了一位，此时不是常规的$right-$left+1，而是要-1
        return $right - $left - 1;
    }

    /**
     * 5. 最长回文子串
     * 大波美人鱼人美波大
     * @param String $s
     * @return String
     */
    function longestPalindrome($s)
    {
        # 中心扩展法 @todo 读一遍代码
        $len = strlen($s);
        if ($len < 2) return $s;         //初始化判断
        $this->len = $len;              //使其成为成员变量
        $this->s = $s;
        $left = $right = 0;             //定义左右边界
        for ($i = 0; $i < $len; ++$i) {
            $lenji = $this->centerExpand($i, $i);    //奇数中心扩散，判断该点的回文长度
            $lenou = $this->centerExpand($i, $i + 1);  //偶数中心扩散
            $maxLen = max($lenji, $lenou);           //取最大
            if ($maxLen > $right - $left + 1) {
                $right = $i + floor($maxLen / 2);     //取新的左右值
                $left = $i - floor(($maxLen - 1) / 2);  //其本身也包含在内，因此要($maxLen-1)
            }
        }
        return substr($s, $left, $right - $left + 1); //截取字符串

        # 马拉车算法 @todo
        $str = '^#' . implode('#', str_split($s)) . '#$';   //分割字符串，使奇偶性统一
        $len = strlen($str);            //计算改好的字符串长度
        $r = array_fill(0, $len, 0);    //初始化半径数组
        $center = $maxRight = 0;        //初始化偏移量：中心点和回文串最大右点
        $maxStr = '';                   //结果，最长的回文串
        for ($i = 1; $i < $len; ++$i) {
            if ($i < $maxRight) {
                $r[$i] = min($maxRight - $i, $r[2 * $center - $i]);    //计算当前回文路径的长度
            }
            while ($str[$i - $r[$i] - 1] == $str[$i + $r[$i] + 1]) {    //扩展回文子串南京
                $r[$i]++;
            }
            if ($i + $r[$i] > $maxRight) {  //如果超出最右的点，则更新中心点和右节点
                $maxRight = $i + $r[$i];
                $center = $i;
            }
            if (1 + 2 * $r[$i] > strlen($maxStr)) {       //计算当前回文子串是否大于记录的结果
                $maxStr = substr($str, $i - $r[$i], 2 * $r[$i] + 1);
            }
        }
        return str_replace('#', '', $maxStr);

        # 暴力法，二重循环
        $n = strlen($s);
        if ($n < 2) return $s;
        $_s = strrev($s);//一次反转
        $huiwen = $s[0];
        $length = 1;
        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                $l = $j - $i + 1;
                if ($l <= $length) continue;
                $sub = substr($s, $i, $l);
                $_sub = substr($_s, $n - 1 - $j, $l);
                if ($sub == $_sub) {
                    $huiwen = $sub;
                    $length = $l;
                }
            }
        }
        return $huiwen;
    }

    /**
     * 判断回文字符串,只算字符和数字，其他字符忽略
     * @param String $s
     * @return Boolean
     */
    function isPalindrome($s)
    {
        if (!$s) return true;
        $s = strtolower($s);
        $i = 0;
        $j = strlen($s) - 1;
        do {
            if (!($s[$i] >= 'a' && $s[$i] <= 'z') && !is_numeric($s[$i])) {
                $i++;
                continue;
            }
            if (!($s[$j] >= 'a' && $s[$j] <= 'z') && !is_numeric($s[$j])) {
                $j--;
                continue;
            }
            if ($s[$i++] != $s[$j--]) return false;
        } while ($i < $j);
        return true;
    }

    /**
     * 第一个不重复的字符
     * @param String $s
     * @return Integer
     */
    function firstUniqChar($s)
    {
        $hash = [];
        for ($i = 0, $n = strlen($s); $i < $n; $i++) {
            $hash[$s[$i]] = isset($hash[$s[$i]]) ? 0 : 1;//是否只出现一次，不用计数
        }
        foreach ($hash as $char => $count) {
            if ($count == 1) return strpos($s, $char);
        }
        return -1;

        # php的字符串方法。。
        $min = strlen($s);
        foreach (count_chars($s, 1) as $code => $n) {
            if ($n == 1 && $min > $pos = strpos($s, chr($code))) {
                $min = $pos;
            }
        }
        return isset($s[$min]) ? $min : -1;
    }

    /**
     * 7.整数反转 120->21, -123 => -321
     * @param Integer $x
     * @return Integer
     */
    function reverse($x)
    {
        if (!$x) return 0;
        return $x > 0
            ? (2147483647 < ($y = intval(strrev($x))) ? 0 : $y)
            : (2147483647 < ($y = intval(strrev(-$x))) ? 0 : -$y);
    }

    /**
     * @param String $s
     * @return Integer
     */
    function romanToInt($s)
    {
        $dict = [
            'I' => 1,
            'V' => 5,
            'X' => 10,
            'L' => 50,
            'C' => 100,
            'D' => 500,
            'M' => 1000,
        ];
        $num = 0;
        $prev = 0;
        for ($i = 0, $n = strlen($s); $i < $n; $i++) {
            $cur = $dict[$s[$i]];
            if ($prev < $cur) $num += $cur - $prev - $prev;
            else $num += $cur;
            $prev = $cur;
        }
        return $num;
    }

    /**
     * 214. 最短回文串（前面加字符使之成回文）
     * 说是困难，就往复杂想；结果挺简单
     * @param String $s
     * @return String
     */
    function shortestPalindrome($s)
    {
        # strrev法优化 快了100倍，高兴
        if (!$s) return '';
        $_s = strrev($s);
        if ($s == $_s) return $s;
        $n = strlen($s);
        for ($i = 1; $i < $n; $i++) {
            if (substr($s, 0, $n - $i) == substr($_s, $i - $n)) return substr($_s, 0, $i) . $s;
        }

        # 暴力法 快了8倍，strrev函数好啊，这都没想到用
        if (!$s || strrev($s) == $s) return $s;
        $t = '';
        for ($i = strlen($s) - 1; $i > 0; $i--) {
            $t .= $s[$i];
            $new_s = $t . $s;
            if ($new_s == strrev($new_s)) {
                return $new_s;
            }
        }

        # 还有什么KMP，@todo

        # 中心扩展法，学了就用上了，但并不适合本题，运算太多，调试了1h；但单双的起始没理清楚；耗时4s，为什么这么慢？
        if (!$s) return '';
        $n = strlen($s);
        $i = intval(($n - 1) / 2);//下标
        $pre = '';
        while ($i) {
            $start = $i;
            $end = $i + 1;//
            while ($start >= 0 && $end < $n && $s[$start] == $s[$end++]) $start--;
            var_dump($i, $start, $end);
            if ($start < 0) {
                while ($end < $n) {
                    $pre = $s[$end++] . $pre;
                }
                return $pre . $s;
            }

            $start = $i;
            $end = $i;
            while ($start >= 0 && $s[$start] == $s[$end++]) $start--;
            var_dump($i, $start, $end);
            if ($start < 0) {
                while ($end < $n) {
                    $pre = $s[$end++] . $pre;
                }
                return $pre . $s;
            }
            $i--;
        }
    }

    /**
     * 459. 重复的子字符串
     * 高级思路没理清楚
     * 双倍字串法，思想很跳跃！类似三角形面积来源于平行四边形/2。
     * @param String $s
     * @return Boolean
     */
    function repeatedSubstringPattern($s)
    {
        # 双倍字串法，S,s表示。如有重复，那么双倍字符首尾连接，SS=ss..ss..=>sSs..=ss..S，
        //如果不从第一个s（破坏掉第一个s）找S,则不可能在第二个S才找到S。
        return strpos($s . $s, $s, 1) != strlen($s);
        // python代码:去掉首尾两个s，也能找到S
        //return s in (s+s)[1:-1]

        # 暴力法
        $sub = '';
        for ($i = 0, $n = intval(strlen($s) / 2); $i < $n; $i++) {
            $sub .= $s[$i];
            if (!str_replace($sub, '', $s)) return true;
        }
        return false;
    }

    /**
     * 93. 复原IP地址
     * @param String $s
     * @return String[]
     */
    function restoreIpAddresses($s)
    {
        $n = strlen($s);
        if ($n < 4) return [];
        if ($n == 4 || $n == 16) {
            return [implode('.', str_split($s, $n / 4))];
        }
        return $this->split('', $s, 4);
    }

    function split($pre, $s, $c)
    {
        if ($c < 2) {
            return $s > 255 || (!$s[0] && isset($s[1])) ? null : [$pre . $s];
        }
        $result = [];
        $cur = '';
        $n = strlen($s);
        for ($i = 1; $i < 4 && $n > $i; $i++) {
            $cur .= $s[$i - 1];
            if ($cur <= 255 && $n - $i < $c * 3) {
                if ($r = $this->split($pre . $cur . '.', substr($s, $i), $c - 1)) {
                    $result = array_merge($result, $r);
                }
            }
            if ($s[0] == 0) break;
        }
        return $result;
    }

    /**
     * 1668.最大重复子字符串
     * @param String $sequence
     * @param String $word
     * @return Integer
     */
    function maxRepeating($sequence, $word)
    {
        /**
         * KMP法O(n+m) <= 暴力比较O(nm)
         * =strpos
         * @param $text
         * @param $pattern
         * @return bool
         */
        $kmp = function ($text, $pattern) {
            // 算出：模式串中每个字符从头重复的序数，如果全都为0说明完全不重复
            $getNext = function ($pattern) {
                $next[0] = -1;
                $i = 0;
                $j = -1;

                $length = strlen($pattern);
                while ($i < $length) {
                    if ($j == -1 || $pattern[$i] == $pattern[$j]) {
                        ++$i;
                        ++$j;
                        $next[$i] = $j;
                    } else {
                        $j = $next[$j];
                    }
                }
                return $next;
            };

            $next = $getNext($pattern);
            $i = 0;
            $j = 0;
            $tLength = strlen($text);
            $pLength = strlen($pattern);

            while ($i < $tLength && $j < $pLength) {
                if ($j == -1 || $text[$i] == $pattern[$j]) {
                    ++$i;
                    ++$j;
                } else {
                    $j = $next[$j];
                }
            }

            if ($j == $pLength) {
                return true;
            }

            return false;
        };

        $ans = 0;
        $str = $word;
        while ($kmp($sequence, $word)) {
            $word .= $str;
            ++$ans;
        }

        return $ans;
    }

    /**
     * 28. strpos
     * @param $haystack
     * @param $needle
     * @return int
     */
    function strStr($haystack, $needle)
    {
        $n = strlen($needle);
        $x = strlen($haystack) - $n;
        for ($i = 0; $i <= $x; $i++) {
            for ($j = 0; $j < $n && $haystack[$i + $j] == $needle[$j]; $j++) ;
            if ($j == $n) return $i;
        }
        return -1;
    }
}
