<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFilterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('show_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('filter_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color',10)->default('#000000');
            $table->string('description')->nullable();
            $table->boolean('searchable')->nullable();
            $table->unsignedBigInteger('show_type')->unsigned();
            $table->foreign('show_type')
            ->references('id')
            ->on('show_types')
            ->onDelete('no action');
            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });

        Schema::create('filters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('name');
            $table->string('table')->nullable();
            $table->unsignedBigInteger('surrogate_key')->unsigned();
            $table->string('value')->nullable();
            $table->string('description')->nullable();
            $table->unsignedBigInteger('filter_type')->unsigned();
            $table->foreign('filter_type')
            ->references('id')
            ->on('filter_types')
            ->onDelete('no action');

            $table->foreign('parent_id')
            ->references('id')
            ->on('filters')
            ->onDelete('no action');

            $table->string('created_by')->nullable();
            $table->string('updated_by')->nullable();
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
        Schema::dropIfExists('filters');
        Schema::dropIfExists('filter_types');
    }
}
