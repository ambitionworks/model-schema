<?php

namespace AmbitionWorks\ModelSchema\Tests\Extra;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

class Car extends Model {
    public function schema(Blueprint $table)
    {
        $table->id();
        $table->string('make');
    }
}
