<?php
declare(strict_types=1);

namespace tests\unit;

use db\Migration;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Support\Facades\Schema;
use PHPUnit\Framework\TestCase;

abstract class DbTestCase extends TestCase
{
    /**
     * @var Migration
     */
    private $migration;

    /**
     * @inheritDoc
     */
    protected function setUp() : void
    {
        parent::setUp();

        $this->migration = new Migration(Manager::schema());
        $this->migration->up();
    }

    /**
     * @inheritDoc
     */
    protected function tearDown() : void
    {
        parent::tearDown();
        $this->migration->rollback();
    }
}
