<?php

class Solution
{
    /**
     * 爬楼梯,就是fibonacci
     * @param Integer $n
     * @return Integer
     */
    function climbStairs($n)
    {
        # 超时写法
        if ($n == 1) return 1;
        if ($n == 2) return 2;
        return $this->climbStairs($n - 1) + $this->climbStairs($n - 2);
    }

    /**
     * Fibonacci sequence
     * 递归法，默认参数初始值，通过传参轮替前两数，妙
     * @param $n
     * @param int $a
     * @param int $b
     * @return int
     */
    function fib2($n, $a = 0, $b = 1)
    {
        #echo "$n, $a, $b\n";
        // n作为计数器，不断递归叠加。序列前移一步就返回$b。
        return $n < 1 ? $a : $this->fib2($n--, $b, $a + $b);
    }

    /**
     * Fibonacci sequence
     * F(0)=0,F(1)=1,F(2)=1,F(3)=2...F(7)=13
     * @param $n
     * @return int
     */
    function fib($n)
    {
        # 巧妙的交换两数!迭代法
        /*
        //交换两数
        $a = 1;
        $b = 2;
        $a += $b;
        $b = $a - $b;
        $a -= $b;
        */
        $prev = 1;
        $cur = 0;
        while ($n-- > 0) {
            $cur += $prev;
            $prev = $cur - $prev;
            #echo "$prev, $cur\n";
        }
        return $cur;

        # 记录前两数,好理解 $a = 0;
        //if (!$n) return 0;
        if ($n < 2) return $n;
        $b = 1;
        $c = 1;
        while ($n-- > 2) {//循环$n-2次
            $a = $b;
            $b = $c;
            $c = $a + $b;
            #echo "$a, $b, $c\n";
        }
        return $c;

        # 数组法，思路简单，吃内存。。
        if ($n < 2) return $n;
        $r[0] = 0;
        $r[1] = 1;
        for ($i = 2; $i <= $n; $i++) {
            $r[$i] = $r[$i - 1] + $r[$i - 2];
        }
        //var_dump($n,$i,$r);
        return $r[$n];//百科代码不对；$r[$i - 1]:$i倒回来一个数

        # 递归算法，分裂式时间复杂度O(2^(n-1))，fib(100)就死机！上缓存大法约等于数组法
        return $n < 2 ? $n : $this->fib($n - 1) + $this->fib($n - 2);

        # 矩阵乘方运算法O(log2n),太难，放弃https://www.cnblogs.com/myoleole/archive/2012/12/01/2797709.html
    }

    /**
     * 最大子序和
     * 变量（左右范围）一多就理不清楚，怕这种题
     * 没想到理清楚之后就5行代码：观察后发现：两头都为正，最大值可以根据当前要不要来线性积累
     * @param Integer[] $nums
     * @return Integer
     */
    function maxSubArray($nums)
    {
        $max = current($nums);//历史最大值
        $cur = 0;//当前最大值
        foreach ($nums as $num) {
            $cur = $cur > 0 ? $cur + $num : $num;
            $cur > $max && $max = $cur;
            //用函数可读性更好
            //$cur = max($cur + $num, $num);
            //$max = max($max, $cur);
        }
        return $max;
    }


    /**
     * 买卖股票的最佳时机
     * 没理清如何简化问题。。
     * @param Integer[] $prices
     * @return Integer
     */
    function maxProfit($prices)
    {
        # 每日回顾
        $min = max($prices);//这步是关键，巧妙
        $x = 0;
        foreach ($prices as $price) {
            if ($price < $min) $min = $price;//买入在低点,抄底
            else $x = max($x, $price - $min);//每天算这一波最大利润
        }
        return $x;

        # 马后炮
        $max = $min = $x = 0;//谷峰差,跌破当前最小值算一次谷峰结束
        for ($i = 1, $n = count($prices); $i < $n; $i++) {
            //没买入
            if ($min == $max) {
                //今天比昨天好，就昨天买入
                if ($prices[$i] > $prices[$i - 1]) {
                    $min = $prices[$i - 1];
                    $max = $prices[$i];
                }
            } else {
                if ($prices[$i] > $max) $max = $prices[$i];//涨则持仓
                elseif ($prices[$i] < $min) {
                    //跌破买入价，则假设在之前最高点平仓获利了。。
                    $x = max($x, $max - $min);
                    $min = $max = 0;
                }
            }
            echo "$min,$max\n";
        }
        return max($max - $min, $x);
    }

    /**
     * 51. N 皇后
     * 经典问题，但现在算首次真正学习！
     * 记得很难，赖得分析了，所以直接看答案。结果这么简单。。
     * 难点：1 结果用一维数组就可以，不用二维数组；2 斜线的判断规律 3 写法流程：递归剪枝回溯，需学会
     * @param Integer $n
     * @return String[][]
     */
    function solveNQueens($n)
    {
        $this->_solveNQueens2($n);
        $r = $this->res;
        // 要求图形化打印出来
        $line = str_repeat('.', $n);
        array_walk_recursive($r, function (&$v, $k) use ($line) {
            $line[$v - 1] = 'Q';
            $v = $line;
        });
        return $r;
    }

