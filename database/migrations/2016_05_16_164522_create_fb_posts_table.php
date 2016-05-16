<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFbPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fb_posts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('fb_page_id')->nullable();
            $table->unsignedBigInteger('fb_id');
            $table->text('message');
            $table->text('permalink_url');
            $table->dateTime('created_time');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('fb_page_id')->references('id')->on('fb_pages')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('fb_posts');
    }
}
