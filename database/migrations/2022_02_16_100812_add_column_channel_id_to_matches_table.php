<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnChannelIdToMatchesTable extends Migration
{

    public function up()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->bigInteger('channel_id')->default(0)->after('user_id');
        });
    }

    public function down()
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('channel_id');
        });
    }
}
