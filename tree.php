<?php

require_once 'node-tree-map.lib.php';

class Solution
{
    private $paths = [];

    /**
     * 257. 二叉树的所有路径
     * @param TreeNode $root
     * @return String[]
     */
    function binaryTreePaths($root)
    {
        if (!$root) return [];
        $this->binaryTreePathsDFS($root);
        return $this->paths;
    }

    function binaryTreePathsDFS($root, $path = '')
    {
        // 理顺后记住这个套路，这里区分了一下isRoot
        $path = $path ? $path . '->' . $root->val : '' . $root->val;
        if (!$root->left && !$root->right) return $this->paths[] = $path;
        if ($root->left) $this->binaryTreePathsDFS($root->left, $path);
        if ($root->right) $this->binaryTreePathsDFS($root->right, $path);
    }

    /**
     * 链表是否有环
     * @param ListNode $head
     * @return Boolean
     */
    function hasCycle($head)
    {
        # 快慢指针，很巧妙
        if (!$head || !$head->next) return false;
        $slow = $fast = $head;
        while ($fast || $fast->next) {
            $fast = $fast->next->next;
            $slow = $slow->next;
            if ($fast === $slow) return true;
        }
        return false;
    }

    /**
     * 回文链表
     * @param ListNode $head
     * @return Boolean
     */
    function isPalindrome2($head)
    {
        if (!$head) return true;
        $arr = [];
        while ($head) {
            $arr[] = $head->val;
            $head = $head->next;
        }
        $i = 0;
        $j = count($arr) - 1;
        while ($i <= $j && $arr[$i] == $arr[$j]) {
            // i,j不要冒进，==才继续加减
            $i++;
            $j--;
        }
        echo "$i $j";
        return $j <= $i;
    }

    /**合并两个有序链表
     * @param ListNode $l1
     * @param ListNode $l2
     * @return ListNode
     */
    function mergeTwoLists($l1, $l2)
    {
        // 加入辅导员，一切都简单化了！高级
        // 不能$p=$l1;因为这是指针，不会拷贝
        $head = $p = new ListNode(0);
        while ($l1 && $l2) {
            if ($l1->val <= $l2->val) {
                $pick = $l1;
                $l1 = $l1->next;
            } else {
                $pick = $l2;
                $l2 = $l2->next;
            }
            $p->next = $pick;
            $p = $pick;
        }
        $p->next = $l1 ? $l1 : $l2;
        return $head->next;

        # 老实解法，啰嗦了
        if (!$l1) return $l2;
        if (!$l2) return $l1;
        if ($l1->val <= $l2->val) {
            $p = $l1;
            $l1 = $l1->next;
        } else {
            $p = $l2;
            $l2 = $l2->next;
        }
        $head = $p;
        while ($l1 && $l2) {
            if ($l1->val <= $l2->val) {
                $pick = $l1;
                $l1 = $l1->next;
            } else {
                $pick = $l2;
                $l2 = $l2->next;
            }
            $p->next = $pick;
            $p = $pick;
        }
        $p->next = $l1 ? $l1 : $l2;
        return $head;
    }

    /**
     * 反转一个单链表
     * 注意区分引用地址和引用值
     * @param ListNode $head
     * @return ListNode
     */
    function reverseList($head)
    {
        $prev = null;
        while ($head) {
            $next = $head->next;
            $head->next = $prev;
            // if(!$next)return $head;//这一句每次执行，浪费时间
            $prev = $head;
            $head = $next;
        }
        return $prev;
    }

    /**
     * 19. 删除链表的倒数第N个节点
     * @param ListNode $head
     * @param Integer $n
     * @return ListNode
     */
    function removeNthFromEnd($head, $n)
    {
        # 辅助的虚拟头部，高级！
        $dummyHead = new ListNode(0);
        $dummyHead->next = $head;
        $p = $q = $dummyHead;
        for ($i = 0; $i < $n + 1; $i++) {
            $q = $q->next;
        }
        while ($q) {
            $q = $q->next;
            $p = $p->next;
        }
        $p->next = $p->next->next;
        return $dummyHead->next;

        # 分析后分别处理删除头部尾部的情况，开始没有理清楚，提交了几次还在固执而糊涂地改代码。
        $i = 1;
        $prevN = $prev = $p = $head;
        while ($p) {
            if ($i > $n) $prevN = $prevN->next;
            $i++;
            if ($p->next) $prev = $p;
            $p = $p->next;
        }
        $total = $i - 1;
        //理清楚不容易：4种情况，删唯一一个，删头部，删最后一个，删中间
        if ($total == 1) return null;
        if ($n == $total) return $head->next;
        if ($n == 1) {
            $prev->next = null;
        } else {
            $prevN->val = $prevN->next->val;
            $prevN->next = $prevN->next->next;
        }
        return $head;
    }

