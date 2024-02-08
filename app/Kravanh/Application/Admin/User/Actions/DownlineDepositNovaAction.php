<?php

namespace App\Kravanh\Application\Admin\User\Actions;

use App\Kravanh\Domain\Match\Exceptions\TransactionNotAllowed;
use App\Kravanh\Domain\User\Actions\BalanceTransferAction;
use App\Kravanh\Domain\User\Exceptions\BalanceIsBlocked;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use KravanhEco\Balance\Balance;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;
use Laravel\Nova\Fields\Currency;
use Laravel\Nova\Fields\Textarea;
use Throwable;

class DownlineDepositNovaAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Deposit';

    public $confirmButtonText = 'Deposit';

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function handle(ActionFields $fields, Collection $models): array
    {
        foreach ($models as $receiver) {
            try {

                if ($this->user->isCompany()
                    && $this->user->hasPermission('Member:direct-deposit')) {
                    $receiver->deposit($fields->amount);
                    return Action::message('Operation run successfully');
                }

                (new BalanceTransferAction())($this->user, $receiver, $fields->remark ?? '', $fields->amount, 'to_downline');
            } catch (BalanceIsBlocked|TransactionNotAllowed $exception) {
                return Action::danger(__($exception->getMessage()));
            } catch (Throwable $e) {
                return Action::danger($e->getMessage());
            }

        }

        return Action::message('Operation run successfully');

    }

    public function fields(): array
    {
        $rules = ['required', 'lte:' . $this->user->getCurrentBalance()];

        if ($this->user->isCompany()) {
            unset($rules[1]);
        }

        return [
            // Currency::make('Amount')
            //     ->rules($rules)
            //     ->symbol($this->user->currency)
            //     ->help('Your currently balance: ' . priceFormat($this->user->getCurrentBalance(), $this->user->currency)),

            Balance::make('Amount')
                ->rules($rules)
                ->currency($this->user->currency)
                ->help('Your currently balance: ' . priceFormat($this->user->getCurrentBalance(), $this->user->currency)),

            Textarea::make('Remark')
        ];
    }
}


