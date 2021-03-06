<?php

namespace PAO;

use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Pagination\PaginationServiceProvider;
use Illuminate\Database\Capsule\Manager as Capsule;

class Database extends Capsule
{

    public function __construct(Container $container = null)
    {

        /**
         * 初始化父方类
         */
        parent::__construct();

        /**
         * 设置配置
         */
        $database = config('database');

        /**
         * 批量加数数据连接
         */
        foreach($database as $db => $config)
        {
            if(!is_array($config)) throw new \InvalidArgumentException('The database configures was error!');
            $this->addConnection($config, $db);
        }

        /**
         * 注册数据库监听
         */
        $this->setEventDispatcher(new Dispatcher(app()));

        /**
         * 设置全局可用
         */
        $this->setAsGlobal();

        /**
         * 启动数据库
         */
        $this->bootEloquent();

        /**
         * 判断是否打开调式sql模式
         */
        if(config('app.debug') || config('app.log')) {
            $this->connection()->enableQueryLog();
        }

        /**
         * 注入分页服务
         */
        app()->register(new PaginationServiceProvider((app())));

    }

    /**
     * [__call 魔术方法实现Facades的呼叫]
     *
     * @param $method
     * @param $parameters
     * @return $this->connection()
     * @author 11.
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->connection(), $method], $parameters);
    }

    /**
     * [getSql 返回查询语句]
     *
     * @author 11.
     */
    public function getSql()
    {
        foreach($this->getConnection()->getQueryLog() as $sql)
        {
            $query = str_replace(array('%', '?'), array('%%', '%s'), $sql['query']);
            $query = vsprintf($query, $sql['bindings']);
            echo '<pre style="font-weight:bold;">SQL: <code style=" color:#FF0000">'.($query).' </code><i style="color:#ddd"> '.$sql['time'].'</i></pre>';
        }

        /*监听方式
        Event::listen('illuminate.query', function($query, $bindings, $time, $name)
        {

            // Format binding data for sql insertion
            foreach ($bindings as $i => $binding) {
                if ($binding instanceof \DateTime) {
                    $bindings[$i] = $binding->format('\'Y-m-d H:i:s\'');
                } else if (is_string($binding)) {
                    $bindings[$i] = "'$binding'";
                }
            }

            // Insert bindings into query
            $query = str_replace(array('%', '?'), array('%%', '%s'), $query);
            $query = vsprintf($query, $bindings);

            // Debug SQL queries
            echo $name.  '->SQL: [ ' . $query . ' ]'. $time .'<br />';
        });
        */
    }

}
