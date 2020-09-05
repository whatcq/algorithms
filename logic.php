<?php

class Solution
{
    
    /**
     * 1567. 乘积为正数的最长子数组长度
     * 正负交换 的思路没想到，这里没有理清楚；所以想到dfs，但又意识到不对，因为正负数都是在线性积累的
     * max是每步判断的！否则代码好复杂！
     * @param Integer[] $nums
     * @return Integer
     */
    function getMaxLen($nums)
    {
        $max = $zheng = $fu = 0;//积为正数/负数 的当前（遇到0会重新开始）最长子数组（连续）的长度
        foreach ($nums as $num) {
            if (!$num) {
                $zheng = $fu = 0;
            } else {
                if ($num > 0) {
                    $zheng++;
                    if ($fu) $fu++;
                } else {
                    // 正负交换
                    $_fu = $zheng + 1;
                    $zheng = $fu ? $fu + 1 : 0;
                    $fu = $_fu;
                }
                if ($zheng > $max) $max = $zheng;
            }
        }
        return $max;
    }

    /**
     * 5499. 重复至少 K 次且长度为 M 的模式
     * @param Integer[] $arr
     * @param Integer $m
     * @param Integer $k
     * @return Boolean
     */
    function containsPattern($arr, $m, $k)
    {
        # 局部优化
        $count = 0;
        $l = ($k - 1) * $m;
        for ($i = 0, $end = count($arr) - $m; $i < $end; $i++) {
            if ($arr[$i] != $arr[$i + $m]) $count = 0;
            elseif (++$count == $l) return true;
        }
        return false;

        # 思路很好，=>连续的(k-1)*m次跳跃相等，网上那么多复杂教程？ author:Heltion
        $ans = $sum = 0;
        for ($i = 0; $i + $m < count($arr); $i += 1) {
            if ($arr[$i] == $arr[$i + $m]) $ans = max($sum += 1, $ans);
            else $sum = 0;
        }
        return $ans >= ($k - 1) * $m;

        # 此题简单（意思输入数据不多），可以用暴力法
    }

    /**
     * 529. 扫雷游戏
     * 看似很简单，但繁琐
     * @param String[][] $board
     * @param Integer[] $click
     * @return String[][]
     */
    function updateBoard(&$board, $click)
    {
        list($_x, $_y) = $click;//current
        //boom
        if ($board[$_x][$_y] == 'M') {
            $board[$_x][$_y] = 'X';
            return $board;
        }
        if ($board[$_x][$_y] != 'E') {
            return $board;
        }

        $xShift = [-1, -1, -1, 0, 0, 1, 1, 1];
        $yShift = [-1, 0, 1, -1, 1, -1, 0, 1];
        $boom = 0;
        foreach ($xShift as $i => $shift) {
            isset($board[$_x + $shift][$_y + $yShift[$i]])
            && ($board[$_x + $shift][$_y + $yShift[$i]] == 'M'
                || $board[$_x + $shift][$_y + $yShift[$i]] == 'X')
            && $boom++;
        }
        // n
        if ($boom) {
            $board[$_x][$_y] = strval($boom);
            return $board;
        }
        // B
        $board[$_x][$_y] = 'B';
        foreach ($xShift as $i => $shift) {
            isset($board[$_x + $shift][$_y + $yShift[$i]])
            && $this->updateBoard($board, [$_x + $shift, $_y + $yShift[$i]]);
        }
        return $board;
    }

