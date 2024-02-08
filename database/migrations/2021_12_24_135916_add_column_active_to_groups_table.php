<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnActiveToGroupsTable extends Migration
{

    public function up(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->after('streaming_server_ip', function (Blueprint $table) {
                $table->boolean('active')->default(true);
                $table->string('css_style')->default('bg-channel-1');
                $table->string('meron')->default('meron');
                $table->string('wala')->default('wala');
            });

        });
    }


    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn(['active', 'css_style', 'meron', 'wala']);
        });
    }
}
