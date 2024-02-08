<?php

use App\Kravanh\Domain\User\Actions\BalanceTransferAction;
use App\Kravanh\Domain\User\Actions\CompanyDepositToSuperSeniorAction;
use App\Kravanh\Support\Enums\Currency;
use Bavix\Wallet\Exceptions\InsufficientFunds;
use Database\Factories\UserCompanyFactory;
use Database\Factories\UserSeniorFactory;
use Database\Factories\UserSuperSeniorFactory;

test('it can transfer from parent to downline', function ($firstAmount, $secondAmount, $exchangeRate, $currency, $mode) {
    $company = UserCompanyFactory::new()->create();
    $superSenior = UserSuperSeniorFactory::new(['currency' => $currency])->create();
    $senior = UserSeniorFactory::new(['super_senior' => $superSenior->id, 'currency' => $currency])->create();
    $superSeniorTotalBalanceInKHR = ($firstAmount + $secondAmount) * $exchangeRate;

    (new CompanyDepositToSuperSeniorAction())(
        company: $company,
        superSenior: $superSenior,
        remark: 'test',
        amount: ($firstAmount + $secondAmount));

    expect($superSenior->balanceInt)->toBe((int)$superSeniorTotalBalanceInKHR);

    (new BalanceTransferAction)(
        sender: $superSenior,
        receiver: $senior,
        remark: 'test',
        amount: $firstAmount,
        mode: $mode
    );

    $superSeniorCurrentBalanceInKHR = $superSenior->balanceInt;
    expect($superSeniorCurrentBalanceInKHR)->toBe((int)($superSeniorTotalBalanceInKHR - ($firstAmount * $exchangeRate)));

    $seniorCurrentBalanceInKHR = $senior->balanceInt;
    expect($seniorCurrentBalanceInKHR)->toBe((int)($firstAmount * $exchangeRate));

    $depositMeta = $senior->transactions()->where('type', 'deposit')->first()->meta;
    expect($depositMeta['type'])->toBe('deposit');
    expect($depositMeta['receiver'])->toBe($senior->name);
    expect($depositMeta['receiver_id'])->toBe($senior->id);
    expect($depositMeta['sender'])->toBe($superSenior->name);
    expect($depositMeta['sender_id'])->toBe($superSenior->id);
    expect($depositMeta['before_balance'])->toBe(0);
    expect($depositMeta['current_balance'])->toBe((int)($firstAmount * $exchangeRate));
    expect($depositMeta['currency'])->toBe($senior->currency->value);


    $withDrawMeta = $superSenior->transactions()->where('type', 'withdraw')->first()->meta;
    expect($withDrawMeta['type'])->toBe('withdraw');
    expect($withDrawMeta['receiver'])->toBe($senior->name);
    expect($withDrawMeta['receiver_id'])->toBe($senior->id);
    expect($withDrawMeta['sender'])->toBe($superSenior->name);
    expect($withDrawMeta['sender_id'])->toBe($superSenior->id);
    expect($withDrawMeta['before_balance'])->toBe((int)$superSeniorTotalBalanceInKHR);
    expect($withDrawMeta['current_balance'])->toBe((int)($superSeniorTotalBalanceInKHR - ($firstAmount * $exchangeRate)));
    expect($withDrawMeta['currency'])->toBe($superSenior->currency->value);

    expect($senior->balanceIsBlocked())->toBeFalse();
    expect($superSenior->balanceIsBlocked())->toBeFalse();


    $superSeniorBeforeBalance = $superSenior->balanceInt;
    $seniorBeforeBalance = $senior->balanceInt;
    (new BalanceTransferAction)(
        sender: $superSenior,
        receiver: $senior,
        remark: 'test',
        amount: $secondAmount,
        mode: $mode
    );

    $superSeniorCurrentBalanceInKHR = $superSenior->balanceInt;
    expect($superSeniorCurrentBalanceInKHR)->toBe(0);

    $seniorCurrentBalanceInKHR = $senior->balanceInt;
    expect($seniorCurrentBalanceInKHR)->toBe((int)(($firstAmount + $secondAmount) * $exchangeRate));

    $depositMeta = $senior
        ->transactions()
        ->where('type', 'deposit')
        ->orderByDesc('id')->first()->meta;

    expect($depositMeta['type'])->toBe('deposit');
    expect($depositMeta['mode'])->toBe($mode);
    expect($depositMeta['receiver'])->toBe($senior->name);
    expect($depositMeta['receiver_id'])->toBe($senior->id);
    expect($depositMeta['sender'])->toBe($superSenior->name);
    expect($depositMeta['sender_id'])->toBe($superSenior->id);
    expect($depositMeta['before_balance'])->toBe($seniorBeforeBalance);
    expect($depositMeta['current_balance'])->toBe($seniorBeforeBalance + (int)($secondAmount * $exchangeRate));
    expect($depositMeta['currency'])->toBe($senior->currency->value);


    $withDrawMeta = $superSenior
        ->transactions()
        ->where('type', 'withdraw')
        ->orderByDesc('id')
        ->first()->meta;

    expect($withDrawMeta['type'])->toBe('withdraw');
    expect($depositMeta['mode'])->toBe($mode);
    expect($withDrawMeta['receiver'])->toBe($senior->name);
    expect($withDrawMeta['receiver_id'])->toBe($senior->id);
    expect($withDrawMeta['sender'])->toBe($superSenior->name);
    expect($withDrawMeta['sender_id'])->toBe($superSenior->id);
    expect($withDrawMeta['before_balance'])->toBe($superSeniorBeforeBalance);
    expect($withDrawMeta['current_balance'])->toBe(0);
    expect($withDrawMeta['currency'])->toBe($superSenior->currency->value);

    expect($senior->balanceIsBlocked())->toBeFalse();
    expect($superSenior->balanceIsBlocked())->toBeFalse();

})->with([
    [10000, 20000, 1, Currency::KHR, 'to_downline'],
    [1, 2, 4000, Currency::USD, 'to_downline'],
    [50, 60, 134, Currency::THB, 'to_downline'],
    [50000, 70000, 0.18, Currency::VND, 'to_downline']
]);