    private $res = [];

    // 过程参数全部跟着传，就不用$this->pie这样麻烦了。。
    function _solveNQueens2($n, $row = 0, $cols = [], $pie = [], $na = [])
    {
        if ($row == $n) {
            $this->res[] = array_keys($cols);
            return;
        }
        // 序号从1或0开始都行，统一就ok
        for ($col = 1; $col <= $n; $col++) {
            //in_array改成isset更快！
            if (isset($cols[$col])
                || isset($pie[$col + $row])
                || isset($na[$col - $row])
            ) {
                continue;
            }
            // 即时运算的方式避免了“回溯”，其他语言也可以通过写一个函数来达到同样效果
            // array_merge会重置数字索引key！注意区别与+
            $this->_solveNQueens2(
                $n,
                $row + 1,
                $cols + [$col => 0],
                $pie + [$col + $row => 0],
                $na + [$col - $row => 0]
            );
        }
    }

    private $cols = [], $pie = [], $na = [];

    // 斜线规律：
    // 捺：i,j=>i+1,j+1=>i+2,j+2...(x-y)是固定的！，且每一条斜线不同
    // 撇：1,2=>2,1或者1,5=>2,4=>3,3...n放大一点才看清规律，(i+j)是固定的！
    // $row从0开始，对应终止判断条件$row==$n
    function _solveNQueens($n, $ans = [], $row = 0)
    {
        if ($row == $n) {
            $this->res[] = $ans;//dfs返回数据是统一的。不可能既能返回[],又能返回[[],[]]，否则至少很麻烦
            return;
        }
        // 序号从1或0开始都行，统一就ok
        for ($col = 1; $col <= $n; $col++) {
            //in_array改成isset更快！
            if (isset($this->cols[$col])
                || isset($this->pie[$col + $row])
                || isset($this->na[$col - $row])
            ) {
                continue;
            }
            $this->cols[$col] = 0;
            $this->pie[$col + $row] = 0;
            $this->na[$col - $row] = 0;
            $this->_solveNQueens($n, array_merge($ans, [$col]), $row + 1);
            unset($this->cols[$col]);
            unset($this->pie[$col + $row]);
            unset($this->na[$col - $row]);
        }
    }

    /**
     * 877. 石子游戏 （486题的特例）
     * x粒石子(奇数)分成n堆(偶数)，排成一排，甲乙两人依次选择一堆（前面或后面）
     * 返回甲是否必胜
     * @param Integer[] $piles
     * @return Boolean
     */
    function stoneGame($piles) {
        // 先手必胜！因为无论怎么排序，无论怎么分，最后都分成了两组，而甲先选。。数学啊
        return true;
    }

    /**
     * 486. 预测赢家
     * 业务挺清晰，代码半天没理清楚。。
     * 我的思路很具象化，抽象天赋不够。。
     * 另外方法：只记录两者的差值
     * @param Integer[] $nums
     * @return Boolean
     * @todo 做出来才几行，这种题要反复做，
     */
    function PredictTheWinner($nums)
    {
        // 这才是动态规划！
        $n = count($nums);
        $dp = $nums;
        for ($i = $n - 2; $i > -1; $i--) {
            for ($j = $i + 1; $j < $n; $j++) {
                $dp[$j] = max($nums[$i] - $dp[$j], $nums[$j] - $dp[$j - 1]);
            }
        }
        return $dp[$n - 1] >= 0;

        $n = count($nums);
        if ($n < 3) return true;
        $this->half = array_sum($nums) / 2;

        // 选左边 或 右边 能否赢
        return $this->play($nums, 1, $n - 1, $nums[0], 0)
        || $this->play($nums, 0, $n - 2, $nums[$n - 1], 0);
    }

    private $half;
    public $playResult = [];

    // 判断输赢，如果这一步还没完，b走一步（情况一:b要前，a再走一步（两种情况），看a能否赢；情况二，b要后，a再走一步（两种情况），a是否也能赢）
    function play($nums, $min, $max, $a = 0, $b = 0)
    {
        // 竟然没有重复的。。
        if (isset($this->playResult[$min][$max][$a])) {
            echo "重";
            return $this->playResult[$min][$max][$a];
        }
        echo "\n=====\n";
        for ($i = $min; $i <= $max; $i++)
            echo $nums[$i], ' ';
        echo "===>$a  $b";
        //剩一个
        if ($max == $min) {
            return $a >= $b + $nums[$max];
        }

        //剩两个
        if ($max == $min + 1) {
            return $a >= $b + abs($nums[$min] - $nums[$max]);
            return $a + min($nums[$min], $nums[$max]) >= $b + max($nums[$min], $nums[$max]);
        }

        //剩三个或以上
        if ($b > $this->half) return $this->playResult[$min][$max][$a] = false;
        if ($a >= $this->half) return $this->playResult[$min][$max][$a] = true;

        // b选左边，a选左边或者右边能赢
        // b选右边，a选左边或者右边也能赢
        return $this->playResult[$min][$max][$a] = (
                $this->play($nums, $min + 2, $max, $a + $nums[$min + 1], $nums[$min] + $b)
                || $this->play($nums, $min + 1, $max - 1, $a + $nums[$max], $nums[$min] + $b)
            ) && (
                $this->play($nums, $min + 1, $max - 1, $a + $nums[$min], $nums[$max] + $b)
                || $this->play($nums, $min, $max - 2, $a + $nums[$max - 1], $nums[$max] + $b)
            );
    }

