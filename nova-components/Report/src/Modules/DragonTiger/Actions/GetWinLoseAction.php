<?php

namespace KravanhEco\Report\Modules\DragonTiger\Actions;

use App\Kravanh\Domain\DragonTiger\Support\TicketStatus;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Date;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use KravanhEco\Report\Modules\DragonTiger\Support\Helpers\DateFilterHelper;

class GetWinLoseAction
{
    public function __invoke(User $user, string $date): Collection
    {
        $upLineId = $user->id;
        $upLineType = $user->type->value;

        $isCompany = $user->type->in([
            UserType::COMPANY,
            UserType::DEVELOPER
        ]);

        $downLineType = $this->getDownLineType($upLineType);

        $companyType = $this->getUpLineType(
            $isCompany ? UserType::SUPER_SENIOR : $upLineType
        );

        $memberRemainingShare = $this->getMemberRemainingShare($upLineType);
        $profitRemainingShare = $this->getProfitRemainingShare($upLineType);
        $companyRemainingShare = $this->getCompanyRemainingShare($companyType);

        $memberCommission = $this->getMemberCommission($upLineType);
        $profitCommission = $this->getProfitCommission($upLineType);
        
        return DB::table('dragon_tiger_tickets AS tickets')
            ->join('users', 'users.id', '=', "tickets.{$downLineType}")
            ->leftJoin('dragon_tiger_games AS games', 'tickets.dragon_tiger_game_id', '=', 'games.id')
            ->select([
                'users.id as accountId',
                'users.name as account',
                'users.phone as phone',
                'users.currency as currency',
                'users.type as userType'
            ])
            ->selectRaw("SUM( tickets.amount ) AS betAmount")
            ->selectRaw("SUM( {$this->selectRawValidAmount()} ) AS validAmount")
            ->selectRaw("SUM( {$this->selectRawWinLose()} * {$memberRemainingShare} ) AS memberWinLose")
            ->selectRaw("{$memberCommission} AS memberCommission")
            ->selectRaw("SUM( {$this->selectRawWinLose()} * {$profitRemainingShare} ) * -1 AS currentWinLose")
            ->selectRaw("{$profitCommission} - {$memberCommission} AS currentCommission")
            ->when(
                $isCompany,
                fn($query) => $query
                    ->selectRaw("0 AS upLineWinLose")
                    ->selectRaw("0 AS upLineCommission"),
                fn($query) => $query
                    ->selectRaw("SUM( {$this->selectRawWinLose()} * {$companyRemainingShare} ) * -1 AS upLineWinLose")
                    ->selectRaw("({$profitCommission}) * -1 AS upLineCommission")
            )
            ->when(
                $isCompany,
                fn($query) => $query,
                fn($query) => $query->where("tickets.{$upLineType}", $upLineId)
            )
            ->where(function ($query) use ($date) {
                $filter = DateFilterHelper::from($date);

                return $query->when(
                    $filter->isDay(),
                    fn($query) => $query->where('tickets.in_day', $filter->date())
                )
                    ->when(
                        $filter->isMonth(),
                        fn($query) => $query
                            ->where('tickets.in_year', Date::now()->year)
                            ->where('tickets.in_month', $filter->date())
                    )
                    ->when(
                        $filter->isWeek() || $filter->isDateRange(),
                        fn($query) => $query->whereBetween('tickets.in_day', $filter->date())
                    );
            })
            ->where('tickets.status', TicketStatus::Accepted)
            ->groupBy("tickets.{$downLineType}")
            ->get();
    }

    protected function selectRawWinLose(): string
    {
        return "CASE
            WHEN
                CONCAT( tickets.bet_on, '_', tickets.bet_type ) IN (
                    CONCAT(games.winner, '_', games.winner),
                    CONCAT('tiger', '_', games.tiger_color),
                    CONCAT('tiger', '_', games.tiger_range),
                    CONCAT('dragon', '_', games.dragon_color),
                    CONCAT('dragon', '_', games.dragon_range)
                ) THEN tickets.payout
            WHEN( ( games.winner = 'tie' ) AND ( CONCAT( tickets.bet_on, '_', tickets.bet_type ) IN ( 'dragon_dragon', 'tiger_tiger' ) ) ) THEN -( tickets.amount / 2 )
            ELSE -( tickets.amount )
        END";
    }

