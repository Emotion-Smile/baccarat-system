<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */

namespace App\Kravanh\Domain\BetCondition\Models{
    /**
     * App\Kravanh\Domain\BetCondition\Models\BetCondition
     *
     * @property int $group_id
     * @property int $user_id
     * @property object $condition
     *
     * @method static \Illuminate\Database\Eloquent\Builder|BetCondition newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|BetCondition newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|BetCondition query()
     * @method static \Illuminate\Database\Eloquent\Builder|BetCondition whereCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetCondition whereGroupId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetCondition whereUserId($value)
     */
    class BetCondition extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\Card\Models{
    /**
     * App\Kravanh\Domain\Cards\Models\Card
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Card newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Card newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Card query()
     */
    class Card extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\DragonTiger\Models{
    /**
     * App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame
     *
     * @property int $id
     * @property int $game_table_id
     * @property int $user_id
     * @property int|null $result_submitted_user_id
     * @property int $round
     * @property int $number
     * @property int|null $dragon_result
     * @property string|null $dragon_type
     * @property string|null $dragon_color
     * @property string|null $dragon_range
     * @property int|null $tiger_result
     * @property string|null $tiger_type
     * @property string|null $tiger_color
     * @property string|null $tiger_range
     * @property string|null $winner
     * @property \Illuminate\Support\Carbon $started_at
     * @property \Illuminate\Support\Carbon $closed_bet_at
     * @property \Illuminate\Support\Carbon|null $result_submitted_at
     * @property mixed|null $statistic
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Kravanh\Domain\Game\Models\GameTable|null $gameTable
     * @property-read \App\Models\User|null $submittedResult
     * @property-read \App\Kravanh\Domain\DragonTiger\Collections\DragonTigerTicketsCollection<int, \App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket> $tickets
     * @property-read int|null $tickets_count
     * @property-read \App\Models\User|null $user
     *
     * @method static \App\Kravanh\Domain\DragonTiger\Collections\DragonTigerGameCollection<int, static> all($columns = ['*'])
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame excludeLiveGame()
     * @method static \App\Kravanh\Domain\DragonTiger\Database\Factories\DragonTigerGameFactory factory(...$parameters)
     * @method static \App\Kravanh\Domain\DragonTiger\Collections\DragonTigerGameCollection<int, static> get($columns = ['*'])
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame newModelQuery()
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame newQuery()
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame query()
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame whereClosedBetAt($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame whereCreatedAt($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame whereDragonColor($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame whereDragonRange($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame whereDragonResult($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame whereDragonType($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame whereGameTableId($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame whereId($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame whereLiveGame()
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame whereNumber($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame whereResultSubmittedAt($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame whereResultSubmittedUserId($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame whereRound($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame whereStartedAt($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame whereStatistic($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame whereTigerColor($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame whereTigerRange($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame whereTigerResult($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame whereTigerType($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame whereUpdatedAt($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame whereUserId($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerGameQueryBuilder|DragonTigerGame whereWinner($value)
     */
    class DragonTigerGame extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\DragonTiger\Models{
    /**
     * App\Kravanh\Domain\DragonTiger\Models\DragonTigerPayoutDeposited
     *
     * @property int $id
     * @property int $dragon_tiger_game_id
     * @property int $member_id
     * @property int $transaction_id
     * @property string $amount
     * @property string $ticket_ids
     * @property int $rollback_transaction_id
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Kravanh\Domain\User\Models\Member|null $member
     *
     * @method static \Illuminate\Database\Eloquent\Builder|DragonTigerPayoutDeposited newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|DragonTigerPayoutDeposited newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|DragonTigerPayoutDeposited query()
     * @method static \Illuminate\Database\Eloquent\Builder|DragonTigerPayoutDeposited whereAmount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|DragonTigerPayoutDeposited whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|DragonTigerPayoutDeposited whereDragonTigerGameId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|DragonTigerPayoutDeposited whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|DragonTigerPayoutDeposited whereMemberId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|DragonTigerPayoutDeposited whereRollbackTransactionId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|DragonTigerPayoutDeposited whereTicketIds($value)
     * @method static \Illuminate\Database\Eloquent\Builder|DragonTigerPayoutDeposited whereTransactionId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|DragonTigerPayoutDeposited whereUpdatedAt($value)
     */
    class DragonTigerPayoutDeposited extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\DragonTiger\Models{
    /**
     * App\Kravanh\Domain\DragonTiger\Models\DragonTigerTicket
     *
     * @property int $id
     * @property int $game_table_id
     * @property int $dragon_tiger_game_id
     * @property int $user_id
     * @property int $super_senior
     * @property int $senior
     * @property int $master_agent
     * @property int $agent
     * @property int $amount
     * @property string $bet_on
     * @property string $bet_type
     * @property float $payout_rate
     * @property int $payout
     * @property string $status
     * @property array $share
     * @property array $commission
     * @property int $in_year
     * @property int $in_month
     * @property int $in_day
     * @property string $ip
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame|null $game
     * @property-read \App\Kravanh\Domain\Game\Models\GameTable|null $gameTable
     * @property-read \App\Kravanh\Domain\User\Models\Member|null $member
     * @property-read \App\Models\User|null $user
     *
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket accepted()
     * @method static \App\Kravanh\Domain\DragonTiger\Collections\DragonTigerTicketsCollection<int, static> all($columns = ['*'])
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket excludeOutstandingTickets()
     * @method static \App\Kravanh\Domain\DragonTiger\Database\Factories\DragonTigerTicketFactory factory(...$parameters)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket filterBy(\App\Kravanh\Domain\DragonTiger\Support\DateFilter $filter)
     * @method static \App\Kravanh\Domain\DragonTiger\Collections\DragonTigerTicketsCollection<int, static> get($columns = ['*'])
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket newModelQuery()
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket newQuery()
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket onlyBetOnDragonAndTigerTickets(\App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame $game)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket onlyWinningTickets(\App\Kravanh\Domain\DragonTiger\Models\DragonTigerGame $game)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket query()
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket whereAgent($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket whereAmount($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket whereBetOn($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket whereBetType($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket whereCommission($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket whereCreatedAt($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket whereDragonTigerGameId($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket whereGameTable(?int $gameTableId)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket whereGameTableId($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket whereId($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket whereInDay($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket whereInMonth($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket whereInYear($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket whereIp($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket whereMasterAgent($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket wherePayout($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket wherePayoutRate($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket whereSenior($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket whereShare($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket whereStatus($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket whereSuperSenior($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket whereUpdatedAt($value)
     * @method static \App\Kravanh\Domain\DragonTiger\Support\QueryBuilders\DragonTigerTicketQueryBuilder|DragonTigerTicket whereUserId($value)
     */
    class DragonTigerTicket extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\Environment\Models{
    /**
     * App\Kravanh\Domain\Environment\Models\Domain
     *
     * @property int $id
     * @property string $domain
     * @property array|null $meta
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Domain findByDomain(string $domain)
     * @method static \Illuminate\Database\Eloquent\Builder|Domain newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Domain newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Domain query()
     * @method static \Illuminate\Database\Eloquent\Builder|Domain whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Domain whereDomain($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Domain whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Domain whereMeta($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Domain whereUpdatedAt($value)
     */
    class Domain extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\Environment\Models{
    /**
     * App\Kravanh\Domain\Environment\Models\Environment
     *
     * @property int $id
     * @property string $name
     * @property string $domain
     * @property int $active
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Nova\Actions\ActionEvent> $actions
     * @property-read int|null $actions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $user
     * @property-read int|null $user_count
     *
     * @method static \Database\Factories\EnvironmentFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|Environment newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Environment newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Environment query()
     * @method static \Illuminate\Database\Eloquent\Builder|Environment whereActive($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Environment whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Environment whereDomain($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Environment whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Environment whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Environment whereUpdatedAt($value)
     */
    class Environment extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\Environment\Models{
    /**
     * App\Kravanh\Domain\Environment\Models\Group
     *
     * @property int $id
     * @property int $environment_id
     * @property string $name
     * @property string|null $streaming_name
     * @property string|null $streaming_link
     * @property string|null $streaming_link_1
     * @property string $default_streaming_link
     * @property string|null $streaming_logo
     * @property string|null $iframe_allow
     * @property string|null $direct_link_allow
     * @property string|null $streaming_server_ip
     * @property int $active
     * @property bool $show_fight_number
     * @property array|null $meta
     * @property int $auto_trader
     * @property int $use_second_trader
     * @property string|null $telegram_chat_id
     * @property string $css_style
     * @property string $meron
     * @property string $wala
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property int $order
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Nova\Actions\ActionEvent> $actions
     * @property-read int|null $actions_count
     * @property-read \App\Kravanh\Domain\Environment\Models\Environment|null $environment
     *
     * @method static \Database\Factories\GroupFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|Group newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Group newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Group ordered(string $direction = 'asc')
     * @method static \Illuminate\Database\Eloquent\Builder|Group query()
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereActive($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereAutoTrader($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereCssStyle($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereDefaultStreamingLink($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereDirectLinkAllow($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereEnvironmentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereIframeAllow($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereMeron($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereMeta($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereOrder($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereShowFightNumber($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereStreamingLink($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereStreamingLink1($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereStreamingLogo($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereStreamingName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereStreamingServerIp($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereTelegramChatId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereUseSecondTrader($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Group whereWala($value)
     */
    class Group extends \Eloquent implements \Spatie\EloquentSortable\Sortable
    {
    }
}

namespace App\Kravanh\Domain\Game\Models{
    /**
     * App\Kravanh\Domain\Game\Models\Game
     *
     * @property int $id
     * @property string $name
     * @property string $label
     * @property string $type
     * @property string|null $icon
     * @property int $active
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Game newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Game newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Game query()
     * @method static \Illuminate\Database\Eloquent\Builder|Game whereActive($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Game whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Game whereIcon($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Game whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Game whereLabel($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Game whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Game whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Game whereUpdatedAt($value)
     */
    class Game extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\Game\Models{
    /**
     * App\Kravanh\Domain\Game\Models\GameTable
     *
     * @property int $id
     * @property int $game_id
     * @property string $label
     * @property string $stream_url
     * @property int $active
     * @property mixed|null $bet_condition
     * @property mixed|null $options
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Kravanh\Domain\Game\Models\Game|null $game
     *
     * @method static \App\Kravanh\Domain\Game\Database\Factories\GameTableFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|GameTable newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|GameTable newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|GameTable query()
     * @method static \Illuminate\Database\Eloquent\Builder|GameTable whereActive($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GameTable whereBetCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GameTable whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GameTable whereGameId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GameTable whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GameTable whereLabel($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GameTable whereOptions($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GameTable whereStreamUrl($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GameTable whereUpdatedAt($value)
     */
    class GameTable extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\Game\Models{
    /**
     * App\Kravanh\Domain\Game\Models\GameTableCondition
     *
     * @property int $game_id
     * @property int $game_table_id
     * @property int $user_id
     * @property string $user_type
     * @property bool $is_allowed
     * @property array $share_and_commission
     * @property array $bet_condition
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     *
     * @method static \Illuminate\Database\Eloquent\Builder|GameTableCondition newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|GameTableCondition newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|GameTableCondition query()
     * @method static \Illuminate\Database\Eloquent\Builder|GameTableCondition whereBetCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GameTableCondition whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GameTableCondition whereGameId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GameTableCondition whereGameTableId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GameTableCondition whereIsAllowed($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GameTableCondition whereShareAndCommission($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GameTableCondition whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GameTableCondition whereUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|GameTableCondition whereUserType($value)
     */
    class GameTableCondition extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\Integration\Models{
    /**
     * App\Kravanh\Domain\Integration\Models\Af88GameCondition
     *
     * @property int $id
     * @property int $user_id
     * @property array $condition
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Af88GameCondition newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Af88GameCondition newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Af88GameCondition query()
     * @method static \Illuminate\Database\Eloquent\Builder|Af88GameCondition whereCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Af88GameCondition whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Af88GameCondition whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Af88GameCondition whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Af88GameCondition whereUserId($value)
     */
    class Af88GameCondition extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\Integration\Models{
    /**
     * App\Kravanh\Domain\Integration\Models\T88GameCondition
     *
     * @property int $id
     * @property int $user_id
     * @property string $game_type
     * @property array $condition
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     *
     * @method static \Illuminate\Database\Eloquent\Builder|T88GameCondition newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|T88GameCondition newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|T88GameCondition query()
     * @method static \Illuminate\Database\Eloquent\Builder|T88GameCondition whereCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|T88GameCondition whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|T88GameCondition whereGameType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|T88GameCondition whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|T88GameCondition whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|T88GameCondition whereUserId($value)
     */
    class T88GameCondition extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\Integration\Models{
    /**
     * App\Kravanh\Domain\Integration\Models\T88PayoutDeposited
     *
     * @method static \Illuminate\Database\Eloquent\Builder|T88PayoutDeposited newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|T88PayoutDeposited newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|T88PayoutDeposited query()
     */
    class T88PayoutDeposited extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\IpInfo\Models{
    /**
     * App\Kravanh\Domain\IpInfo\Models\IpInfo
     *
     * @property string $ip
     * @property int $user_id
     * @property string $name
     * @property string|null $city
     * @property string|null $region
     * @property string|null $country
     * @property bool $vpn
     * @property bool $proxy
     * @property bool $tor
     * @property bool $relay
     * @property bool $hosting
     * @property string|null $service
     * @property array $payload
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     *
     * @method static \Illuminate\Database\Eloquent\Builder|IpInfo newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|IpInfo newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|IpInfo query()
     * @method static \Illuminate\Database\Eloquent\Builder|IpInfo whereCity($value)
     * @method static \Illuminate\Database\Eloquent\Builder|IpInfo whereCountry($value)
     * @method static \Illuminate\Database\Eloquent\Builder|IpInfo whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|IpInfo whereHosting($value)
     * @method static \Illuminate\Database\Eloquent\Builder|IpInfo whereIp($value)
     * @method static \Illuminate\Database\Eloquent\Builder|IpInfo whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|IpInfo wherePayload($value)
     * @method static \Illuminate\Database\Eloquent\Builder|IpInfo whereProxy($value)
     * @method static \Illuminate\Database\Eloquent\Builder|IpInfo whereRegion($value)
     * @method static \Illuminate\Database\Eloquent\Builder|IpInfo whereRelay($value)
     * @method static \Illuminate\Database\Eloquent\Builder|IpInfo whereService($value)
     * @method static \Illuminate\Database\Eloquent\Builder|IpInfo whereTor($value)
     * @method static \Illuminate\Database\Eloquent\Builder|IpInfo whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|IpInfo whereUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|IpInfo whereVpn($value)
     */
    class IpInfo extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\Match\Models{
    /**
     * App\Kravanh\Domain\Match\Models\BetRecord
     *
     * @property int $id
     * @property int|null $environment_id
     * @property int $group_id
     * @property int|null $member_type_id
     * @property int $match_id
     * @property int $transaction_id
     * @property int $user_id
     * @property int $super_senior
     * @property int $senior
     * @property int $master_agent
     * @property int $agent
     * @property int $fight_number
     * @property \Illuminate\Support\Carbon $bet_date
     * @property \Illuminate\Support\Carbon $bet_time
     * @property string $currency
     * @property int $amount
     * @property string $payout_rate
     * @property int $payout
     * @property int $benefit
     * @property \App\Kravanh\Domain\Match\Supports\Enums\BetOn $bet_on
     * @property int $result
     * @property \App\Kravanh\Domain\Match\Supports\Enums\BetStatus $status
     * @property \App\Kravanh\Domain\Match\Supports\Enums\BetType $type
     * @property string|null $ip
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Kravanh\Domain\User\Models\Agent|null $_agent
     * @property-read \App\Kravanh\Domain\User\Models\MasterAgent|null $_master_agent
     * @property-read \App\Kravanh\Domain\User\Models\Senior|null $_senior
     * @property-read \App\Kravanh\Domain\User\Models\SuperSenior|null $_super_senior
     * @property-read \App\Kravanh\Domain\Environment\Models\Group|null $group
     * @property-read \App\Kravanh\Domain\Match\Models\Matches $match
     * @property-read \Bavix\Wallet\Models\Transaction|null $transaction
     * @property-read \App\Kravanh\Domain\User\Models\Member $user
     *
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord byDate($date)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord byStatus($status)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord exceptSomeUserType($user)
     * @method static \Database\Factories\BetRecordFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord forceIndex(string $index)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord onlyLiveMatch()
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord query()
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord today()
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord useIndex(string $index)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereAmount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereBenefit($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereBetDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereBetOn($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereBetTime($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereCurrency($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereEnvironmentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereFightNumber($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereGroupId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereIp($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereMasterAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereMatchId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereMemberTypeId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord wherePayout($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord wherePayoutRate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereResult($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereSenior($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereSuperSenior($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereTransactionId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord whereUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|BetRecord withoutLiveMatch()
     */
    class BetRecord extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\Match\Models{
    /**
     * App\Kravanh\Domain\Match\Models\DuplicatePayout
     *
     * @property int $id
     * @property int $match_id
     * @property int $user_id
     * @property string $group
     * @property string $user
     * @property int $amount
     * @property int $tx_count
     * @property int $withdraw_amount
     * @property int $already_withdraw
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     *
     * @method static \Illuminate\Database\Eloquent\Builder|DuplicatePayout newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|DuplicatePayout newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|DuplicatePayout query()
     * @method static \Illuminate\Database\Eloquent\Builder|DuplicatePayout whereAlreadyWithdraw($value)
     * @method static \Illuminate\Database\Eloquent\Builder|DuplicatePayout whereAmount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|DuplicatePayout whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|DuplicatePayout whereGroup($value)
     * @method static \Illuminate\Database\Eloquent\Builder|DuplicatePayout whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|DuplicatePayout whereMatchId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|DuplicatePayout whereTxCount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|DuplicatePayout whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|DuplicatePayout whereUser($value)
     * @method static \Illuminate\Database\Eloquent\Builder|DuplicatePayout whereUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|DuplicatePayout whereWithdrawAmount($value)
     */
    class DuplicatePayout extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\Match\Models{
    /**
     * App\Kravanh\Domain\Match\Models\Matches
     *
     * @property int $id
     * @property int $environment_id
     * @property int $group_id
     * @property int $user_id
     * @property int $channel_id
     * @property int $fight_number
     * @property \Illuminate\Support\Carbon $match_date
     * @property \Illuminate\Support\Carbon $match_started_at
     * @property \Illuminate\Support\Carbon|null $match_end_at
     * @property int $payout_total
     * @property int $payout_meron
     * @property \Illuminate\Support\Carbon|null $bet_started_at
     * @property \Illuminate\Support\Carbon|null $bet_stopped_at
     * @property int $bet_duration
     * @property int $meron_total_bet
     * @property int $meron_total_payout
     * @property int $wala_total_bet
     * @property int $wala_total_payout
     * @property \App\Kravanh\Domain\Match\Supports\Enums\MatchResult $result
     * @property int $total_ticket
     * @property array|null $meta
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Match\Models\BetRecord> $betRecords
     * @property-read int|null $bet_records_count
     * @property-read \App\Kravanh\Domain\Environment\Models\Environment $environment
     * @property-read bool $bet_closed
     * @property-read bool $bet_opened
     * @property-read string $blue_label
     * @property-read bool $match_end
     * @property-read mixed $payout_wala
     * @property-read string $red_label
     * @property-read int|float $total_bet_duration
     * @property-read string $type
     * @property-read \App\Kravanh\Domain\Environment\Models\Group|null $group
     * @property-read \App\Models\User $user
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Matches activeMatch()
     * @method static \App\Kravanh\Domain\Match\Collections\MatchCollection<int, static> all($columns = ['*'])
     * @method static \Database\Factories\MatchesFactory factory(...$parameters)
     * @method static \App\Kravanh\Domain\Match\Collections\MatchCollection<int, static> get($columns = ['*'])
     * @method static \Illuminate\Database\Eloquent\Builder|Matches matchEnded()
     * @method static \Illuminate\Database\Eloquent\Builder|Matches newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Matches newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Matches onlyWinLossResult()
     * @method static \Illuminate\Database\Eloquent\Builder|Matches query()
     * @method static \Illuminate\Database\Eloquent\Builder|Matches today()
     * @method static \Illuminate\Database\Eloquent\Builder|Matches whereBetDuration($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Matches whereBetStartedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Matches whereBetStoppedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Matches whereChannelId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Matches whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Matches whereEnvironmentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Matches whereFightNumber($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Matches whereGroupId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Matches whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Matches whereMatchDate($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Matches whereMatchEndAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Matches whereMatchStartedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Matches whereMeronTotalBet($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Matches whereMeronTotalPayout($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Matches whereMeta($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Matches wherePayoutMeron($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Matches wherePayoutTotal($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Matches whereResult($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Matches whereTotalTicket($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Matches whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Matches whereUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Matches whereWalaTotalBet($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Matches whereWalaTotalPayout($value)
     */
    class Matches extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\Match\Models{
    /**
     * App\Kravanh\Domain\Match\Models\PayoutDeposit
     *
     * @property int $id
     * @property int $match_id
     * @property int $member_id
     * @property int $transaction_id
     * @property string|null $depositor
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     *
     * @method static \Illuminate\Database\Eloquent\Builder|PayoutDeposit newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|PayoutDeposit newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|PayoutDeposit query()
     * @method static \Illuminate\Database\Eloquent\Builder|PayoutDeposit whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|PayoutDeposit whereDepositor($value)
     * @method static \Illuminate\Database\Eloquent\Builder|PayoutDeposit whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|PayoutDeposit whereMatchId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|PayoutDeposit whereMemberId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|PayoutDeposit whereTransactionId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|PayoutDeposit whereUpdatedAt($value)
     */
    class PayoutDeposit extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\Match\Models{
    /**
     * App\Kravanh\Domain\Match\Models\Spread
     *
     * @property int $id
     * @property string $name
     * @property int $payout_deduction
     * @property bool $active
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $agents
     * @property-read int|null $agents_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $members
     * @property-read int|null $members_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
     * @property-read int|null $users_count
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Spread newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Spread newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Spread query()
     * @method static \Illuminate\Database\Eloquent\Builder|Spread whereActive($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Spread whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Spread whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Spread whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Spread wherePayoutDeduction($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Spread whereUpdatedAt($value)
     */
    class Spread extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\UserOption\Models{
    /**
     * App\Kravanh\Domain\UserOption\Models\UserOption
     *
     * @property int $user_id
     * @property string $option
     * @property string|null $value
     *
     * @method static \Illuminate\Database\Eloquent\Builder|UserOption newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserOption newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|UserOption query()
     * @method static \Illuminate\Database\Eloquent\Builder|UserOption whereOption($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserOption whereUserId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|UserOption whereValue($value)
     */
    class UserOption extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\User\Models{
    /**
     * App\Kravanh\Domain\User\Models\Agent
     *
     * @property int $id
     * @property int|null $domain_id
     * @property int|null $current_team_id
     * @property int|null $environment_id
     * @property int $group_id
     * @property int|null $spread_id
     * @property int $super_senior
     * @property int $senior
     * @property int $master_agent
     * @property int $agent
     * @property string $name
     * @property string|null $phone
     * @property string|null $email
     * @property \Illuminate\Support\Carbon|null $email_verified_at
     * @property string $password
     * @property string|null $two_factor_secret
     * @property string|null $two_factor_recovery_codes
     * @property \App\Kravanh\Domain\User\Supports\Enums\UserType $type
     * @property array|null $condition
     * @property string|null $remember_token
     * @property string|null $profile_photo_path
     * @property bool $internet_betting
     * @property \App\Kravanh\Domain\User\Supports\Enums\Status $status
     * @property int $suspend
     * @property \App\Kravanh\Domain\Match\Supports\Enums\BetType $bet_type
     * @property \Illuminate\Support\Carbon|null $last_login_at
     * @property int $online
     * @property string|null $ip
     * @property int $allow_stream
     * @property int $allow_af88_game
     * @property int $allow_t88_game
     * @property int $can_bet_when_disable
     * @property int $use_group_condition
     * @property \App\Kravanh\Support\Enums\Currency $currency
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Nova\Actions\ActionEvent> $actions
     * @property-read int|null $actions_count
     * @property-read \App\Kravanh\Domain\Integration\Models\Af88GameCondition|null $af88GameCondition
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Match\Models\BetRecord> $bets
     * @property-read int|null $bets_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Environment\Models\Group> $disableGroups
     * @property-read int|null $disable_groups_count
     * @property-read \App\Kravanh\Domain\Environment\Models\Domain|null $domain
     * @property-read \App\Kravanh\Domain\Environment\Models\Environment|null $environment
     * @property-read float|int|string $balance
     * @property-read int $balance_int
     * @property-read bool $can_play_dragon_tiger
     * @property-read string $maximum_bet_per_ticket
     * @property-read string $minimum_bet_per_ticket
     * @property-read bool $normal_member
     * @property-read string $profile_photo_url
     * @property-read \App\Kravanh\Domain\Environment\Models\Group|null $group
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\LoginHistory> $loginHistories
     * @property-read int|null $login_histories_count
     * @property-read \App\Kravanh\Domain\Match\Collections\MatchCollection<int, \App\Kravanh\Domain\Match\Models\Matches> $matches
     * @property-read int|null $matches_count
     * @property-read \App\Kravanh\Domain\User\Models\MemberPassword|null $memberPassword
     * @property-read \App\Kravanh\Domain\User\Models\MemberType|null $memberType
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\Message> $messages
     * @property-read int|null $messages_count
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
     * @property-read int|null $notifications_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\Role> $roles
     * @property-read int|null $roles_count
     * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
     * @property-read \App\Kravanh\Domain\Match\Models\Spread|null $spread
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Integration\Models\T88GameCondition> $t88GameConditions
     * @property-read int|null $t88_game_conditions_count
     * @property-read int|null $tags_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
     * @property-read int|null $tokens_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Bavix\Wallet\Models\Transaction> $transactions
     * @property-read int|null $transactions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Bavix\Wallet\Models\Transfer> $transfers
     * @property-read int|null $transfers_count
     * @property-read \App\Models\User|null $underAgent
     * @property-read \App\Models\User|null $underMasterAgent
     * @property-read \App\Models\User|null $underSenior
     * @property-read \App\Models\User|null $underSuperSenior
     * @property-read \Bavix\Wallet\Models\Wallet $wallet
     *
     * @method static \Illuminate\Database\Eloquent\Builder|User active()
     * @method static \Illuminate\Database\Eloquent\Builder|User currentBetReport($matchId)
     * @method static \Database\Factories\UserAgentFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|User member()
     * @method static \Illuminate\Database\Eloquent\Builder|Agent newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Agent newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Agent query()
     * @method static \Illuminate\Database\Eloquent\Builder|User todayBetReport($matchId = 0)
     * @method static \Illuminate\Database\Eloquent\Builder|User trader()
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereAllowAf88Game($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereAllowStream($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereAllowT88Game($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereBetType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereCanBetWhenDisable($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereCurrency($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereCurrentTeamId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereDomainId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereEmailVerifiedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereEnvironmentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereGroupId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereInternetBetting($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereIp($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereLastLoginAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereMasterAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereOnline($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent wherePhone($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereProfilePhotoPath($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereSenior($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereSpreadId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereSuperSenior($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereSuspend($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereTwoFactorRecoveryCodes($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereTwoFactorSecret($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Agent whereUseGroupCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAllTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAnyTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder|User withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     */
    class Agent extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\User\Models{
    /**
     * App\Kravanh\Domain\User\Models\Company
     *
     * @property int $id
     * @property int|null $domain_id
     * @property int|null $current_team_id
     * @property int|null $environment_id
     * @property int $group_id
     * @property int|null $spread_id
     * @property int $super_senior
     * @property int $senior
     * @property int $master_agent
     * @property int $agent
     * @property string $name
     * @property string|null $phone
     * @property string|null $email
     * @property \Illuminate\Support\Carbon|null $email_verified_at
     * @property string $password
     * @property string|null $two_factor_secret
     * @property string|null $two_factor_recovery_codes
     * @property \App\Kravanh\Domain\User\Supports\Enums\UserType $type
     * @property array|null $condition
     * @property string|null $remember_token
     * @property string|null $profile_photo_path
     * @property bool $internet_betting
     * @property \App\Kravanh\Domain\User\Supports\Enums\Status $status
     * @property int $suspend
     * @property \App\Kravanh\Domain\Match\Supports\Enums\BetType $bet_type
     * @property \Illuminate\Support\Carbon|null $last_login_at
     * @property int $online
     * @property string|null $ip
     * @property int $allow_stream
     * @property int $allow_af88_game
     * @property int $allow_t88_game
     * @property int $can_bet_when_disable
     * @property int $use_group_condition
     * @property \App\Kravanh\Support\Enums\Currency $currency
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Nova\Actions\ActionEvent> $actions
     * @property-read int|null $actions_count
     * @property-read \App\Kravanh\Domain\Integration\Models\Af88GameCondition|null $af88GameCondition
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Match\Models\BetRecord> $bets
     * @property-read int|null $bets_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Environment\Models\Group> $disableGroups
     * @property-read int|null $disable_groups_count
     * @property-read \App\Kravanh\Domain\Environment\Models\Domain|null $domain
     * @property-read \App\Kravanh\Domain\Environment\Models\Environment|null $environment
     * @property-read float|int|string $balance
     * @property-read int $balance_int
     * @property-read bool $can_play_dragon_tiger
     * @property-read string $maximum_bet_per_ticket
     * @property-read string $minimum_bet_per_ticket
     * @property-read bool $normal_member
     * @property-read string $profile_photo_url
     * @property-read \App\Kravanh\Domain\Environment\Models\Group|null $group
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\LoginHistory> $loginHistories
     * @property-read int|null $login_histories_count
     * @property-read \App\Kravanh\Domain\Match\Collections\MatchCollection<int, \App\Kravanh\Domain\Match\Models\Matches> $matches
     * @property-read int|null $matches_count
     * @property-read \App\Kravanh\Domain\User\Models\MemberPassword|null $memberPassword
     * @property-read \App\Kravanh\Domain\User\Models\MemberType|null $memberType
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\Message> $messages
     * @property-read int|null $messages_count
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
     * @property-read int|null $notifications_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\Role> $roles
     * @property-read int|null $roles_count
     * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
     * @property-read \App\Kravanh\Domain\Match\Models\Spread|null $spread
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Integration\Models\T88GameCondition> $t88GameConditions
     * @property-read int|null $t88_game_conditions_count
     * @property-read int|null $tags_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
     * @property-read int|null $tokens_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Bavix\Wallet\Models\Transaction> $transactions
     * @property-read int|null $transactions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Bavix\Wallet\Models\Transfer> $transfers
     * @property-read int|null $transfers_count
     * @property-read \App\Models\User|null $underAgent
     * @property-read \App\Models\User|null $underMasterAgent
     * @property-read \App\Models\User|null $underSenior
     * @property-read \App\Models\User|null $underSuperSenior
     * @property-read \Bavix\Wallet\Models\Wallet $wallet
     *
     * @method static \Illuminate\Database\Eloquent\Builder|User active()
     * @method static \Illuminate\Database\Eloquent\Builder|User currentBetReport($matchId)
     * @method static \Database\Factories\UserCompanyFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|User member()
     * @method static \Illuminate\Database\Eloquent\Builder|Company newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Company newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Company query()
     * @method static \Illuminate\Database\Eloquent\Builder|User todayBetReport($matchId = 0)
     * @method static \Illuminate\Database\Eloquent\Builder|User trader()
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereAllowAf88Game($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereAllowStream($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereAllowT88Game($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereBetType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereCanBetWhenDisable($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereCurrency($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereCurrentTeamId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereDomainId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereEmailVerifiedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereEnvironmentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereGroupId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereInternetBetting($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereIp($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereLastLoginAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereMasterAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereOnline($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company wherePhone($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereProfilePhotoPath($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereSenior($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereSpreadId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereSuperSenior($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereSuspend($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereTwoFactorRecoveryCodes($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereTwoFactorSecret($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Company whereUseGroupCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAllTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAnyTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder|User withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     */
    class Company extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\User\Models{
    /**
     * App\Kravanh\Domain\User\Models\LoginHistory
     *
     * @property int $id
     * @property int $user_id
     * @property string $ip
     * @property string $user_agent
     * @property \Illuminate\Support\Carbon $login_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\User $user
     *
     * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory query()
     * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereIp($value)
     * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereLoginAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereUserAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|LoginHistory whereUserId($value)
     */
    class LoginHistory extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\User\Models{
    /**
     * App\Kravanh\Domain\User\Models\MasterAgent
     *
     * @property int $id
     * @property int|null $domain_id
     * @property int|null $current_team_id
     * @property int|null $environment_id
     * @property int $group_id
     * @property int|null $spread_id
     * @property int $super_senior
     * @property int $senior
     * @property int $master_agent
     * @property int $agent
     * @property string $name
     * @property string|null $phone
     * @property string|null $email
     * @property \Illuminate\Support\Carbon|null $email_verified_at
     * @property string $password
     * @property string|null $two_factor_secret
     * @property string|null $two_factor_recovery_codes
     * @property \App\Kravanh\Domain\User\Supports\Enums\UserType $type
     * @property array|null $condition
     * @property string|null $remember_token
     * @property string|null $profile_photo_path
     * @property bool $internet_betting
     * @property \App\Kravanh\Domain\User\Supports\Enums\Status $status
     * @property int $suspend
     * @property \App\Kravanh\Domain\Match\Supports\Enums\BetType $bet_type
     * @property \Illuminate\Support\Carbon|null $last_login_at
     * @property int $online
     * @property string|null $ip
     * @property int $allow_stream
     * @property int $allow_af88_game
     * @property int $allow_t88_game
     * @property int $can_bet_when_disable
     * @property int $use_group_condition
     * @property \App\Kravanh\Support\Enums\Currency $currency
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Nova\Actions\ActionEvent> $actions
     * @property-read int|null $actions_count
     * @property-read \App\Kravanh\Domain\Integration\Models\Af88GameCondition|null $af88GameCondition
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Match\Models\BetRecord> $bets
     * @property-read int|null $bets_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Environment\Models\Group> $disableGroups
     * @property-read int|null $disable_groups_count
     * @property-read \App\Kravanh\Domain\Environment\Models\Domain|null $domain
     * @property-read \App\Kravanh\Domain\Environment\Models\Environment|null $environment
     * @property-read float|int|string $balance
     * @property-read int $balance_int
     * @property-read bool $can_play_dragon_tiger
     * @property-read string $maximum_bet_per_ticket
     * @property-read string $minimum_bet_per_ticket
     * @property-read bool $normal_member
     * @property-read string $profile_photo_url
     * @property-read \App\Kravanh\Domain\Environment\Models\Group|null $group
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\LoginHistory> $loginHistories
     * @property-read int|null $login_histories_count
     * @property-read \App\Kravanh\Domain\Match\Collections\MatchCollection<int, \App\Kravanh\Domain\Match\Models\Matches> $matches
     * @property-read int|null $matches_count
     * @property-read \App\Kravanh\Domain\User\Models\MemberPassword|null $memberPassword
     * @property-read \App\Kravanh\Domain\User\Models\MemberType|null $memberType
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\Message> $messages
     * @property-read int|null $messages_count
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
     * @property-read int|null $notifications_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\Role> $roles
     * @property-read int|null $roles_count
     * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
     * @property-read \App\Kravanh\Domain\Match\Models\Spread|null $spread
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Integration\Models\T88GameCondition> $t88GameConditions
     * @property-read int|null $t88_game_conditions_count
     * @property-read int|null $tags_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
     * @property-read int|null $tokens_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Bavix\Wallet\Models\Transaction> $transactions
     * @property-read int|null $transactions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Bavix\Wallet\Models\Transfer> $transfers
     * @property-read int|null $transfers_count
     * @property-read \App\Models\User|null $underAgent
     * @property-read \App\Models\User|null $underMasterAgent
     * @property-read \App\Models\User|null $underSenior
     * @property-read \App\Models\User|null $underSuperSenior
     * @property-read \Bavix\Wallet\Models\Wallet $wallet
     *
     * @method static \Illuminate\Database\Eloquent\Builder|User active()
     * @method static \Illuminate\Database\Eloquent\Builder|User currentBetReport($matchId)
     * @method static \Database\Factories\UserMasterAgentFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|User member()
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent query()
     * @method static \Illuminate\Database\Eloquent\Builder|User todayBetReport($matchId = 0)
     * @method static \Illuminate\Database\Eloquent\Builder|User trader()
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereAllowAf88Game($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereAllowStream($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereAllowT88Game($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereBetType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereCanBetWhenDisable($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereCurrency($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereCurrentTeamId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereDomainId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereEmailVerifiedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereEnvironmentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereGroupId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereInternetBetting($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereIp($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereLastLoginAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereMasterAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereOnline($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent wherePhone($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereProfilePhotoPath($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereSenior($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereSpreadId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereSuperSenior($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereSuspend($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereTwoFactorRecoveryCodes($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereTwoFactorSecret($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MasterAgent whereUseGroupCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAllTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAnyTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder|User withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     */
    class MasterAgent extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\User\Models{
    /**
     * App\Kravanh\Domain\User\Models\Member
     *
     * @property int $id
     * @property int|null $domain_id
     * @property int|null $current_team_id
     * @property int|null $environment_id
     * @property int $group_id
     * @property int|null $spread_id
     * @property int $super_senior
     * @property int $senior
     * @property int $master_agent
     * @property int $agent
     * @property string $name
     * @property string|null $phone
     * @property string|null $email
     * @property \Illuminate\Support\Carbon|null $email_verified_at
     * @property string $password
     * @property string|null $two_factor_secret
     * @property string|null $two_factor_recovery_codes
     * @property \App\Kravanh\Domain\User\Supports\Enums\UserType $type
     * @property array|null $condition
     * @property string|null $remember_token
     * @property string|null $profile_photo_path
     * @property bool $internet_betting
     * @property \App\Kravanh\Domain\User\Supports\Enums\Status $status
     * @property int $suspend
     * @property \App\Kravanh\Domain\Match\Supports\Enums\BetType $bet_type
     * @property \Illuminate\Support\Carbon|null $last_login_at
     * @property int $online
     * @property string|null $ip
     * @property int $allow_stream
     * @property int $allow_af88_game
     * @property int $allow_t88_game
     * @property int $can_bet_when_disable
     * @property int $use_group_condition
     * @property \App\Kravanh\Support\Enums\Currency $currency
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Nova\Actions\ActionEvent> $actions
     * @property-read int|null $actions_count
     * @property-read \App\Kravanh\Domain\Integration\Models\Af88GameCondition|null $af88GameCondition
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Match\Models\BetRecord> $bets
     * @property-read int|null $bets_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Environment\Models\Group> $disableGroups
     * @property-read int|null $disable_groups_count
     * @property-read \App\Kravanh\Domain\Environment\Models\Domain|null $domain
     * @property-read \App\Kravanh\Domain\Environment\Models\Environment|null $environment
     * @property-read float|int|string $balance
     * @property-read int $balance_int
     * @property-read bool $can_play_dragon_tiger
     * @property-read string $maximum_bet_per_ticket
     * @property-read string $minimum_bet_per_ticket
     * @property-read bool $normal_member
     * @property-read string $profile_photo_url
     * @property-read \App\Kravanh\Domain\Environment\Models\Group|null $group
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\LoginHistory> $loginHistories
     * @property-read int|null $login_histories_count
     * @property-read \App\Kravanh\Domain\Match\Collections\MatchCollection<int, \App\Kravanh\Domain\Match\Models\Matches> $matches
     * @property-read int|null $matches_count
     * @property-read \App\Kravanh\Domain\User\Models\MemberPassword|null $memberPassword
     * @property-read \App\Kravanh\Domain\User\Models\MemberType|null $memberType
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\Message> $messages
     * @property-read int|null $messages_count
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
     * @property-read int|null $notifications_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\Role> $roles
     * @property-read int|null $roles_count
     * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
     * @property-read \App\Kravanh\Domain\Match\Models\Spread|null $spread
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Integration\Models\T88GameCondition> $t88GameConditions
     * @property-read int|null $t88_game_conditions_count
     * @property-read int|null $tags_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
     * @property-read int|null $tokens_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Bavix\Wallet\Models\Transaction> $transactions
     * @property-read int|null $transactions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Bavix\Wallet\Models\Transfer> $transfers
     * @property-read int|null $transfers_count
     * @property-read \App\Models\User|null $underAgent
     * @property-read \App\Models\User|null $underMasterAgent
     * @property-read \App\Models\User|null $underSenior
     * @property-read \App\Models\User|null $underSuperSenior
     * @property-read \Bavix\Wallet\Models\Wallet $wallet
     *
     * @method static \Illuminate\Database\Eloquent\Builder|User active()
     * @method static \Illuminate\Database\Eloquent\Builder|User currentBetReport($matchId)
     * @method static \Database\Factories\UserMemberFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|User member()
     * @method static \Illuminate\Database\Eloquent\Builder|Member newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Member newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Member query()
     * @method static \Illuminate\Database\Eloquent\Builder|User todayBetReport($matchId = 0)
     * @method static \Illuminate\Database\Eloquent\Builder|User trader()
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereAllowAf88Game($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereAllowStream($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereAllowT88Game($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereBetType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereCanBetWhenDisable($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereCurrency($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereCurrentTeamId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereDomainId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereEmailVerifiedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereEnvironmentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereGroupId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereInternetBetting($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereIp($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereLastLoginAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereMasterAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereOnline($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member wherePhone($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereProfilePhotoPath($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereSenior($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereSpreadId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereSuperSenior($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereSuspend($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereTwoFactorRecoveryCodes($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereTwoFactorSecret($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Member whereUseGroupCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAllTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAnyTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder|User withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     */
    class Member extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\User\Models{
    /**
     * App\Kravanh\Domain\User\Models\MemberPassword
     *
     * @property int $id
     * @property int $user_id
     * @property string $password
     * @property string|null $type
     * @property string|null $device
     * @property string|null $browser
     * @property string|null $robot
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \App\Models\User $user
     *
     * @method static \Illuminate\Database\Eloquent\Builder|MemberPassword newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|MemberPassword newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|MemberPassword query()
     * @method static \Illuminate\Database\Eloquent\Builder|MemberPassword whereBrowser($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MemberPassword whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MemberPassword whereDevice($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MemberPassword whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MemberPassword wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MemberPassword whereRobot($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MemberPassword whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MemberPassword whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MemberPassword whereUserId($value)
     */
    class MemberPassword extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\User\Models{
    /**
     * App\Kravanh\Domain\User\Models\MemberType
     *
     * @property int $id
     * @property string $name
     * @property bool $active
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $agents
     * @property-read int|null $agents_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $members
     * @property-read int|null $members_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
     * @property-read int|null $users_count
     *
     * @method static \Illuminate\Database\Eloquent\Builder|MemberType newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|MemberType newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|MemberType query()
     * @method static \Illuminate\Database\Eloquent\Builder|MemberType whereActive($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MemberType whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MemberType whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MemberType whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|MemberType whereUpdatedAt($value)
     */
    class MemberType extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\User\Models{
    /**
     * App\Kravanh\Domain\User\Models\Message
     *
     * @property int $id
     * @property array $message
     * @property \Illuminate\Support\Carbon|null $sent_at
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read array $translations
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
     * @property-read int|null $users_count
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Message newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Message newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Message query()
     * @method static \Illuminate\Database\Eloquent\Builder|Message whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Message whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Message whereMessage($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Message whereSentAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Message whereUpdatedAt($value)
     */
    class Message extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\User\Models{
    /**
     * App\Kravanh\Domain\User\Models\Permission
     *
     * @property int $id
     * @property string $name
     * @property string $label
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property string|null $deleted_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Nova\Actions\ActionEvent> $actions
     * @property-read int|null $actions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\Role> $roles
     * @property-read int|null $roles_count
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
     * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Permission whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Permission whereLabel($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
     */
    class Permission extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\User\Models{
    /**
     * App\Kravanh\Domain\User\Models\PermissionRole
     *
     * @property int $permission_id
     * @property int $role_id
     *
     * @method static \Illuminate\Database\Eloquent\Builder|PermissionRole newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|PermissionRole newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|PermissionRole query()
     * @method static \Illuminate\Database\Eloquent\Builder|PermissionRole wherePermissionId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|PermissionRole whereRoleId($value)
     */
    class PermissionRole extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\User\Models{
    /**
     * App\Kravanh\Domain\User\Models\Role
     *
     * @property int $id
     * @property string $name
     * @property string $label
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property string|null $deleted_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Nova\Actions\ActionEvent> $actions
     * @property-read int|null $actions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\Permission> $permissions
     * @property-read int|null $permissions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
     * @property-read int|null $users_count
     *
     * @method static \Database\Factories\RoleFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Role query()
     * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Role whereDeletedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Role whereLabel($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
     */
    class Role extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\User\Models{
    /**
     * App\Kravanh\Domain\User\Models\RoleUser
     *
     * @property int $role_id
     * @property int $user_id
     *
     * @method static \Illuminate\Database\Eloquent\Builder|RoleUser newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|RoleUser newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|RoleUser query()
     * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereRoleId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|RoleUser whereUserId($value)
     */
    class RoleUser extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\User\Models{
    /**
     * App\Kravanh\Domain\User\Models\Senior
     *
     * @property int $id
     * @property int|null $domain_id
     * @property int|null $current_team_id
     * @property int|null $environment_id
     * @property int $group_id
     * @property int|null $spread_id
     * @property int $super_senior
     * @property int $senior
     * @property int $master_agent
     * @property int $agent
     * @property string $name
     * @property string|null $phone
     * @property string|null $email
     * @property \Illuminate\Support\Carbon|null $email_verified_at
     * @property string $password
     * @property string|null $two_factor_secret
     * @property string|null $two_factor_recovery_codes
     * @property \App\Kravanh\Domain\User\Supports\Enums\UserType $type
     * @property array|null $condition
     * @property string|null $remember_token
     * @property string|null $profile_photo_path
     * @property bool $internet_betting
     * @property \App\Kravanh\Domain\User\Supports\Enums\Status $status
     * @property int $suspend
     * @property \App\Kravanh\Domain\Match\Supports\Enums\BetType $bet_type
     * @property \Illuminate\Support\Carbon|null $last_login_at
     * @property int $online
     * @property string|null $ip
     * @property int $allow_stream
     * @property int $allow_af88_game
     * @property int $allow_t88_game
     * @property int $can_bet_when_disable
     * @property int $use_group_condition
     * @property \App\Kravanh\Support\Enums\Currency $currency
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Nova\Actions\ActionEvent> $actions
     * @property-read int|null $actions_count
     * @property-read \App\Kravanh\Domain\Integration\Models\Af88GameCondition|null $af88GameCondition
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Match\Models\BetRecord> $bets
     * @property-read int|null $bets_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Environment\Models\Group> $disableGroups
     * @property-read int|null $disable_groups_count
     * @property-read \App\Kravanh\Domain\Environment\Models\Domain|null $domain
     * @property-read \App\Kravanh\Domain\Environment\Models\Environment|null $environment
     * @property-read float|int|string $balance
     * @property-read int $balance_int
     * @property-read bool $can_play_dragon_tiger
     * @property-read string $maximum_bet_per_ticket
     * @property-read string $minimum_bet_per_ticket
     * @property-read bool $normal_member
     * @property-read string $profile_photo_url
     * @property-read \App\Kravanh\Domain\Environment\Models\Group|null $group
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\LoginHistory> $loginHistories
     * @property-read int|null $login_histories_count
     * @property-read \App\Kravanh\Domain\Match\Collections\MatchCollection<int, \App\Kravanh\Domain\Match\Models\Matches> $matches
     * @property-read int|null $matches_count
     * @property-read \App\Kravanh\Domain\User\Models\MemberPassword|null $memberPassword
     * @property-read \App\Kravanh\Domain\User\Models\MemberType|null $memberType
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\Message> $messages
     * @property-read int|null $messages_count
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
     * @property-read int|null $notifications_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\Role> $roles
     * @property-read int|null $roles_count
     * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
     * @property-read \App\Kravanh\Domain\Match\Models\Spread|null $spread
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Integration\Models\T88GameCondition> $t88GameConditions
     * @property-read int|null $t88_game_conditions_count
     * @property-read int|null $tags_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
     * @property-read int|null $tokens_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Bavix\Wallet\Models\Transaction> $transactions
     * @property-read int|null $transactions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Bavix\Wallet\Models\Transfer> $transfers
     * @property-read int|null $transfers_count
     * @property-read \App\Models\User|null $underAgent
     * @property-read \App\Models\User|null $underMasterAgent
     * @property-read \App\Models\User|null $underSenior
     * @property-read \App\Models\User|null $underSuperSenior
     * @property-read \Bavix\Wallet\Models\Wallet $wallet
     *
     * @method static \Illuminate\Database\Eloquent\Builder|User active()
     * @method static \Illuminate\Database\Eloquent\Builder|User currentBetReport($matchId)
     * @method static \Database\Factories\UserSuperSeniorFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|User member()
     * @method static \Illuminate\Database\Eloquent\Builder|Senior newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Senior newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Senior query()
     * @method static \Illuminate\Database\Eloquent\Builder|User todayBetReport($matchId = 0)
     * @method static \Illuminate\Database\Eloquent\Builder|User trader()
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereAllowAf88Game($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereAllowStream($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereAllowT88Game($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereBetType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereCanBetWhenDisable($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereCurrency($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereCurrentTeamId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereDomainId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereEmailVerifiedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereEnvironmentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereGroupId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereInternetBetting($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereIp($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereLastLoginAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereMasterAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereOnline($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior wherePhone($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereProfilePhotoPath($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereSenior($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereSpreadId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereSuperSenior($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereSuspend($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereTwoFactorRecoveryCodes($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereTwoFactorSecret($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Senior whereUseGroupCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAllTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAnyTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder|User withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     */
    class Senior extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\User\Models{
    /**
     * App\Kravanh\Domain\User\Models\SubAccount
     *
     * @property int $id
     * @property int|null $domain_id
     * @property int|null $current_team_id
     * @property int|null $environment_id
     * @property int $group_id
     * @property int|null $spread_id
     * @property int $super_senior
     * @property int $senior
     * @property int $master_agent
     * @property int $agent
     * @property string $name
     * @property string|null $phone
     * @property string|null $email
     * @property \Illuminate\Support\Carbon|null $email_verified_at
     * @property string $password
     * @property string|null $two_factor_secret
     * @property string|null $two_factor_recovery_codes
     * @property \App\Kravanh\Domain\User\Supports\Enums\UserType $type
     * @property array|null $condition
     * @property string|null $remember_token
     * @property string|null $profile_photo_path
     * @property bool $internet_betting
     * @property \App\Kravanh\Domain\User\Supports\Enums\Status $status
     * @property int $suspend
     * @property \App\Kravanh\Domain\Match\Supports\Enums\BetType $bet_type
     * @property \Illuminate\Support\Carbon|null $last_login_at
     * @property int $online
     * @property string|null $ip
     * @property int $allow_stream
     * @property int $allow_af88_game
     * @property int $allow_t88_game
     * @property int $can_bet_when_disable
     * @property int $use_group_condition
     * @property \App\Kravanh\Support\Enums\Currency $currency
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Nova\Actions\ActionEvent> $actions
     * @property-read int|null $actions_count
     * @property-read \App\Kravanh\Domain\Integration\Models\Af88GameCondition|null $af88GameCondition
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Match\Models\BetRecord> $bets
     * @property-read int|null $bets_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Environment\Models\Group> $disableGroups
     * @property-read int|null $disable_groups_count
     * @property-read \App\Kravanh\Domain\Environment\Models\Domain|null $domain
     * @property-read \App\Kravanh\Domain\Environment\Models\Environment|null $environment
     * @property-read float|int|string $balance
     * @property-read int $balance_int
     * @property-read bool $can_play_dragon_tiger
     * @property-read string $maximum_bet_per_ticket
     * @property-read string $minimum_bet_per_ticket
     * @property-read bool $normal_member
     * @property-read string $profile_photo_url
     * @property-read \App\Kravanh\Domain\Environment\Models\Group|null $group
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\LoginHistory> $loginHistories
     * @property-read int|null $login_histories_count
     * @property-read \App\Kravanh\Domain\Match\Collections\MatchCollection<int, \App\Kravanh\Domain\Match\Models\Matches> $matches
     * @property-read int|null $matches_count
     * @property-read \App\Kravanh\Domain\User\Models\MemberPassword|null $memberPassword
     * @property-read \App\Kravanh\Domain\User\Models\MemberType|null $memberType
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\Message> $messages
     * @property-read int|null $messages_count
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
     * @property-read int|null $notifications_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\Role> $roles
     * @property-read int|null $roles_count
     * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
     * @property-read \App\Kravanh\Domain\Match\Models\Spread|null $spread
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Integration\Models\T88GameCondition> $t88GameConditions
     * @property-read int|null $t88_game_conditions_count
     * @property-read int|null $tags_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
     * @property-read int|null $tokens_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Bavix\Wallet\Models\Transaction> $transactions
     * @property-read int|null $transactions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Bavix\Wallet\Models\Transfer> $transfers
     * @property-read int|null $transfers_count
     * @property-read \App\Models\User|null $underAgent
     * @property-read \App\Models\User|null $underMasterAgent
     * @property-read \App\Models\User|null $underSenior
     * @property-read \App\Models\User|null $underSuperSenior
     * @property-read \Bavix\Wallet\Models\Wallet $wallet
     *
     * @method static \Illuminate\Database\Eloquent\Builder|User active()
     * @method static \Illuminate\Database\Eloquent\Builder|User currentBetReport($matchId)
     * @method static \Illuminate\Database\Eloquent\Builder|User member()
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount query()
     * @method static \Illuminate\Database\Eloquent\Builder|User todayBetReport($matchId = 0)
     * @method static \Illuminate\Database\Eloquent\Builder|User trader()
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereAllowAf88Game($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereAllowStream($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereAllowT88Game($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereBetType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereCanBetWhenDisable($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereCurrency($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereCurrentTeamId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereDomainId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereEmailVerifiedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereEnvironmentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereGroupId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereInternetBetting($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereIp($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereLastLoginAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereMasterAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereOnline($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount wherePhone($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereProfilePhotoPath($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereSenior($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereSpreadId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereSuperSenior($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereSuspend($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereTwoFactorRecoveryCodes($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereTwoFactorSecret($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SubAccount whereUseGroupCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAllTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAnyTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder|User withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     */
    class SubAccount extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\User\Models{
    /**
     * App\Kravanh\Domain\User\Models\SuperSenior
     *
     * @property int $id
     * @property int|null $domain_id
     * @property int|null $current_team_id
     * @property int|null $environment_id
     * @property int $group_id
     * @property int|null $spread_id
     * @property int $super_senior
     * @property int $senior
     * @property int $master_agent
     * @property int $agent
     * @property string $name
     * @property string|null $phone
     * @property string|null $email
     * @property \Illuminate\Support\Carbon|null $email_verified_at
     * @property string $password
     * @property string|null $two_factor_secret
     * @property string|null $two_factor_recovery_codes
     * @property \App\Kravanh\Domain\User\Supports\Enums\UserType $type
     * @property array|null $condition
     * @property string|null $remember_token
     * @property string|null $profile_photo_path
     * @property bool $internet_betting
     * @property \App\Kravanh\Domain\User\Supports\Enums\Status $status
     * @property int $suspend
     * @property \App\Kravanh\Domain\Match\Supports\Enums\BetType $bet_type
     * @property \Illuminate\Support\Carbon|null $last_login_at
     * @property int $online
     * @property string|null $ip
     * @property int $allow_stream
     * @property int $allow_af88_game
     * @property int $allow_t88_game
     * @property int $can_bet_when_disable
     * @property int $use_group_condition
     * @property \App\Kravanh\Support\Enums\Currency $currency
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Nova\Actions\ActionEvent> $actions
     * @property-read int|null $actions_count
     * @property-read \App\Kravanh\Domain\Integration\Models\Af88GameCondition|null $af88GameCondition
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Match\Models\BetRecord> $bets
     * @property-read int|null $bets_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Environment\Models\Group> $disableGroups
     * @property-read int|null $disable_groups_count
     * @property-read \App\Kravanh\Domain\Environment\Models\Domain|null $domain
     * @property-read \App\Kravanh\Domain\Environment\Models\Environment|null $environment
     * @property-read float|int|string $balance
     * @property-read int $balance_int
     * @property-read bool $can_play_dragon_tiger
     * @property-read string $maximum_bet_per_ticket
     * @property-read string $minimum_bet_per_ticket
     * @property-read bool $normal_member
     * @property-read string $profile_photo_url
     * @property-read \App\Kravanh\Domain\Environment\Models\Group|null $group
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\LoginHistory> $loginHistories
     * @property-read int|null $login_histories_count
     * @property-read \App\Kravanh\Domain\Match\Collections\MatchCollection<int, \App\Kravanh\Domain\Match\Models\Matches> $matches
     * @property-read int|null $matches_count
     * @property-read \App\Kravanh\Domain\User\Models\MemberPassword|null $memberPassword
     * @property-read \App\Kravanh\Domain\User\Models\MemberType|null $memberType
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\Message> $messages
     * @property-read int|null $messages_count
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
     * @property-read int|null $notifications_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\Role> $roles
     * @property-read int|null $roles_count
     * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
     * @property-read \App\Kravanh\Domain\Match\Models\Spread|null $spread
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Integration\Models\T88GameCondition> $t88GameConditions
     * @property-read int|null $t88_game_conditions_count
     * @property-read int|null $tags_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
     * @property-read int|null $tokens_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Bavix\Wallet\Models\Transaction> $transactions
     * @property-read int|null $transactions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Bavix\Wallet\Models\Transfer> $transfers
     * @property-read int|null $transfers_count
     * @property-read \App\Models\User|null $underAgent
     * @property-read \App\Models\User|null $underMasterAgent
     * @property-read \App\Models\User|null $underSenior
     * @property-read \App\Models\User|null $underSuperSenior
     * @property-read \Bavix\Wallet\Models\Wallet $wallet
     *
     * @method static \Illuminate\Database\Eloquent\Builder|User active()
     * @method static \Illuminate\Database\Eloquent\Builder|User currentBetReport($matchId)
     * @method static \Database\Factories\UserSuperSeniorFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|User member()
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior query()
     * @method static \Illuminate\Database\Eloquent\Builder|User todayBetReport($matchId = 0)
     * @method static \Illuminate\Database\Eloquent\Builder|User trader()
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereAllowAf88Game($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereAllowStream($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereAllowT88Game($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereBetType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereCanBetWhenDisable($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereCurrency($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereCurrentTeamId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereDomainId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereEmailVerifiedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereEnvironmentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereGroupId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereInternetBetting($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereIp($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereLastLoginAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereMasterAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereOnline($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior wherePhone($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereProfilePhotoPath($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereSenior($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereSpreadId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereSuperSenior($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereSuspend($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereTwoFactorRecoveryCodes($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereTwoFactorSecret($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|SuperSenior whereUseGroupCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAllTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAnyTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder|User withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     */
    class SuperSenior extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\User\Models{
    /**
     * App\Kravanh\Domain\User\Models\Trader
     *
     * @property int $id
     * @property int|null $domain_id
     * @property int|null $current_team_id
     * @property int|null $environment_id
     * @property int $group_id
     * @property int|null $spread_id
     * @property int $super_senior
     * @property int $senior
     * @property int $master_agent
     * @property int $agent
     * @property string $name
     * @property string|null $phone
     * @property string|null $email
     * @property \Illuminate\Support\Carbon|null $email_verified_at
     * @property string $password
     * @property string|null $two_factor_secret
     * @property string|null $two_factor_recovery_codes
     * @property \App\Kravanh\Domain\User\Supports\Enums\UserType $type
     * @property array|null $condition
     * @property string|null $remember_token
     * @property string|null $profile_photo_path
     * @property bool $internet_betting
     * @property \App\Kravanh\Domain\User\Supports\Enums\Status $status
     * @property int $suspend
     * @property \App\Kravanh\Domain\Match\Supports\Enums\BetType $bet_type
     * @property \Illuminate\Support\Carbon|null $last_login_at
     * @property int $online
     * @property string|null $ip
     * @property int $allow_stream
     * @property int $allow_af88_game
     * @property int $allow_t88_game
     * @property int $can_bet_when_disable
     * @property int $use_group_condition
     * @property \App\Kravanh\Support\Enums\Currency $currency
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Nova\Actions\ActionEvent> $actions
     * @property-read int|null $actions_count
     * @property-read \App\Kravanh\Domain\Integration\Models\Af88GameCondition|null $af88GameCondition
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Match\Models\BetRecord> $bets
     * @property-read int|null $bets_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Environment\Models\Group> $disableGroups
     * @property-read int|null $disable_groups_count
     * @property-read \App\Kravanh\Domain\Environment\Models\Domain|null $domain
     * @property-read \App\Kravanh\Domain\Environment\Models\Environment|null $environment
     * @property-read \App\Kravanh\Domain\Game\Models\GameTable|null $gameTable
     * @property-read float|int|string $balance
     * @property-read int $balance_int
     * @property-read bool $can_play_dragon_tiger
     * @property-read string $maximum_bet_per_ticket
     * @property-read string $minimum_bet_per_ticket
     * @property-read bool $normal_member
     * @property-read string $profile_photo_url
     * @property-read \App\Kravanh\Domain\Environment\Models\Group|null $group
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\LoginHistory> $loginHistories
     * @property-read int|null $login_histories_count
     * @property-read \App\Kravanh\Domain\Match\Collections\MatchCollection<int, \App\Kravanh\Domain\Match\Models\Matches> $matches
     * @property-read int|null $matches_count
     * @property-read \App\Kravanh\Domain\User\Models\MemberPassword|null $memberPassword
     * @property-read \App\Kravanh\Domain\User\Models\MemberType|null $memberType
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\Message> $messages
     * @property-read int|null $messages_count
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
     * @property-read int|null $notifications_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\Role> $roles
     * @property-read int|null $roles_count
     * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
     * @property-read \App\Kravanh\Domain\Match\Models\Spread|null $spread
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Integration\Models\T88GameCondition> $t88GameConditions
     * @property-read int|null $t88_game_conditions_count
     * @property-read int|null $tags_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
     * @property-read int|null $tokens_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Bavix\Wallet\Models\Transaction> $transactions
     * @property-read int|null $transactions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Bavix\Wallet\Models\Transfer> $transfers
     * @property-read int|null $transfers_count
     * @property-read \App\Models\User|null $underAgent
     * @property-read \App\Models\User|null $underMasterAgent
     * @property-read \App\Models\User|null $underSenior
     * @property-read \App\Models\User|null $underSuperSenior
     * @property-read \Bavix\Wallet\Models\Wallet $wallet
     *
     * @method static \Illuminate\Database\Eloquent\Builder|User active()
     * @method static \Illuminate\Database\Eloquent\Builder|User currentBetReport($matchId)
     * @method static \Database\Factories\TraderFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|User member()
     * @method static \Illuminate\Database\Eloquent\Builder|Trader newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Trader newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Trader query()
     * @method static \Illuminate\Database\Eloquent\Builder|User todayBetReport($matchId = 0)
     * @method static \Illuminate\Database\Eloquent\Builder|User trader()
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereAllowAf88Game($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereAllowStream($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereAllowT88Game($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereBetType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereCanBetWhenDisable($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereCurrency($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereCurrentTeamId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereDomainId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereEmailVerifiedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereEnvironmentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereGroupId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereInternetBetting($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereIp($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereLastLoginAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereMasterAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereOnline($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader wherePhone($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereProfilePhotoPath($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereSenior($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereSpreadId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereSuperSenior($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereSuspend($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereTwoFactorRecoveryCodes($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereTwoFactorSecret($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Trader whereUseGroupCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAllTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAnyTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder|User withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     */
    class Trader extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\User\Models{
    /**
     * App\Kravanh\Domain\User\Models\Transaction
     *
     * @property int $id
     * @property string $payable_type
     * @property int $payable_id
     * @property int $wallet_id
     * @property string $type
     * @property string $amount
     * @property int $confirmed
     * @property mixed|null $meta
     * @property string $uuid
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction query()
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereAmount($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereConfirmed($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereMeta($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePayableId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction wherePayableType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereUuid($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Transaction whereWalletId($value)
     */
    class Transaction extends \Eloquent
    {
    }
}

namespace App\Kravanh\Domain\WalletBackup\Models{
    /**
     * App\Kravanh\Domain\WalletBackup\Models\WalletBackup
     *
     * @property int $id
     * @property int $wallet_id
     * @property int $holder_id
     * @property string $holder_type
     * @property string $balance
     * @property string $cache_balance
     * @property string $login_balance
     * @property int $last_updated_balance
     * @property bool $is_cache_balance_updated
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     *
     * @method static \Illuminate\Database\Eloquent\Builder|WalletBackup newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|WalletBackup newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|WalletBackup query()
     * @method static \Illuminate\Database\Eloquent\Builder|WalletBackup whereBalance($value)
     * @method static \Illuminate\Database\Eloquent\Builder|WalletBackup whereCacheBalance($value)
     * @method static \Illuminate\Database\Eloquent\Builder|WalletBackup whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|WalletBackup whereHolderId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|WalletBackup whereHolderType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|WalletBackup whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|WalletBackup whereIsCacheBalanceUpdated($value)
     * @method static \Illuminate\Database\Eloquent\Builder|WalletBackup whereLastUpdatedBalance($value)
     * @method static \Illuminate\Database\Eloquent\Builder|WalletBackup whereLoginBalance($value)
     * @method static \Illuminate\Database\Eloquent\Builder|WalletBackup whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|WalletBackup whereWalletId($value)
     */
    class WalletBackup extends \Eloquent
    {
    }
}

namespace App\Models{
    /**
     * App\Models\Session
     *
     * @property int $id
     * @property int|null $user_id
     * @property string|null $ip_address
     * @property string|null $ip_address_vapor
     * @property string|null $host
     * @property string|null $user_agent
     * @property string $payload
     * @property int $last_activity
     *
     * @method static \Illuminate\Database\Eloquent\Builder|Session newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Session newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|Session query()
     * @method static \Illuminate\Database\Eloquent\Builder|Session whereHost($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Session whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Session whereIpAddress($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Session whereIpAddressVapor($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Session whereLastActivity($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Session wherePayload($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Session whereUserAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|Session whereUserId($value)
     */
    class Session extends \Eloquent
    {
    }
}

namespace App\Models{
    /**
     * App\Models\User
     *
     * @property int $id
     * @property int|null $domain_id
     * @property int|null $current_team_id
     * @property int|null $environment_id
     * @property int $group_id
     * @property int|null $spread_id
     * @property int $super_senior
     * @property int $senior
     * @property int $master_agent
     * @property int $agent
     * @property string $name
     * @property string|null $phone
     * @property string|null $email
     * @property \Illuminate\Support\Carbon|null $email_verified_at
     * @property string $password
     * @property string|null $two_factor_secret
     * @property string|null $two_factor_recovery_codes
     * @property \App\Kravanh\Domain\User\Supports\Enums\UserType $type
     * @property array|null $condition
     * @property string|null $remember_token
     * @property string|null $profile_photo_path
     * @property bool $internet_betting
     * @property \App\Kravanh\Domain\User\Supports\Enums\Status $status
     * @property int $suspend
     * @property \App\Kravanh\Domain\Match\Supports\Enums\BetType $bet_type
     * @property \Illuminate\Support\Carbon|null $last_login_at
     * @property int $online
     * @property string|null $ip
     * @property int $allow_stream
     * @property int $allow_af88_game
     * @property int $allow_t88_game
     * @property int $can_bet_when_disable
     * @property int $use_group_condition
     * @property \App\Kravanh\Support\Enums\Currency $currency
     * @property \Illuminate\Support\Carbon|null $created_at
     * @property \Illuminate\Support\Carbon|null $updated_at
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Nova\Actions\ActionEvent> $actions
     * @property-read int|null $actions_count
     * @property-read \App\Kravanh\Domain\Integration\Models\Af88GameCondition|null $af88GameCondition
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Match\Models\BetRecord> $bets
     * @property-read int|null $bets_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Environment\Models\Group> $disableGroups
     * @property-read int|null $disable_groups_count
     * @property-read \App\Kravanh\Domain\Environment\Models\Domain|null $domain
     * @property-read \App\Kravanh\Domain\Environment\Models\Environment|null $environment
     * @property-read float|int|string $balance
     * @property-read int $balance_int
     * @property-read bool $can_play_dragon_tiger
     * @property-read string $maximum_bet_per_ticket
     * @property-read string $minimum_bet_per_ticket
     * @property-read bool $normal_member
     * @property-read string $profile_photo_url
     * @property-read \App\Kravanh\Domain\Environment\Models\Group|null $group
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\LoginHistory> $loginHistories
     * @property-read int|null $login_histories_count
     * @property-read \App\Kravanh\Domain\Match\Collections\MatchCollection<int, \App\Kravanh\Domain\Match\Models\Matches> $matches
     * @property-read int|null $matches_count
     * @property-read \App\Kravanh\Domain\User\Models\MemberPassword|null $memberPassword
     * @property-read \App\Kravanh\Domain\User\Models\MemberType|null $memberType
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\Message> $messages
     * @property-read int|null $messages_count
     * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
     * @property-read int|null $notifications_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\User\Models\Role> $roles
     * @property-read int|null $roles_count
     * @property \Illuminate\Database\Eloquent\Collection<int, \Spatie\Tags\Tag> $tags
     * @property-read \App\Kravanh\Domain\Match\Models\Spread|null $spread
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Kravanh\Domain\Integration\Models\T88GameCondition> $t88GameConditions
     * @property-read int|null $t88_game_conditions_count
     * @property-read int|null $tags_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
     * @property-read int|null $tokens_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Bavix\Wallet\Models\Transaction> $transactions
     * @property-read int|null $transactions_count
     * @property-read \Illuminate\Database\Eloquent\Collection<int, \Bavix\Wallet\Models\Transfer> $transfers
     * @property-read int|null $transfers_count
     * @property-read User|null $underAgent
     * @property-read User|null $underMasterAgent
     * @property-read User|null $underSenior
     * @property-read User|null $underSuperSenior
     * @property-read \Bavix\Wallet\Models\Wallet $wallet
     *
     * @method static \Illuminate\Database\Eloquent\Builder|User active()
     * @method static \Illuminate\Database\Eloquent\Builder|User currentBetReport($matchId)
     * @method static \Database\Factories\UserFactory factory(...$parameters)
     * @method static \Illuminate\Database\Eloquent\Builder|User member()
     * @method static \Illuminate\Database\Eloquent\Builder|User newModelQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|User newQuery()
     * @method static \Illuminate\Database\Eloquent\Builder|User query()
     * @method static \Illuminate\Database\Eloquent\Builder|User todayBetReport($matchId = 0)
     * @method static \Illuminate\Database\Eloquent\Builder|User trader()
     * @method static \Illuminate\Database\Eloquent\Builder|User whereAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereAllowAf88Game($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereAllowStream($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereAllowT88Game($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereBetType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereCanBetWhenDisable($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereCreatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrency($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereCurrentTeamId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereDomainId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereEmail($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereEmailVerifiedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereEnvironmentId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereGroupId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereInternetBetting($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereIp($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereLastLoginAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereMasterAgent($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereName($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereOnline($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User wherePassword($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User wherePhone($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereProfilePhotoPath($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereRememberToken($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereSenior($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereSpreadId($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereStatus($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereSuperSenior($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereSuspend($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorRecoveryCodes($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereTwoFactorSecret($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereType($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereUpdatedAt($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User whereUseGroupCondition($value)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAllTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAllTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAnyTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     * @method static \Illuminate\Database\Eloquent\Builder|User withAnyTagsOfAnyType($tags)
     * @method static \Illuminate\Database\Eloquent\Builder|User withoutTags(\ArrayAccess|\Spatie\Tags\Tag|array|string $tags, ?string $type = null)
     */
    class User extends \Eloquent implements \Bavix\Wallet\Interfaces\Wallet
    {
    }
}