    /**
     * 1562. 查找大小为 M 的最新分组
     * @param Integer[] $arr
     * @param Integer $m
     * @return Integer
     * 挺复杂一个题，超时。。
     * 经典的一个题，两个思路的巨大差别
     */
    function findLatestStep($arr, $m)
    {
        if ($m == ($n = count($arr))) return $m;
        //$str = str_repeat('0', $n);
        //没想到，顺着来反而更好！
        //link这个思想很高级，所有区间，都用(start=>end,end=>start)来表示，
        //新加一个直接判断相邻就合并！摸石头过河，不需要搜索！【快】
        //相比倒过来思考，这个得遍历完整个数组，才能找到m最后的所在。
        $link = [];
        $count = 0;
        $step = -1;
        for ($i = 0; $i < $n; $i++) {
            $x = $arr[$i];
            $l = isset($link[$x - 1]) ? $link[$x - 1] : $x;
            $r = isset($link[$x + 1]) ? $link[$x + 1] : $x;
            if ($x - $l == $m) $count--;//当前与左边合并，x-l+1==m+1则原来左边长度m=>m+1了，所以计数减一
            if ($r - $x == $m) $count--;//这里仔细才能捋清
            if ($r - $l + 1 == $m) $count++;//当前无论是否合并后，连成的长度
            if ($count > 0) $step = $i + 1;//记录或更新含有m长度的步骤
            $link[$l] = $r;//合并后原来的link可以删除，但无所谓啦
            $link[$r] = $l;
            //$str[$arr[$i] - 1] = '1';
            //echo "$str=====$i\n";
            //echo "$i => $x ==== $l $r\n";
            //print_r($link);
        }
        return $step;

        //==============
        //我的思路，倒过来看找第一次出现m长度的字符串的时候。没问题
        //问题是：需要记录所有连续区间，每次循环都需要去找到对应区间
        //那么就找吧，顺序搜索超时，改成二分法查找依然超时，
        //其他语言有TreeSet这样的查找树功能，提升了一点性能能通过，但php二分查找已经是优化了。。
        if ($m == ($n = count($arr))) return $m;

        $i = $n;
        $range = [1 => $n];
        $range = [1, $n];
        $str = str_repeat('1', $n);
        while ($i-- > $m) {
            $cur = $arr[$i];
            // 改成二分法搜索，好不容易调试对了，但依然超时！
            $l = 0;
            $r = count($range) - 1;//=== 2 * ($n - $i + 1) - 1;
            while ($l < $r - 1) {
                $middle = intval(($l + $r) / 2);
                if ($range[$middle] > $cur) {
                    $r = $middle;
                } else {
                    $l = $middle;
                }
                //echo "$l $r\n";
            }
            //l>=middle,
            //数据结构是 [start1, end1, start2, end2, ...]两两一组，所以需要处理
            if ($l % 2) {
                $l--;
                $r--;
            }
            $start = $range[$l];
            $end = $range[$r];
            //echo "------------------\n";
            //echo "$start, $end, $cur==$arr[$i]\n";
            if ($m == $cur - $start || $m == $end - $cur) return $i;
            if ($start == $cur) $range[$l] = $cur + 1;
            elseif ($end == $cur) $range[$r] = $cur - 1;
            else array_splice($range, $l + 1, 0, [$cur - 1, $cur + 1]);

            //$str[$arr[$i] - 1] = '.';
            //echo "$str=====$i\n";
            //echo implode(" ", $range), "\n";

            //======顺序搜索：数据结构：[start1 => end1, start2 => end2, ...]
            // foreach ($range as $start => $end) {
            //     if ($cur >= $start && $cur <= $end) {
            //         if ($m == $cur - $start || $m == $end - $cur) return $i;
            //         if ($start == $cur) unset($range[$start]);
            //         else $range[$start] = $cur - 1;
            //         if ($end > $cur) $range[$cur + 1] = $end;
            //         break;
            //     }
            // }
        }
        return -1;
    }

