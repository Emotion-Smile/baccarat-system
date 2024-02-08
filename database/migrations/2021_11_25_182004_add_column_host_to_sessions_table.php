<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnHostToSessionsTable extends Migration
{

    public function up(): void
    {
        Schema::table('sessions', function (Blueprint $table) {

            $table->string('host')
                ->nullable()
                ->after('ip_address');

            $table->string('ip_address_vapor')
                ->nullable()
                ->after('ip_address');
        });
    }


    public function down(): void
    {
        Schema::table('sessions', function (Blueprint $table) {
            $table->dropColumn('host');
            $table->dropColumn('ip_address_vapor');
        });
    }
}
