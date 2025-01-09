<?php

namespace src\repository\models;

use Doctrine\ODM\MongoDB\Mapping\Annotations\Document;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Field;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Id;

#[Document(collection: 'users')]
class User {

    #[Id(type: 'string')]
    public ?string $id;
    #[Field(type: "string")]
    public string $email;
    #[Field(type: "string")]
    public string $name;

    public function __construct(?string $id = null, string $email = "", string $name = "")
    {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
    }

}