    /**
     * 332. 重新安排行程
     * 实际上是把所有二级数组首尾连起来。。
     * @param String[][] $tickets
     * @return String[]
     */
    function findItinerary($tickets)
    {
        # dfs 自循环优化：为了去掉全局排序，改了之后跟迭代法差不多了，却没改成功；改成if+usort没多大意思。

        # dfs会对所有tickets排序。。
        usort($tickets, function ($a, $b) {
            return strcmp($a[0] . $a[1], $b[0] . $b[1]);
        });
        return $this->findItineraryDFS($tickets, ['JFK']);

        # 迭代法压栈，复杂些
        $route = ['JFK'];
        $tmpNode = [];//悔棋队列
        while ($tickets) {
            $options = [];
            $cur = end($route);
            foreach ($tickets as $key => $ticket) {
                if ($ticket[0] == $cur) {
                    $options[$key] = $ticket[1];
                }
            }
            if ($options) {
                // 有路可走；分岔点的另一条路
                //【关键】只可能走错一次，否则有两个盲端，无解，死循环（才意识到这一点，汗）
                if (count($options) > 1) {
                    asort($options);
                    reset($options);
                }
                $key = key($options);
                $route[] = $tickets[$key][1];
                unset($tickets[$key]);

                // 再！还回走错的票（岔路）到队尾
                if ($tmpNode) {
                    $tickets = array_merge($tickets, $tmpNode);//会重新索引！
                    $tmpNode = [];
                }
            } else {
                // 票还没用完，却无路可走，悔棋：上一张票
                $to = array_pop($route);
                $from = end($route);
                $tmpNode[] = [$from, $to];
            }
        }
        return $route;

        # 思路：map，依次找，结果，无法处理盲端/走不完的问题。。未完成!
        $map = [];
        foreach ($tickets as $ticket) {
            list($from, $to) = $ticket;
            isset($map[$from])
                ? (is_array($map[$from])
                ? $map[$from][] = $to
                : $map[$from] = [$map[$from], $to])
                : $map[$from] = $to;
        }
        $cur = 'JFK';
        $route = [$cur];
        while (isset($map[$cur])) {//$map还没完呢。。
            if (is_array($map[$cur])) {
                sort($map[$cur]);
                foreach ($map[$cur] as $i => $next) {
                    if (isset($map[$next])) {//只能判断下一站
                        unset($map[$cur][$i]);
                        break;
                    }
                }
                if (count($map[$cur]) == 1) $map[$cur] = current($map[$cur]);
            } else {
                $next = $map[$cur];
                unset($map[$cur]);
            }
            $route[] = $cur = $next;
        }
        return $route;
    }

    function findItineraryDFS($tickets, $route = [])
    {
        if (!$tickets) return $route;
        $cur = end($route);
        foreach ($tickets as $i => $ticket) {
            list($from, $to) = $ticket;
            if ($from == $cur) {
                $tmp = $tickets;
                unset($tmp[$i]);
                if ($_route = $this->findItineraryDFS($tmp, array_merge($route, [$to]))) {
                    return $_route;
                }// 此路不通不会再试，能避免无解的死循环，题目倒是说没这种情况输入
            }
        }
        return false;
    }

    /**
     * 491. 递增子序列
     * 给定一个整型数组, 你的任务是找到所有该数组的递增子序列，递增子序列的长度至少是2。
     * 输入: [4, 6, 7, 7]
     * 输出: [[4, 6], [4, 7], [4, 6, 7], [4, 6, 7, 7], [6, 7], [6, 7, 7], [7,7], [4,7,7]]
     * 感觉简单，代码流程迟迟整理不出来：因为跳跃交叉的情况如何展开成一维算法？
     * @param Integer[] $nums
     * @return Integer[][]
     */
    function findSubsequences($nums)
    {
        $r = [];
        $paths = [];
        $n = count($nums);
        // 传地址的方式可以避免 `$this->` 的繁琐写法
        $this->findSubsequencesDFS(0, [], $nums, $n, $r, $paths);
        return $r;

        #根据实例来推运算过程，漏解
        $r = [];
        for ($i = 0, $n = count($nums); $i < $n; $i++) {
            $cur = $nums[$i];
            for ($k = 1; $k < $n; $k++) {
                $curArr = [$cur];
                for ($j = $i + $k; $j < $n; $j++) {
                    $curArr[] = $nums[$j];
                    $r[] = $curArr;//array_merge($curArr, [$nums[$i]]);
                }
            }
//
//            foreach ($r as $item) {
//                $r[] = array_merge($item, [$nums[$i]]);
//            }
//            $prev = null;
//            for ($j = 0; $j < $i; $j++) {
//                if($nums[$i] == $prev)continue;
//                $prev = $nums[$i];
//                $r[] = [$nums[$j], $nums[$i]];
//            }

        }
        return $r;

        #思路没理清
        $r = [];
        for ($i = 1, $n = count($nums); $i < $n; $i++) {
            foreach ($r as $item) {
                $r[] = array_merge($item, [$nums[$i]]);
            }
            $prev = null;
            for ($j = 0; $j < $i; $j++) {
                if ($nums[$i] == $prev) continue;
                $prev = $nums[$i];
                $r[] = [$nums[$j], $nums[$i]];
            }

        }
        return $r;
    }

