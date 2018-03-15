<?php

namespace Larrock\Core\Tests\DatabaseTest;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateLinkDatabase
{
    public function setUpLinkDatabase()
    {
        DB::connection()->getSchemaBuilder()->create('link', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_parent')->unsigned();
            $table->integer('id_child')->unsigned();
            $table->char('model_parent', 191);
            $table->char('model_child', 191);
            $table->float('cost')->nullable();
            $table->timestamps();
            $table->index(['id_parent', 'model_parent', 'model_child']);
        });
    }
}