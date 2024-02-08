<?php

namespace App\Kravanh\Application\Admin\Environment;

use App\Kravanh\Application\Admin\Resource;
use App\Kravanh\Domain\Environment\Models\Group as GroupModel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Naoray\NovaJson\JSON;
use OptimistDigital\NovaSortable\Traits\HasSortableRows;

class Group extends Resource
{
    use HasSortableRows;

    public static $model = GroupModel::class;

    public static $title = 'name';

    public static $search = ['name'];


    public function numberField(string $title, string $name): Number
    {
        return Number::make($title, $name)
            ->default(0)
            ->rules(
                'required',
                'numeric'
            )
            ->hideFromIndex();
    }

    public function fields(Request $request): array
    {
        return [
            ID::make('Id'),

            BelongsTo::make('Environment')->rules('required'),

            Text::make('Name')->rules('required'),

            Text::make('Streaming Name'),

            Text::make('Streaming Link 1', 'streaming_link')->rules('required'),

            Text::make('Streaming Link 2', 'streaming_link_1'),

            Select::make('Default Streaming Link')
                ->options([
                    'streaming_link' => 'Streaming Link 1',
                    'streaming_link_1' => 'Streaming Link 2'
                ])
                ->default('streaming_link')
                ->displayUsingLabels(),

            Text::make('Streaming Logo'),

            Text::make('Meron')
                ->rules('required')
                ->default('Meron'),

            Text::make('Wala')
                ->rules('required')
                ->default('Wala'),

            Select::make('CSS Style', 'css_style')->options([
                'bg-channel-1' => 'bg-channel-1',
                'bg-channel-2' => 'bg-channel-2',
                'bg-channel-3' => 'bg-channel-3',
                'bg-channel-4' => 'bg-channel-4',
                'bg-channel-5' => 'bg-channel-5',
                'bg-channel-6' => 'bg-channel-6',
            ]),

            Text::make('Iframe Allow')->nullable(),

            Text::make('Direct Link Allow')->nullable(),

            Text::make('Streaming Server Ip')->rules('required'),

            Boolean::make('Active')
                ->default(true),
            Boolean::make('Use Second Trader', 'use_second_trader')->default(false),

            Boolean::make('Auto Trader')
                ->default(false),

            Boolean::make('Show Fight Number')
                ->default(false),
            Text::make('Telegram Chat Id'),
            JSON::make('Meta', 'meta', [
                $this->numberField('KHR Match Limit', 'KHR_match_limit'),
                $this->numberField('USD Match Limit', 'USD_match_limit'),
                $this->numberField('THB Match Limit', 'THB_match_limit'),
                $this->numberField('VND Match Limit', 'VND_match_limit'),
                $this->numberField('KHR Minimum bet per ticket', 'KHR_min_bet'),
                $this->numberField('KHR Maximum bet per ticket', 'KHR_max_bet'),
                $this->numberField('USD Minimum bet per ticket', 'USD_min_bet'),
                $this->numberField('USD Maximum bet per ticket', 'USD_max_bet'),
                $this->numberField('THB Minimum bet per ticket', 'THB_min_bet'),
                $this->numberField('THB Maximum bet per ticket', 'THB_max_bet'),
                $this->numberField('VND Minimum bet per ticket', 'VND_min_bet'),
                $this->numberField('VND Maximum bet per ticket', 'VND_max_bet'),
            ])
        ];
    }
}
