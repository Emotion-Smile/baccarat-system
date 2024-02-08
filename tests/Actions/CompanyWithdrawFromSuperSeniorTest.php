<?php


use App\Kravanh\Domain\User\Actions\CompanyDepositToSuperSeniorAction;
use App\Kravanh\Domain\User\Actions\CompanyWithdrawFromSuperSeniorAction;
use App\Kravanh\Support\Enums\Currency;
use Bavix\Wallet\Exceptions\BalanceIsEmpty;
use Database\Factories\UserCompanyFactory;
use Database\Factories\UserSuperSeniorFactory;

test('The company can withdraw from super senior',
    function ($firstWithdrawAmount, $secondWithdrawAmount, $exchangeRate, $currency) {

        $company = UserCompanyFactory::new()->create();
        $superSenior = UserSuperSeniorFactory::new(['currency' => $currency])->create();
        $currentBalance = (int)(($firstWithdrawAmount + $secondWithdrawAmount) * $exchangeRate);
        (new CompanyDepositToSuperSeniorAction())($company, $superSenior, 'test', ($firstWithdrawAmount + $secondWithdrawAmount));
        expect($superSenior->balanceInt)->toBe($currentBalance);

        (new CompanyWithdrawFromSuperSeniorAction())($company, $superSenior, 'test', $firstWithdrawAmount);
        $superSenior->refresh();

        expect($superSenior->currency->value)->toBe($currency);

        $firstTransactionMeta = $superSenior
            ->transactions()
            ->where('type', 'withdraw')
            ->first()->meta;

        expect($firstTransactionMeta['type'])->toBe('withdraw');
        expect($firstTransactionMeta['mode'])->toBe('company');
        expect($firstTransactionMeta['withdraw_by'])->toBe($company->id);
        expect($firstTransactionMeta['withdrawer'])->toBe($company->name);
        expect($firstTransactionMeta['withdraw_from'])->toBe($superSenior->name);
        expect($firstTransactionMeta['withdraw_from_id'])->toBe($superSenior->id);
        expect($firstTransactionMeta['before_balance'])->toBe($currentBalance);
        expect($firstTransactionMeta['current_balance'])->toBe($currentBalance - (int)($firstWithdrawAmount * $exchangeRate));
        expect($firstTransactionMeta['currency'])->toBe($superSenior->currency->value);

        (new CompanyWithdrawFromSuperSeniorAction())($company, $superSenior, 'rest', $secondWithdrawAmount);

        expect($superSenior->transactions()->count())->toBe(3);
        $secondTransactionMeta = $superSenior
            ->transactions()
            ->where('type', 'withdraw')
            ->orderByDesc('id')
            ->first()->meta;

        $currentBalance = (int)(($firstWithdrawAmount + $secondWithdrawAmount) * $exchangeRate);

        expect($superSenior->balanceInt)->toBe(0);
        expect($secondTransactionMeta['before_balance'])->toBe($currentBalance - (int)($firstWithdrawAmount * $exchangeRate));
        expect($secondTransactionMeta['current_balance'])->toBe(0);
        expect($firstTransactionMeta['currency'])->toBe($superSenior->currency->value);
        expect($superSenior->balanceIsBlocked())->toBeFalse();

        (new CompanyWithdrawFromSuperSeniorAction())($company, $superSenior, 'test', $secondWithdrawAmount);

    })
    ->with([
        [10000, 20000, 1, Currency::KHR],
        [1, 2, 4000, Currency::USD],
        [50, 60, 134, Currency::THB],
        [50000, 70000, 0.18, Currency::VND]
    ])->throws(BalanceIsEmpty::class);




