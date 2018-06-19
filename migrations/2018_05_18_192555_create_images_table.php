<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('image_upload.table', 'images'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('hash', 128)->unique();
            $table->string('title');
            $table->string('extension');
            $table->unsignedInteger('width');
            $table->unsignedInteger('height');
            $table->string('mime');
            $table->string('size');
            $table->string('cloud_url')->default('local');
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists(config('image_upload.table', 'images'));
    }
}
