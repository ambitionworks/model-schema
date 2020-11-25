<?php

namespace AmbitionWorks\ModelSchema\Tests\Models\ChangeField;

use AmbitionWorks\ModelSchema\Tests\Models\Car;
use Illuminate\Database\Schema\Blueprint;

class CarWithChangedField extends Car {
    public function schema(Blueprint $table)
    {
        $table->id();
        $table->longText('make');
    }
}
