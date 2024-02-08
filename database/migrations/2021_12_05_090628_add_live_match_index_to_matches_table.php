<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLiveMatchIndexToMatchesTable extends Migration
{
    public function up(): void
    {
        if (!app()->runningUnitTests()) {
            Schema::table('matches', function (Blueprint $table) {
                $table->index(['environment_id', 'match_end_at'], 'live_match_index');
            });
        }

    }

    public function down(): void
    {
        if (!app()->runningUnitTests()) {
            Schema::table('matches', function (Blueprint $table) {
                $table->dropIndex('live_match_index');
            });
        }
    }
}
