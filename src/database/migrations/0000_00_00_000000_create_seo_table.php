<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLarrockSeoTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('seo', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('seo_title');
			$table->text('seo_description');
			$table->text('seo_keywords');
			$table->integer('id_connect');
			$table->string('url_connect');
			$table->string('type_connect');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('seo');
	}

}
