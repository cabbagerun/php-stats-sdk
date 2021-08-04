<?php

namespace Jianzhi\Stats\base;

class RedisBase extends Base
{
    private $redis;
    //实例化的对象,单例模式.
    static private $_instance = [];
    private        $k;
    //当前地址
    protected static $host = REDIS_HOST;
    //当前端口
    protected static $port = REDIS_PORT;
    //当前权限认证码
    protected static $password = REDIS_PASSWORD;
    //key前缀
    protected static $prefix = REDIS_PREFIX;
    //当前数据库
    private $select = 0;
    //连接属性数组
    protected $attr = [
        'select'     => REDIS_SELECT,//选择的数据库
        'timeout'    => 30,//连接超时时间，redis配置文件中默认为300秒
        'persistent' => false,//是否长连接
    ];
    //什么时候重新建立连接
    protected $expireTime;

    /**
     * 连接数据库
     * @param array $config [host,port,password]
     * @param array $attr [select,timeout,persistent]
     */
    private function connect(array $config = [], array $attr = [])
    {
        if (extension_loaded('redis')) {
            $this->attr       = array_merge($this->attr, $attr);
            $this->expireTime = time() + $this->attr['timeout'];
            $redisCnf = $this->redisConfig();
            self::$host       = $config['host'] ?? ($redisCnf['host'] ?? '127.0.0.1');
            self::$port       = $config['port'] ?? ($redisCnf['port'] ?? 6379);
            self::$password   = $config['password'] ?? ($redisCnf['password'] ?? '');
            $this->redis      = new \Redis();
            if (isset($this->attr['persistent']) && $this->attr['persistent']) {
                $persistentId = 'persistent_id_' . ($this->attr['select'] ?? REDIS_SELECT);
                $this->redis->pconnect(self::$host, self::$port, $this->attr['timeout'], $persistentId);
            } else {
                $this->redis->connect(self::$host, self::$port, $this->attr['timeout']);
            }
            if (self::$password) {
                $this->auth(self::$password);
            }
        } else {
            throw new \BadFunctionCallException('not support: redis');
        }
    }

    /**
     * 获取实例化
     * @param array $config
     * @param array $attr
     * @return RedisBase
     */
    public static function instance(array $config = [], array $attr = [])
    {
        $attr['select'] = $attr['select'] ?? REDIS_SELECT;
        $k              = md5(implode('', $config) . $attr['select']);
        if (!isset(self::$_instance[$k]) || !(self::$_instance[$k] instanceof self)) {
            self::$_instance[$k] = new self();
            self::$_instance[$k]->connect($config, $attr);
            self::$_instance[$k]->k      = $k;
            self::$_instance[$k]->k      = $k;
            self::$_instance[$k]->select = $attr['select'];
            //如果不是0号库，选择一下数据库。
            if ($attr['select'] != 0) {
                self::$_instance[$k]->select($attr['select']);
            }
        } elseif (time() > self::$_instance[$k]->expireTime) {
            self::$_instance[$k]->close();
            self::$_instance[$k] = new self();
            self::$_instance[$k]->connect($config, $attr);
            self::$_instance[$k]->k      = $k;
            self::$_instance[$k]->select = $attr['select'];
            //如果不是0号库，选择一下数据库。
            if ($attr['select'] != 0) {
                self::$_instance[$k]->select($attr['select']);
            }
        }
        return self::$_instance[$k];
    }

    /*****************hash表操作函数*******************/

    /**
     * Increment the number stored at key by one
     * @param $key
     * @return mixed
     */
    public function incr($key)
    {
        return $this->redis->incr($key);
    }

    /**
     * Decrement the number stored at key by one.
     * @param $key
     * @return mixed
     */
    public function decr($key)
    {
        return $this->redis->decr($key);
    }

    /**
     * 得到hash表中一个字段的值
     * @param string $key 缓存key
     * @param string $field 字段
     * @return string|false
     */
    public function hGet($key, $field)
    {
        return $this->redis->hGet($key, $field);
    }

