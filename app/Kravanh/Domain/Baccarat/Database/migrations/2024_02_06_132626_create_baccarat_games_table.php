<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaccaratGamesTable extends Migration
{

    public function up(): void
    {
        Schema::create('baccarat_games', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_table_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('result_submitted_user_id')->nullable();
            $table->unsignedInteger('round'); //1
            $table->unsignedInteger('number'); //12  = 1/12
            $table->unsignedTinyInteger('player_first_card_value')->nullable();
            $table->string('player_first_card_type')->nullable();
            $table->string('player_first_card_color')->nullable();
            $table->unsignedTinyInteger('player_first_card_points')->nullable();
            $table->unsignedTinyInteger('player_second_card_value')->nullable();
            $table->string('player_second_card_type')->nullable();
            $table->string('player_second_card_color')->nullable();
            $table->unsignedTinyInteger('player_second_card_points')->nullable();
            $table->unsignedTinyInteger('player_third_card_value')->nullable();
            $table->string('player_third_card_type')->nullable();
            $table->string('player_third_card_color')->nullable();
            $table->unsignedTinyInteger('player_third_card_points')->nullable();
            $table->unsignedTinyInteger('player_total_points')->nullable();
            $table->unsignedTinyInteger('player_points')->nullable();

            $table->unsignedTinyInteger('banker_first_card_value')->nullable();
            $table->string('banker_first_card_type')->nullable();
            $table->string('banker_first_card_color')->nullable();
            $table->unsignedTinyInteger('banker_first_card_points')->nullable();
            $table->unsignedTinyInteger('banker_second_card_value')->nullable();
            $table->string('banker_second_card_type')->nullable();
            $table->string('banker_second_card_color')->nullable();
            $table->unsignedTinyInteger('banker_second_card_points')->nullable();
            $table->unsignedTinyInteger('banker_third_card_value')->nullable();
            $table->string('banker_third_card_type')->nullable();
            $table->string('banker_third_card_color')->nullable();
            $table->unsignedTinyInteger('banker_third_card_points')->nullable();
            $table->unsignedTinyInteger('banker_total_points')->nullable();
            $table->unsignedTinyInteger('banker_points')->nullable();
            $table->jsonb('winner')->nullable(); //player,banker,tie,big,small,player.pair,banker.pair
            $table->timestamp('started_at');
            $table->timestamp('closed_bet_at');
            $table->timestamp('result_submitted_at')->nullable();
            // $table->jsonb('statistic')->nullable();
            $table->timestamps();

            // $table->string('player_type', 20)->nullable();
            // $table->string('player_range', 20)->nullable();
            // $table->unsignedTinyInteger('player_result')->nullable(); // 0-9
            // // $table->json('player_card'); //card1: 4, card2: 4, card3: 5 or optional
            // $table->string('player_card_1');
            // $table->string('player_card_2');
            // $table->string('player_card_3')->nullable();
            // $table->unsignedTinyInteger('banker_result')->nullable(); // 0-9
            // $table->string('banker_type', 20)->nullable(); //heart,diamond,club,spade
            // $table->string('banker_range', 20)->nullable(); //big,small
            // // $table->json('banker_card'); //card1: 4, card2: 4, card3: 5 or optional
            // $table->string('banker_card_1');
            // $table->string('banker_card_2');
            // $table->string('banker_card_3')->nullable();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('baccarat_games');
    }
}
