<?php

namespace AmbitionWorks\ModelSchema\Tests\Models\AddField;

use AmbitionWorks\ModelSchema\Tests\Models\Car;
use Illuminate\Database\Schema\Blueprint;

class CarWithAddedField extends Car {
    public function schema(Blueprint $table)
    {
        $table->id();
        $table->string('make');
        $table->integer('year');
    }
}