test('it can transfer from downline to parent', function ($firstAmount, $secondAmount, $exchangeRate, $currency, $mode) {
    $company = UserCompanyFactory::new()->create();
    $superSenior = UserSuperSeniorFactory::new(['currency' => $currency])->create();
    $senior = UserSeniorFactory::new(['super_senior' => $superSenior->id, 'currency' => $currency])->create();
    $superSeniorTotalBalance = ($firstAmount + $secondAmount) * $exchangeRate;

    (new CompanyDepositToSuperSeniorAction())(
        company: $company,
        superSenior: $superSenior,
        remark: 'test',
        amount: ($firstAmount + $secondAmount));

    expect($superSenior->balanceInt)->toBe((int)$superSeniorTotalBalance);

    (new BalanceTransferAction)(
        sender: $superSenior,
        receiver: $senior,
        remark: 'test',
        amount: $firstAmount,
        mode: $mode
    );

    $superSeniorCurrentBalance = $superSenior->balanceInt;
    expect($superSeniorCurrentBalance)->toBe((int)($superSeniorTotalBalance - ($firstAmount * $exchangeRate)));

    $seniorCurrentBalance = $senior->balanceInt;
    expect($seniorCurrentBalance)->toBe((int)($firstAmount * $exchangeRate));

    $depositMeta = $senior->transactions()->where('type', 'deposit')->first()->meta;
    expect($depositMeta['type'])->toBe('deposit');
    expect($depositMeta['receiver'])->toBe($senior->name);
    expect($depositMeta['receiver_id'])->toBe($senior->id);
    expect($depositMeta['sender'])->toBe($superSenior->name);
    expect($depositMeta['sender_id'])->toBe($superSenior->id);
    expect($depositMeta['before_balance'])->toBe(0);
    expect($depositMeta['current_balance'])->toBe((int)($firstAmount * $exchangeRate));
    expect($depositMeta['currency'])->toBe($senior->currency->value);


    $withDrawMeta = $superSenior->transactions()->where('type', 'withdraw')->first()->meta;
    expect($withDrawMeta['type'])->toBe('withdraw');
    expect($withDrawMeta['receiver'])->toBe($senior->name);
    expect($withDrawMeta['receiver_id'])->toBe($senior->id);
    expect($withDrawMeta['sender'])->toBe($superSenior->name);
    expect($withDrawMeta['sender_id'])->toBe($superSenior->id);
    expect($withDrawMeta['before_balance'])->toBe((int)$superSeniorTotalBalance);
    expect($withDrawMeta['current_balance'])->toBe((int)($superSeniorTotalBalance - ($firstAmount * $exchangeRate)));
    expect($withDrawMeta['currency'])->toBe($superSenior->currency->value);

    expect($senior->balanceIsBlocked())->toBeFalse();
    expect($superSenior->balanceIsBlocked())->toBeFalse();


    $superSeniorBeforeBalance = $superSenior->balanceInt;
    $seniorBeforeBalance = $senior->balanceInt;
    (new BalanceTransferAction)(
        sender: $superSenior,
        receiver: $senior,
        remark: 'test',
        amount: $secondAmount,
        mode: $mode
    );

    $superSeniorCurrentBalance = $superSenior->balanceInt;
    expect($superSeniorCurrentBalance)->toBe(0);

    $seniorCurrentBalance = $senior->balanceInt;
    expect($seniorCurrentBalance)->toBe((int)(($firstAmount + $secondAmount) * $exchangeRate));

    $depositMeta = $senior
        ->transactions()
        ->where('type', 'deposit')
        ->orderByDesc('id')->first()->meta;

    expect($depositMeta['type'])->toBe('deposit');
    expect($depositMeta['mode'])->toBe($mode);
    expect($depositMeta['receiver'])->toBe($senior->name);
    expect($depositMeta['receiver_id'])->toBe($senior->id);
    expect($depositMeta['sender'])->toBe($superSenior->name);
    expect($depositMeta['sender_id'])->toBe($superSenior->id);
    expect($depositMeta['before_balance'])->toBe($seniorBeforeBalance);
    expect($depositMeta['current_balance'])->toBe($seniorBeforeBalance + (int)($secondAmount * $exchangeRate));
    expect($depositMeta['currency'])->toBe($senior->currency->value);


    $withDrawMeta = $superSenior
        ->transactions()
        ->where('type', 'withdraw')
        ->orderByDesc('id')
        ->first()->meta;

    expect($withDrawMeta['type'])->toBe('withdraw');
    expect($depositMeta['mode'])->toBe($mode);
    expect($withDrawMeta['receiver'])->toBe($senior->name);
    expect($withDrawMeta['receiver_id'])->toBe($senior->id);
    expect($withDrawMeta['sender'])->toBe($superSenior->name);
    expect($withDrawMeta['sender_id'])->toBe($superSenior->id);
    expect($withDrawMeta['before_balance'])->toBe($superSeniorBeforeBalance);
    expect($withDrawMeta['current_balance'])->toBe(0);
    expect($withDrawMeta['currency'])->toBe($superSenior->currency->value);

    expect($senior->balanceIsBlocked())->toBeFalse();
    expect($superSenior->balanceIsBlocked())->toBeFalse();

})->with([
    [10000, 20000, 1, Currency::KHR, 'from_downline'],
    [1, 2, 4000, Currency::USD, 'from_downline'],
    [50, 60, 134, Currency::THB, 'from_downline'],
    [50000, 70000, 0.18, Currency::VND, 'from_downline']
]);

