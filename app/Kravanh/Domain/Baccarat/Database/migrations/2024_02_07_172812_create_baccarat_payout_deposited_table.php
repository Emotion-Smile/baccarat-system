<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaccaratPayoutDepositedTable extends Migration
{

    public function up(): void
    {
        Schema::create('baccarat_payout_deposited', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('baccarat_game_id');
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('transaction_id');
            $table->decimal('amount', 64, 0);
            $table->string('ticket_ids');
            $table->unsignedBigInteger('rollback_transaction_id')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('baccarat_payout_deposited');
    }
}
