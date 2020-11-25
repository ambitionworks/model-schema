<?php

namespace AmbitionWorks\ModelSchema\Console;

use AmbitionWorks\ModelSchema\DiscoverModels;
use AmbitionWorks\ModelSchema\ModelMigrate;

use Illuminate\Console\Command;

class MigrateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'model-schema:migrate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Updates the database schema for all models defining a schema method.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        ModelMigrate::run((new DiscoverModels)->discover());
    }
}
