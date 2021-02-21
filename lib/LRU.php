<?php

/**
 * 基于数组hash的简单算法，有没有问题？
 * 也许还得有链表
 */
class LRU
{
    private $cache = [];
    private $cacheTime = [];
    private $timeCache = [];
    const LENGTH = 3;
    private $time = 1;

    private function getTime()
    {
        //list($usec, $sec) = explode(" ", microtime());"$sec$usec";毫秒还是会冲突。。
        if ($this->time > self::LENGTH) $this->time = 1;
        return $this->time++;
    }

    function save($key, $value)
    {
        $this->cache[$key] = $value;
        $time = $this->getTime();
        if (isset($this->timeCache[$time])) unset($this->cache[$this->timeCache[$time]], $this->cacheTime[$this->timeCache[$time]]);
        $this->cacheTime[$key] = $time;
        $this->timeCache[$time] = $key;
        if (count($this->timeCache) > self::LENGTH) {
            reset($this->timeCache);
            $key = array_shift($this->timeCache);
            unset($this->cacheTime[$key], $this->cache[$key]);
        }
    }

    function get($key)
    {
        if (!isset($this->cache[$key])) return null;
        if (count($this->cache) > self::LENGTH) {
            unset($this->timeCache[$this->cacheTime[$key]], $this->cacheTime[$key]);
            $this->cacheTime[$key] = $time = $this->getTime();
            $this->timeCache[$time] = $key;
        }
        return $this->cache[$key];
    }

    function dd()
    {
        print_r($this->timeCache);
        print_r($this->cacheTime);
        print_r($this->cache);
    }
}

$cache = new LRU();
foreach (range(1, 6) as $i) {
    echo "save---key$i:\n";
    $cache->save("key$i", $i);
    $cache->dd();
    $x = rand(1, 6);
    echo "get---key$x:\n";
    $cache->get("key$x");
    $cache->dd();
}
