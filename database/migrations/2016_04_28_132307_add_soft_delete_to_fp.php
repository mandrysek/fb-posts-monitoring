<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeleteToFp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forbidden_posts', function (Blueprint $table) {
            $table->softDeletes()->after('created_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('forbidden_posts', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
