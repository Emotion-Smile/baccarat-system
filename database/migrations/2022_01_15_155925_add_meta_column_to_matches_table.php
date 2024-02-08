<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMetaColumnToMatchesTable extends Migration
{

    public function up(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->json('meta')->nullable()->after('total_ticket');
        });
    }


    public function down(): void
    {
        Schema::table('matches', function (Blueprint $table) {
            $table->dropColumn('meta');
        });
    }
}
