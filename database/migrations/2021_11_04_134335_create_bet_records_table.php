<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBetRecordsTable extends Migration
{

    public function up(): void
    {
        Schema::create('bet_records', function (Blueprint $table) {

            $table->id();

            $table
                ->foreignId('environment_id') // testing -> test.cbs.cocking.com, production
                ->nullable();

            $table->foreignId('match_id')
                ->constrained();

            $table->foreignId('user_id')
                ->constrained(); // member

            $table
                ->unsignedBigInteger('super_senior')
                ->default(0);

            $table
                ->unsignedBigInteger('senior')
                ->default(0);

            $table
                ->unsignedBigInteger('master_agent')
                ->default(0);

            $table
                ->unsignedBigInteger('agent')
                ->default(0);

            $table->unsignedInteger('fight_number');

            $table
                ->date('bet_date');

            $table
                ->timestamp('bet_time')
                ->useCurrent();

            $table
                ->unsignedBigInteger('amount'); // 10000

            $table
                ->unsignedFloat('payout_rate'); // 0.90

            $table
                ->unsignedBigInteger('payout'); // 9000

            $table->bigInteger('benefit'); // 1000

            $table
                ->unsignedTinyInteger('bet_on'); // meron or wala

            $table
                ->unsignedTinyInteger('result') //
                ->default(0);

            $table
                ->unsignedTinyInteger('status')
                ->default(0); // accepted, pending ,cancelled

            $table
                ->boolean('type')
                ->default(0); //auto_accept,check,suspect

            $table
                ->string('ip')
                ->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bet_records');
    }
}
