<?php

/**
 * Trie字典树(array)
 * User: Cqiu
 * Date: 2020/11/1
 * Time: 21:29
 */
class Trie
{
    private $dict = [];

    public function getDict()
    {
        return $this->dict;
    }

    function add($word)
    {
        $p = &$this->dict;
        for ($i = 0, $n = strlen($word); $i < $n; $i++) {
            isset($p[$word[$i]]) or $p[$word[$i]] = [];
            $p = &$p[$word[$i]];
        }
        $p[0] = 0;//end
    }

    function find($word)
    {
        $p = &$this->dict;
        $i = 0;
        while (isset($word[$i]) && isset($p[$word[$i]])) {
            $p = &$p[$word[$i++]];
        }
        return !isset($word[$i]) && isset($p[0]);
    }

    // 太晚了，睡吧
    function remove($word)
    {
        $p = &$this->dict;
        $i = 0;
        while (isset($word[$i]) && isset($p[$word[$i]])) {
            $p = &$p[$word[$i++]];
        }
        if (!isset($word[$i]) && isset($p[0])) {
            unset($p[0]);
            // 这段代码写了太久。。2020-11-2
            $n = strlen($word);
            while ($n) {
                $p = &$this->dict;
                for ($i = 0; $i < $n; $i++) {
                    // not found
                    if (!isset($p[$word[$i]])) {
                        break 2;
                    }
                    if (!$p[$word[$i]]) {
                        unset($p[$word[$i]]);
                        // have 同辈
                        if ($p) break 2;
                        // 无子，无同辈，继续删
                        $n--;
                        break;
                    }
                    $p = &$p[$word[$i]];
                }
            }
            return true;
        }
        return false;
    }
}
