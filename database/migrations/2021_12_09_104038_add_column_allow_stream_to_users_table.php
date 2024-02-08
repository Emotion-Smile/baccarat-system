<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAllowStreamToUsersTable extends Migration
{

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table
                ->boolean('allow_stream')
                ->default(0)
                ->after('ip');
        });
    }


    public function down() : void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('allow_stream');
        });
    }
}
