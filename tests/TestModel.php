<?php

namespace Tests;

use Keypress\Syncer\Interfaces\SyncableInterface;

class TestModel implements SyncableInterface
{
    public $id;
    public $name;
    public $email;

    public function __construct($id, $name, $email)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
    }

    public function getSyncableProperties()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email
        ];
    }

    public function setSyncableProperties(array $propertiesValues)
    {
        $this->id = $propertiesValues['id'];
        $this->name = $propertiesValues['name'];
        $this->email = $propertiesValues['email'];
    }

    public function is($other)
    {
        return $this->id === $other->id;
    }
}