    /**
     * 733. 图像渲染 油漆桶工具
     * 读题很难（逐个字词到位）,解题容易
     * @param Integer[][] $image
     * @param Integer $sr
     * @param Integer $sc
     * @param Integer $newColor
     * @return Integer[][]
     */
    function floodFill($image, $sr, $sc, $newColor)
    {
        static $rows, $cols;
        if ($image[$sr][$sc] == $newColor) return $image;
        $color = $image[$sr][$sc];
        $image[$sr][$sc] = $newColor;
        is_null($rows) && $rows = count($image);
        is_null($cols) && $cols = count($image[0]);
        $sr > 0 && $image[$sr - 1][$sc] == $color && $image = $this->floodFill($image, $sr - 1, $sc, $newColor);
        $sc > 0 && $image[$sr][$sc - 1] == $color && $image = $this->floodFill($image, $sr, $sc - 1, $newColor);
        $sr < $rows - 1 && $image[$sr + 1][$sc] == $color && $image = $this->floodFill($image, $sr + 1, $sc, $newColor);
        $sc < $cols - 1 && $image[$sr][$sc + 1] == $color && $image = $this->floodFill($image, $sr, $sc + 1, $newColor);
        return $image;

        $color = $image[$sr][$sc];
        $image[$sr][$sc] = $newColor;
        $sr > 0 && $image[$sr - 1][$sc] == $color && $image = $this->floodFill($image, $sr - 1, $sc, $newColor);
        $sc > 0 && $image[$sr][$sc - 1] == $color && $image = $this->floodFill($image, $sr, $sc - 1, $newColor);
        $sr < count($image) - 1 && $image[$sr + 1][$sc] == $color && $image = $this->floodFill($image, $sr + 1, $sc, $newColor);
        $sc < count($image[0]) - 1 && $image[$sr][$sc + 1] == $color && $image = $this->floodFill($image, $sr, $sc + 1, $newColor);
        return $image;
    }

    /**
     * 546. 移除盒子，消消看，得最大积分
     * 很难，理清楚哪里开刀都不容易，
     * 看了教程，copy代码，run是对的，提交输出错误(貌似function.static有问题)
     * @param Integer[] $boxes
     * @return Integer
     */
    function removeBoxes($boxes)
    {
        return $this->calcPoints($boxes, 0, count($boxes) - 1, 0);
        $map = [];
        $range = [];
        foreach ($boxes as $i => $x) {
            //isset($map[$x]) ? $map[$x]++ : $map[$x] = 1;
            if (isset($map[$x])) {
                $map[$x]++;
            } else {
                $map[$x] = 1;
                $range[$x][0] = $i;
            }
            $range[$x][1] = $i;
        }
        print_r($map);
        asort($map);
        foreach ($map as $x => $c) {
            echo "$x : $c => " . ($range[$x][1] - $range[$x][0]) . " =>{$range[$x][0]} {$range[$x][1]}\n";
        }
        $total = 0;
        foreach ($map as $c) {
            $total += $c * $c;
        }
        return $total;
    }

    /**
     * 用多维数组|字符串存储数据
     * @param array $path [$l, $r, $k]
     * @param null $value
     * @return array|int|mixed|null
     */
    function kv(array $path, $value = null)
    {
        static $data = [];//leetcode不支持?

        # 字符串方式
        $key = implode('_', $path);
        if (is_null($value)) return isset($data[$key]) ? $data[$key] : 0;
        return $data[$key] = $value;

        # 数组方式
        $p = &$data;
        foreach ($path as $route) {
            isset($p[$route]) or $p[$route] = [];
            $p = &$p[$route];
        }
        if (is_null($value)) return $p ? $p : 0;
        return $p = $value;
    }

    private $kv = [];

