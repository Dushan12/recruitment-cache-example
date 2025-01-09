<?php


// TODO write up diagram on excalidraw about the architecture
// TODO create test on postman and store it in the codebase
// TODO store the code in Github repository
// TODO prepare the recruitment questions
// TODO prepare interview questions

use cache\IUsersCache;
use Doctrine\ODM\MongoDB\MongoDBException;
use src\repository\models\User;
use src\repository\UsersRepository;

// TODO test
class UsersService {

    private UsersRepository $usersRepository;
    private IUsersCache $usersCache;

    public function __construct(UsersRepository $usersRepository, IUsersCache $cacheImpl) {
        $this->usersRepository = $usersRepository;
        $this->usersCache = $cacheImpl;
    }

    public function getUserById(string $id): User
    {
        $loadedUserFromCache = $this->usersCache->getUser($id);
        if($loadedUserFromCache == null) {
            $loadedUser = $this->usersRepository->getUserById($id);
            $this->usersCache->storeUser($loadedUser);
            return $loadedUser;
        } else {
            return $loadedUserFromCache;
        }
    }

    /**
     * @throws MongoDBException
     */
    public function deleteAllUsers(): int
    {
        return $this->usersRepository->deleteAllUsers();
    }

}