<?php

namespace App\Kravanh\Application\Admin\User\Actions;

use App\Kravanh\Application\Admin\Support\UseNovaAction;
use App\Kravanh\Domain\User\Actions\CompanyDepositToSuperSeniorAction;
use App\Kravanh\Domain\User\Exceptions\BalanceIsBlocked;
// use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use KravanhEco\Balance\Balance;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
// use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Textarea;

class CompanyDepositToSuperSeniorNovaAction extends Action
{
    use InteractsWithQueue, Queueable;
    use UseNovaAction;

    public $name = 'Deposit';

    public $confirmButtonText = 'Deposit';

    public function __construct(
        private mixed $model
    )
    {}

    public function handle(ActionFields $fields, Collection $models): array
    {
        foreach ($models as $superSenior) {
            try {
                (new CompanyDepositToSuperSeniorAction())(user(), $superSenior, $fields->remark ?? '', $fields->amount);
            } catch (BalanceIsBlocked $exception) {
                return Action::danger(__($exception->getMessage()));
            } catch (\Throwable $e) {
                return Action::danger($e->getMessage());
            }
        }
        return Action::message('Operation run successfully');
    }

    public function fields(): array
    {
        return [
            // Currency::make('Amount')
            //     ->rules('required')
            //     ->symbol($this->model?->currency ?? 'KHR'),

            Balance::make('Amount')
                ->currency($this->model?->currency ?? 'KHR')
                ->rules('required'),

            Textarea::make('Remark')->default('')
        ];
    }


    public static function build(mixed $model)
    {
        return static::make($model)
            ->onlyOnDetail()
            ->allowToRun(fn() => user()->isCompany());
    }


}
