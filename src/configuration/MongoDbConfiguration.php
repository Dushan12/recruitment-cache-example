<?php


namespace src\src\configuration;
class MongoDbConfiguration
{
    public string $databaseConnection;
    public string $databaseName;

    public function __construct(string $databaseConnection, string $databaseName)
    {
        $this->databaseConnection = $databaseConnection;
        $this->databaseName = $databaseName;
    }
}