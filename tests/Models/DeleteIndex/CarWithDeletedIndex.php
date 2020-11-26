<?php

namespace AmbitionWorks\ModelSchema\Tests\Models\DeleteIndex;

use AmbitionWorks\ModelSchema\Tests\Models\Car;
use Illuminate\Database\Schema\Blueprint;

class CarWithDeletedIndex extends Car {
    protected $table = 'cars';
    public function schema(Blueprint $table)
    {
        $table->id();
        $table->string('make');
    }
}
