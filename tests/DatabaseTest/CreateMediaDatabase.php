<?php

namespace Larrock\Core\Tests\DatabaseTest;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class CreateMediaDatabase
{
    public function setUpMediaDatabase()
    {
        DB::connection()->getSchemaBuilder()->create('media', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('model');
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            $table->string('mime_type')->nullable();
            $table->string('disk');
            $table->unsignedInteger('size');
            $table->json('manipulations');
            $table->json('custom_properties');
            $table->json('responsive_images');
            $table->unsignedInteger('order_column')->nullable();
            $table->nullableTimestamps();
        });

        DB::connection()->table('media')->insert([
            'model_id' => 1,
            'model_type' => 'blocks',
            'collection_name' => 'images',
            'name' => 'test',
            'file_name' => 'test.jpg',
            'mime_type' => 'image/jpeg',
            'disk' => 'media',
            'size' => 1000,
            'manipulations' => '[]',
            'responsive_images' => '[]',
            'custom_properties' => '{"alt": "photo", "gallery": "gelievye-shary"}',
            'order_column' => 1
        ]);
    }
}