    /**
     * 为hash表设定一个字段的值
     * @param string $key 缓存key
     * @param string $field 字段
     * @param string $value 值。
     * @return bool
     */
    public function hSet($key, $field, $value)
    {
        return $this->redis->hSet($key, $field, $value);
    }

    /**
     * 判断hash表中，指定field是不是存在
     * @param string $key 缓存key
     * @param string $field 字段
     * @return bool
     */
    public function hExists($key, $field)
    {
        return $this->redis->hExists($key, $field);
    }

    /**
     * 删除hash表中指定字段 ,支持批量删除
     * @param string $key 缓存key
     * @param string $field 字段
     * @return int
     */
    public function hdel($key, $field)
    {
        $fieldArr = explode(',', $field);
        $delNum   = 0;

        foreach ($fieldArr as $row) {
            $row    = trim($row);
            $delNum += $this->redis->hDel($key, $row);
        }

        return $delNum;
    }

    /**
     * 返回hash表元素个数
     * @param string $key 缓存key
     * @return int|bool
     */
    public function hLen($key)
    {
        return $this->redis->hLen($key);
    }

    /**
     * 为hash表设定一个字段的值,如果字段存在，返回false
     * @param string $key 缓存key
     * @param string $field 字段
     * @param string $value 值。
     * @return bool
     */
    public function hSetNx($key, $field, $value)
    {
        return $this->redis->hSetNx($key, $field, $value);
    }

    /**
     * 为hash表多个字段设定值。
     * @param string $key
     * @param array $value
     * @return array|bool
     */
    public function hMset($key, $value)
    {
        if (!is_array($value)) {
            return false;
        }
        return $this->redis->hMset($key, $value);
    }

    /**
     * 为hash表多个字段设定值。
     * @param string $key
     * @param array|string $value string以','号分隔字段
     * @return array|bool
     */
    public function hMget($key, $field)
    {
        if (!is_array($field)) {
            $field = explode(',', $field);
        }
        return $this->redis->hMget($key, $field);
    }

    /**
     * 为hash表设这累加，可以负数
     * @param string $key
     * @param int $field
     * @param string $value
     * @return bool
     */
    public function hIncrBy($key, $field, $value)
    {
        $value = intval($value);
        return $this->redis->hIncrBy($key, $field, $value);
    }

    /**
     * 返回所有hash表的所有字段
     * @param string $key
     * @return array|bool
     */
    public function hKeys($key)
    {
        return $this->redis->hKeys($key);
    }

    /**
     * 返回所有hash表的字段值，为一个索引数组
     * @param string $key
     * @return array|bool
     */
    public function hVals($key)
    {
        return $this->redis->hVals($key);
    }

    /**
     * 返回所有hash表的字段值，为一个关联数组
     * @param string $key
     * @return array|bool
     */
    public function hGetAll($key)
    {
        $keys = $this->redis->hKeys($key);
        $hash = [];
        if ($keys) {
            foreach ($keys as $k) {
                $hash[$k] = $this->redis->hGet($key, $k);
            }
        }
        return $hash;
        //return $this->redis->hGetAll($key);
    }

    /*********************有序集合操作*********************/

    /**
     * 给当前集合添加一个元素
     * 如果value已经存在，会更新order的值。
     * @param string $key
     * @param string $order 序号
     * @param string $value 值
     * @return bool
     */
    public function zAdd($key, $order, $value)
    {
        return $this->redis->zAdd($key, $order, $value);
    }

    /**
     * 给当前集合添加多个元素
     * @param array $data array('key', score1, value1, score2, value2)
     * @return mixed
     */
    public function batchZAdd($data)
    {
        return call_user_func_array(array($this->redis, 'zadd'), $data);
    }

