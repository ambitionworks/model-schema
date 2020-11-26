<?php

namespace AmbitionWorks\ModelSchema\Tests\Models\ChangeIndex;

use AmbitionWorks\ModelSchema\Tests\Models\Car;
use Illuminate\Database\Schema\Blueprint;

class CarWithChangedIndex extends Car {
    protected $table = 'cars';
    public function schema(Blueprint $table)
    {
        $table->id();
        $table->string('make');
        $table->index('make', 'cars_changed_make_index');
    }
}
