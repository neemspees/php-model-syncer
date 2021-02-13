<?php

namespace Keypress\Syncer;

use Generator;
use Keypress\Syncer\Interfaces\SyncableInterface;
use Keypress\Syncer\Interfaces\SyncerInterface;
use Keypress\Syncer\Models\Match;

class Syncer implements SyncerInterface
{
    /**
     * {@inheritDoc}
     */
    public function sync(array $left, array $right, array $config = [], $flags = 0)
    {
        $temp = [];
        foreach ($this->getMatches($left, $right) as $match) {
             $temp[] = $this->syncProperties($match->getLeft(), $match->getRight(), $config);
        }
        if (!$this->hasFlag($flags, static::IGNORE_NEW_ITEMS_LEFT)) {
            $temp = $this->mergeMissing($temp, $left);
        }
        if (!$this->hasFlag($flags, static::IGNORE_NEW_ITEMS_RIGHT)) {
            $temp = $this->mergeMissing($temp, $right);
        }
        return $temp;
    }

    /**
     * @param array<SyncableInterface> $left
     * @param array<SyncableInterface> $right
     *
     * @return Generator<Match>
     */
    private function &getMatches(array $left, array $right)
    {
        foreach ($left as $a) {
            foreach ($right as $b) {
                if (!$a->is($b)) {
                    continue;
                }
                $match = $this->createMatch($a, $b);
                yield $match;
                continue 2;
            }
        }
    }

    /**
     * @param SyncableInterface $a
     * @param SyncableInterface $b
     * @param array<string,string> $config
     *
     * @return SyncableInterface
     */
    private function syncProperties($a, $b, array $config = [])
    {
        $aProperties = $a->getSyncableProperties();
        $bProperties = $b->getSyncableProperties();
        $mergedProperties = $aProperties;
        $changedProperties = array_diff_assoc($aProperties, $bProperties);
        foreach ($changedProperties as $key => $value) {
            if (array_key_exists($key, $config) && $this->hasFlag($config[$key], static::PREFER_ATTRIBUTE_LEFT)) {
                continue;
            }
            $mergedProperties[$key] = $bProperties[$key];
        }
        $clone = clone $a;
        $clone->setSyncableProperties($mergedProperties);
        return $clone;
    }

    /**
     * @param mixed $left
     * @param mixed $right
     *
     * @return Match
     */
    private function createMatch($left, $right)
    {
        $match = new Match();
        $match->setLeft($left);
        $match->setRight($right);
        return $match;
    }

    /**
     * @param array<SyncableInterface> $target
     * @param array<SyncableInterface> $source
     *
     * @return array<SyncableInterface>
     */
    private function mergeMissing(array $target, array $source)
    {
        foreach ($source as $a) {
            foreach ($target as $b) {
                if ($a->is($b)) {
                    continue 2;
                }
            }
            $target[] = $a;
        }
        return $target;
    }

    /**
     * @param int $flags
     * @param int $flag
     *
     * @return bool
     */
    private function hasFlag($flags, $flag)
    {
        return ($flags & $flag) === $flag;
    }
}