    /**
     * 给$value成员的order值，增加$num,可以为负数
     * @param string $key
     * @param string $num 序号
     * @param string $value 值
     * @return 返回新的order
     */
    public function zinCry($key, $num, $value)
    {
        return $this->redis->zIncrBy($key, $num, $value);
    }

    /**
     * 删除值为value的元素
     * @param string $key
     * @param stirng $value
     * @return bool
     */
    public function zRem($key, $value)
    {
        return $this->redis->zRem($key, $value);
    }

    /**
     * 集合以order递增排列后，0表示第一个元素，-1表示最后一个元素
     * @param string $key
     * @param int $start
     * @param int $end
     * @return array|bool
     */
    public function zRange($key, $start, $end)
    {
        return $this->redis->zRange($key, $start, $end);
    }

    /**
     * 集合以order递减排列后，0表示第一个元素，-1表示最后一个元素
     * @param string $key
     * @param int $start
     * @param int $end
     * @return array|bool
     */
    public function zRevRange($key, $start, $end)
    {
        return $this->redis->zRevRange($key, $start, $end);
    }

    /**
     * 集合以order递增排列后，返回指定order之间的元素。
     * min和max可以是-inf和+inf　表示最大值，最小值
     * @param string $key
     * @param int $start
     * @param int $end
     * @return array|bool
     * @package array $option 参数
     *     withscores=>true，表示数组下标为Order值，默认返回索引数组
     *     limit=>array(0,1) 表示从0开始，取一条记录。
     */
    public function zRangeByScore($key, $start = '-inf', $end = "+inf", $option = array())
    {
        return $this->redis->zRangeByScore($key, $start, $end, $option);
    }

    /**
     * 集合以order递减排列后，返回指定order之间的元素。
     * min和max可以是-inf和+inf　表示最大值，最小值
     * @param string $key
     * @param int $start
     * @param int $end
     * @return array|bool
     * @package array $option 参数
     *     withscores=>true，表示数组下标为Order值，默认返回索引数组
     *     limit=>array(0,1) 表示从0开始，取一条记录。
     */
    public function zRevRangeByScore($key, $start = '-inf', $end = "+inf", $option = array())
    {
        return $this->redis->zRevRangeByScore($key, $start, $end, $option);
    }

    /**
     * 返回order值在start end之间的数量
     * @param $key
     * @param $start
     * @param $end
     */
    public function zCount($key, $start, $end)
    {
        return $this->redis->zCount($key, $start, $end);
    }

    /**
     * 返回值为value的order值
     * @param $key
     * @param $value
     */
    public function zScore($key, $value)
    {
        return $this->redis->zScore($key, $value);
    }

    /**
     * 返回集合以score递增加排序后，指定成员的排序号，从0开始。
     * @param $key
     * @param $value
     */
    public function zRank($key, $value)
    {
        return $this->redis->zRank($key, $value);
    }

    /**
     * 返回集合以score递增加排序后，指定成员的排序号，从0开始。
     * @param $key
     * @param $value
     */
    public function zRevRank($key, $value)
    {
        return $this->redis->zRevRank($key, $value);
    }

    /**
     * 删除集合中，score值在start end之间的元素　包括start end
     * min和max可以是-inf和+inf　表示最大值，最小值
     * @param $key
     * @param $start
     * @param $end
     * @return 删除成员的数量。
     */
    public function zRemRangeByScore($key, $start, $end)
    {
        return $this->redis->zRemRangeByScore($key, $start, $end);
    }

    /**
     * 返回集合元素个数。
     * @param $key
     */
    public function zCard($key)
    {
        return $this->redis->zCard($key);
    }
    /*********************队列操作命令************************/

    /**
     * 在队列尾部插入一个元素
     * @param $key
     * @param $value
     * 返回队列长度
     */
    public function rPush($key, $value)
    {
        return $this->redis->rPush($key, $value);
    }

    /**
     * 在队列尾部插入一个元素 如果key不存在，什么也不做
     * @param $key
     * @param $value
     * 返回队列长度
     */
    public function rPushx($key, $value)
    {
        return $this->redis->rPushx($key, $value);
    }

