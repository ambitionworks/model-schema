<?php

namespace AmbitionWorks\ModelSchema\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

class Car extends Model {
    protected $table = 'cars';
    public function schema(Blueprint $table)
    {
        $table->id();
        $table->string('make');
    }
}
