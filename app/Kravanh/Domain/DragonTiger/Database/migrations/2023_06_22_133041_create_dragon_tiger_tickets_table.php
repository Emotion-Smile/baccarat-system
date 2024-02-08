<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDragonTigerTicketsTable extends Migration
{

    public function up(): void
    {
        Schema::create('dragon_tiger_tickets', function (Blueprint $table) {

            $table->id();

            $table->foreignId('game_table_id');
            $table->foreignId('dragon_tiger_game_id');
            $table->foreignId('user_id');

            $table->unsignedBigInteger('super_senior')->default(0);
            $table->unsignedBigInteger('senior')->default(0);
            $table->unsignedBigInteger('master_agent')->default(0);
            $table->unsignedBigInteger('agent')->default(0);
            $table->unsignedBigInteger('amount'); // 10000  KHR

            $table->string('bet_on', 20); // dragon or tiger or tie
            $table->string('bet_type', 20); // tie, tiger, dragon, red, black, small, big

            $table->unsignedFloat('payout_rate'); // 1.0, 0.90
            $table->unsignedBigInteger('payout'); // 9000

            $table->string('status', 20)->default('accepted'); // accepted, pending ,cancelled

            $table->jsonb('share'); // {company: 10, superSenior: 10}
            $table->jsonb('commission'); // {company: 0, superSenior: 1}

            $table->unsignedSmallInteger('in_year'); //2023
            $table->unsignedTinyInteger('in_month'); // 01
            $table->unsignedInteger('in_day'); // 20230901

            $table->ipAddress('ip');
            $table->timestamps();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('dragon_tiger_tickets');
    }
}
