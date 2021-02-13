<?php

namespace Keypress\Syncer\Models;

use Keypress\Syncer\Interfaces\MatchInterface;

class Match implements MatchInterface
{
    protected $left;
    protected $right;

    /**
     * @return mixed
     */
    public function getLeft()
    {
        return $this->left;
    }

    /**
     * @param mixed $value
     */
    public function setLeft($value)
    {
        $this->left = $value;
    }

    /**
     * @return mixed
     */
    public function getRight()
    {
        return $this->right;
    }

    /**
     * @param mixed $value
     */
    public function setRight($value)
    {
        $this->right = $value;
    }
}