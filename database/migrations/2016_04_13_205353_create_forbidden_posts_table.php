<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForbiddenPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('forbidden_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fb_page_id');
            $table->unsignedBigInteger('fb_id');
            $table->text('message');
            $table->text('permalink_url');
            $table->text('banned_found');
            $table->dateTime('created_time');
            $table->timestamps();

            $table->foreign('fb_page_id')->references('id')->on('fb_pages')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('forbidden_posts');
    }
}
