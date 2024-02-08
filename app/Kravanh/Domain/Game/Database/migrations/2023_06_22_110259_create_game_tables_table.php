<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameTablesTable extends Migration
{

    public function up(): void
    {
        Schema::create('game_tables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_id');
            $table->string('label');
            $table->string('stream_url');
            $table->boolean('active')->default(true);
            $table->jsonb('bet_condition')->nullable();
            $table->jsonb('options')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('game_tables');
    }
}
