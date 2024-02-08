<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnCurrencyAndGroupIdToBetRecordsTable extends Migration
{

    public function up(): void
    {
        Schema::table('bet_records', function (Blueprint $table) {
            $table
                ->unsignedBigInteger('group_id')
                ->default(1)
                ->after('environment_id');

            $table
                ->string('currency')
                ->default('KHR')
                ->after('bet_time');
        });
    }

    public function down(): void
    {
        Schema::table('bet_records', function (Blueprint $table) {
            $table->dropColumn(['currency', 'group_id']);
        });
    }
}
