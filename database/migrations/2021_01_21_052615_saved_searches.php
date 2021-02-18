<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SavedSearches extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saved_searches', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('user_id');


            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('no action');

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('saved_searches_has_filters', function (Blueprint $table) {
            $table->unsignedBigInteger('saved_searches_id');
            $table->unsignedBigInteger('filter_id');

            $table->foreign('saved_searches_id')
                ->references('id')
                ->on('saved_searches')
                ->onDelete('cascade');

            $table->foreign('filter_id')
                ->references('id')
                ->on('filters')
                ->onDelete('cascade');

            $table->primary(['saved_searches_id', 'filter_id'], 'saved_searches_has_filters_filter_id_saved_searches_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saved_searches_has_filters');
        Schema::dropIfExists('saved_searches');
    }
}
