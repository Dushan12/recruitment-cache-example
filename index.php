<?php

include_once(__DIR__ . "/src/repository/UsersRepository.php");
include_once(__DIR__ . "/src/services/UsersService.php");
include_once(__DIR__ . "/src/factories/DocumentManagerFactory.php");
include_once(__DIR__ . "/src/configuration/AppConfiguration.php");
include_once(__DIR__ . "/src/configuration/MongoDbConfiguration.php");
include_once(__DIR__ . "/src/configuration/RedisConfiguration.php");
include_once(__DIR__ . "/src/cache/memory/MemoryUsersCache.php");
include_once(__DIR__ . "/src/cache/redis/RedisUsersCache.php");

use cache\IUsersCache;
use memory\MemoryUsersCache;
use redis\RedisUsersCache;
use src\factories\DocumentManagerFactory;
use src\repository\models\User;
use src\repository\UsersRepository;
use src\src\configuration\AppConfiguration;
use src\src\configuration\MongoDbConfiguration;
use src\src\configuration\RedisConfiguration;

require __DIR__ . '/vendor/autoload.php';

function getRedisConfig(): RedisConfiguration
{
    return new RedisConfiguration(
        $databaseHost = '127.0.0.1',
        $databasePort = 6379,
    );
}

function getMongoDbConfiguration(): MongoDbConfiguration {
    return new MongoDbConfiguration(
        $databaseConnection = "mongodb://localhost:27017",
        $databaseName = "users"
    );
}

function getAppConfig($redisConfig, $mongoDbConfig): AppConfiguration
{
    return new AppConfiguration($mongoDbConfig, $redisConfig, $cacheImplementation = "memory");
}


function getUserRepository(): UsersRepository {
    return new UsersRepository(
        DocumentManagerFactory::getInstance(getAppConfig(getRedisConfig(), getMongoDbConfiguration()))
    );
}

function getUserService(): UsersService
{
    $appConfig = getAppConfig(getRedisConfig(), getMongoDbConfiguration());

    /**
     * @var IUsersCache $cacheImpl
     */
    $cacheImpl = null;

    if($appConfig->cacheImplementation == "redis") {
        $cacheImpl = new RedisUsersCache($appConfig->redisConfiguration);
    } else {
        $cacheImpl = new MemoryUsersCache();
    }

    return new UsersService(
        new UsersRepository(
            DocumentManagerFactory::getInstance($appConfig)
        ),
        $cacheImpl
    );
}


app()->get('/api/v1/users/{id}', function ($id) {
    $usersService = getUserService();
    $result = $usersService->getUserById($id);
    response()->json(json_encode($result), 200);
});

app()->get('/api/v1/users', function () {
    $usersRepository = getUserRepository();
    $result = $usersRepository->getAllUsers();
    response()->json(json_encode($result), 200);
});

app()->post('/api/v1/users', function () {
    $usersRepository = getUserRepository();
    $userParsed = request()->get("user");
    $userInput = new User(null, ...$userParsed);
    $usersRepository->saveUser($userInput);
    response()->json("success", 200);
});

app()->delete('/api/v1/users', function () {
    $usersService = getUserService();
    $usersService->deleteAllUsers();
});

app()->run();