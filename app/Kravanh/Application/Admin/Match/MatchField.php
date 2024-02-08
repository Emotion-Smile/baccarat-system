<?php

namespace App\Kravanh\Application\Admin\Match;

use App\Kravanh\Application\Admin\Environment\Environment;
use App\Kravanh\Application\Admin\Environment\Group;
use App\Kravanh\Application\Admin\User\Trader;
use App\Kravanh\Domain\Match\Supports\Enums\MatchResult;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Text;
use SimpleSquid\Nova\Fields\Enum\Enum;

trait MatchField
{

    public function buildForm(): array
    {
        return [
            ID::make(__('ID'), 'id')->sortable()->hideFromIndex(),
            BelongsTo::make(
                'Environment',
                'environment',
                Environment::class
            )->hideFromIndex(),
            BelongsTo::make(
                'Trader',
                'user',
                Trader::class,
            )->sortable(),
            BelongsTo::make(
                'Group',
                'group',
                Group::class
            ),
            Text::make('Fight Number', 'fight_number'),
            Enum::make('Result', 'result')->attach(MatchResult::class),
            DateTime::make('Date', 'match_date')
                ->format('DD-M-Y')
                ->sortable(),

            DateTime::make('Started', 'match_started_at')
                ->format('HH:mm:ss'),

            DateTime::make('Ended', 'match_end_at')
                ->format('HH:mm:ss'),

//            Text::make('Bet Duration', function () {
//                return $this->total_bet_duration . ' sec';
//            })->hideFromIndex(),
            Number::make('Tickets', 'total_ticket')
                ->sortable(),

            Number::make('Total bet', function () {
                return priceFormat($this->totalBet());
            }),


//            Number::make('If meron win', function () {
//                return priceFormat($this->meronBenefit(), '');
//            })->hideFromIndex(),
//
//            Number::make('If wala win', function () {
//                return priceFormat($this->walaBenefit(), '');
//            })->hideFromIndex(),

            HasMany::make('Bet Records', 'BetRecords', BetRecord::class)
        ];
    }
}
