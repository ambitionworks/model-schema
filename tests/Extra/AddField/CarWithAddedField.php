<?php

namespace AmbitionWorks\ModelSchema\Tests\Extra\AddField;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

class CarWithAddedField extends Model {
    protected $table = 'cars';
    public function schema(Blueprint $table)
    {
        $table->id();
        $table->string('make');
        $table->integer('year');
    }
}