    /**
     * @param array $boxes
     * @param int $l
     * @param int $r
     * @param int $k boxes[r]右边同值个数
     * @return int|mixed
     */
    function calcPoints($boxes, $l, $r, $k)
    {
        if ($l > $r) return 0;
        if (isset($this->kv["$l-$r-$k"]) && $this->kv["$l-$r-$k"] != 0) return $this->kv["$l-$r-$k"];
        while ($r > $l && $boxes[$r] == $boxes[$r - 1]) {
            $r--;
            $k++;
        }
        $this->kv["$l-$r-$k"] = $this->calcPoints($boxes, $l, $r - 1, 0)
            + ($k + 1) * ($k + 1);
        for ($i = $l; $i < $r; $i++) {
            if ($boxes[$i] == $boxes[$r]) {
                $this->kv["$l-$r-$k"] = max(
                    $this->kv["$l-$r-$k"],
                    $this->calcPoints($boxes, $l, $i, $k + 1)
                    + $this->calcPoints($boxes, $i + 1, $r - 1, 0)
                );
            }
        }
        //echo "dp[$l][$r][$k]=>{$this->kv["$l-$r-$k"]}\n";
        return $this->kv["$l-$r-$k"];

        #
        if ($l > $r) return 0;
        if (($value = $this->kv([$l, $r, $k])) != 0) return $value;
        while ($r > $l && $boxes[$r] == $boxes[$r - 1]) {
            $r--;
            $k++;
        }
        $this->kv([$l, $r, $k], $this->calcPoints($boxes, $l, $r - 1, 0) + ($k + 1) * ($k + 1));
        for ($i = $l; $i < $r; $i++) {
            if ($boxes[$i] == $boxes[$r]) {
                $this->kv([$l, $r, $k], max(
                    $this->kv([$l, $r, $k]),
                    $this->calcPoints($boxes, $l, $i, $k + 1) + $this->calcPoints($boxes, $i + 1, $r - 1, 0)
                ));
            }
        }
        //echo "dp[$l][$r][$k]=>{$dp[$l][$r][$k]}\n";
        return $this->kv([$l, $r, $k]);
    }
    /**
     * 43.字符串相乘
     * 业务最好懂，理顺没要太多时间
     * @param String $num1
     * @param String $num2
     * @return String
     */
    function multiply($num1, $num2)
    {
        # 最后统一进位，快一些。。没想到可行
        if (!$num1 || !$num2) return '0';//特殊情况，不然可能返回'000'
        $i = $n1 = strlen($num1);
        $n2 = strlen($num2);
        $sum = [];
        while ($i--) {//乘数
            $p0 = $n1 - $i - 1 + $n2 - 1;
            $j = $n2;
            while ($j--) {//被乘数
                $p = $p0 - $j;
                isset($sum[$p]) or $sum[$p] = 0;
                $sum[$p] = $num2[$j] * $num1[$i] + $sum[$p];//当前位 总值
            }
            // print_r($sum);
        }
        unset($num1, $num2, $p0, $p1, $i, $j, $n1, $n2);
        $add = 0;
        # 进位处理
        foreach ($sum as $i => $x) {
            $x += $add;
            $add = intval($x / 10);
            $sum[$i] = $x % 10;
        }
        $add && $sum[$i + 1] = $add;
        return implode('', array_reverse($sum));

        # 不reverse试试
        $sum2 = [];
        $n = count($sum);
        foreach ($sum as $i => $x) {
            $x += $add;
            $add = intval($x / 10);
            //$sum2[$n - $i] = $x % 10;#还需要array_fill 或者 ksort，多余！
            array_unshift($sum2, $x % 10);
        }
        //$add && array_unshift($sum2, $add);
        return ($add ? $add : '') . implode('', $sum2);

        # 逐个连接不如批量连接
        $total = '';
        foreach ($sum as $x) {
            $x += $add;
            $add = intval($x / 10);
            $total = ($x % 10) . $total;
        }
        $add && $total = $add . $total;
        return $total;

        # 每位及时进位
        if (!$num1 || !$num2) return '0';//特殊情况，不然可能返回'000'
        $i = $n1 = strlen($num1);
        $n2 = strlen($num2);
        $sum = [];
        while ($i--) {//乘数
            $p0 = $n1 - $i - 1 + $n2 - 1;
            $j = $n2;
            $add = 0;//进位
            while ($j--) {//被乘数
                $p = $p0 - $j;
                isset($sum[$p]) or $sum[$p] = 0;
                $_ = $num2[$j] * $num1[$i] + $add + $sum[$p];//当前位 总值
                $sum[$p] = $_ % 10;//个位 值
                $add = $_ < 10 ? 0 : intval($_ / 10);
            }
            $add && $sum[$p + 1] = $add;
            //print_r($sum);
        }
        return implode('', array_reverse($sum));
    }

    /**
     * 20. 有效的括号
     * 这么简单且曾经学过的题目居然还做了20min，思路一开始是计数，后来才理清楚：压栈
     * 反括号来了，前一个是否是对应的正括号
     * @param String $s
     * @return Boolean
     */
    function isValid($s)
    {
        $map = [
            ')' => '(',
            ']' => '[',
            '}' => '{',
        ];
        $stack = [];
        for ($i = 0, $n = strlen($s); $i < $n; $i++) {
            if (isset($map[$s[$i]])) {
                if ($map[$s[$i]] != array_pop($stack)) return false;
                else continue;
            }
            $stack[] = $s[$i];
        }
        return !$stack;

        # 思路简洁，不过靠函数多次去遍历处理，不快
        $l = 0;
        while (strlen($s) != $l) {
            $l = strlen($s);
            $s = str_replace(['()', '[]', '{}'], [], $s);
        }
        return !$s;
    }

    private $map = [];//记录自己的parents

