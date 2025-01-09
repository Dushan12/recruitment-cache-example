<?php
namespace src\repository;

require_once "models/User.php";

use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\MongoDBException;
use src\repository\models\User;
use Throwable;

class UsersRepository {

    private DocumentManager $mongoDbDocumentManager;

    public function __construct(DocumentManager $mongoDb) {
        $this->mongoDbDocumentManager = $mongoDb;
    }

    /**
     * @throws MongoDBException
     */
    public function getAllUsers(): array
    {
        $job  =
            $this->mongoDbDocumentManager
            ->createQueryBuilder(User::class)
            ->find();
        $result = $job->getQuery()->execute();
        return $result->toArray();
    }

    public function getUserById(string $id): User
    {
        return $this->mongoDbDocumentManager
                ->find(User::class, $id);
    }

    /**
     * @throws MongoDBException
     * @throws Throwable
     */
    public function saveUser(User $user): void
    {
        $this->mongoDbDocumentManager->persist($user);
        $this->mongoDbDocumentManager->flush();
    }

    /**
     * @throws MongoDBException
     */
    public function deleteAllUsers(): int
    {
        $deleteResult = $this->mongoDbDocumentManager
            ->createQueryBuilder(User::class)
            ->remove()
            ->getQuery()
            ->execute();
        return $deleteResult->getDeletedCount();
    }
}