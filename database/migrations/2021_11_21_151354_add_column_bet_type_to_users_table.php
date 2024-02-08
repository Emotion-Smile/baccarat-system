<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnBetTypeToUsersTable extends Migration
{

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table
                ->boolean('bet_type')
                ->default(1) //1 = auto_accept,2 = check, 3 = suspect
                ->after('suspend');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table
                ->dropColumn('bet_type');
        });
    }
}
