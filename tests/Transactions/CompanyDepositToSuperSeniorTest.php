<?php


use App\Kravanh\Domain\User\Actions\CompanyDepositToSuperSeniorAction;
use App\Kravanh\Support\Enums\Currency;
use Database\Factories\UserCompanyFactory;
use Database\Factories\UserSuperSeniorFactory;

test('The company can deposit to super senior',
    function ($firstDepositAmount, $secondDepositAmount, $exchangeRate, $currency) {

        $company = UserCompanyFactory::new()->create();
        $superSenior = UserSuperSeniorFactory::new(['currency' => $currency])->create();
        $currentBalance = (int)($firstDepositAmount * $exchangeRate);

        (new CompanyDepositToSuperSeniorAction())($company, $superSenior, 'test', $firstDepositAmount);

        $superSenior->refresh();

        expect($superSenior->balanceInt)->toBe($currentBalance)
            ->and($superSenior->currency->value)->toBe($currency);

        $firstTransactionMeta = $superSenior->transactions->first()->meta;
        expect($firstTransactionMeta['type'])->toBe('company_deposit')
            ->and($firstTransactionMeta['deposit_by'])->toBe($company->id)
            ->and($firstTransactionMeta['depositor'])->toBe($company->name)
            ->and($firstTransactionMeta['receiver'])->toBe($superSenior->name)
            ->and($firstTransactionMeta['receiver_id'])->toBe($superSenior->id)
            ->and($firstTransactionMeta['before_balance'])->toBe(0)
            ->and($firstTransactionMeta['current_balance'])->toBe($currentBalance)
            ->and($firstTransactionMeta['currency'])->toBe($superSenior->currency->value);

        $beforeBalance = $superSenior->balanceInt;
        (new CompanyDepositToSuperSeniorAction())($company, $superSenior, 'test', $secondDepositAmount);

        expect($superSenior->transactions()->count())->toBe(2);
        $secondTransactionMeta = $superSenior->transactions()->orderByDesc('id')->first()->meta;

        $currentBalance = (int)(($firstDepositAmount + $secondDepositAmount) * $exchangeRate);

        expect($superSenior->balanceInt)->toBe($currentBalance)
            ->and($secondTransactionMeta['before_balance'])->toBe($beforeBalance)
            ->and($secondTransactionMeta['current_balance'])->toBe($currentBalance)
            ->and($firstTransactionMeta['currency'])->toBe($superSenior->currency->value)
            ->and($superSenior->balanceIsBlocked())->toBeFalse();

    })
    ->with([
        [10000, 20000, 1, Currency::KHR],
        [1, 2, 4000, Currency::USD],
        [50, 60, 134, Currency::THB],
        [50000, 70000, 0.18, Currency::VND]
    ]);




