<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameTableConditionsTable extends Migration
{

    public function up(): void
    {
        Schema::create('game_table_conditions', function (Blueprint $table) {

            $table->unsignedBigInteger('game_id');
            $table->unsignedBigInteger('game_table_id');
            $table->unsignedBigInteger('user_id');
            $table->string('user_type', 20);
            $table->boolean('is_allowed')->default(true); // disable table
            $table->jsonb('share_and_commission');
            $table->jsonb('bet_condition');
            $table->timestamps();

            $table->primary(['game_id', 'game_table_id', 'user_id']);
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('game_table_conditions');
    }
}
