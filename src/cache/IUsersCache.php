<?php
namespace cache;

use src\repository\models\User;

interface IUsersCache
{
    public function storeUser(User $user): void;

    public function getUser(string $userId): ?User;
}