test('it can not deposit to downline if deposit amount greater than current balance', function ($firstAmount, $secondAmount, $exchangeRate, $currency, $mode) {
    $company = UserCompanyFactory::new()->create();
    $superSenior = UserSuperSeniorFactory::new(['currency' => $currency])->create();
    $senior = UserSeniorFactory::new(['super_senior' => $superSenior->id, 'currency' => $currency])->create();
    $superSeniorTotalBalanceInKHR = ($firstAmount + $secondAmount) * $exchangeRate;

    (new CompanyDepositToSuperSeniorAction())(
        company: $company,
        superSenior: $superSenior,
        remark: 'test',
        amount: ($firstAmount + $secondAmount));

    expect($superSenior->balanceInt)->toBe((int)$superSeniorTotalBalanceInKHR);

    (new BalanceTransferAction)(
        sender: $superSenior,
        receiver: $senior,
        remark: 'test',
        amount: ($firstAmount + $secondAmount) + 1000,
        mode: $mode
    );

})->with([
    [10000, 20000, 1, Currency::KHR, 'to_downline'],
    [1, 2, 4000, Currency::USD, 'to_downline'],
    [50, 60, 134, Currency::THB, 'to_downline'],
    [50000, 70000, 0.18, Currency::VND, 'to_downline']
])->throws(InsufficientFunds::class);


test('it can not transfer if transaction withdraw and deposit is disabled', function () {
    nova_set_setting_value('disable_withdraw_deposit', 1);
    (new BalanceTransferAction)(
        sender: null,
        receiver: null,
        remark: 'test',
        amount: 0,
        mode: ''
    );
})->expectException(\App\Kravanh\Domain\Match\Exceptions\TransactionNotAllowed::class);
