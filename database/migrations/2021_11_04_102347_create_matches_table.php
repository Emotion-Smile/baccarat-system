<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMatchesTable extends Migration
{

    public function up(): void
    {
        Schema::create('matches', function (Blueprint $table) {

            $table->id();

            $table
                ->foreignId('environment_id')
                ->constrained();

            $table
                ->foreignId('user_id')
                ->constrained();

            $table
                ->unsignedBigInteger('fight_number'); // sequence per day

            $table
                ->date('match_date');

            $table
                ->timestamp('match_started_at')
                ->useCurrent();

            $table->timestamp('match_end_at')  // update the of the match
                ->nullable();

            $table
                ->unsignedInteger('payout_total'); // manual update

            $table
                ->unsignedInteger('payout_meron'); // manual update

            $table
                ->timestamp('bet_started_at')  //open bet
                ->nullable();

            $table
                ->timestamp('bet_stopped_at')  //close bet
                ->nullable();

            $table
                ->unsignedTinyInteger('bet_duration')
                ->default(30); // in second

            $table
                ->unsignedBigInteger('meron_total_bet')  //update every bet
                ->default(0);

            $table
                ->unsignedBigInteger('meron_total_payout')  //update every bet
                ->default(0);

            $table
                ->unsignedBigInteger('wala_total_bet')  //update every bet
                ->default(0);

            $table
                ->unsignedBigInteger('wala_total_payout')  //update every bet
                ->default(0);

            $table
                ->unsignedTinyInteger('result')  //update the end of math
                ->default(0);

            $table
                ->unsignedInteger('total_ticket')  //update every bet
                ->default(0);

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
}