    /**
     * 在队列头部插入一个元素
     * @param $key
     * @param $value
     * 返回队列长度
     */
    public function lPush($key, $value)
    {
        return $this->redis->lPush($key, $value);
    }

    /**
     * 在队列头插入一个元素 如果key不存在，什么也不做
     * @param $key
     * @param $value
     * 返回队列长度
     */
    public function lPushx($key, $value)
    {
        return $this->redis->lPushx($key, $value);
    }

    /**
     * 返回队列长度
     * @param $key
     */
    public function lLen($key)
    {
        return $this->redis->lLen($key);
    }

    /**
     * 返回队列指定区间的元素
     * @param $key
     * @param $start
     * @param $end
     */
    public function lRange($key, $start, $end)
    {
        return $this->redis->lrange($key, $start, $end);
    }

    /**
     * 返回队列中指定索引的元素
     * @param $key
     * @param $index
     */
    public function lIndex($key, $index)
    {
        return $this->redis->lIndex($key, $index);
    }

    /**
     * 设定队列中指定index的值。
     * @param $key
     * @param $index
     * @param $value
     */
    public function lSet($key, $index, $value)
    {
        return $this->redis->lSet($key, $index, $value);
    }

    /**
     * 删除值为vaule的count个元素
     * PHP-REDIS扩展的数据顺序与命令的顺序不太一样，不知道是不是bug
     * count>0 从尾部开始
     *  >0　从头部开始
     *  =0　删除全部
     * @param $key
     * @param $count
     * @param $value
     */
    public function lRem($key, $count, $value)
    {
        return $this->redis->lRem($key, $value, $count);
    }

    /**
     * 删除并返回队列中的头元素。
     * @param $key
     */
    public function lPop($key)
    {
        return $this->redis->lPop($key);
    }

    /**
     * 删除并返回队列中的尾元素
     * @param $key
     */
    public function rPop($key)
    {
        return $this->redis->rPop($key);
    }

    /**
     * 删除并返回队列中的尾元素(堵塞)
     * @param $key
     * @param $timeout
     */
    public function brPop($key, $timeout)
    {
        return $this->redis->brPop($key, $timeout);
    }

    /**
     * 对一个列表进行修剪(trim)，就是说，让列表只保留指定区间内的元素，不在指定区间之内的元素都将被删除。
     * @param $key
     * @param $start
     * @param $stop
     * @return mixed
     */
    public function lTrim($key, $start, $stop)
    {
        return $this->redis->lTrim($key, $start, $stop);
    }

    /*************redis字符串操作命令*****************/


    /**
     * Set the string value in argument as value of the key.
     *
     * @since If you're using Redis >= 2.6.12, you can pass extended options as explained in example
     *
     * @param string $key
     * @param string|mixed $value string if not used serializer
     * @param int|array $timeout [optional] Calling setex() is preferred if you want a timeout.<br>
     * Since 2.6.12 it also supports different flags inside an array. Example ['NX', 'EX' => 60]<br>
     *  - EX seconds -- Set the specified expire time, in seconds.<br>
     *  - PX milliseconds -- Set the specified expire time, in milliseconds.<br>
     *  - PX milliseconds -- Set the specified expire time, in milliseconds.<br>
     *  - NX -- Only set the key if it does not already exist.<br>
     *  - XX -- Only set the key if it already exist.<br>
     * <pre>
     * // Simple key -> value set
     * $redis->set('key', 'value');
     *
     * // Will redirect, and actually make an SETEX call
     * $redis->set('key','value', 10);
     *
     * // Will set the key, if it doesn't exist, with a ttl of 10 seconds
     * $redis->set('key', 'value', ['nx', 'ex' => 10]);
     *
     * // Will set a key, if it does exist, with a ttl of 1000 miliseconds
     * $redis->set('key', 'value', ['xx', 'px' => 1000]);
     * </pre>
     *
     * @return bool TRUE if the command is successful
     *
     * @link     https://redis.io/commands/set
     */
    public function set($key, $value, $timeout = null)
    {
        return $this->redis->set($key, $value, $timeout);
    }

