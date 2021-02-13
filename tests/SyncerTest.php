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
        ];;

        $syncer = new Syncer();
        $actual = $syncer->sync($left, $right);

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
