<?php

namespace App\Kravanh\Application\Admin\Environment;

use App\Kravanh\Application\Admin\Environment\Actions\ReloadVideoStreaming;
use App\Kravanh\Application\Admin\Environment\Actions\ShowMemberIdNovaAction;
use App\Kravanh\Application\Admin\Resource;
use App\Kravanh\Domain\Environment\Models\Environment as EnvironmentModel;
use Illuminate\Http\Request;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Text;

class Environment extends Resource
{
    public static $model = EnvironmentModel::class;

    public static $title = 'name';

    public static $search = ['name'];

    public function fields(Request $request)
    {
        return [
            ID::make('Id'),
            Text::make('Name')->rules('required'),
            Text::make('Domain')->rules('required')
        ];
    }

    public function actions(Request $request)
    {
        return [
            ReloadVideoStreaming::make(),
            ShowMemberIdNovaAction::make()
        ];
    }
}
