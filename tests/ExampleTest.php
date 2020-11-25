<?php

namespace AmbitionWorks\ModelSchema\Tests;

use AmbitionWorks\ModelSchema\Console\MigrateCommand;
use AmbitionWorks\ModelSchema\DiscoverModels;
use AmbitionWorks\ModelSchema\ModelMigrate;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Support\Facades\Facade;

class ExampleTest extends TestCase
{
    protected $db;

    /**
     * Bootstrap database.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->db = $db = new DB;

        $db->addConnection([
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', ''),
            'username' => env('DB_USERNAME', ''),
            'password' => env('DB_PASSWORD', ''),
        ]);

        $db->setAsGlobal();

        $container = new Container;
        $container->instance('db', $db->getDatabaseManager());
        Facade::setFacadeApplication($container);
    }

    protected function tearDown(): void
    {
        Facade::clearResolvedInstances();
        Facade::setFacadeApplication(null);
    }

    /** @test */
    public function it_can_discover_models()
    {
        $this->assertCount(1, (new DiscoverModels(getcwd().'/tests/Extra/', 'AmbitionWorks\ModelSchema\Tests\Extra\\'))->discover());
    }

    /** @test */
    public function it_can_run_command()
    {
        $this->artisan(MigrateCommand::class)
            ->assertExitCode(0)
            ->run();
    }

    /** @test */
    public function it_creates_model_table()
    {
        ModelMigrate::run((new DiscoverModels(getcwd().'/tests/Extra/', 'AmbitionWorks\ModelSchema\Tests\Extra\\'))->discover());
        $this->assertTrue($this->db->connection()->getSchemaBuilder()->hasTable('cars'));
    }

    /** @test */
    public function it_can_add_fields()
    {
        ModelMigrate::run((new DiscoverModels(getcwd().'/tests/Extra/', 'AmbitionWorks\ModelSchema\Tests\Extra\\'))->discover());
        $this->assertFalse($this->db->connection()->getSchemaBuilder()->hasColumn('cars', 'year'));

        ModelMigrate::run((new DiscoverModels(getcwd().'/tests/Extra/AddField', 'AmbitionWorks\ModelSchema\Tests\Extra\AddField\\'))->discover());
        $this->assertTrue($this->db->connection()->getSchemaBuilder()->hasColumn('cars', 'year'));
    }

    /** @test */
    public function it_can_delete_fields()
    {
        ModelMigrate::run((new DiscoverModels(getcwd().'/tests/Extra/', 'AmbitionWorks\ModelSchema\Tests\Extra\\'))->discover());
        $this->assertTrue($this->db->connection()->getSchemaBuilder()->hasColumn('cars', 'make'));

        ModelMigrate::run((new DiscoverModels(getcwd().'/tests/Extra/DeleteField', 'AmbitionWorks\ModelSchema\Tests\Extra\DeleteField\\'))->discover());
        $this->assertFalse($this->db->connection()->getSchemaBuilder()->hasColumn('cars', 'make'));
    }

    /** @test */
    public function it_can_change_fields()
    {
        ModelMigrate::run((new DiscoverModels(getcwd().'/tests/Extra/', 'AmbitionWorks\ModelSchema\Tests\Extra\\'))->discover());
        $this->assertEquals('string', $this->db->connection()->getSchemaBuilder()->getColumnType('cars', 'make'));

        ModelMigrate::run((new DiscoverModels(getcwd().'/tests/Extra/ChangeField', 'AmbitionWorks\ModelSchema\Tests\Extra\ChangeField\\'))->discover());
        $this->assertEquals('text', $this->db->connection()->getSchemaBuilder()->getColumnType('cars', 'make'));
    }
}