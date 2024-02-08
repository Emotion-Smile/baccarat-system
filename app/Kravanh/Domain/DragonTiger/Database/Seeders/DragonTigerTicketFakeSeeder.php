<?php

namespace App\Kravanh\Domain\DragonTiger\Database\Seeders;

use App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket;
use Illuminate\Database\Seeder;

final class DragonTigerTicketFakeSeeder extends Seeder
{

    // php artisan db:seed --class=\\App\\Kravanh\\Domain\\DragonTiger\\Database\\Seeders\\DragonTigerTicketFakeSeeder
    public function run(): void
    {
        DragonTigerTicket::truncate();
        DragonTigerTicket::factory()->count(10)->create();

    }

}
