<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('source_id');
            $table->unsignedBigInteger('query_id');
            $table->string('creator')->nullable();
            $table->string('author')->nullable();
            $table->string('title');
            $table->text('description');
            $table->text('url')->nullable();
            $table->text('url_to_image')->nullable();
            $table->longText('content');
            $table->dateTime('published_at');
            $table->foreign('source_id')->references('id')->on('api_sources')->onDelete('cascade');
            $table->foreign('query_id')->references('id')->on('queries')->onDelete('cascade');
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
        Schema::dropIfExists('news');
    }
}
