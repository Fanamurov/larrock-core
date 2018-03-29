<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConfigTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        if( !Schema::hasTable('config')){
            Schema::create('config', function(Blueprint $table){
                $table->increments('id');
                $table->char('name');
                $table->text('value');
                $table->char('type');
                $table->timestamps();

                $table->index(['name', 'type']);
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
		Schema::dropIfExists('config');
	}

}
