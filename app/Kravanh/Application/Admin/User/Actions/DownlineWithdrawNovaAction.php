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

class DownlineWithdrawNovaAction extends Action
{
    use InteractsWithQueue, Queueable;

    public $name = 'Withdraw';

    public $confirmButtonText = 'Withdraw';

    private $receiveUser;

    public function __construct(User $receiveUser)
    {
        $this->receiveUser = $receiveUser;
    }

    public function actionClass()
    {
        return 'btn-danger';
    }


    public function handle(ActionFields $fields, Collection $models): array
    {
        //block withdraw
        foreach ($models as $sender) {

            try {
                $memberBlocked = cache('balance:withdraw:block', []);

                if (in_array($sender->id, $memberBlocked)) {
                    return Action::danger('Your balance was blocked because the match need to submit the result');
                }

                cache()->forget('balance:withdraw:block');

                if ($this->receiveUser->isCompany()
                    && $this->receiveUser->hasPermission('Member:direct-withdraw')) {

                    $sender->forceWithdraw($fields->amount);
//                    $currentBalance = $sender->balanceInt;
//                    $meta = [
//                        'type' => 'withdraw',
//                        'mode' => 'company',
//                        'withdraw_by' => $this->receiveUser->id,
//                        'withdrawer' => $this->receiveUser->name,
//                        'withdraw_from' => $sender->name,
//                        'withdraw_from_id' => $sender->id,
//                        'before_balance' => $currentBalance + $fields->amount,
//                        'current_balance' => $currentBalance,
//                        'wallet_balance' => $currentBalance,
//                        'currency' => $sender->currency,
//                        'note' => $fields->remark ?? ''
//                    ];
//
//                    $tx->meta = $meta;
//                    $tx->save();


                    return Action::message('Operation run successfully');
                }

                (new BalanceTransferAction())($sender, $this->receiveUser, $fields->remark ?? '', $fields->amount, 'from_downline');

            } catch (BalanceIsBlocked|TransactionNotAllowed $exception) {
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
            //     ->symbol($this->receiveUser->currency)
            //     ->rules('required'),

            Balance::make('Amount')
                ->currency($this->receiveUser->currency)
                ->rules('required'),

            Textarea::make('Remark')
        ];
    }
}
