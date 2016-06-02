<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFpColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forbidden_posts', function (Blueprint $table) {
            $table->string('state')->default('found')->index()->after('banned_found');
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
            $table->dropColumn('state');
        });
    }
}
