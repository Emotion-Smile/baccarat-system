<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAutoTraderToGroupsTable extends Migration
{

    public function up(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->boolean('auto_trader')
                ->default(false)
                ->after('active');
        });
    }


    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn('auto_trader');
        });
    }
}
