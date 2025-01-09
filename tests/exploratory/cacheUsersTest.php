<?php

namespace exploratory;

include_once(__DIR__."/../../src/repository/UsersRepository.php");
include_once(__DIR__."/../../src/repository/models/User.php");
require 'vendor/autoload.php';

use Doctrine\ODM\MongoDB\MongoDBException;
use PHPUnit\Framework\TestCase;
use Predis\Client as PredisClient;

final class cacheUsersTest extends TestCase
{

    private PredisClient $r;

    protected function setUp(): void
    {
        $this->r = new PredisClient([
            'scheme'   => 'tcp',
            'host'     => '127.0.0.1',
            'port'     => 6379,
            'password' => '',
            'database' => 0,
        ]);
    }

    protected function tearDown(): void
    {

    }


    /**
     * @throws \Throwable
     * @throws MongoDBException
     */
    public function testCacheRepositoryStoreAndRetrieveUserByKeyTest()
    {
        $this->r->set("test_key", "test_value");
        $result = $this->r->get("test_key");
        $this->assertSame('test_key', $result);
    }

}