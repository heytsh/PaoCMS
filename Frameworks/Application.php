<?php

namespace PAO;

use PAO\Exception\PAOException;
use PAO\Exception\SystemException;
use PAO\Services\SystemServiceProvider;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Events\EventServiceProvider;


defined('PAO') || die('Invalid Construct System');

version_compare(PHP_VERSION,'5.5.0','ge') || die('The php version least must 5.5.0 ');

/**
 * 框架核心驱动集成 Class Application
 *
 * @package PAO
 * @version 20151123
 */
class Application extends Container
{
    /**
     * 自动加载器
     * @var \Composer\Autoload\ClassLoader $loader
     */
    private $loader;

    /**
     * 已注入的模块
     * @var array
     */
    private $is_bindings = [];


    /**
     * 已注册的服务
     * @var array
     */
    private $is_providers = [];


    /**
     * Frameworks constructor.
     * @param $loader
     */
    public function __construct($loader)
    {
        $this->loader = $loader;
    }

    /**
     * [Issue 核心应用构造方法]
     */
    public function Issue()
    {
        /**
         * 核心注入
         */
        static::setInstance($this);

        /**
         * 单例模式
         */
        $this->instance('app', $this);

        /**
         * 注入容器
         */
        $this->instance('Illuminate\Container\Container', $this);

        /**
         * 注册系统组件
         */
        $this->registerContainerAliases();

        /**
         * 注册自动加载
         */
        $this->registerAutoLoadAlias();

        /**
         * 配置系统环境
         */
        $this->registerSystemEnvironment();

        /**
         * 异常模块注入
         */
        $this->registerExceptionHandling();

        /**
         * 初始外观模式
         * @var $this \Illuminate\Contracts\Foundation\Application
         */
        Facade::setFacadeApplication($this);

        /**
         * 基本服务注册
         */
        $this->registerBaseServiceProviders();

        /**
         * 启航
         */
        $this->Navigate();
    }


    /**
     * [make 全局注入方法]
     *
     * @param string $abstract [方法名称]
     * @param array $parameters [方法参数]
     * @return mixed
     */
    public function make($abstract, array $parameters = [])
    {
        if (!isset($this->is_bindings[$abstract]) && $this->isAlias($abstract)) {
            $this->singleton($abstract, $this->getAlias($abstract));
            $this->is_bindings[$abstract] = true;
        }

        return parent::make($abstract, $parameters);
    }

    /**
     * [config 容器配置读取方法]
     *
     * @param $config [配置文件项]
     * @return mixed
     * @example $this->config('app.debug');
     */
    public function config($config)
    {
        return $this->make('config')->get($config);
    }

    /**
     * [Navigate 路由导航]
     *
     */
    private function Navigate()
    {
        /**
         * dispatch
         */
        $response = $this->make('router')->dispatch($this->make('request') , $this->make('response'));

        //重置Response
        if(!$response instanceof \PAO\Http\Response)
        {
            throw new SystemException('The Output Must be Instance of PAO\Response');
        }
        /**
         * 响应请求
         * @var $response \PAO\Http\Response
         */
        $response->send();
    }

    /**
     * Autoload
     *
     * @return \Composer\Autoload\ClassLoader
     */
    public function loader()
    {
        return $this->loader;
    }

    /**
     * [register 服务提供者注册器]
     *
     * @param $provider
     */
    public function register($provider, $options = [])
    {
        if(!$provider instanceof ServiceProvider)
        {
            $provider = new $provider($this);
        }

        if(isset($this->is_providers[$providerName = get_class($provider)]))
        {
            return;
        }
        $this->is_providers[$providerName] = true;

        $provider->register();
        /**
         * @var $provider \PAO\Services\SystemServiceProvider
         */
        $provider->boot();
    }

    /**
     * [注册核心容器中的别名]
     *
     * @return void
     */
    private function registerContainerAliases()
    {
        $this->aliases = array(
            'app' => 'PAO\Application',
            'router' => 'PAO\Routing\Router',
            'config' => 'PAO\Config\Config',
            'request' => 'PAO\Http\Request',
            'response' => 'PAO\Http\Response',
            'cookie' => 'PAO\Http\Cookie',
            'session' => 'PAO\Http\Session',
            'crypt' => 'PAO\Crypt\Crypt',
            'captcha' => 'PAO\Captcha\Captcha',
            'validator' => 'PAO\Form\Validator',
            'lang' => 'PAO\Language\Lang',
            'view' => 'PAO\View',
            'curl' => 'PAO\Http\Curl',
            'file' => 'PAO\FileSystem\Files',
            'cache' => 'PAO\Cache\Cache',
            'log' => 'PAO\Logger\Logger',
            'db' => 'PAO\Database'
        );
    }

    /**
     * [registerAutoLoadAlias 注册自动加载]
     */
    private function registerAutoLoadAlias()
    {
        $classMap = array(
            'Arr' => __DIR__.'/Support/Arr.php',
            'Str' => __DIR__.'/Support/Str.php',
            'Schema' => __DIR__.'/Support/Facades/Schema.php'
        );

        foreach($this->aliases as $alias => $class) {
            $alias = $alias == 'db' ? strtoupper($alias) : ucfirst($alias);
            $classMap[$alias] = __DIR__.'/Support/Facades/'.$alias.'.php';
        }
        $this->loader->addClassMap($classMap);
    }


    /**
     * [registerExceptionHandling 异常服务注册]
     *
     */
    private function registerExceptionHandling()
    {

        //实例化异常类
        $PAOException = new PAOException();

        //设置抛出异常
        set_exception_handler(function ($e)use($PAOException) {
            $PAOException->Exception($e);
        });

        //异常错误处理
        set_error_handler(array($PAOException, 'handleError'));

        //致命错误处理
        register_shutdown_function(array($PAOException,'handleShutdown'));
    }

    /**
     * [registerBaseServiceProviders 基本服务注册]
     *
     * @author 11.
     */
    private function registerBaseServiceProviders()
    {
        /**
         * 系统服务
         * @var $this \Illuminate\Contracts\Foundation\Application
         */
        $this->register(new SystemServiceProvider($this));

        /**
         * 事件服务
         * @var $this \Illuminate\Contracts\Foundation\Application
         */
        $this->register(new EventServiceProvider($this));
    }


    /**
     * [registerSystemEnvironment 初始化配置系统环境]
     *
     * @author 11.
     */
    private function registerSystemEnvironment()
    {
        /**
         * 设置错误报告
         */
        if($this->config('app.debug')) {
            error_reporting(E_ALL);
            ini_set('display_errors', 'On');
        }else{
            error_reporting(0);
            ini_set('display_errors', 'Off');
        }

        /**
         * 设置系统时区
         */
        if ($timezone = $this->config('app.timezone')) {
            date_default_timezone_set($timezone);
        }

        /**
         * 设置环境编码
         */
        if ($charset = $this->config('app.charset')) {
            mb_internal_encoding($charset);
        }

    }
}
