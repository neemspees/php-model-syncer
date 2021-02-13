<?php

namespace Tests;

use Keypress\Syncer\Interfaces\SyncableInterface;
use Keypress\Syncer\Syncer;
use PHPUnit\Framework\TestCase;
use Tests\Constraints\SyncConstraint;

class SyncerTest extends TestCase
{
    /** @test */
    public function syncCopiesRightAttributesOverLeftAttributes()
    {
        $left = [
            new TestModel(1, 'Name1', 'name1@test.test'),
            new TestModel(2, 'Name2', 'name2@test.test')
        ];
        $right = [
            new TestModel(1, 'Name1', 'name1@test.test'),
            new TestModel(2, 'Name2', 'changed@test.test')
        ];
        $expected = $right;

        $syncer = new Syncer();
        $actual = $syncer->sync($left, $right);

        $this->assertSync($expected, $actual);
    }

    /** @test */
    public function syncCopiesRightAttributesOverLeftAttributesWhenNotAllObjectsInRightArray()
    {
        $left = [
            new TestModel(1, 'Name1', 'name1@test.test'),
            new TestModel(2, 'Name2', 'name2@test.test')
        ];
        $right = [
            new TestModel(2, 'Name2', 'changed@test.test')
        ];
        $expected = [
            new TestModel(1, 'Name1', 'name1@test.test'),
            new TestModel(2, 'Name2', 'changed@test.test')
        ];;

        $syncer = new Syncer();
        $actual = $syncer->sync($left, $right);

        $this->assertSync($expected, $actual);
    }

    /** @test */
    public function syncCopiesRightAttributesOverLeftAttributesWhenNotAllObjectsInLeftArray()
    {
        $left = [
            new TestModel(2, 'Name2', 'name2@test.test')
        ];
        $right = [
            new TestModel(1, 'Name1', 'name1@test.test'),
            new TestModel(2, 'Name2', 'changed@test.test')
        ];
        $expected = [
            new TestModel(1, 'Name1', 'name1@test.test'),
            new TestModel(2, 'Name2', 'changed@test.test')
        ];

        $syncer = new Syncer();
        $actual = $syncer->sync($left, $right);

        $this->assertSync($expected, $actual);
    }

    /** @test */
    public function applyingTheIgnoreNewLeftFlagIgnoresItemsThatAreNotInRight()
    {
        $left = [
            new TestModel(1, 'Name1', 'name1@test.test'),
            new TestModel(2, 'Name2', 'name2@test.test')
        ];
        $right = [
            new TestModel(2, 'Name2', 'changed@test.test'),
            new TestModel(3, 'Name3', 'name3@test.test')
        ];
        $expected = [
            new TestModel(2, 'Name2', 'changed@test.test'),
            new TestModel(3, 'Name3', 'name3@test.test')
        ];

        $syncer = new Syncer();
        $actual = $syncer->sync($left, $right, [], Syncer::IGNORE_NEW_ITEMS_LEFT);

        $this->assertSync($expected, $actual);
    }

    /** @test */
    public function applyingTheIgnoreNewRightFlagIgnoresItemsThatAreNotInLeft()
    {
        $left = [
            new TestModel(2, 'Name2', 'name2@test.test'),
            new TestModel(3, 'Name3', 'name3@test.test')
        ];
        $right = [
            new TestModel(1, 'Name1', 'name1@test.test'),
            new TestModel(2, 'Name2', 'changed@test.test')
        ];
        $expected = [
            new TestModel(2, 'Name2', 'changed@test.test'),
            new TestModel(3, 'Name3', 'name3@test.test')
        ];

        $syncer = new Syncer();
        $actual = $syncer->sync($left, $right, [], Syncer::IGNORE_NEW_ITEMS_RIGHT);

        $this->assertSync($expected, $actual);
    }