    /**
     * 前面值选好了，后面的依次追加，这样递归
     * @param int $start 开始点
     * @param array $path 选上的value
     * @param array $nums 原参数
     * @param $n
     * @param $r
     * @param $paths
     */
    function findSubsequencesDFS($start, $path, $nums, $n, &$r, &$paths)
    {
        if (count($path) > 1) {
            $_path = implode('-', $path);
            if (!isset($paths[$_path])) {
                $r[] = $path;
                $paths[$_path] = 0;
            }
        }
        for ($i = $start; $i < $n; $i++) {
            $prev = end($path);
            $cur = $nums[$i];
            if (!$path || $prev <= $cur) {
                $path[] = $cur;
                $this->findSubsequencesDFS($i + 1, $path, $nums, $n, $r, $paths);
                array_pop($path);
            }
        }
    }

    /*
    if(count($nums)==2){
        list($a, $b)=$nums;
        if(is_array($a)){
            list($i, $j)=$a;
            $realNums = $b;
            $a = $realNums[$i];
            $b = $realNums[$j];
            //$realNums = count($realNums) > 1 ? array_values($realNums) : current($realNums);
            $realNums[$i] = $a+$b;
            unset($realNums[$j]);
            if($this->judgePoint24(array_values($realNums)))return true;
        }

        switch(24){
            case $a+$b:return true;
            case $a*$b:return true;
            case abs($a-$b):return true;
            case $a/$b:return true;
            case $b/$a:return true;
            default:return false;
        }
    }
    */
    public $count = 0;

    /**
     * 679. 24点游戏
     * 没想到整理代码之后这么短。。
     * @param Integer[] $nums
     * @return Boolean
     */
    function judgePoint24($nums)
    {
        if (count($nums) == 1) {
            $this->count++;
            echo $nums[0], "\n";
            return round($nums[0], 3) == 24;//还可怎么判断？
        }
        # 分析题目，不断组合两数计算，（开始思路就对了，实现流程整理了1h）js怎么写？go,py呢？
        foreach ($nums as $i => $a) {
            foreach ($nums as $j => $b) {
                if ($i == $j) continue;
                $_nums = $nums;
                unset($_nums[$i], $_nums[$j]);
                if ($i > $j && $this->judgePoint24(array_merge([$a + $b], $_nums))) return true;//交换律优化
                if ($this->judgePoint24(array_merge([$a - $b], $_nums))) return true;
                if ($i > $j && $this->judgePoint24(array_merge([$a * $b], $_nums))) return true;
                if ($b && $this->judgePoint24(array_merge([$a / $b], $_nums))) return true;
            }
        }
        /*
        for ($i = 0, $n = count($nums); $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($this->judgePoint24($this->calc($nums, $i, $j, '+'))) return true;
                if ($this->judgePoint24($this->calc($nums, $i, $j, '-'))) return true;
                if ($this->judgePoint24($this->calc($nums, $i, $j, '*'))) return true;
                if ($this->judgePoint24($this->calc($nums, $i, $j, '/'))) return true;
            }
        }
        */
        return false;
    }

    function calc($nums, $i, $j, $op)
    {
        echo "$nums[$i] $op $nums[$j] =>";
        switch ($op) {
            case '+':
                $nums[$i] = $nums[$i] + $nums[$j];
                break;
            case '-':
                $nums[$i] = $nums[$i] - $nums[$j];
                break;
            case '*':
                $nums[$i] = $nums[$i] * $nums[$j];
                break;
            case '/':
                if ($nums[$j] == 0) return [0];
                $nums[$i] = $nums[$i] / $nums[$j];
                break;
        }
        unset($nums[$j]);
        return array_values($nums);
    }

    //=============
    private $f = [];//包含当前值
    private $g = [];//不包含当前值 的最大可能

    private function sumX($root, $i = 1)
    {
        if (!$root) return;
        $l = $i << 1;
        if ($root->left) {
            $this->sumX($root->left, $l);
        } else {
            $this->f[$l] = 0;
            $this->g[$l] = 0;
        }
        $r = $l + 1;
        if ($root->right) {
            $this->sumX($root->right, $r);
        } else {
            $this->f[$r] = 0;
            $this->g[$r] = 0;
        }
        $this->f[$i] = $root->val + $this->g[$l] + $this->g[$r];
        $this->g[$i] = ($this->f[$l] > $this->g[$l] ? $this->f[$l] : $this->g[$l])
            + ($this->f[$r] > $this->g[$r] ? $this->f[$r] : $this->g[$r]);
        //max($this->f[$l], $this->g[$l]) + max($this->f[$r], $this->g[$r]);
    }