    /**
     * 对称二叉树
     *
     * @param TreeNode $root
     * @return Boolean
     */
    function isSymmetric($root)
    {
        if ($root == null) return true;

        return $this->isSymmetricHelper($root->left, $root->right);
        return $this->isSymmetricHelper($root, $root);//牛逼

        #不用递归，重复代码太多
        if (!$root) return true;
        if ($root->left xor $root->right) return false;
        $nodes = [[$root->left, $root->right]];
        while ($pair = array_pop($nodes)) {
            list($l, $r) = $pair;

            if ($l->val != $r->val) return false;
            //if (($l && !$r) || ($r && !$l) || $l->val != $r->val) return false;
            if ($l->right xor $r->left) return false;
            if ($l->right and $r->left) $nodes[] = [$l->right, $r->left];
            if ($l->left xor $r->right) return false;
            if ($l->left and $r->right) $nodes[] = [$l->left, $r->right];
        }
        return true;
    }

    function isSymmetricHelper($leftChild, $rightChild)
    {
        // 学到这种写法 xor 之替代！
        if ($leftChild == null && $rightChild == null) return true;
        if ($leftChild == null || $rightChild == null) return false;

        return $leftChild->val == $rightChild->val
            && $this->isSymmetricHelper($leftChild->left, $rightChild->right)
            && $this->isSymmetricHelper($leftChild->right, $rightChild->left);
    }

    /**
     * 111. 二叉树的最小深度
     * @param TreeNode $root
     * @return Integer
     */
    function minDepth($root)
    {
        # dfs会全部遍历为什么更快？
        if (!$root) return 0;
        $left = $this->minDepth($root->left);
        $right = $this->minDepth($root->right);

        if (!$left || !$right) {
            return $left + $right + 1;
        } else {
            return min($left, $right) + 1;
        }

        # bfs
        if (!$root) return 0;
        $level = 1;
        $nodes = [$root];
        while ($nodes) {
            $children = [];
            foreach ($nodes as $node) {
                if (!$node->left && !$node->right) return $level;
                if ($node->left) $children[] = $node->left;
                if ($node->right) $children[] = $node->right;
            }
            $level++;
            $nodes = $children;
        }
        return $level;
    }


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

    /**
     * 109. 有序链表转换二叉搜索树
     * 快慢指针，学过；需要记录前面
     * 还可以用数组来记录所以节点
     * @param ListNode $head
     * @return TreeNode
     */
    function sortedListToBST($head)
    {
        if (!$head) return null;
        if (!$head->next) return new TreeNode($head->val);
        $pre = $head;
        $p = $pre->next;
        $q = $p->next;
        while ($q && $q->next) {
            $pre = $pre->next;
            $p = $pre->next;
            $q = $q->next->next;
        }
        $pre->next = null;
        $root = new TreeNode($p->val);
        $root->left = $this->sortedListToBST($head);
        $root->right = $this->sortedListToBST($p->next);
        return $root;
    }

    private $prev;

    /**
     * 98. 验证二叉搜索树:左>root>右所有!
     *
     * @param TreeNode $root
     * @param int $min PHP_INT_MIN <- 等于最小值就是bug,还得用null
     * @param int $max PHP_INT_MAX
     * @return Boolean
     */
    function isValidBST($root, $min = null, $max = null)
    {
        # 递归法，思路更清晰
        if (!$root) return true;
        if (!is_null($min) && $root->val <= $min) return false;
        if (!is_null($max) && $root->val >= $max) return false;
        return $this->isValidBST($root->left, $min, $root->val)
            && $this->isValidBST($root->right, $root->val, $max);

        # 中序遍历 
        return $this->inOrder($root);
    }

    // 中序遍历
    function inOrder($root)
    {
        if (!$root) return true;
        if (!$this->inOrder($root->left)) return false;
        if (!is_null($this->prev) && $this->prev >= $root->val) return false;
        $this->prev = $root->val;
        return $this->inOrder($root->right);
    }

