<?php

namespace App\Providers;

use App\Kravanh\Application\Admin\Dashboard\Metrics\TotalAgentValue;
use App\Kravanh\Application\Admin\Dashboard\Metrics\TotalMasterAgentValue;
use App\Kravanh\Application\Admin\Dashboard\Metrics\TotalMemberValue;
use App\Kravanh\Application\Admin\Dashboard\Metrics\TotalSeniorValue;
use App\Kravanh\Application\Admin\Dashboard\Metrics\TotalSuperSeniorValue;
use App\Kravanh\Application\Admin\Nova\LoginController;
use App\Kravanh\Application\Admin\Setting\Settings;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use Coroowicaksono\ChartJsIntegration\BarChart;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use KravanhEco\Report\Report;
use Laravel\Nova\Nova;
use Laravel\Nova\NovaApplicationServiceProvider;
use OptimistDigital\NovaSettings\NovaSettings;

class NovaServiceProvider extends NovaApplicationServiceProvider
{
    public function boot(): void
    {
        parent::boot();

        $this->app->alias(
            LoginController::class,
            \Laravel\Nova\Http\Controllers\LoginController::class
        );

        NovaSettings::addSettingsFields(
            fn() => Settings::fields(),
            Settings::castsFields()
        );
    }


    protected function routes(): void
    {
        Nova::routes()
            ->withAuthenticationRoutes();
        //->withPasswordResetRoutes();
        //->register();
    }

    protected function gate(): void
    {
        Gate::define('viewNova', function ($user = null) {
            return !in_array($user->type->value, [UserType::MEMBER, UserType::TRADER]);
        });
    }

    protected function cards(): array
    {
        $dashboard = [];
        $data = Cache::remember('dashboard:bet:seven-days', now()->addMinutes(5), function () {
            return DB::table('matches')
                ->selectRaw('(SUM(`meron_total_bet`) + SUM(`wala_total_bet`)) as total_amount')
                ->selectRaw('SUM(`total_ticket`) as total_ticket')
                ->selectRaw('match_date')
                ->groupBy('match_date')
                ->limit(7)
                ->orderByDesc('match_date')
                ->get()
                ->reverse();
        });

        $totalAmount = $data->pluck('total_amount')->toArray();
        $totalTick = $data->pluck('total_ticket')->toArray();
        $date = $data->pluck('match_date')->toArray();

        if (user()->isRoot() || user()->isCompany()) {
            $dashboard = [
                TotalSuperSeniorValue::make()->width('1/5'),
                TotalSeniorValue::make()->width('1/5'),
                TotalMasterAgentValue::make()->width('1/5'),
                TotalAgentValue::make()->width('1/5'),
                TotalMemberValue::make()->width('1/5'),
                (new BarChart())
                    ->title('Bet Amount')
                    ->series(array([
                        'barPercentage' => 0.5,
                        'label' => 'Amount',
                        'backgroundColor' => '#679BDB',
                        'data' => $totalAmount,
                    ]))
                    ->options([
                        'xaxis' => [
                            'categories' => $date
                        ],
                    ]),
                (new BarChart())
                    ->title('Ticket')
                    ->series(array([
                        'barPercentage' => 0.5,
                        'label' => 'Ticket',
                        'backgroundColor' => '#DB5E51',
                        'data' => $totalTick,
                    ]))
                    ->options([
                        'xaxis' => [
                            'categories' => $date
                        ],
                    ]),
            ];
        }
        return $dashboard;
//        return [
//            //new TotalUserValue(),
//            //new UserPartition(),
//            //new Help,
////            (new BarChart())
////                ->title('Member Win/Loss')
////                ->animations([
////                    'enabled' => true,
////                    'easing' => 'easeinout',
////                ])
////                ->series(array([
////                    'barPercentage' => 0.5,
////                    'label' => 'Win/Loss',
////                    'borderColor' => '#90ed7d',
////                    'data' => $reportData['win_loss']
////                ]))
////                ->options([
////                    'xaxis' => [
////                        'categories' => $reportData['day']
////                    ],
////                ])
////                ->width('2/3'),
////            (new CompanyWinLossValue())->width('1/3')
//
//        ];
    }

    protected function dashboards(): array
    {
        return [

        ];
    }


    public function tools(): array
    {
        return [
            new Report,
            (new NovaSettings)
                ->canSee(fn() => user()->hasPermission('Setting:full-manage') || user()->isRoot())
        ];
    }


    public function register(): void
    {
        Nova::sortResourcesBy(function ($resource) {
            return $resource::$priority ?? 99999;
        });


        Nova::report(function ($exception) {

        });

    }

    protected function resources(): void
    {

        Nova::resourcesIn(app_path('Kravanh/Application/Admin'));

        Nova::resourcesIn(app_path('Kravanh/Domain/Game/App/Nova'));
        Nova::resourcesIn(app_path('Kravanh/Domain/DragonTiger/App/Nova'));

    }
}
