<?php

/**
 * Class PriorityQueue 小顶堆 ->insert($sth, $priority);
 * SplPriorityQueue默认是大顶堆
 * SplHeap只有数字排序
 */
class PriorityQueue extends \SplPriorityQueue
{
    public function compare($priority1, $priority2)
    {
        return $priority2 - $priority1;
    }
}

class Solution
{
    /**
     * 347. 前 K 个高频元素
     * topk （前k大）用小根堆，维护堆大小不超过 k 即可。每次压入堆前和堆顶元素比较，如果比堆顶元素还小，直接扔掉，否则压入堆。
     * 检查堆大小是否超过 k，如果超过，弹出堆顶。复杂度是 nlogk
     * 避免使用大根堆，因为你得把所有元素压入堆，复杂度是 nlogn，而且还浪费内存。如果是海量元素，那就挂了。
     * 求前 k 大，用小根堆，求前 k 小，用大根堆。
     * php提供的数据结构，很可以用用！
     * @param Integer[] $nums
     * @param Integer $k
     * @return Integer[]
     */
    function topKFrequent($nums, $k)
    {
        if (!$nums || !$k) return 0;
        $count = array_count_values($nums);

        # 优化，用小顶堆
        $pq = new PriorityQueue();
        foreach ($count as $num => $freq) {
            if ($freq >= $pq->top()) {
                $pq->insert($num, $freq);
                if ($pq->count() > $k) $pq->extract();
            }
        }
        $ans = [];
        foreach ($pq as $item) {
            array_unshift($ans, $item);
        }
        return $ans;

        # 大顶堆  O(n log n)
        $pq = new SplPriorityQueue();
        foreach ($count as $num => $freq) {
            $pq->insert($num, $freq);
        }
        $ans = [];
        for ($i = 0; $i < $k; ++$i) {
            $ans[] = $pq->extract();
        }

        return $ans;

        # 数组做法
        if (!$nums || !$k) return 0;
        foreach ($nums as $num) {
            isset($map[$num]) ? $map[$num]++ : $map[$num] = 1;
        }
        arsort($map);
        // print_r($map);
        $r = [];
        $i = 1;
        foreach ($map as $v => $c) {
            $r[] = $v;
            if ($i++ == $k) break;
        }
        return $r;
    }

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