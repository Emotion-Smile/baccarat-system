<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIpInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('ip_infos', function (Blueprint $table) {
            $table->ipAddress('ip')->primary();
            $table->unsignedInteger('user_id');
            $table->string('name');
            $table->string('city')->nullable();
            $table->string('region')->nullable();
            $table->string('country')->nullable();
            $table->boolean('vpn')->default(false);
            $table->boolean('proxy')->default(false);
            $table->boolean('tor')->default(false);
            $table->boolean('relay')->default(false);
            $table->boolean('hosting')->default(false);
            $table->string('service')->nullable();
            $table->json('payload');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_infos');
    }
}
