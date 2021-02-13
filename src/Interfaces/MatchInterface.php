<?php

namespace Keypress\Syncer\Interfaces;

interface MatchInterface
{
    /**
     * @return mixed
     */
    public function getLeft();

    /**
     * @return mixed
     */
    public function getRight();
}