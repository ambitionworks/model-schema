<?php

namespace AmbitionWorks\ModelSchema;

use Illuminate\Support\ServiceProvider;

class ModelSchemaServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                Console\MigrateCommand::class,
            ]);
        }
    }
}