    protected function getMemberRemainingShare(string $userType): string
    {
        return match ($userType) {
            UserType::AGENT => 1,
            UserType::MASTER_AGENT => "( tickets.share->'$.master_agent' + tickets.share->'$.senior' + tickets.share->'$.super_senior' + tickets.share->'$.company' ) / 100",
            UserType::SENIOR => "( tickets.share->'$.senior' + tickets.share->'$.super_senior' + tickets.share->'$.company' ) / 100",
            UserType::SUPER_SENIOR => "( tickets.share->'$.super_senior' + tickets.share->'$.company' ) / 100",
            UserType::COMPANY => "( tickets.share->'$.company' ) / 100",
        };
    }

    protected function getProfitRemainingShare(string $userType): string
    {
        return "( tickets.share->'$.{$userType}' ) / 100";
    }

    protected function getCompanyRemainingShare(string $userType): string
    {
        return match ($userType) {
            UserType::AGENT => "( tickets.share->'$.agent' + tickets.share->'$.master_agent' + tickets.share->'$.senior' + tickets.share->'$.super_senior' + tickets.share->'$.company' ) / 100",
            UserType::MASTER_AGENT => "( tickets.share->'$.master_agent' + tickets.share->'$.senior' + tickets.share->'$.super_senior' + tickets.share->'$.company' ) / 100",
            UserType::SENIOR => "( tickets.share->'$.senior' + tickets.share->'$.super_senior' + tickets.share->'$.company' ) / 100",
            UserType::SUPER_SENIOR => "( tickets.share->'$.super_senior' + tickets.share->'$.company' ) / 100",
            UserType::COMPANY => "( tickets.share->'$.company' ) / 100",
        };
    }

    protected function selectRawValidAmount(): string
    {
        return "CASE
            WHEN games.winner = 'cancel' THEN 0
            WHEN( ( games.winner = 'tie' ) AND ( CONCAT( tickets.bet_on, '_', tickets.bet_type ) IN ( 'dragon_dragon', 'tiger_tiger' ) ) ) THEN ( tickets.amount / 2 )
            ELSE ( tickets.amount )
        END";
    }

    protected function getMemberCommission(string $userType): string
    {
        return match ($userType) {
            UserType::AGENT => "( SUM( tickets.commission->'$.agent' * {$this->selectRawValidAmount()} ) )",
            UserType::MASTER_AGENT => "( SUM( tickets.commission->'$.agent' * {$this->selectRawValidAmount()} * tickets.share->'$.agent' / 100 ) )",
            UserType::SENIOR => "( SUM( tickets.commission->'$.master_agent' * {$this->selectRawValidAmount()} * tickets.share->'$.master_agent' / 100 ) )",
            UserType::SUPER_SENIOR => "( SUM( tickets.commission->'$.senior' * {$this->selectRawValidAmount()} * tickets.share->'$.senior' / 100 ) )",
            UserType::COMPANY => "( SUM( tickets.commission->'$.super_senior' * {$this->selectRawValidAmount()} * tickets.share->'$.super_senior' / 100 ) )",
        };
    }

    protected function getProfitCommission(string $userType): string
    {
        return match ($userType) {
            UserType::AGENT => "( SUM( tickets.commission->'$.agent' * {$this->selectRawValidAmount()} * tickets.share->'$.agent' / 100 ) )",
            UserType::MASTER_AGENT => "( SUM( tickets.commission->'$.master_agent' * {$this->selectRawValidAmount()} * tickets.share->'$.master_agent' / 100 ) )",
            UserType::SENIOR => "( SUM( tickets.commission->'$.senior' * {$this->selectRawValidAmount()} * tickets.share->'$.senior' / 100 ) )",
            UserType::SUPER_SENIOR => "( SUM( tickets.commission->'$.super_senior' * {$this->selectRawValidAmount()} * tickets.share->'$.super_senior' / 100 ) )",
            UserType::COMPANY => '0',
        };
    }

    protected function getUpLineType(string $userType): string
    {
        return match ($userType) {
            UserType::MEMBER => UserType::AGENT,
            UserType::AGENT => UserType::MASTER_AGENT,
            UserType::MASTER_AGENT => UserType::SENIOR,
            UserType::SENIOR => UserType::SUPER_SENIOR,
            UserType::SUPER_SENIOR => UserType::COMPANY,
        };
    }

    protected function getDownLineType(string $userType): string
    {
        return match ($userType) {
            UserType::AGENT => 'user_id',
            UserType::MASTER_AGENT => UserType::AGENT,
            UserType::SENIOR => UserType::MASTER_AGENT,
            UserType::SUPER_SENIOR => UserType::SENIOR,
            UserType::COMPANY => UserType::SUPER_SENIOR,
        };
    }
}
