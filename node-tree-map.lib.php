<?php

# 无向图
class Node
{
    public $val = null;
    public $neighbors = null;

    function __construct($val = 0)
    {
        $this->val = $val;
        $this->neighbors = array();
    }
}

# 二维数组生成无向图
// [[2,4],[1,3],[2,4],[1,3]] 节点1有两邻节点2,4,...
function array2Node($arr)
{
    $nodes = [];
    foreach ($arr as $i => $nbs) {
        $nodes[$i + 1] = new Node($i + 1);
    }
    foreach ($arr as $i => $nbs) {
        foreach ($nbs as $nb) {
            $nodes[$i + 1]->neighbors[] = $nodes[$nb];
        }
    }
    return $nodes[1];
}

//var_dump(array2Node([[2, 4], [1, 3], [2, 4], [1, 3]]));
//die;
function Node2array($node)
{
    if (!$node) return [];
    $arr = [];
    $nodes = [$node];
    while ($node = array_shift($nodes)) {
        if (isset($arr[$node->val])) continue;
        $nbs = [];
        foreach ($node->neighbors as $neighbor) {
            $nbs[] = $neighbor->val;
            $nodes[] = $neighbor;
        }
        $arr[$node->val] = $nbs;
    }
    ksort($arr);
    return array_values($arr);
}

//var_dump(Node2array(array2Node([[2, 4], [1, 3], [2, 4], [1, 3]])));
//die;

class ListNode
{
    public $val = 0;
    public $next = null;

    function __construct($val)
    {
        $this->val = $val;
    }

    function toArray()
    {
        $arr = [];
        $p = $this;
        while ($p) {
            $arr[] = $p->val;
            $p = $p->next;
        }
        return $arr;
    }
}

function array2ListNode($arr)
{
    $prev = $root = new ListNode(array_shift($arr));
    foreach ($arr as $item) {
        $node = new ListNode($item);
        $prev->next = $node;
        $prev = $node;
    }
    return $root;
}

/*
// 对象一旦创建出来，就在内存中，变量只是一个引用/指针。
$list = array2ListNode([1, 2, 3]);
$head = $list;
$list = $list->next;//只是改变指针指向，原对象并没有修改
$list->next = $list->next->next;
//↑修改了对象$list，但原来的$list->next还在内存中，如果没有引用了，等待垃圾回收;相当于删除了list.next
$list->val = $list->next->val;
$list->next = $list->next->next;
//↑∴用list.next覆盖list，相当于删除了list

print_r($head->toArray());
print_r($list->toArray());

var_dump($head, $list);
*/

class TreeNode
{
    public $val = null;
    public $left = null;
    public $right = null;

    function __construct($val = 0, $left = null, $right = null)
    {
        $this->val = $val;
        $this->left = $left;
        $this->right = $right;
    }

    function toArray()
    {
        $nodes = [$this];
        $arr = [$this->val];
        while ($nodes) {
            $newNodes = [];
            foreach ($nodes as $node) {
                if ($node->left) {
                    $arr[] = $node->left->val;
                    $newNodes[] = $node->left;
                } else {
                    $arr[] = null;
                }
                if ($node->right) {
                    $arr[] = $node->right->val;
                    $newNodes[] = $node->right;
                } else {
                    $arr[] = null;
                }
            }
            $nodes = $newNodes;
        }
        $i = count($arr);
        while (is_null($arr[--$i])) unset($arr[$i]);
        return $arr;
    }
}

// 这才是leetcode上的逐层生成法
// var_dump(array2TreeNode([1, null, 2, 2, 3, null, 4]));
function array2TreeNode($arr)
{
    $i = 0;
    $n = count($arr);
    $parentNode[] = $root = new TreeNode($arr[$i]);
    while ($i < $n - 1 && $parentNode) {
        $newParentNode = [];
        foreach ($parentNode as $node) {
            if ($i++ < $n - 1) {
                is_null($arr[$i])
                    ? $node->left = null
                    : $newParentNode[] = $node->left = new TreeNode($arr[$i]);
            }
            if ($i++ < $n - 1) {
                is_null($arr[$i])
                    ? $node->right = null
                    : $newParentNode[] = $node->right = new TreeNode($arr[$i]);
            }
        }
        $parentNode = $newParentNode;
    }

    return $root;
}

/**
 * @param Integer[] $nums
 * @param int $min
 * @param null $max
 * @return TreeNode
 */
function sortedArrayToBST($nums, $min = 0, $max = null)
{
    //$self = __FUNCTION__;//这种方式倒是写方便，但执行增加了运算量
    is_null($max) && $max = count($nums) - 1;
    //$middle = intval(($min + $max) / 2);//
    $middle = ($min + $max + ($max - $min) % 2) / 2;//双数时取后面一个，这样生成子树在left，导出数组会更短
    $node = new TreeNode($nums[$middle]);
    if ($middle > $min) $node->left = sortedArrayToBST($nums, $min, $middle - 1);
    if ($middle < $max) $node->right = sortedArrayToBST($nums, $middle + 1, $max);
    return $node;
}

//var_dump(array2TreeNode([1, null, 2, 2, 3, null, 4]));
//die;

/**
 * array要求是满二叉占位表示的
 * BinaryTree::create([1, 2, 3]))
 * Class BinaryTree
 */
class BinaryTree
{
    private $arr, $len;
    public $root;

    public function __construct(array $arr)
    {
        $this->BinaryTree($arr);
    }

    public function BinaryTree(array $arr)
    {
        $this->arr = $arr;
        $this->len = count($this->arr);
        $root = new TreeNode($this->arr[0]);    //数组第一个为根结点
        $root->left = $this->generate(1);       //根据数组下标进行构建二叉树
        $root->right = $this->generate(2);
        //return $root;                           //返回根结点
        $this->root = $root;
    }

    /**
     * 构建二叉树递归函数
     * 注意，这个要求数组是满二叉的表示。。
     * @param $index
     * @return TreeNode
     */
    private function generate($index)
    {
        if (is_null($this->arr[$index])) {          //为#则构建节点后直接返回
            return null;
            $node = new TreeNode(null);
            return $node;
        }
        $node = new TreeNode($this->arr[$index]); //有值则按照具体值构建子节点
        $key = $index * 2 + 1;                    //二叉树在数组上的显示是2倍跳着的
        if ($key < $this->len) {                  //防止数组越界
            $node->left = $this->generate($key++);
        }
        if ($key < $this->len) {
            $node->right = $this->generate($key);
        }
        return $node;
    }

    public static function create(array $arr)
    {
        return (new self($arr))->root;
    }

    /**
     * 前序遍历
     * @param $root
     */
    public function getTree($root)
    {
        if ($root) {
            echo $root->val;
            $this->getTree($root->left);
            $this->getTree($root->right);
        }
    }
}
