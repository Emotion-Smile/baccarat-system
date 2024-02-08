<?php

namespace App\Kravanh\Domain\WalletBackup\Actions;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

final class RemoveDuplicateWalletAction
{
    public function __invoke(): int
    {
        $holderIds = (new GetHolderIdDuplicateWalletAction())()->toArray();

        if (empty($holderIds)) {
            return 0;
        }

        $wallets = (new GetDuplicationWalletAction())($holderIds);
        $owners = $this->getWalletOwners($holderIds);

        $owners->each(fn($owner) => $this->deleteWallet(
            wallet: $this->getWalletByOwner($wallets, $owner)
        ));

        return count($holderIds);

    }

    private function getWalletOwners(array $userIds): Collection
    {
        return DB::table('users')
            ->whereIn('id', $userIds)
            ->where('type', '!=', 'company')
            ->get(['id', 'type']);
    }

    private function deleteWallet(array $wallet): void
    {
        DB::table('wallets')
            ->where('holder_id', $wallet['holder_id'])
            ->where('holder_type', '!=', $wallet['holder_type'])
            ->delete();
    }

    private function getWalletByOwner($wallets, $owner): array
    {

        return $wallets
            ->where('holder_id', $owner->id)
            ->where('type', $owner->type)
            ->first();
    }
}
