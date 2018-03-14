<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSeoTable extends Migration {

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
			$table->string('seo_title')->nullable();
			$table->text('seo_description')->nullable();
			$table->text('seo_keywords')->nullable();
			$table->integer('seo_id_connect')->nullable();
			$table->string('seo_url_connect')->nullable();
			$table->string('seo_type_connect');
			$table->timestamps();

            $table->index(['seo_id_connect', 'seo_url_connect']);
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
