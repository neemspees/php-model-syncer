<?php

namespace Keypress\Syncer\Interfaces;

interface SyncableInterface
{
    /**
     * Get an array of property names as key and their values as value
     *
     * @return array<string,mixed>
     */
    public function getSyncableProperties();

    /**
     * An array of property names as key and their new values to be set
     *
     * @param array<string,mixed> $propertiesValues
     *
     * @return void
     */
    public function setSyncableProperties(array $propertiesValues);

    /**
     * Whether this object is equal to the passed in object
     *
     * @param mixed $other
     *
     * @return boolean
     */
    public function is($other);
}
