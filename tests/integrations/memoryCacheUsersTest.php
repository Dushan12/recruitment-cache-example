<?php

namespace integrations;

include_once(__DIR__."/../../src/repository/UsersRepository.php");
include_once(__DIR__."/../../src/repository/models/User.php");
include_once(__DIR__."/../../src/cache/memory/MemoryUsersCache.php");

require 'vendor/autoload.php';

use memory\MemoryUsersCache;
use PHPUnit\Framework\TestCase;
use src\repository\models\User;

/*
 * TODO test fails because the memory cache APCu library does not work in the test context
 */
final class memoryCacheUsersTest extends TestCase
{

    protected function setUp(): void
    {
    }

    protected function tearDown(): void
    {

    }

/*    public function testCacheRepositoryStoreAndRetrieveUserByKeyTest()
    {
        $target = new MemoryUsersCache();
        $input = new User('6777d184df04a1c62b063b57', 'dushan.gajikj@rldatix.com', 'Dushan');
        $target->storeUser($input);
        sleep(2);
        $actual = $target->getUser($input->id);
        $this->assertSame($actual->id, $input->id);
    }*/

}