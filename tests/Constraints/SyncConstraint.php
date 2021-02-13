<?php

namespace Tests\Constraints;

use Keypress\Syncer\Interfaces\SyncableInterface;

class SyncConstraint extends \PHPUnit_Framework_Constraint
{
    /**
     * @var array<SyncableInterface>
     */
    protected $expected;

    /**
     * SyncConstraint constructor.
     * @param array<SyncableInterface> $expected
     */
    public function __construct($expected)
    {
        parent::__construct();
        $this->expected = $expected;
    }

    /**
     * @param array<SyncableInterface> $actual
     *
     * @return bool
     */
    protected function matches($actual)
    {
        if (count($this->expected) !== count($actual)) {
            return false;
        }

        foreach ($this->expected as $expectedItem) {
            foreach ($actual as $actualItem) {
                if ($expectedItem->is($actualItem) && $this->propsAreSync($expectedItem, $actualItem)) {
                    continue 2;
                }
            }
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function toString()
    {
        return 'Not all items are in sync';
    }

    /**
     * @param SyncableInterface $a
     * @param SyncableInterface $b
     *
     * @return bool
     */
    private function propsAreSync($a, $b)
    {
        $diff = array_diff_assoc($a->getSyncableProperties(), $b->getSyncableProperties());
        return count($diff) === 0;
    }
}