    // 自己的祖先是否依赖自己
    function inCircle($son, $father)
    {
        if (isset($this->map[$father])) {
            if (in_array($son, $this->map[$father])) {
                return true;
            }
            foreach ($this->map[$father] as $parent) {
                if ($this->inCircle($son, $parent)) {
                    return true;
                }
            }
        }
        $this->map[$son][] = $father;
        return false;
    }

    /**
     * 207. 课程表
     * 开始没搞懂题目，以为是 1->2->3，去除循环依赖，看能否选出几门课
     * 提交多次才明白题意，求这是不是单向图，用递归一下就搞定了
     * 掐时间做题很紧张，不断提交不断错。。洗澡才能整理好思路。
     * 为什么$numCourses没用？？——另外有更高效算法
     * @param Integer $numCourses
     * @param Integer[][] $prerequisites
     * @return Boolean
     * @todo 拓扑序列，又被叫做有向无环图，DirectedAcyclicGraph（DAG）。 循环停止的条件：即不存在入度为0的顶点
     */
    function canFinish($numCourses, $prerequisites)
    {
        foreach ($prerequisites as $group) {
            list($son, $father) = $group;//儿子依赖老汉
            if ($this->inCircle($son, $father)) return false;
        }
        return true;
    }


    private $board, $x, $y;
    private $no = [];

    /**
     * 130. 被围绕的区域
     * 这是我稍微有感觉一点的题目，但抽象还是花了些时间
     * i,j搞反了，错了3次。。。
     * 学习提高：直接在原数据上改，更快！
     * @param String[][] $board
     * @return NULL
     */
    function solve(&$board)
    {
        $this->x = count($board[0]);
        $this->y = count($board);
        for ($i = 1; $i < $this->x - 1; $i++) {
            if ($board[0][$i] == 'O') {
                $this->explore($board, 1, $i);
            }
            if ($board[$this->y - 1][$i] == 'O') {
                $this->explore($board, $this->y - 2, $i);
            }
        }
        for ($j = 1; $j < $this->y - 1; $j++) {
            if ($board[$j][0] == 'O') {
                $this->explore($board, $j, 1);
            }
            if ($board[$j][$this->x - 1] == 'O') {
                $this->explore($board, $j, $this->x - 2);
            }
        }
        p($board);
        for ($i = 1; $i < $this->x - 1; $i++) {
            for ($j = 1; $j < $this->y - 1; $j++) {
                if ($board[$j][$i] == 'O') {
                    $board[$j][$i] = 'X';
                } elseif (!$board[$j][$i]) {
                    $board[$j][$i] = 'O';
                }
            }
        }
    }

    // 行、列
    function explore(&$board, $j, $i)
    {
        if ($board[$j][$i] != 'O') return;

        $board[$j][$i] = '';
        $j < $this->y - 2 && $this->explore($board, $j + 1, $i);
        $j > 1 && $this->explore($board, $j - 1, $i);
        $i < $this->x - 2 && $this->explore($board, $j, $i + 1);
        $i > 1 && $this->explore($board, $j, $i - 1);
    }
//    function solve(&$board)
//    {
//        $this->board = $board;
//        $this->x = count($board[0]);
//        $this->y = count($board);
//        for ($i = 1; $i < $this->x - 1; $i++) {
//            if ($board[0][$i] == 'O') {
//                $this->explore(1, $i);
//            }
//            if ($board[$this->y - 1][$i] == 'O') {
//                $this->explore($this->y - 2, $i);
//            }
//        }
//        for ($j = 1; $j < $this->y - 1; $j++) {
//            if ($board[$j][0] == 'O') {
//                $this->explore($j, 1);
//            }
//            if ($board[$j][$this->x - 1] == 'O') {
//                $this->explore($j, $this->x - 2);
//            }
//        }
//        print_r($this->no);
//        for ($i = 1; $i < $this->x - 1; $i++) {
//            for ($j = 1; $j < $this->y - 1; $j++) {
//                if (!isset($this->no[$j][$i])) {
//                    $board[$j][$i] = 'X';
//                }
//            }
//        }
//    }
//
//    // 行、列
//    function explore($j, $i)
//    {
//        if ($this->board[$j][$i] != 'O' || isset($this->no[$j][$i])) return;
//
//        $this->no[$j][$i] = 0;
//        $j < $this->y - 2 && $this->explore($j + 1, $i);
//        $j > 1            && $this->explore($j - 1, $i);
//        $i < $this->x - 2 && $this->explore($j, $i + 1);
//        $i > 1            && $this->explore($j, $i - 1);
//    }