    /**
     * 得到一个key
     * @param string $key
     */
    public function get($key)
    {
        return $this->redis->get($key);
    }

    /**
     * 设置一个有过期时间的key
     * @param string $key
     * @param string|int $expire
     * @param string $value
     */
    public function setex($key, $expire, $value)
    {
        return $this->redis->setex($key, $expire, $value);
    }


    /**
     * 设置一个key,如果key存在,不做任何操作.
     * @param $key
     * @param $value
     */
    public function setnx($key, $value)
    {
        return $this->redis->setnx($key, $value);
    }

    /**
     * 批量设置key
     * @param $arr
     */
    public function mset($arr)
    {
        return $this->redis->mset($arr);
    }

    /*************redis　无序集合操作命令*****************/

    /**
     * 返回集合中所有元素
     * @param $key
     */
    public function sMembers($key)
    {
        return $this->redis->sMembers($key);
    }

    /**
     * 求2个集合的差集
     * @param $key1
     * @param $key2
     */
    public function sDiff($key1, $key2)
    {
        return $this->redis->sDiff($key1, $key2);
    }

    /**
     * 添加集合。由于版本问题，扩展不支持批量添加。这里做了封装
     * @param $key
     * @param string|array $value
     */
    public function sAdd($key, $value)
    {
        if (!is_array($value)) {
            $arr = array($value);
        } else {
            $arr = $value;
        }
        foreach ($arr as $row) {
            $this->redis->sAdd($key, $row);
        }
    }

    /**
     * 返回无序集合的元素个数
     * @param $key
     */
    public function scard($key)
    {
        return $this->redis->scard($key);
    }

    /**
     * 从集合中删除一个元素
     * @param $key
     * @param $value
     */
    public function srem($key, $value)
    {
        return $this->redis->srem($key, $value);
    }

    /**
     * 随机返回并删除名称为key的set中一个元素
     * @param $key
     * @return mixed
     */
    public function sPop($key)
    {
        return $this->redis->sPop($key);
    }

    /*************redis管理操作命令*****************/

    /**
     * 选择数据库
     * @param int $select 数据库ID号
     * @return bool
     */
    public function select($select)
    {
        $this->select = $select;
        return $this->redis->select($select);
    }

    /**
     * 清空当前数据库
     * @return bool
     */
    public function flushDB()
    {
        return $this->redis->flushDB();
    }

    /**
     * 返回当前库状态
     * @return array
     */
    public function info()
    {
        return $this->redis->info();
    }

    /**
     * 同步保存数据到磁盘
     */
    public function save()
    {
        return $this->redis->save();
    }

    /**
     * 异步保存数据到磁盘
     */
    public function bgSave()
    {
        return $this->redis->bgSave();
    }

    /**
     * 返回最后保存到磁盘的时间
     */
    public function lastSave()
    {
        return $this->redis->lastSave();
    }

    /**
     * 返回key,支持*多个字符，?一个字符
     * 只有*　表示全部
     * @param string $key
     * @return array
     */
    public function keys($key)
    {
        return $this->redis->keys($key);
    }

    /**
     * 删除指定key
     * @param $key
     */
    public function del($key)
    {
        return $this->redis->del($key);
    }

    /**
     * 判断一个key值是不是存在
     * @param $key
     */
    public function exists($key)
    {
        return $this->redis->exists($key);
    }

    /**
     * 为一个key设定过期时间 单位为秒
     * @param string $key
     * @param string | int $expire
     */
    public function expire($key, $expire)
    {
        return $this->redis->expire($key, $expire);
    }

