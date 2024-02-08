<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWalletBackupsTable extends Migration
{

    public function up(): void
    {
        Schema::create('wallet_backups', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('wallet_id');
            $table->unsignedBigInteger('holder_id');
            $table->string('holder_type');
            $table->decimal('balance', 64, 0)->default(0);
            $table->decimal('cache_balance', 64, 0)->default(0);
            $table->decimal('login_balance', 64, 0)->default(0);
            $table->integer('last_updated_balance'); //202311
            $table->boolean('is_cache_balance_updated')->default(false);
            $table->timestamps();

            $table->index('last_updated_balance');
            $table->index('is_cache_balance_updated');
            $table->index(['holder_id', 'holder_type']);


        });
    }


    public function down(): void
    {
        Schema::dropIfExists('wallet_backups');
    }
}
