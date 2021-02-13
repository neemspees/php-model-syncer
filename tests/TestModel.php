<?php

namespace Tests;

use Keypress\Syncer\Interfaces\SyncableInterface;

class TestModel implements SyncableInterface
{
    public $id;
    public $name;
    public $email;
    public $updatedAt;

    public function __construct($id, $name, $email, $updatedAt = 0)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->updatedAt = $updatedAt;
    }

    public function getSyncableProperties()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'updated_at' => $this->updatedAt
        ];
    }

    public function setSyncableProperties(array $propertiesValues)
    {
        $this->id = $propertiesValues['id'];
        $this->name = $propertiesValues['name'];
        $this->email = $propertiesValues['email'];
        $this->updatedAt = $propertiesValues['updated_at'];
    }

    public function is($other)
    {
        return $this->id === $other->id;
    }
}
