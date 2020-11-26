<?php

namespace AmbitionWorks\ModelSchema\Tests;

use AmbitionWorks\ModelSchema\Console\MigrateCommand;
use AmbitionWorks\ModelSchema\DiscoverModels;
use AmbitionWorks\ModelSchema\ModelMigrate;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Support\Facades\Facade;

class ModelSchemaTest extends TestCase
{
    use RefreshDatabase;

    protected $db;

    /**
     * Bootstrap database.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        RefreshDatabaseState::$migrated = false;

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
        $this->assertCount(1, (new DiscoverModels)
            ->withPath(__DIR__.'/Models/')
            ->withNamespace('AmbitionWorks\ModelSchema\Tests\Models\\')
            ->discover()
        );
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
        ModelMigrate::run((new DiscoverModels)
            ->withPath(__DIR__.'/Models/')
            ->withNamespace('AmbitionWorks\ModelSchema\Tests\Models\\')
            ->discover()
        );

        $this->assertTrue($this->db->connection()->getSchemaBuilder()->hasTable('cars'));
    }

    /** @test */
    public function it_creates_default_fields()
    {
        ModelMigrate::run((new DiscoverModels)
            ->withPath(__DIR__.'/Models/')
            ->withNamespace('AmbitionWorks\ModelSchema\Tests\Models\\')
            ->discover()
        );

        $this->assertTrue($this->db->connection()->getSchemaBuilder()->hasColumn('cars', 'make'));
    }

    /** @test */
    public function it_can_add_fields()
    {
        ModelMigrate::run((new DiscoverModels)
            ->withPath(__DIR__.'/Models/')
            ->withNamespace('AmbitionWorks\ModelSchema\Tests\Models\\')
            ->discover()
        );

        $this->assertFalse($this->db->connection()->getSchemaBuilder()->hasColumn('cars', 'year'));

        ModelMigrate::run((new DiscoverModels)
            ->withPath(__DIR__.'/Models/AddField')
            ->withNamespace('AmbitionWorks\ModelSchema\Tests\Models\AddField\\')
            ->discover()
        );

        $this->assertTrue($this->db->connection()->getSchemaBuilder()->hasColumn('cars', 'year'));
    }

    /** @test */
    public function it_can_delete_fields()
    {
        ModelMigrate::run((new DiscoverModels)
            ->withPath(__DIR__.'/Models/')
            ->withNamespace('AmbitionWorks\ModelSchema\Tests\Models\\')
            ->discover()
        );

        ModelMigrate::run((new DiscoverModels)
            ->withPath(__DIR__.'/Models/DeleteField')
            ->withNamespace('AmbitionWorks\ModelSchema\Tests\Models\DeleteField\\')
            ->discover()
        );

        $this->assertFalse($this->db->connection()->getSchemaBuilder()->hasColumn('cars', 'make'));
    }

    /** @test */
    public function it_can_change_fields()
    {
        ModelMigrate::run((new DiscoverModels)
            ->withPath(__DIR__.'/Models/')
            ->withNamespace('AmbitionWorks\ModelSchema\Tests\Models\\')
            ->discover()
        );
        $this->assertEquals('string', $this->db->connection()->getSchemaBuilder()->getColumnType('cars', 'make'));

        ModelMigrate::run((new DiscoverModels)
            ->withPath(__DIR__.'/Models/ChangeField')
            ->withNamespace('AmbitionWorks\ModelSchema\Tests\Models\ChangeField\\')
            ->discover()
        );
        $this->assertEquals('text', $this->db->connection()->getSchemaBuilder()->getColumnType('cars', 'make'));
    }

    /** @test */
    public function it_can_add_indexes()
    {
        ModelMigrate::run((new DiscoverModels)
            ->withPath(__DIR__.'/Models/')
            ->withNamespace('AmbitionWorks\ModelSchema\Tests\Models\\')
            ->discover()
        );

        $indexes = $this->db->getConnection()->getDoctrineSchemaManager()->listTableIndexes('cars');
        $this->assertArrayHasKey('cars_make_index', $indexes);
    }

    /** @test */
    public function it_can_change_index_names()
    {
        ModelMigrate::run((new DiscoverModels)
            ->withPath(__DIR__.'/Models/')
            ->withNamespace('AmbitionWorks\ModelSchema\Tests\Models\\')
            ->discover()
        );

        ModelMigrate::run((new DiscoverModels)
            ->withPath(__DIR__.'/Models/ChangeIndex')
            ->withNamespace('AmbitionWorks\ModelSchema\Tests\Models\ChangeIndex\\')
            ->discover()
        );

        $indexes = $this->db->getConnection()->getDoctrineSchemaManager()->listTableIndexes('cars');
        $this->assertArrayHasKey('cars_changed_make_index', $indexes);
    }

    /** @test */
    public function it_can_delete_indexes()
    {
        ModelMigrate::run((new DiscoverModels)
            ->withPath(__DIR__.'/Models/')
            ->withNamespace('AmbitionWorks\ModelSchema\Tests\Models\\')
            ->discover()
        );

        ModelMigrate::run((new DiscoverModels)
            ->withPath(__DIR__.'/Models/DeleteIndex')
            ->withNamespace('AmbitionWorks\ModelSchema\Tests\Models\DeleteIndex\\')
            ->discover()
        );

        $indexes = $this->db->getConnection()->getDoctrineSchemaManager()->listTableIndexes('cars');
        $this->assertArrayNotHasKey('cars_make_index', $indexes);
    }
}