    /**
     * 343. 整数拆分 最大乘积
     * 归纳法排除了一些情况，没到数学法
     * @param Integer $n
     * @return Integer
     */
    function integerBreak($n)
    {
        if ($n < 2 || $n > 58) return 0;
        $max = 1;
        for ($i = 2; $i < $n; $i++) {
            $yu = $n % $i;
            $per = ($n - $yu) / $i;
            $_max = $yu && $per > 1 ? ($i + $yu) * pow($i, $per - 1) : pow($i, $per);
            $_max2 = pow($i, $per) * $yu;
            echo "$i    $per    $_max    $_max2\n";
            if ($_max > $max) $max = $_max;
            else break;
        }
        echo '=>', $max;
        return $max;
    }

    /**
     * 3. 无重复字符的最长子串
     * 很简单，最近学会了用map；但逐个unset比内置函数substr慢了
     * 另外，函数max()竟然不如strlen($subStr) > $len ? strlen($subStr) : $len语句快！
     * 不过速度反馈不准确，同一代码执行两次时间差异有时挺大
     * @param String $s
     * @return Integer
     */
    function lengthOfLongestSubstring($s)
    {
        //定义一个临时变量
        $subStr = "";
        $len = 0;
        //字符串长度
        $strLens = strlen($s);
        for ($i = 0; $i < $strLens; $i++) {
            //如果在临时变量中不存在则直接存入
            $ret = strpos($subStr, $s[$i]);
            if ($ret === false) {
                $subStr .= $s[$i];
            } else {
                $len = strlen($subStr) > $len ? strlen($subStr) : $len;
                $subStr .= $s[$i];
                //abcabcbb  比如到了第二个a之后
                $subStr = substr($subStr, $ret + 1);
            }
        }
        $len = strlen($subStr) > $len ? strlen($subStr) : $len;
        return $len;

        $map = [];
        $i = 0;
        $n = strlen($s);
        $max = 0;
        while ($i < $n) {
            if (isset($map[$s[$i]])) {
                $max = max($max, count($map));
                foreach ($map as $v => $_) {
                    unset($map[$v]);
                    if ($v == $s[$i]) {
                        break;
                    }
                }
            }
            $map[$s[$i]] = 0;
            $i++;
        }
        return max($max, count($map));
    }

    /**
     * 415. 字符串相加
     * 这么简单的题，我整了半天。。
     * - 字符串知识
     * - 引用的问题
     * - 判断的问题，有时为了流程简洁一点、少点判断，结果产生判断漏洞。
     * @param String $num1
     * @param String $num2
     * @return String
     */
    function addStrings($num1, $num2)
    {
        # 递归交换位置，代码更简洁
        if (($l2 = strlen($num2)) > ($l1 = strlen($num1))) return $this->addStrings($num2, $num1);
        $add = 0;
        while ($l1--) {
            if (!$add && $l2 <= 0) break;
            $add += ($l2 > 0 ? $num2[--$l2] : 0) + $num1[$l1];
            $num1[$l1] = $add % 10;
            $add = $add >= 10 ? 1 : 0;
        }
        $add && $num1 = '1' . $num1;
        return $num1;

        # 指针
        $l1 = strlen($num1);
        $l2 = strlen($num2);
        if ($l1 > $l2) {
            $l1 = $l1;
            $x = $l1 - $l2;
            $p = &$num1;
            $p2 = &$num2;
        } else {
            $l1 = $l2;
            $x = $l2 - $l1;
            $p2 = &$num1;
            $p = &$num2;
        }
        $add = 0;
        while ($l1--) {
            $add += ($l1 >= $x ? $p2[$l1 - $x] : 0);
            if (!$add) break;
            $add += $p[$l1];
            if ($add >= 10) {
                $p[$l1] = $add % 10;
                $add = 1;
            } else {
                $p[$l1] = $add;
                $add = 0;
            }
        }
        $add && $p = '1' . $p;
        return $p;
    }
    
    private $maze = ["S#O", "M..", "M.T"];
    private $m;
    private $n;
    //private $map;//STMO#.

