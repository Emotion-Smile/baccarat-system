<?php

use App\Kravanh\Domain\User\Supports\Enums\Status;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table
                ->foreignId('current_team_id')
                ->nullable();

            $table
                ->foreignId('environment_id') // testing -> test.cbs.cocking.com, production
                ->nullable();

            $table->unsignedBigInteger('super_senior')
                ->default(0);

            $table->unsignedBigInteger('senior')
                ->default(0);

            $table->unsignedBigInteger('master_agent')
                ->default(0);

            $table
                ->unsignedBigInteger('agent')
                ->default(0);

            $table->string('name');

            $table
                ->string('phone')
                ->nullable();

            $table
                ->string('email')
                ->unique()
                ->nullable();

            $table
                ->timestamp('email_verified_at')
                ->nullable();

            $table
                ->string('password');

            $table
                ->string('type', 50)
                ->default(UserType::MEMBER);

            $table->json('condition')
                ->nullable();

            $table
                ->rememberToken();

            $table
                ->string('profile_photo_path', 2048)
                ->nullable();

            $table
                ->boolean('internet_betting')
                ->default(true);

            $table
                ->string('status')
                ->default(Status::OPEN); //open, lock  -> if lock not allow to bet the math

            $table
                ->boolean('suspend')
                ->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}
