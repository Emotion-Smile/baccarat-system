<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTransactionIdToBetRecordsTable extends Migration
{
    public function up(): void
    {
        Schema::table('bet_records', function (Blueprint $table) {
            $table->unsignedBigInteger('transaction_id')
                ->default(0)
                ->after('match_id');
        });
    }

    public function down(): void
    {
        Schema::table('bet_records', function (Blueprint $table) {
            $table->dropColumn('transaction_id');
        });
    }
}
