<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexMatchResultSymbolToMatchesTable extends Migration
{

    public function up(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->index(['group_id', 'match_date'], 'match_result_symbol_index');
        });
    }

    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropIndex('match_result_symbol_index');
        });
    }
}