    /**
     * 235. 二叉搜索树的最近公共祖先
     * root = [6,2,8,0,4,7,9,null,null,3,5], p = 2, q = 4 输出: 2
     * @param TreeNode $root
     * @param TreeNode $p
     * @param TreeNode $q
     * @return TreeNode|null
     */
    function lowestCommonAncestorBST($root, $p, $q)
    {
        # 优化
        //$min = min($p->val, $q->val);
        //$max = max($p->val, $q->val);
        if ($p->val > $q->val) {
            $min = $q->val;
            $max = $p->val;
        } else {
            $min = $p->val;
            $max = $q->val;
        }
        // find node(min<node<max)
        while ($root) {
            if ($root->val > $max) $root = $root->left;
            elseif ($root->val < $min) $root = $root->right;
            else return $root;
        }
        return $root;

        # 不用递归
        while ($root) {
            if ($root->val > $p->val && $root->val > $q->val) $root = $root->left;
            elseif ($root->val < $p->val && $root->val < $q->val) $root = $root->right;
            else return $root;
        }
        return $root;

        # 递归 为什么还快一点点？
        if ($q->val > $root->val && $p->val > $root->val) {
            //p和q的值都大于根节点的值,说明在右边
            return $this->lowestCommonAncestorBST($root->right, $p, $q);
        } elseif ($q->val < $root->val && $p->val < $root->val) {
            //p和q的值都小于根节点的值,说明在左边
            return $this->lowestCommonAncestorBST($root->left, $p, $q);
        } else {
            //q或者q的值等于root的值,或者是 q和p在root的左右两边时,就为最近的公共祖先
            return $root;
        }
    }

    /**
     * 236. 二叉树的最近公共祖先（区别：必须要找到；搜索树是有序的，只用看范围比大小）
     * root = [3,5,1,6,2,0,8,null,null,7,4], p = 5, q = 4 输出: 5
     * @param TreeNode $root
     * @param TreeNode $p
     * @param TreeNode $q
     * @return TreeNode|null
     */
    function lowestCommonAncestor($root, $p, $q)
    {
        // 不要用=== @see https://www.php.net/manual/zh/language.oop5.object-comparison.php
        // 在左右子树找到p或q，再最终判断，这个思路很高级啊！一般只会想找一个。。然后不容易理清。。
        if (!$root || $root == $q || $root == $p) return $root;
        $left = $this->lowestCommonAncestor($root->left, $p, $q);
        $right = $this->lowestCommonAncestor($root->right, $p, $q);
        // 三元表达式 组合，不加括号的话总是记不住优先顺序。。
        return $left ? ($right ? $root : $left) : $right;
    }

    /**
     * 110. 是否平衡二叉树
     * 我喜欢代码简洁，但也不最极客
     * @param TreeNode $root
     * @return Boolean
     */
    function isBalanced($root)
    {
        return $this->height($root) > -1;

        # 缺点：level对同一节点会重复计算
        if (!$root) return true;
        return $this->isBalanced($root->left)
            && $this->isBalanced($root->right)
            && abs($this->level($root->left) - $this->level($root->right)) <= 1;
    }

    function level($root)
    {
        if (!$root) return 0;
        return max($this->level($root->left), $this->level($root->right)) + 1;
    }

    // 不平衡返回-1
    function height($root)
    {
        if (!$root) return 0;
        //echo ".{$root->val}\n";
        if (0 > $lh = $this->height($root->left)) return -1;
        if (0 > $rh = $this->height($root->right)) return -1;
        return abs($lh - $rh) <= 1 ? max($lh, $rh) + 1 : -1;
    }


    /**
     * 133. 克隆图
     * @param Node $node
     * @return Node
     */
    function cloneGraph($node)
    {
        if (!$node) return null;

        $nodes = [];//已克隆标记
        $_nodes = [$node];//待克隆
        while ($_node = array_shift($_nodes)) {
            if (!empty($nodes[$_node->val])) continue;
            $nodes[$_node->val] = new Node($_node->val);
            foreach ($_node->neighbors as $neighbor) {
                if (empty($nodes[$neighbor->val])) $nodes[$neighbor->val] = 0;
                $nodes[$_node->val]->neighbors[] = &$nodes[$neighbor->val];
                $_nodes[] = $neighbor;
            }
        }
        /*
        ksort($nodes);
        foreach ($nodes as $i => $item) {
            foreach ($item->neighbors as $neighbor) {
                echo $neighbor->val, ' ';
            }
            echo "\n";
        }
        */
        return $nodes[$node->val];

        // var_dump(($this->Node2array($node)));
        //return $this->array2Node($this->Node2array($node));
    }

    ////////////////
    private $n1, $n2, $pre;