    /**
     * 返回一个key还有多久过期，单位秒
     * @param $key
     */
    public function ttl($key)
    {
        return $this->redis->ttl($key);
    }

    /**
     * 设定一个key什么时候过期，time为一个时间戳
     * @param $key
     * @param $time
     */
    public function exprieAt($key, $time)
    {
        return $this->redis->expireAt($key, $time);
    }

    /**
     * 关闭服务器链接
     */
    public function close()
    {
        return $this->redis->close();
    }

    /**
     * 关闭所有连接
     */
    public static function closeAll()
    {
        foreach (self::$_instance as $o) {
            if ($o instanceof self) {
                $o->close();
            }
        }
    }

    /** 这里不关闭连接，因为session写入会在所有对象销毁之后。
     * public function __destruct()
     * {
     * return $this->redis->close();
     * }
     **/
    /**
     * 返回当前数据库key数量
     */
    public function dbSize()
    {
        return $this->redis->dbSize();
    }

    /**
     * 返回一个随机key
     */
    public function randomKey()
    {
        return $this->redis->randomKey();
    }

    /**
     * 得到当前数据库ID
     * @return int
     */
    public function getselect()
    {
        return $this->select;
    }

    /**
     * 返回当前密码
     */
    public function getAuth()
    {
        return self::$password;
    }

    public function getHost()
    {
        return self::$host;
    }

    public function getPort()
    {
        return self::$port;
    }

    public function getConnInfo()
    {
        return array(
            'host'     => self::$password,
            'port'     => self::$host,
            'password' => self::$port
        );
    }
    /*********************事务的相关方法************************/

    /**
     * 监控key,就是一个或多个key添加一个乐观锁
     * 在此期间如果key的值如果发生的改变，刚不能为key设定值
     * 可以重新取得Key的值。
     * @param $key
     */
    public function watch($key)
    {
        return $this->redis->watch($key);
    }

    /**
     * 取消当前链接对所有key的watch
     *  EXEC 命令或 DISCARD 命令先被执行了的话，那么就不需要再执行 UNWATCH 了
     */
    public function unwatch()
    {
        return $this->redis->unwatch();
    }

    /**
     * 开启一个事务
     * 事务的调用有两种模式Redis::MULTI和Redis::PIPELINE，
     * 默认是Redis::MULTI模式，
     * Redis::PIPELINE管道模式速度更快，但没有任何保证原子性有可能造成数据的丢失
     */
    public function multi($type = \Redis::MULTI)
    {
        return $this->redis->multi($type);
    }

    /**
     * 执行一个事务
     * 收到 EXEC 命令后进入事务执行，事务中任意命令执行失败，其余的命令依然被执行
     */
    public function exec()
    {
        return $this->redis->exec();
    }

    /**
     * 回滚一个事务
     */
    public function discard()
    {
        return $this->redis->discard();
    }

    /**
     * 测试当前链接是不是已经失效
     * 没有失效返回+PONG
     * 失效返回false
     */
    public function ping()
    {
        return $this->redis->ping();
    }

    public function auth($password)
    {
        return $this->redis->auth($password);
    }
    /*********************自定义的方法,用于简化操作************************/

    /**
     * 得到一组的ID号
     * @param $prefix
     * @param $ids
     */
    public function hashAll($prefix, $ids)
    {
        if ($ids == false) {
            return false;
        }
        if (is_string($ids)) {
            $ids = explode(',', $ids);
        }
        $arr = array();
        foreach ($ids as $id) {
            $key = $prefix . '.' . $id;
            $res = $this->hGetAll($key);
            if ($res != false) {
                $arr[] = $res;
            }
        }

        return $arr;
    }

    /**
     * 生成一条消息，放在redis数据库中。使用0号库。
     * @param string|array $msg
     */
    public function pushMessage($lkey, $msg)
    {
        if (is_array($msg)) {
            $msg = json_encode($msg);
        }
        $key = md5($msg);

        //如果消息已经存在，删除旧消息，已当前消息为准
        //echo $n=$this->lRem($lkey, 0, $key)."\n";
        //重新设置新消息
        $this->lPush($lkey, $key);
        $this->setex($key, 3600, $msg);
        return $key;
    }


