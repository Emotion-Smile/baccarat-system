<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemberTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        //groupId:matchId:memberTypeId:meron:enable
        //groupId:matchId:memberTypeId:wala:disable
        //groupId:matchId:memberTypeId:draw:disable

        //groupId:matchId:memberTypeId:meron:totalBet
        //groupId:matchId:memberTypeId:wala:totalBet
        //groupId:matchId:memberTypeId:draw:totalBet

        //ticket need member type
        //table users ref with current_team_id
        Schema::create('member_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        Schema::table('bet_records', function (Blueprint $table) {
            $table
                ->unsignedBigInteger('member_type_id')
                ->nullable()
                ->after('group_id');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('bet_records', function (Blueprint $table) {
            $table->dropColumn('member_type_id');
        });

        Schema::dropIfExists('member_type_id');
    }
}
