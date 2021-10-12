<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApiSourceQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('api_source_queries', function (Blueprint $table) {
            $table->unsignedBigInteger('source_id');
            $table->unsignedBigInteger('query_id');
            $table->foreign('source_id')->references('id')->on('api_sources')->onDelete('cascade');
            $table->foreign('query_id')->references('id')->on('queries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('api_source_queries');
    }
}
