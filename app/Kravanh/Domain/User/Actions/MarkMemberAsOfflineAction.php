<?php

namespace App\Kravanh\Domain\User\Actions;

use App\Kravanh\Domain\Match\Models\Matches;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;

class MarkMemberAsOfflineAction
{
    public function __invoke(): array
    {
        $membersShouldOffline = $this->membersShouldMarkAsOffline($this->getMembersOnline());
        $this->markMembersAsOffline($membersShouldOffline);
        return $membersShouldOffline;
    }

    public function getMembersOnline()
    {
        return User::query()
            ->select(['id', 'name', 'environment_id', 'group_id', 'last_login_at', 'online'])
            ->where('online', 1)
            ->where('allow_stream', 0)
            ->where('type', UserType::MEMBER)
            ->get();
    }

    public function membersShouldMarkAsOffline($memberOnline): array
    {
        $membersShouldOffline = [];

        $lastFightNumberOfGroup = [
            1 => Matches::lastFightNumber(1, 1),
            2 => Matches::lastFightNumber(1, 2),
            3 => Matches::lastFightNumber(1, 1),
            4 => Matches::lastFightNumber(1, 4),
        ];

        foreach ($memberOnline as $member) {

            $memberLastFightNumber = $member->getLastBetAt()['fight_number'];
            $loginDurationInMinute = $member->last_login_at->diffInMinutes();
            $groupId = $member->getLastBetAt()['group_id'] ?? $lastFightNumberOfGroup[$member->group_id];
            $matchLastFightNumber = $lastFightNumberOfGroup[$groupId];

            if ($matchLastFightNumber === 0) {
                continue;
            }

            /**
             * if member login without action in 30min
             * if member don't bet in 10 min
             * if member last fight number of bet > current match fight number in 10 min.
             * if member don't bet between 5 match in 10 min.
             */

            if ($loginDurationInMinute > 30) {
                $membersShouldOffline[] = $member->id;
                continue;
            }

            if ($memberLastFightNumber === 0 && $loginDurationInMinute > 10) {
                $membersShouldOffline[] = $member->id;
                continue;
            }

            if (($memberLastFightNumber > $matchLastFightNumber) && $loginDurationInMinute > 10) {
                $membersShouldOffline[] = $member->id;
                continue;
            }

            $betweenMatch = $matchLastFightNumber - $memberLastFightNumber;

            if ($betweenMatch > 5) {
                $membersShouldOffline[] = $member->id;
            }

        }

        return $membersShouldOffline;

    }

    public function markMembersAsOffline(array $membersId): void
    {
//        User::query()
//            ->whereIn('id', $membersId)
//            ->update([
//                'online' => 0
//            ]);
    }
}
