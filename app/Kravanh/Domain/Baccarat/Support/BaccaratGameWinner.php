<?php

namespace App\Kravanh\Domain\Baccarat\Support;

enum BaccaratGameWinner
{
    const Player = 'player';
    const Banker = 'banker';
    const Tie = 'tie';
    const Cancel = 'cancel';
    const Big = 'big';
    const Small = 'small';
    const PlayerPair = 'player_pair';
    const BankerPair = 'banker_pair';
}
