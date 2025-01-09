<?php

namespace redis;

include_once(__DIR__ . "/../IUsersCache.php");

use cache\IUsersCache;
use Predis\Client as PredisClient;
use src\repository\models\User;
use src\src\configuration\RedisConfiguration;

require 'vendor/autoload.php';

class RedisUsersCache implements IUsersCache
{

    private PredisClient $redisClient;

    public function __construct(RedisConfiguration $redisConfiguration)
    {

        $this->redisClient = new PredisClient([
            'scheme' => 'tcp',
            'host' => $redisConfiguration->databaseHost,
            'port' => $redisConfiguration->databasePort,
            'password' => '',
            'database' => 0,
        ]);
    }

    public function storeUser(User $user): void
    {
        if($user->id != null) {
            $key = 'user_key_' . $user->id;
            $ttl = 60;
            $this->redisClient->set($key, json_encode($user), 'EX', $ttl);
        }
    }

    public function getUser(string $userId): ?User
    {
        $key = 'user_key_' . $userId;
        $loadedFromCache = $this->redisClient->get($key);
        if ($loadedFromCache == null)
            return null;
        else {
            return new User(...get_object_vars(json_decode($loadedFromCache, false)));
        }
    }

}