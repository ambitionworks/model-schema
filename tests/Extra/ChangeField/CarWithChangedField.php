<?php

namespace AmbitionWorks\ModelSchema\Tests\Extra\ChangeField;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

class CarWithChangedField extends Model {
    protected $table = 'cars';
    public function schema(Blueprint $table)
    {
        $table->id();
        $table->longText('make');
    }
}
