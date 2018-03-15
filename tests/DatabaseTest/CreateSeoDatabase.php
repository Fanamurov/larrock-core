<?php

namespace Larrock\Core\Tests\DatabaseTest;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateSeoDatabase
{
    public function setUpSeoDatabase()
    {
        DB::connection()->getSchemaBuilder()->create('seo', function (Blueprint $table) {
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

        DB::connection()->table('seo')->insert([
            'seo_title' => 'test',
            'seo_description' => 'test',
            'seo_keywords' => 'test',
            'seo_id_connect' => 1,
            'seo_url_connect' => 'test',
            'seo_type_connect' => 'test',
        ]);
    }
}