    //==============
    private $total = 0;

    /**
     * 968. 监控二叉树 难在理清逻辑流程
     *
     * @param  TreeNode $root
     * @return Integer
     */
    function minCameraCover($root)
    {
        if (!$root) return 0;
        if ($this->minCameraCoverDFS($root) === 0) $this->total++;
        return $this->total;
    }

    function minCameraCoverDFS($root)
    {
        //当前节点3种状态
        $l = $r = 2;//默认不用管！
        if ($root->left) $l = $this->minCameraCoverDFS($root->left);
        if ($root->right) $r = $this->minCameraCoverDFS($root->right);
        if ($l === 0 || $r === 0) {
            $this->total++;
            return 1;//1：自己安监视器 
        }
        if ($l === 1 || $r === 1) return 2;//2：可被子女照看，故未装监视器
        return 0;//0：无子女照看，要么自己装，要么靠爹
    }

    /**
     * @param TreeNode $root
     * @return array [包含当前值, 不包含当前值]
     */
    function dp($root)
    {
        if (!$root) return [0, 0];
        $leftSum = $this->dp($root->left);
        $rightSum = $this->dp($root->right);
        return [
            $root->val + $leftSum[1] + $rightSum[1],
            max($leftSum[0], $leftSum[1]) + max($rightSum[0], $rightSum[1])
//            ($leftSum[0] > $leftSum[1] ? $leftSum[0] : $leftSum[1])
//            + ($rightSum[0] > $rightSum[1] ? $rightSum[0] : $rightSum[1])
        ];
    }

    /**
     * 337. 打家劫舍 III
     * @param TreeNode $root
     * @return Integer
     */
    function rob3($root)
    {
        if (!$root) return 0;

        # 直接返回更快
        list($a, $b) = $this->dp($root);
        return max($a, $b);

        # 全局保存数据
        $this->sumX($root);
//        print_r($this->f);
//        print_r($this->g);
        return max($this->f[1], $this->g[1]);
    }

    /**
     * 打家劫舍I
     * 动态规划，需要系统学习，有了套路才能将这种题化繁为简！不然反复理不清楚。
     * @param Integer[] $nums
     * @return Integer
     */
    function rob($nums)
    {
        if (!$nums) return 0;
        if (count($nums) <= 2) return max($nums);
        $dp[0] = $nums[0];
        $dp[1] = max($dp[0], $nums[1]);
        for ($i = 2, $n = count($nums); $i < $n; $i++) {
            $dp[$i] = max($dp[$i - 2] + $nums[$i], $dp[$i - 1]);
        }
        return $dp[$i - 1];
    }

    /**
     * 40. 组合总和 II
     * 竟然乱试做对了，还100%
     * @param Integer[] $candidates
     * @param Integer $target
     * @return Integer[][]
     */
    function combinationSum2($candidates, $target)
    {
        sort($candidates);
        return $this->combinationSum2DFS($candidates, $target, []);
    }

    function combinationSum2DFS($candidates, $target, $ans = [])
    {
        $r = [];
        $last = end($ans);
        $prev = null;
        foreach ($candidates as $i => $x) {
            if ($x < $last) continue;//组合过滤i<j,前面已经组合后面的，后面不能再组合前面的
            if ($x === $prev) continue;//重复过滤,另外：选择的数可以用pos来定位
            $prev = $x;
            if ($x < $target) {
                unset($candidates[$i]);
                $r = array_merge($r, $this->combinationSum2DFS($candidates, $target - $x, array_merge($ans, [$x])));
            } elseif ($x == $target) $r[] = array_merge($ans, [$x]);
            else break;
        }
        return $r;
    }

    /**
     * 77. 组合
     * 感觉很简单一个题！怎么卡住了？不知怎么写。。多重循环是bfs
     * 先想好用dfs还是bfs？
     * @param Integer $n
     * @param Integer $k
     * @return Integer[][]
     * @todo 回头再捋一捋
     */
    function combine($n, $k)
    {
        // 特殊情况
        if ($k > $n || !$k || !$n) return [];
        $range = range(1, $n);
        if ($n == $k) return [$range];
        if ($k == 1) {
            foreach ($range as &$item) {
                $item = [$item];
            }
            return $range;
        }

        # 递归
        $this->combineDFS($n, $k);
        return $this->result;

        # C(m,n)=C(m-1,n)+C(m-1,n-1) 公式高级
        # 小王是亲戚，他不去则另外人里选n；他要去则另外人里选n-1
        $ans = $this->combine($n - 1, $k);
        foreach ($this->combine($n - 1, $k - 1) as $item) {
            $item[] = $n;
            $ans[] = $item;
        }
        return $ans;
    }

    private $result = [];

