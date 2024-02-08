<?php

namespace App\Kravanh\Domain\Baccarat\Support;

enum BaccaratGameWinner
{
    const Player = 'player';
    const Banker = 'banker';
    const Tie = 'tie';
    const Cancel = 'cancel';
}
