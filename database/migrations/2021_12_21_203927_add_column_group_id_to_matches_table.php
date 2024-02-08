<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnGroupIdToMatchesTable extends Migration
{

    public function up(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->unsignedBigInteger('group_id')
                ->default(1)
                ->after('environment_id');
        });
    }


    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('group_id');
        });
    }
}
