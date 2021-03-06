<?php

namespace PAO\Http\Session\Handler;
/**
 * NativeRedisSessionStorage.
 *
 * Driver for the redis session save handler provided by the redis PHP extension.
 *
 * @see https://github.com/nicolasff/phpredis
 *
 * @author Andrej Hudec <pulzarraider@gmail.com>
 */
class RedisSessionHandler extends \SessionHandler
{
    /**
     * Constructor.
     *
     * @param string $savePath Path of redis server.
     */
    public function __construct($savePath = null)
    {
        if (!extension_loaded('redis')) {
            throw new \RuntimeException('PHP does not have "redis" session module registered');
        }
        $savePath = $savePath ?: sprintf('tcp://%s:%s?persistent=0&prefix=%s&database=%d&timeout=%d',
            config('cache.redis.host', '127.0.0.1'),
            config('cache.redis.port', '6379'),
            config('session.prefix', 'PAO_SESSION'),
            config('session.sess_database', 0),
            config('cache.redis.timeout', '5')
        );
        ini_set('session.save_handler', 'redis');
        ini_set('session.save_path', $savePath);
    }
}