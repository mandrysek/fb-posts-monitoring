<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannedCombsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banned_strings_combs', function (Blueprint $table) {
            $table->unsignedInteger('parent_id');
            $table->unsignedInteger('child_id');

            $table->primary(['parent_id', 'child_id']);

            $table->foreign('parent_id')->references('id')->on('banned_strings')->onDelete('cascade');
            $table->foreign('child_id')->references('id')->on('banned_strings')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('banned_strings_combs');
    }
}
