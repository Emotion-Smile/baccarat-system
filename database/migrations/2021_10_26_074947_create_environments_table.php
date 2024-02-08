<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEnvironmentsTable extends Migration
{

    public function up(): void
    {
        Schema::create('environments', function (Blueprint $table) {

            $table->id();

            $table->string('name');

            $table->string('domain');

            $table
                ->boolean('active')
                ->default(true);

            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('environments');
    }
}
