<?php

namespace App\Kravanh\Application\Admin\Environment;

use App\Kravanh\Application\Admin\Resource;
use App\Kravanh\Domain\Environment\Models\Domain as DomainModel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Naoray\NovaJson\JSON;
use NovaItemsField\Items;

class Domain extends Resource
{
    public static $model = DomainModel::class;

    public static $title = 'domain';

    public static $search = ['domain'];

    public function fields(Request $request): array
    {
        return [
            Text::make('Domain')
                ->creationRules([
                    'required',
                    'unique:domains,domain'
                ])
                ->updateRules([
                    'required',
                    'unique:domains,domain,{{resourceId}}'
                ]),
            
            JSON::make('Meta', 'meta', [
                Select::make('Login View', 'login_view')
                    ->options([
                        'Default' => 'Default',
                        'OptionOne' => 'Option One',
                    ])
                    ->displayUsingLabels(), 
                    
                Select::make('Theme Color', 'theme_color')
                    ->options([
                        '' => 'Default',
                        'sd88' => 'SD88',
                        'kv88' => 'KV88',
                        'ls88' => 'LS88',
                        'vk88' => 'VK88',
                        'ct7' => 'CT7',
                        'sco88' => 'SCO88',
                        'dp88' => 'DP88',
                        't88' => 'T88',
                        'vt88' => 'VT88',
                        'jack388' => 'JACK388',
                        'pb' => 'Power Betting',
                    ])
                    ->displayUsingLabels(), 
                    
                Text::make('Logo Url', 'logo_url')
                    ->onlyOnForms(),

                Image::make('Logo Url', 'logo_url')
                    ->preview(function ($value) {
                        return $value;
                    })
                    ->thumbnail(function ($value) {
                        return $value;
                    })
                    ->disableDownload()
                    ->exceptOnForms(),

                Number::make('Logo Width', 'logo_width'),

                Items::make('Promotion Images', 'promotion_images')
                    ->placeholder('Image Link')
                    ->createButtonValue('+')
                    ->draggable()
                    ->hideFromIndex(),

                Items::make('Phone Numbers', 'phone_numbers')
                    ->inputType('number')
                    ->placeholder('Phone Number')
                    ->createButtonValue('+')
                    ->draggable()
                    ->hideFromIndex(),

                Text::make('Telegram Link', 'telegram_link'),

                Text::make('Facebook Link', 'facebook_link'),

                Text::make('Copyright Text', 'copyright_text'),
            ]),
        ];
    }
}