    /**
     * @param String[] $maze
     * @return Integer
     */
    function minimalSteps($maze)
    {
        $this->maze = $maze;
        $this->m = $m = count($maze);
        $this->n = $n = strlen($maze[0]);
        $map = [];
        foreach ($maze as $i => $row) {
            for ($j = 0; $j < $n; $j++) {
                //echo $row[$j];
                isset($map[$row[$j]]) or $map[$row[$j]] = [];
                array_push($map[$row[$j]], [$i, $j]);
            }
        }
        //print_r($map);
        $this->map = $map;

        if (isset($map['M'])) {
            if (isset($map['O'])) {
                //$A = $this->findStepCount($map['S'])
            }
        }

        return $this->findStepCount($map['S'][0], $map['T'][0]);
    }

    function findStepCount($start, $target, $count = 0)
    {
        static $roadMap = [];
        // no!
        if ($this->maze[$start[0]][$start[1]] === '#') return -1;
        // record min step
        if (isset($roadMap[$start[0]][$start[1]])) {
            if ($roadMap[$start[0]][$start[1]] > $count) $roadMap[$start[0]][$start[1]] = $count;
            else return -1;//走转了，放弃
        } else {
            $roadMap[$start[0]][$start[1]] = $count;
        }
        // found
        if ($start == $target) {
            return $roadMap[$target[0]][$target[1]];
        }
        // explore
        $try = [];
        $start[0] < $this->m - 1 && $try[] = $this->findStepCount([$start[0] + 1, $start[1]], $target, $count + 1);
        $start[1] < $this->n - 1 && $try[] = $this->findStepCount([$start[0], $start[1] + 1], $target, $count + 1);
        $start[0] > 0 && $try[] = $this->findStepCount([$start[0] - 1, $start[1]], $target, $count + 1);
        $start[1] > 0 && $try[] = $this->findStepCount([$start[0], $start[1] - 1], $target, $count + 1);
        $try = array_filter($try, function ($item) {
            return $item > 0;
        });
        return $try ? min($try) : -1;
    }
}

function p($ditu)
{
    foreach ($ditu as $j => $row) {
        echo implode(' ', $row), "\n";
    }
    echo "\n";
}

//$ditu = [["X","X","X","X","X","X","X","X","X","X","X","X","X","X","X","X","X","X","X","X"],["X","X","X","X","X","X","X","X","X","O","O","O","X","X","X","X","X","X","X","X"],["X","X","X","X","X","O","O","O","X","O","X","O","X","X","X","X","X","X","X","X"],["X","X","X","X","X","O","X","O","X","O","X","O","O","O","X","X","X","X","X","X"],["X","X","X","X","X","O","X","O","O","O","X","X","X","X","X","X","X","X","X","X"],["X","X","X","X","X","O","X","X","X","X","X","X","X","X","X","X","X","X","X","X"]];
//p($ditu);
//$ditu = [["X","X","X","X","X","X","X","X","X","X","X","X","X","X","X","X","X","X","X","X"],["X","X","X","X","X","X","X","X","X","X","X","X","X","X","X","X","X","X","X","X"],["X","X","X","X","X","O","X","X","X","X","X","X","X","X","X","X","X","X","X","X"],["X","X","X","X","X","O","X","X","X","X","X","X","X","X","X","X","X","X","X","X"],["X","X","X","X","X","O","X","X","X","X","X","X","X","X","X","X","X","X","X","X"],["X","X","X","X","X","O","X","X","X","X","X","X","X","X","X","X","X","X","X","X"]];
//p($ditu);
$ditu = [["X", "X", "X", "X"], ["X", "O", "O", "X"], ["X", "X", "O", "X"], ["X", "O", "X", "X"]];
p($ditu);
(new Solution())->solve($ditu);
p($ditu);
die;

$dilei =
    [["E", "E", "E", "E", "E", "E", "E", "E"], ["E", "E", "E", "E", "E", "E", "E", "M"], ["E", "E", "M", "E", "E", "E", "E", "E"], ["M", "E", "E", "E", "E", "E", "E", "E"], ["E", "E", "E", "E", "E", "E", "E", "E"], ["E", "E", "E", "E", "E", "E", "E", "E"], ["E", "E", "E", "E", "E", "E", "E", "E"], ["E", "E", "M", "M", "E", "E", "E", "E"]];
p($dilei);
$r = (new Solution())->updateBoard($dilei, [0, 0]);
p($r);
die;
die;
$r = (new Solution())->removeBoxes([1, 2, 3, 2]);
print_r($r);
die;