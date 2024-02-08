<?php

namespace App\Kravanh\Domain\Game\Supports;

enum GameName: string
{
    //https://php.watch/versions/8.1/enums#:~:text=Enums%20as%20Parameter%2C%20Property%20and,one%20of%20the%20Enumerated%20values.&text=The%20play%20function%20accepts%20an,will%20result%20in%20a%20%5CTypeError%20.
    case DragonTiger = 'dragon_tiger';
    case Cockfight = 'cockfight';
    case Sport = 'sport';
    case Baccarat = 'baccarat';
}