    /**
     * 99. 恢复二叉搜索树
     * @param TreeNode $root
     * @return TreeNode
     */
    function recoverTree($root)
    {
        $this->recoverNode($root);
        if ($this->n1) {
            $tmp = $this->n1->val;
            $this->n1->val = $this->n2->val;
            $this->n2->val = $tmp;
        }
        return $root;
    }

    function recoverNode($root)
    {
        if (is_null($root)) return;
        $this->recoverNode($root->left);
        if ($this->pre && $this->pre->val > $root->val) {
            if (!$this->n1) $this->n1 = $this->pre;
            $this->n2 = $root;
        }
        $this->pre = $root;
        $this->recoverNode($root->right);
        return;
        # 下面代码保留，就是没有树的常规认识，没做出来
        $p = null;
        if ($root->left) {
            if ($root->right && $root->left->val > $root->right->val) {
                return [$root->left, $root->right];
            }
            if ($root->left->right && $root->left->right->val > $root->val) {
                return [$root, $root->left->right];
            }
            if ($root->left->left && $root->left->left->val > $root->val) {
                return [$root, $root->left->left];
            }
            if ($root->left->val > $root->val) {
                return [$root, $root->left];
            }
            if ($r = $this->recoverNode($root->left)) {
                return $r;
            }
        }
        if ($root->right) {
            if ($root->right->left && $root->right->left->val < $root->val) {
                return [$root, $root->right->left];
            }
            if ($root->right->right && $root->right->right->val < $root->val) {
                return [$root, $root->right->right];
            }
            if ($root->right->val < $root->val) {
                return [$root, $root->right];
            }
            if ($r = $this->recoverNode($root->right)) {
                return $r;
            }
        }
        return null;
    }

    /**
     * @param TreeNode $p
     * @param TreeNode $q
     * @return Boolean
     */
    function isSameTree($p, $q)
    {
        // 这个最简单
        return serialize($p) == serialize($q);

        // if((!$p && $q ) || ($p && !$q)){
        //     return false;
        // }
        // if($p->val != $q->val){
        //     return false;
        // }
        // if($p == null && $q == null){
        //     return true;
        // }
        // return $this->isSameTree($p->left,$q->left) && $this->isSameTree($p->right,$q->right);
        // 上面代码看起来清楚些

        // 以p为标准。。
        // p空，则返回q是否也是空
        if (!$p) return is_null($q);
        // p不空，则q不能为空，值等，孩子等
        return $q && $p->val == $q->val && $this->isSameTree($p->left, $q->left) && $this->isSameTree($p->right, $q->right);
    }

    /**
     * 2. 两数相加 链表
     * 逻辑很简单，编码不容易
     * @param ListNode $l1
     * @param ListNode $l2
     * @return ListNode
     */
    function addTwoNumbers($l1, $l2)
    {
        $p = $l1;
        $p2 = $l2;
        $add = 0;
        while ($p && ($p2 || $add)) {
            if ($p2) {
                $add += $p2->val;
                $p2 = $p2->next;
            }
            $p->val += $add;

            if ($p->val >= 10) {
                $add = 1;
                $p->val -= 10;
            } else {
                $add = 0;
            }

            if ($p->next) {
                $p = $p->next;
            } elseif ($p2) {
                $p->next = $p2;
                $p = $p2;
                $p2 = null;
            } else {
                if ($add) $p->next = new ListNode(1);
                break;
            }
        }

        return $l1;
    }

    /**
     * 114. 二叉树展开为链表
     * 感觉很简单，但没对
     * - 没搞懂判定是怎么提供输入和输出的
     * - AB*01的逻辑老是没理顺！
     * @param TreeNode $root
     * @return NULL
     */
    function flatten($root)
    {
        # 无递归，牛，逐层调整
        while ($root != null) {
            if ($root->left != null) {
                $tmp = $root->left;
                while ($tmp->right != null) $tmp = $tmp->right;
                $tmp->right = $root->right;
                $root->right = $root->left;
                $root->left = null;
            }
            $root = $root->right;
        }
        //var_dump($root);
        return $root;//这里返回null啊。。没搞懂判定是怎么获取输出的。。

        # 递归，整理左边，整理右边，再调整
        if (!($root->left || $root->right)) return $root;
        $root->right && $this->flatten($root->right);
        if ($p = $root->left) {
            $this->flatten($root->left);
            while ($p->right) {
                $p = $p->right;
            }
            $p->right = $root->right;
            $root->right = $root->left;
            $root->left = null;
        }
        return $root;
    }
}
