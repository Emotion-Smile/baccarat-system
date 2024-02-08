<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{

    public function up(): void
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('environment_id');
            $table->string('name');
            $table->string('streaming_link')->nullable();
            $table->string('iframe_allow')->nullable();
            $table->string('direct_link_allow')->nullable();
            $table->string('streaming_server_ip')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
}
