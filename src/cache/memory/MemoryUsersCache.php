<?php

namespace memory;

include_once(__DIR__ . "/../IUsersCache.php");

use cache\IUsersCache;
use src\repository\models\User;

require 'vendor/autoload.php';

class MemoryUsersCache implements IUsersCache
{

    public function storeUser(User $user): void
    {
        if ($user->id != null) {
            $key = 'user_key_' . $user->id;
            $data = json_encode($user);
            $ttl = 60;
            apcu_store($key, $data, $ttl);
        }
    }

    public function getUser(string $userId): ?User
    {
        $key = 'user_key_' . $userId;
        $loadedFromCache = apcu_fetch($key);;
        if ($loadedFromCache !== false) {
            return new User(...get_object_vars(json_decode($loadedFromCache, false)));
        } else {
            return null;
        }
    }

}