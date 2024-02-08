<?php

use App\Kravanh\Domain\User\Actions\CompanyDepositToSuperSeniorAction;
use App\Kravanh\Domain\User\Exceptions\BalanceIsBlocked;
use App\Kravanh\Support\Enums\Currency;
use Database\Factories\UserCompanyFactory;
use Database\Factories\UserSuperSeniorFactory;

test("Can block user's balance", function () {
    $superSenior = UserSuperSeniorFactory::new(['currency' => Currency::VND])->create();
    $superSenior->blockBalance();
    expect($superSenior->balanceIsBlocked())->toBeTrue();
});

test("Ensure the transaction cannot make when user's balance is blocked", function () {
    $company = UserCompanyFactory::new()->create();
    $superSenior = UserSuperSeniorFactory::new(['currency' => Currency::VND])->create();
    $firstDepositAmount = 50000;
    $superSenior->blockBalance();

    (new CompanyDepositToSuperSeniorAction())($company, $superSenior, 'test', $firstDepositAmount);

})->throws(BalanceIsBlocked::class);
