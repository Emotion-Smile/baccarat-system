<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnMetaToGroupsTable extends Migration
{

    public function up(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->json('meta')
                ->after('active')
                ->nullable();
        });
    }


    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn('meta');
        });
    }
}
