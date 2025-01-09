<?php

namespace integrations;

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
use Testcontainers\Container\Container;
use Testcontainers\Wait\WaitForLog;

final class saveUsersTest extends TestCase
{

    private Container $container;
    private DocumentManager $dm;

    protected function setUp(): void
    {
        $this->container =
            Container::make('mongo:7.0')
                ->withPrivileged(true)
            ->withPort(27017, 27017);

        $this->container->withWait(new WaitForLog("/LogicalSessionCacheRefresh/i", true)); // "/Connection accepted/i", true
        $this->container->run();

        $config = new Configuration();
        $config->setProxyDir(__DIR__ . '/../../src/repository/Proxies');
        $config->setProxyNamespace('Proxies');
        $config->setHydratorDir(__DIR__ . '/../../src/repository/Hydrators');
        $config->setHydratorNamespace('Hydrators');
        $config->setDefaultDB('doctrine_odm');
        $config->setMetadataDriverImpl(AttributeDriver::create(__DIR__ . '/../../src/repository/Documents'));
        $connectionString = "mongodb://localhost:27017";
        $client = new Client($connectionString);
        $this->dm = DocumentManager::create($client, $config);
    }

    protected function tearDown(): void
    {
    }

    /**
     * @throws MongoDBException
     * @throws \Throwable
     */
    public function testUsersRepositoryCrudUsersTest()
    {
        $target = new UsersRepository($this->dm);

        $input1 = new User(null, 'dushan.gajikj1@rldatix.com', 'Dushan1');
        $input2 = new User(null, 'dushan.gajikj2@rldatix.com', 'Dushan2');
        $target->saveUser($input1);
        $target->saveUser($input2);

        $actual  = $target->getAllUsers();

        $firstElement = $actual[0];
        $secondElement = $actual[1];

        $firstElementId = $firstElement->id;

        $loadedUserById = $target->getUserById($firstElementId);



        $arrayLength = sizeOf($actual);
        $deleteResult = $target->deleteAllUsers();

        $this->assertSame($loadedUserById->id, $firstElementId);
        $this->assertSame($arrayLength, 2);
        $this->assertSame($firstElement->email,'dushan.gajikj1@rldatix.com');
        $this->assertSame($firstElement->name, 'Dushan1');
        $this->assertSame($secondElement->email,'dushan.gajikj2@rldatix.com');
        $this->assertSame($secondElement->name, 'Dushan2');
        $this->assertSame($deleteResult, 2);
    }

}