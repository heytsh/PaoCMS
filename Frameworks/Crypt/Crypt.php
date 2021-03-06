<?php

namespace PAO\Crypt;

use PAO\Crypt\Driver\Null;
use PAO\Crypt\Driver\CryptDriver;


class Crypt
{
    /**
     * Crypt private key
     *
     * @var string
     */
    private $key;

    /**
     * Crypt driver
     *
     * @var CryptDriver
     */
    private $driver = null;


    /**
     * Crypt constructor.
     * @param null $key
     */
    public function __construct($key = null)
    {
        $this->key = $key ?: config('app.token', 'pao_');
    }

    /**
     * $key
     *
     * @param null $key
     * @return null|string
     */
    public function key($key = null)
    {
        return $key ? $this->key = $key : $this->key;
    }


    /**
     * 加密方式加载
     * @param $driver
     * @return CryptDriver
     */
    public function driver($driver = null)
    {
        if(!$driver){
            return $this->null();
        }else{
            if(method_exists($this, $driver)){
                return $this->$driver();
            }else{
                throw new \InvalidArgumentException('Unable load crypt driver');
            }
        }
    }

    /**
     * the default crypt rc4
     *
     * @return CryptDriver
     */
    public function null(){
       if($this->driver instanceof Null) {
           return $this->driver;
       }
        return $this->driver = new Null($this->key);
    }

    /**
     * [__call]
     *
     * @param       $method
     * @param array $parameters
     * @return $this->driver()
     * @author 11.
     */
    public function __call($method, array $parameters = [])
    {
        return call_user_func_array([$this->driver(), $method], $parameters);
    }
}