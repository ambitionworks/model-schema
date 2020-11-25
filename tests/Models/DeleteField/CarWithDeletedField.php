<?php

namespace AmbitionWorks\ModelSchema\Tests\Models\DeleteField;

use AmbitionWorks\ModelSchema\Tests\Models\Car;
use Illuminate\Database\Schema\Blueprint;

class CarWithDeletedField extends Car {
    protected $table = 'cars';
    public function schema(Blueprint $table)
    {
        $table->id();
    }
}
