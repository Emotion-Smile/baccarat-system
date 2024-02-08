<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrencyColumnToUsersTable extends Migration
{

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table
                ->string('currency')
                ->default('KHR')
                ->after('last_login_at');
        });
    }


    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('currency');
        });
    }
}