    function combineDFS($n, $k, $start = 1, $picks = [], $length = 0)
    {
        //start并不是length!
        //echo count($picks)==$start-1 ? 'Y':'N';
        if ($length == $k) {
            $this->result[] = $picks;
            return;
        }

        // 此时剩余可选数字个数 $n - $i + 1
        // 所需数字个数 $k - $length
        for ($i = $start; $n - $i + 1 >= $k - $length; $i++) {//我的思路：i<=n-k+1-length
            $this->combineDFS($n, $k, $i + 1, array_merge($picks, [$i]), $length + 1);
        }
    }

    /**
     * 47. 全排列 II
     *
     * @param Integer[] $nums
     * @return Integer[][]
     */
    function permuteUnique($nums)
    {
        sort($nums);
        $this->permute($nums, 0, count($nums) - 1);
        return $this->r;
    }

    private $r = [];

    /**
     * 全排列套路：循环，依次选出一个放到第一位(交换)，剩下的继续全排列
     * 公式：A(n, m)=A(n, 1)A(n-1, m)=nA(n-1, m)
     * @param $nums
     * @param $left
     * @param $right int 只是记录右边界
     * @return mixed
     */
    function permute($nums, $left, $right)
    {
        if ($left == $right) return $this->r[] = $nums;
        for ($i = $left; $i <= $right; $i++) {
            if ($i != $left && $nums[$left] == $nums[$i]) continue;
            $tmp = $nums[$i];
            $nums[$i] = $nums[$left];
            $nums[$left] = $tmp;
            $this->permute($nums, $left + 1, $right);
        }
    }

    /**
     * 79. 单词搜索
     * 很简单的一个回溯题，具象一点的题好做一点，记住这个写法
     * @param String[][] $board
     * @param String $word
     * @return Boolean
     */
    function exist($board, $word)
    {
        foreach ($board as $i => $line) {
            foreach ($line as $j => $v) {
                if ($v == $word[0]) {
                    if (!isset($word[1])) return true;
                    if ($this->explore($board, $word, $i, $j)) return true;
                }
            }
        }
        return false;
    }

    function explore($board, $word, $i, $j, $map = [], $index = 1)
    {
        $map[$i][$j] = 0;
        foreach ([[0, 1], [0, -1], [1, 0], [-1, 0]] as list($x, $y)) {
            $_i = $i + $x;
            $_j = $j + $y;
            if (isset($board[$_i][$_j])
                && !isset($map[$_i][$_j])
                && $board[$_i][$_j] == $word[$index]
            ) {
                if (isset($word[$index + 1])) {
                    if ($this->explore($board, $word, $_i, $_j, $map, $index + 1)) return true;
                } else {
                    return true;
                }
            }
        }
        return false;
    }

    //python 一行牛逼！
    //class Solution(object):
    //    def canPartition(self, s):
    //        return 1-sum(s)%2==reduce(lambda x,y:x|(x<<y), s, 1)>>sum(s)/2&1
    /*
    //c++位运算法，烧脑
    class Solution {
    public:
        bool canPartition(vector<int>& nums) {

            if(nums.size()<2)   return false;

            int sum = accumulate(nums.begin(), nums.end(), 0);
            if(sum&1)  return false;

            bitset<10001> bits(1);
            for (auto n : nums) bits |= bits << n;
            return bits[sum >> 1];
        }
    }
    */
    /**
     * 416. 分割等和子集
     * 耗费我半天时间的题，终于搞定了。
     * 自己的dfs重复深搜了，超时，半天没理清楚；后来学到了，记录no[target][num]去重：此乃记忆深搜
     * 解题说是背包问题，网上找半天没找到php版，找到的还是错的，其他语言的也没翻译成功。
     * 走完leetcode用例的坑，网上答案的坑，最终3个解法：记忆深搜，暴力背包，迭代法全组合，还可以优化成位运算，牛了逼了
     * 2020/10/13 1:41 这题最终又耗费我3h，不能死磕了，要劳逸
     * @param Integer[] $nums
     * @return Boolean
     */
    function canPartition($nums)
    {
        $sum = array_sum($nums);
        if ($sum % 2) return false;
        $target = $sum / 2;

        #全组合，直接迭代，替代掉背包的低效循环（有的值组合不了！）
        //用set表示可以组成的和
        $set = [0 => 0];
        foreach ($nums as $num) {
            foreach ($set as $_sum => $_) {
                //if (!is_numeric($item)) continue;
                if ($target < $_sum += $num) continue;//这里不能break！只是本轮递增，但_sum小的可能前面几轮没出现过——那也就不用排序了！
                if ($target == $_sum) return true;//加这行还快点？也许是多测试用例综合吧
                $set[$_sum] = 0;
            }
            //$set["=$i"] = '-------' . $num;
        }
        return false;

        sort($nums);
        #深搜，排序+记忆化去重
        return $this->canPartitionFind($nums, $sum / 2, 0);
        return $this->dfs($nums, $sum / 2, 0);
        return $this->_01($nums, $sum / 2, 0);

        $target = $sum / 2;
        #背包，思路：因为题目说nums<100,n<200，所以总值<2w/2，所以用dp(我以为是高级方法，理解才知垃圾)
        //总和 => 是否可以组成
        $dp[0] = true;
        //主要为了初始化数组，不然报Notice错误
        //这方法不排序（也不知排序耗时不？），先用一个数nums[0]填一空，这思想太low了，不理解
        for ($i = 1; $i <= $target; $i++) {
            $dp[$i] = $i == $nums[0];
            /*这代码典型啰嗦
            if ($i == $nums[0]) {
                $dp[$i] = true;
            } else {
                $dp[$i] = false;
            }*/
        }
        for ($i = 1; $i < count($nums); $i++) {
            for ($j = $target; $j >= $nums[$i]; $j--) {
                //O(n^2)遍历得，当前值能否组合，这么做不如全组合了：(@see 78. 子集)
                $dp[$j] = $dp[$j] || $dp[$j - $nums[$i]];
            }
        }
        return $dp[$target];
    }

