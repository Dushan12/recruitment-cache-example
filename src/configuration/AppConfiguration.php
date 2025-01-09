<?php


namespace src\src\configuration;

class AppConfiguration
{
    public MongoDbConfiguration $mongoDbConfiguration;
    public RedisConfiguration $redisConfiguration;

    public string $cacheImplementation;

    public function __construct(MongoDbConfiguration $mongoDbConfiguration, RedisConfiguration $redisConfiguration, string $cacheImplementation = "redis")
    {
        $this->mongoDbConfiguration = $mongoDbConfiguration;
        $this->redisConfiguration = $redisConfiguration;
        $this->cacheImplementation = $cacheImplementation;
    }

}