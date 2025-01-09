<?php


namespace src\factories;

use Doctrine\ODM\MongoDB\Configuration;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Mapping\Driver\AttributeDriver;
use MongoDB\Client;
use src\src\configuration\AppConfiguration;

class DocumentManagerFactory
{

    static function getInstance(AppConfiguration $configuration): DocumentManager
    {
        $config = new Configuration();
        $config->setProxyDir(__DIR__ . '/../repository/Proxies');
        $config->setProxyNamespace('Proxies');
        $config->setHydratorDir(__DIR__ . '/../repository/Hydrators');
        $config->setHydratorNamespace('Hydrators');
        $config->setDefaultDB($configuration->mongoDbConfiguration->databaseName);
        $config->setMetadataDriverImpl(AttributeDriver::create(__DIR__ . '/../repository/Documents'));
        $client = new Client($configuration->mongoDbConfiguration->databaseConnection);
        return DocumentManager::create($client, $config);
    }

}