    /**
     * 得到条批量删除key的命令
     * @param $keys
     * @param $select
     */
    public function delKeys($keys, $select)
    {
        $redisInfo = $this->getConnInfo();
        $cmdArr    = array(
            'redis-cli',
            '-a',
            $redisInfo['password'],
            '-h',
            $redisInfo['host'],
            '-p',
            $redisInfo['port'],
            '-n',
            $select,
        );
        $redisStr  = implode(' ', $cmdArr);
        $cmd       = "{$redisStr} KEYS \"{$keys}\" | xargs {$redisStr} del";
        return $cmd;
    }

    // 加锁
    public function lock($key, $expTime = 60)
    {
        $val = $this->redis->set($key, time() + $expTime, ['nx', 'ex' => $expTime]);

        return $val;
    }

    // 解锁
    public function unLock($key)
    {
        return $this->redis->del($key);
    }

    /**
     * @param $key
     * @param $step
     * @return mixed
     */
    public function incrBy($key, $step)
    {
        return $this->redis->incrBy($key, $step);
    }

    /**
     * @param $key
     * @param $field
     * @return mixed
     */
    public function hashDel($key, $field)
    {
        return $this->redis->hDel($key, $field);
    }

    /**
     * 添加成员的经纬度信息
     * GEOADD key 经度 纬度 member
     * @param $key
     * @param $longitude
     * @param $latitude
     * @param $member
     * @return mixed
     *
     */
    public function geoAdd($key, $longitude, $latitude, $member)
    {
        return $this->redis->geoAdd($key, $longitude, $latitude, $member);
    }

    /**
     * 计算成员间距离
     * GEODIST key member1 member2 [unit] unit 为结果单位，可选，支持：m，km，mi，ft，分别表示米（默认），千米，英里，英尺
     * @param $key
     * @param $member1
     * @param $member2
     * @return mixed
     */
    public function geoDist($key, $member1, $member2)
    {
        return $this->redis->geoDist($key, $member1, $member2);
    }

    /**
     * 基于经纬度坐标的范围查询
     * GEORADIUS key longitude latitude radius m|km|ft|mi [WITHCOORD] [WITHDIST] [WITHHASH] [COUNT count] [ASC|DESC] [STORE key] [STOREDIST key]
     * @param $key
     * @param $longitude
     * @param $latitude
     * @param $radius
     * @param $unit
     * @param $param
     * @return mixed
     */
    public function geoRadius($key, $longitude, $latitude, $radius, $unit, $param)
    {
        return $this->redis->geoRadius($key, $longitude, $latitude, $radius, $unit, $param);
    }

    /**
     * 基于成员位置范围查询
     * GEORADIUSBYMEMBER key member radius m|km|ft|mi [WITHCOORD] [WITHDIST] [WITHHASH] [COUNT count] [ASC|DESC] [STORE key] [STOREDIST ke##y]
     * @param $key
     * @param $member
     * @param $radius
     * @param $unit
     * @param array $param
     * @return mixed
     */
    public function geoRadiusByMember($key, $member, $radius, $unit, $param)
    {
        return $this->redis->geoRadiusByMember($key, $member, $radius, $unit, $param);
    }

    /**
     * 获取成员经纬度|计算经纬度Hash
     * GEOPOS key member [member …] |GEOHASH key member [member …]
     * @param string $key
     * @param array $member
     * @param string $method 获取经纬度（geoPos）获取经纬度hash(geoHash)
     * @return mixed
     */
    public function redisOption(string $key, array $member, string $method = 'geoPos')
    {
        $member = array_merge([$key], $member);
        $result = call_user_func_array(array($this->redis, $method), $member);
        return $result;
    }
}