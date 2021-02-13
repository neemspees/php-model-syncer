<?php

namespace Keypress\Syncer\Interfaces;

interface SyncerInterface
{
    const IGNORE_NEW_ITEMS_LEFT = 1;
    const IGNORE_NEW_ITEMS_RIGHT = 2;
    const IGNORE_NEW_ITEMS_BOTH = 3;

    const PREFER_ATTRIBUTE_LEFT = 1;
    const PREFER_ATTRIBUTE_RIGHT = 2;

    /**
     * Merge 2 arrays of objects that implement the IExposeProperties interface
     *
     * @param SyncableInterface[] $left
     * @param SyncableInterface[] $right
     * @param array<string,int> $config
     * @param int $flags
     *
     * @return SyncableInterface[]
     */
    public function sync(array $left, array $right, array $config = [], $flags = 0);
}