    /** @test */
    public function applyingTheIgnoreNewLeftAndIgnoreNewRightFlagsIgnoresItemsThatAreNotInBoth()
    {
        $left = [
            new TestModel(1, 'Name1', 'name1@test.test'),
            new TestModel(2, 'Name2', 'name2@test.test')
        ];
        $right = [
            new TestModel(2, 'Name2', 'changed@test.test'),
            new TestModel(3, 'Name3', 'name3@test.test')
        ];
        $expected = [
            new TestModel(2, 'Name2', 'changed@test.test')
        ];

        $syncer = new Syncer();
        $actual = $syncer->sync($left, $right, [], Syncer::IGNORE_NEW_ITEMS_LEFT | Syncer::IGNORE_NEW_ITEMS_RIGHT);

        $this->assertSync($expected, $actual);
    }

    /** @test */
    public function applyingTheIgnoreBothFlagIgnoresItemsThatAreNotInBoth()
    {
        $left = [
            new TestModel(1, 'Name1', 'name1@test.test'),
            new TestModel(2, 'Name2', 'name2@test.test')
        ];
        $right = [
            new TestModel(2, 'Name2', 'changed@test.test'),
            new TestModel(3, 'Name3', 'name3@test.test')
        ];
        $expected = [
            new TestModel(2, 'Name2', 'changed@test.test')
        ];

        $syncer = new Syncer();
        $actual = $syncer->sync($left, $right, [], Syncer::IGNORE_NEW_ITEMS_BOTH);

        $this->assertSync($expected, $actual);
    }

    /** @test */
    public function configuringAnAttributeToPreferLeftTakesTheLeftItemsValueForThatAttribute()
    {
        $left = [
            new TestModel(2, 'Name2', 'name2@test.test')
        ];
        $right = [
            new TestModel(2, 'Changed Name', 'changed@test.test')
        ];
        $expected = [
            new TestModel(2, 'Changed Name', 'name2@test.test')
        ];

        $syncer = new Syncer();
        $actual = $syncer->sync($left, $right, ['email' => Syncer::PREFER_ATTRIBUTE_LEFT]);

        $this->assertSync($expected, $actual);
    }

    /** @test */
    public function configuringAnAttributeToPreferRightTakesTheRightItemsValueForThatAttribute()
    {
        $left = [
            new TestModel(2, 'Name2', 'name2@test.test')
        ];
        $right = [
            new TestModel(2, 'Changed Name', 'changed@test.test')
        ];
        $expected = [
            new TestModel(2, 'Changed Name', 'changed@test.test')
        ];

        $syncer = new Syncer();
        $actual = $syncer->sync($left, $right, ['email' => Syncer::PREFER_ATTRIBUTE_RIGHT]);

        $this->assertSync($expected, $actual);
    }

    /** @test */
    public function configuringAnAttributeWithACallbackWorks()
    {
        $left = [
            new TestModel(1, 'Name 1', 'name1@test.test', 100),
            new TestModel(2, 'Name 2', 'name2@test.test', 100)
        ];
        $right = [
            new TestModel(1, 'Name 1', 'changed1@test.test', 50),
            new TestModel(2, 'Name 2', 'changed2@test.test', 200)
        ];
        $expected = [
            new TestModel(1, 'Name 1', 'name1@test.test', 100),
            new TestModel(2, 'Name 2', 'changed2@test.test', 200)
        ];

        $preferLastUpdated = function ($a, $b) {
            return $a->updatedAt >= $b->updatedAt ? -1 : 1;
        };
        $syncer = new Syncer();
        $actual = $syncer->sync($left, $right, ['email' => $preferLastUpdated, 'updated_at' => $preferLastUpdated]);

        $this->assertSync($expected, $actual);
    }

    /**
     * @param array<SyncableInterface> $expected
     * @param array<SyncableInterface> $actual
     *
     * @return void
     */
    private function assertSync(array $expected, array $actual)
    {
        self::assertThat($actual, new SyncConstraint($expected));
    }
}
