<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDragonTigerGamesTable extends Migration
{

    public function up(): void
    {
        Schema::create('dragon_tiger_games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_table_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('result_submitted_user_id')->nullable();
            $table->unsignedInteger('round'); //1
            $table->unsignedInteger('number'); //12  = 1/12
            $table->unsignedTinyInteger('dragon_result')->nullable(); // 1-13
            $table->string('dragon_type', 20)->nullable(); //heart,diamond,club,spade
            $table->string('dragon_color', 20)->nullable(); //red,black
            $table->string('dragon_range', 20)->nullable(); //big,small
            $table->unsignedTinyInteger('tiger_result')->nullable(); // 1-13
            $table->string('tiger_type', 20)->nullable(); //heart,diamond,club,spade
            $table->string('tiger_color', 20)->nullable(); //red,black
            $table->string('tiger_range', 20)->nullable(); //big,small
            $table->string('winner', 20)->nullable(); //dragon,tiger,tie
            $table->timestamp('started_at');
            $table->timestamp('closed_bet_at');
            $table->timestamp('result_submitted_at')->nullable();
            $table->jsonb('statistic')->nullable();
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('dragon_tiger_games');
    }
}
