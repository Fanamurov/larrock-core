<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLinkTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if( !Schema::hasTable('link')){
            Schema::create('link', function(Blueprint $table)
            {
                $table->increments('id');
                $table->integer('id_parent')->unsigned();
                $table->integer('id_child')->unsigned();
                $table->char('model_parent', 191);
                $table->char('model_child', 191);
                $table->timestamps();
                $table->index(['id_parent', 'model_parent', 'model_child']);
            });
        }
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('link');
	}

}
