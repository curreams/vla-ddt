<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavoriteTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description')->nullable();
            $table->bigInteger('user_id')->unsigned();


            $table->foreign('user_id')
            ->references('id')
            ->on('users')
            ->onDelete('no action');

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('favorite_has_filters', function (Blueprint $table) {
            $table->unsignedBigInteger('favorite_id');
            $table->unsignedBigInteger('filter_id');

            $table->foreign('favorite_id')
                ->references('id')
                ->on('favorites')
                ->onDelete('cascade');

            $table->foreign('filter_id')
                ->references('id')
                ->on('filters')
                ->onDelete('cascade');

            $table->primary(['favorite_id', 'filter_id'], 'favorite_has_filters_filter_id_favorite_id_primary');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('favorite_has_filters');
        Schema::dropIfExists('favorites');
    }
}
