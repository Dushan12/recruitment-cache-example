<?php

namespace exploratory;

include_once(__DIR__."/../../src/repository/UsersRepository.php");
include_once(__DIR__."/../../src/repository/models/User.php");

use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AttributeDriver;
use Doctrine\ODM\MongoDB\MongoDBException;
use MongoDB\Client;
use PHPUnit\Framework\TestCase;
use src\repository\models\User;
use src\repository\UsersRepository;

final class saveUsersTest extends TestCase
{

    private DocumentManager $dm;

    protected function setUp(): void
    {
        $config = new Configuration();
        $config->setProxyDir(__DIR__ . '/../../src/repository/Proxies');
        $config->setProxyNamespace('Proxies');
        $config->setHydratorDir(__DIR__ . '/../../src/repository/Hydrators');
        $config->setHydratorNamespace('Hydrators');
        $config->setDefaultDB('doctrine_odm');
        $config->setMetadataDriverImpl(AttributeDriver::create(__DIR__ . '/../../src/repository/Documents'));
        $connectionString = "mongodb://127.0.0.1:27017";
        $client = new Client($connectionString);
        $this->dm = DocumentManager::create($client, $config);
    }

    protected function tearDown(): void
    {

    }


    /**
     * @throws \Throwable
     * @throws MongoDBException
     */
    public function testUsersRepositoryCrudUsersTest()
    {
        $target = new UsersRepository($this->dm);
        $target->deleteAllUsers();
        $input = new User('', 'dushan.gajikj@rldatix.com', 'Dushan');
        $target->saveUser($input);
        $actual  = $target->getAllUsers();
        $firstElement = $actual[0];
        $arrayLength = sizeOf($actual);
        $this->assertSame($arrayLength, 1);
        $this->assertSame($firstElement->email,'dushan.gajikj@rldatix.com');
        $this->assertSame($firstElement->name, 'Dushan');
        $target = new UsersRepository($this->dm);
        $deleteResult = $target->deleteAllUsers();
        $this->assertSame($deleteResult, 1);
    }

}