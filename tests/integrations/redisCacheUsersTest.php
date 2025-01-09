<?php

namespace integrations;

include_once(__DIR__."/../../src/repository/UsersRepository.php");
include_once(__DIR__."/../../src/repository/models/User.php");
include_once(__DIR__."/../../src/cache/redis/RedisUsersCache.php");
include_once(__DIR__."/../../src/configuration/RedisConfiguration.php");

require 'vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use redis\RedisUsersCache;
use src\repository\models\User;
use src\src\configuration\RedisConfiguration;
use Testcontainers\Container\Container;
use Testcontainers\Wait\WaitForLog;

final class redisCacheUsersTest extends TestCase
{

    protected function setUp(): void
    {
        $this->container =
            Container::make('redis:latest')
                ->withPrivileged(true)
                ->withPort(6379, 6379);


        $this->container->withWait(new WaitForLog( "/Ready to accept connections tcp/i", true));
        $this->container->run();
    }

    protected function tearDown(): void
    {

    }

    public function testCacheRepositoryStoreAndRetrieveUserByKeyTest()
    {
        $target = new RedisUsersCache(new RedisConfiguration(
            '127.0.0.1', 6379
        ));

        $input = new User('6777d184df04a1c62b063b57', 'dushan.gajikj@rldatix.com', 'Dushan');
        $target->storeUser($input);
        sleep(2);
        $actual = $target->getUser($input->id);
        $this->assertSame($actual->id, $input->id);
    }

}