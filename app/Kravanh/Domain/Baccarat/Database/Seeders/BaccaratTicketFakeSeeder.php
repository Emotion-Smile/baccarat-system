<?php

namespace App\Kravanh\Domain\Baccarat\Database\Seeders;

use App\Kravanh\Domain\Baccarat\Models\BaccaratTicket;
use Illuminate\Database\Seeder;

final class BaccaratTicketFakeSeeder extends Seeder
{

    // php artisan db:seed --class=\\App\\Kravanh\\Domain\\Baccarat\\Database\\Seeders\\BaccaratTicketFakeSeeder
    public function run(): void
    {
        BaccaratTicket::truncate();
        BaccaratTicket::factory()->count(10)->create();

    }

}
