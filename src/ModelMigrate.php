<?php

namespace AmbitionWorks\ModelSchema;

use Doctrine\DBAL\Schema\Comparator;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModelMigrate
{
    /**
     * Makes schema updates based on changes between the database and the
     * model's schema() method.
     *
     * @param array $models
     * @return void
     */
    public static function run(array $models): void
    {
        foreach ($models as $className) {
            $class = app($className);

            if (method_exists($class, 'schema')) {
                if (Schema::hasTable($class->getTable())) {
                    $tempTable = 'temp_'.$class->getTable();

                    Schema::dropIfExists($tempTable);
                    Schema::create($tempTable, function (Blueprint $table) use ($class) {
                        $class->schema($table);
                    });

                    $schemaManager = $class->getConnection()->getDoctrineSchemaManager();
                    $classTableDetails = $schemaManager->listTableDetails($class->getTable());
                    $tempTableDetails = $schemaManager->listTableDetails($tempTable);
                    $tableDiff = (new Comparator)->diffTable($classTableDetails, $tempTableDetails);

                    if ($tableDiff) {
                        $schemaManager->alterTable($tableDiff);
                    }

                    Schema::drop($tempTable);
                } else {
                    Schema::create($class->getTable(), function (Blueprint $table) use ($class) {
                        $class->schema($table);
                    });
                }
            }
        }
    }
}
