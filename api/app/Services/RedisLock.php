<?php namespace App\Services;

use Illuminate\Support\Facades\Redis;

class RedisLock
{
    private $key;
    private $expire;

    public function __construct($key, $expire)
    {
        $this->key = $key;
        $this->expire = $expire;
    }

    public function acquire()
    {
        $result = Redis::set($this->key, 1, 'EX', $this->expire, 'NX');
        return $result ? true : false;
    }

    public function release()
    {
        Redis::del($this->key);
    }
}