    //看来这是之前通过的代码！晕,这不跟我dfs一样了嘛，还得记忆去重
    function canPartitionFind($nums, $target, $index)
    {
        if ($target == 0) return true;
        echo "$target $index\n";
        for ($i = $index, $n = count($nums); $i < $n; $i++) {
            if ($i > $index && $nums[$i] == $nums[$i - 1]) continue;//这根本没用，只是同级去重，记忆去重就够了，也就不用排序了！
            //if ($target == $nums[$i]) return true;//出结果只有一次，放在递归函数里。。。其实一样的！不过那样可以有初始化判断。
            if ($target < $nums[$i]) return false;
            if (isset($this->no[$target][$nums[$i]])) continue;
            if ($this->canPartitionFind($nums, $target - $nums[$i], $i + 1)) return true;
            $this->no[$target][$nums[$i]] = 0;
        }
        return false;
    }

    //这个01背包代码逻辑清晰代码简洁，跟我dfs是一个道理，但也没有去重，超时
    function _01($nums, $target, $i)
    {
        if ($nums[$i] == $target) return true;
        if ($nums[$i] > $target) return false;
        if (!isset($nums[$i + 1])) return false;
        return $this->_01($nums, $target - $nums[$i], $i + 1) || $this->_01($nums, $target, $i + 1);
    }

    private $no = [];

    function dfs($nums, $target, $p)
    {
        echo implode(' ', $nums), " = $target\n";
        $prev = null;
        foreach ($nums as $i => $num) {
            if ($i <= $p) continue;//我竟然在这里没写=，超时了
            if (/*isset($nums[$i - 1]) && */
                $nums[$i - 1] == $num
            ) continue;
            if ($num == $target) return true;
            elseif ($num < $target) {
                if (isset($this->no[$target][$num])) continue;
                if ($prev == $num) continue;
                $prev = $num;
                //$tmp = $nums;
                //unset($tmp[$i]);
                //if (!$tmp) return false;
                if ($this->dfs($nums, $target - $num, $i)) return true;
                $this->no[$target][$num] = 0;
            } else return false;
        }
        return false;
    }

    /**
     * 1664. 生成平衡数组的方案数
     * @param Integer[] $nums
     * @return Integer
     */
    function waysToMakeFair($nums)
    {
        $n = count($nums);
        $dp = array_fill(0, $n + 1, 0);
        $ans = 0;
        for ($i = 1; $i < $n + 1; ++$i) {
            $dp[$i] = $dp[$i - 1] + ($i % 2 ? $nums[$i - 1] : -$nums[$i - 1]);
        }
        for ($i = 1; $i < $n + 1; ++$i) {
            if ($dp[$i - 1] == $dp[$n] - $dp[$i])
                $ans++;
        }
        return $ans;

        // 我就是不会动态规划 的具象思维
        $n = count($nums);
        if ($n < 1) return 0;
        if ($n == 1) return 1;
        if ($n == 2) return 0;

        $sum[-2] = 0;
        $sum[-1] = 0;
        for ($i = 0; $i < $n; $i++) {
            $sum[$i] = $nums[$i] + $sum[$i - 2];
        }
        // print_r($sum);
        $c = 0;
        $last[0] = $sum[$n - 1 - ($n - 1) % 2];//最后一个序数余0则就是最后一个，余1则前一个
        $last[1] = $sum[$n - 1 - $n % 2];
        foreach ($nums as $i => $num) {
            //remove $num
            $cur = $i % 2;
            $other = ($i + 1) % 2;
            //echo $sum[$i] - $num + $last[$other] - $sum[$i - 1] ,'==', $sum[$i - 1] + $last[$cur] - $sum[$i], " ---$i\n";
            if ($sum[$i] - $num + $last[$other] - $sum[$i - 1] == $sum[$i - 1] + $last[$cur] - $sum[$i]) $c++;
        }
        return $c;
    }
}
