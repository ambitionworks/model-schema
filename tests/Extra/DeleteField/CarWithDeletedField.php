<?php

namespace AmbitionWorks\ModelSchema\Tests\Extra\DeleteField;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

class CarWithDeletedField extends Model {
    protected $table = 'cars';
    public function schema(Blueprint $table)
    {
        $table->id();
    }
}
