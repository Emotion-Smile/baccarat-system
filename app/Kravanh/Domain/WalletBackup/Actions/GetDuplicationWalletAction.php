<?php

namespace App\Kravanh\Domain\WalletBackup\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class GetDuplicationWalletAction
{
    public function __invoke(array $holderIds): Collection
    {
        if (empty($holderIds)) return Collection::make();

        $wallets = DB::table('wallets')
            ->whereIn('holder_id', $holderIds)
            ->get(['holder_id', 'holder_type']);

        return $wallets->map(fn($wallet) => [
            'holder_id' => $wallet->holder_id,
            'holder_type' => $wallet->holder_type,
            'type' => $this->fromWalletHolderTypeToUserType($wallet->holder_type)
        ]);
    }

    private function fromWalletHolderTypeToUserType(string $holderType): string
    {

        $type = collect(explode("\\", $holderType))->last();

        return match ($type) {
            'Member' => 'member',
            'Agent' => 'agent',
            'MasterAgent' => 'master_agent',
            'Senior' => 'senior',
            'SuperSenior' => 'super_senior',
            'Company' => 'company',
            'Trader' => 'trader',
            'User' => 'user'
        };
    }

}
