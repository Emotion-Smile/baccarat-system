<?php

namespace App\Kravanh\Domain\Integration\Supports\Nova;

use App\Kravanh\Domain\Integration\Contracts\Services\AF88Contract;
use App\Kravanh\Domain\User\Supports\Enums\UserType;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use KravanhEco\Balance\Balance;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Select;

class AF88GameConditionNovaActionFields
{
    protected User $user;
    protected array $userDetail;
    protected ?array $existUserDetail;
    protected bool $onUpdate;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->userDetail = App::make(AF88Contract::class)->getUserDetail();
        $this->existUserDetail = $user->af88GameCondition?->condition;
        $this->onUpdate = $user->hasAF88GameCondition();
    }

    public function __invoke(): array
    {
        return match (request()->user()->type->value) {
            UserType::AGENT => $this->memberFields(),
            default => $this->fields()
        };
    }

    public function fields(): array
    {
        return [
            ...$this->accountShareFields(),

            ...$this->singleBetLimitFields(),

            ...$this->parlayBetLimitFields(),

            ...$this->memberCommissionSingleBet(),

            ...$this->memberCommissionParlayBet(),

            ...$this->statusFields()
        ];
    }

    public function memberFields(): array
    {
        return [
            ...$this->singleBetLimitFields([
                $this->winLimitPerDay(),
            ]),

            ...$this->parlayBetLimitFields(),

            ...$this->memberCommissionSingleBetSelectField(),

            ...$this->memberCommissionParlayBetSelectField(),

            ...$this->statusFields()
        ];
    }

    protected function statusFields(): array
    {
        return $this->headerWrapperField(
            name: 'Status',
            fields: [
                $this->suspendField(),

                $this->lockField()
            ]
        );
    }

    protected function accountShareFields(): array
    {
        $currentAccountShare = $this->userDetail['down_line_share'] ?? 100;

        return $this->headerWrapperField(
            name: "Account Share ( Current account share {$currentAccountShare}% )",
            fields: [
                Number::make('My Position Taking', 'my_position_taking')
                    ->default($this->existUserDetail['my_position_taking'] ?? null)
                    ->rules([
                        'required',
                        "lte:{$currentAccountShare}",
                        function ($attribute, $value, $fail) use ($currentAccountShare) {
                            $downLineShare = request('down_line_share');

                            if($downLineShare) {
                                $totalUserShare = $value + $downLineShare;

                                if ($totalUserShare > $currentAccountShare) {
                                    $fail("My Position Taking + Downline Share cannot greater than {$currentAccountShare}%");
                                }

                                if($totalUserShare < $currentAccountShare) {
                                    $fail("My Position Taking + Downline Share cannot less than {$currentAccountShare}%");
                                }
                            }
                        }
                    ])
                    ->readonly($this->onUpdate),

                Number::make('Downline Share', 'down_line_share')
                    ->default($this->existUserDetail['down_line_share'] ?? null)
                    ->rules([
                        'required',
                        "lte:{$currentAccountShare}"
                    ])
                    ->readonly($this->onUpdate),
            ]
        );
    }

    protected function singleBetLimitFields(
        array $moreFields = []
    ): array
    {
        $currency = $this->userDetail['currency'];

        $minimumBet = $this->userDetail['minimum_single_bet'] ?? null;
        $maximumBet = $this->userDetail['maximum_single_bet'] ?? null;
        $matchLimited = $this->userDetail['match_limited_single_bet'] ?? null;

        return $this->headerWrapperField(
            name: 'Single Bet Limit (HDP/OU/OE)',
            fields: [
                Balance::make('Minimum Bet', 'minimum_single_bet')
                    ->default($this->existUserDetail['minimum_single_bet'] ?? null)
                    ->rules(
                        'required',
                        'numeric',
                        $minimumBet ? "gte:{$minimumBet}" : null
                    )
                    ->help($this->getCurrencyHelpText($minimumBet, $currency)),

                Balance::make('Maximum Bet', 'maximum_single_bet')
                    ->default($this->existUserDetail['maximum_single_bet'] ?? null)
                    ->rules(
                        'required',
                        'numeric',
                        $minimumBet ? "gt:{$minimumBet}" : null,
                        $maximumBet ? "lte:{$maximumBet}" : null,
                    )
                    ->help($this->getCurrencyHelpText($maximumBet, $currency)),

                Balance::make('Match Limited', 'match_limited_single_bet')
                    ->default($this->existUserDetail['match_limited_single_bet'] ?? null)
                    ->rules(
                        'required',
                        'numeric',
                        $matchLimited ? "lte:{$matchLimited}" : null
                    )
                    ->help($this->getCurrencyHelpText($matchLimited, $currency)),

                ...$moreFields,
            ]
        );
    }

    protected function parlayBetLimitFields(): array
    {
        $currency = $this->userDetail['currency'];

        $minimumBet = $this->userDetail['minimum_mix_parlay_bet'] ?? null;
        $maximumBet = $this->userDetail['maximum_mix_parlay_bet'] ?? null;
        $maximumPayout = $this->userDetail['maximum_payout_mix_parlay_bet'] ?? null;

        return $this->headerWrapperField(
            name: 'Parlay Bet Limit',
            fields: [
                Balance::make('Minimum Bet', 'minimum_mix_parlay_bet')
                    ->default($this->existUserDetail['minimum_mix_parlay_bet'] ?? null)
                    ->rules(
                        'required',
                        'numeric',
                        $minimumBet ? "gte:{$minimumBet}" : null
                    )
                    ->help($this->getCurrencyHelpText($minimumBet, $currency)),

                Balance::make('Maximum Bet', 'maximum_mix_parlay_bet')
                    ->default($this->existUserDetail['maximum_mix_parlay_bet'] ?? null)
                    ->rules(
                        'required',
                        'numeric',
                        $minimumBet ? "gt:{$minimumBet}" : null,
                        $maximumBet ? "lte:{$maximumBet}" : null,
                    )
                    ->help($this->getCurrencyHelpText($maximumBet, $currency)),

                Balance::make('Maximum Payout Per Ticket', 'maximum_payout_mix_parlay_bet')
                    ->default($this->existUserDetail['maximum_payout_mix_parlay_bet'] ?? null)
                    ->rules(
                        'required',
                        'numeric',
                        $maximumPayout ? "lte:{$maximumPayout}" : null,
                    )
                    ->help($this->getCurrencyHelpText($maximumPayout, $currency)),
            ]
        );
    }

    protected function memberCommissionSingleBet(): array
    {
        return $this->headerWrapperField(
            name: 'Member Commission (HDP/OU/OE)',
            fields: [
                ...$this->generateMemberCommissionFields(
                    prefixKey: 'commission_hdp_ou_oe'
                )
            ]
        );
    }

    protected function memberCommissionParlayBet(): array
    {
        return $this->headerWrapperField(
            name: 'Member Commission (Parlay Bet)',
            fields: [
                ...$this->generateMemberCommissionFields(
                    prefixKey: 'commission_par_cs_tg'
                )
            ]
        );
    }

    protected function generateMemberCommissionFields(
        string $prefixKey
    ): array
    {
        return Collection::make([
            '4' => '4',
            '5' => 'a',
            '6' => 'b',
            '7' => 'c'
        ])
            ->map(function ($groupType, $percentage) use ($prefixKey) {
                $key = $prefixKey . '_group_' . $groupType;
                $commission = $this->userDetail[$key] ?? null;

                $name = 'Group ' . Str::upper($groupType) . ' - ' . $percentage . '%';

                return Number::make($name, $key)
                    ->default($this->existUserDetail[$key] ?? null)
                    ->rules(
                        'gte:0',
                        $commission ? "lte:{$commission}" : 'lte:100'
                    )
                    ->step('any')
                    ->help($this->getCommissionHelpText($commission));
            })
            ->values()
            ->toArray();
    }

    protected function memberCommissionSingleBetSelectField(): array
    {
        return $this->headerWrapperField(
            name: 'Member Commission (HDP/OU/OE)',
            fields: [
                $this->generateMemberCommissionSelectField(
                    fieldKey: 'commission_hdp_ou_oe',
                    valueKey: 'commission_hdp_ou_or'
                )
            ]
        );
    }

    protected function memberCommissionParlayBetSelectField(): array
    {
        return $this->headerWrapperField(
            name: 'Member Commission (Parlay Bet)',
            fields: [
                $this->generateMemberCommissionSelectField(
                    fieldKey: 'commission_par_cs_tg',
                    valueKey: 'commission_par_cs_tg'
                )
            ]
        );
    }

    protected function generateMemberCommissionSelectField(
        string $fieldKey,
        string $valueKey
    ): Select
    {
        return Select::make('Group', $fieldKey . '_selected_group')
            ->options(
                Collection::make([
                    '4' => '4',
                    '5' => 'a',
                    '6' => 'b',
                    '7' => 'c'
                ])
                ->flatMap(function ($groupType, $percentage) use ($valueKey) {
                    $key = $valueKey . '_group_' . $groupType;
                    $name = 'Group ' . Str::upper($groupType) . ' - ' . $percentage . '%';

                    return [$key => $name];
                })
                ->toArray()
            )
            ->displayUsingLabels()
            ->withMeta([
                'placeholder' => 'Choose Group'
            ])
            ->rules([
                'required',
                'string'
            ])
            ->default($this->existUserDetail[$fieldKey . '_selected_group'] ?? null);
    }

    protected function winLimitPerDay(): Balance
    {
        return Balance::make('Win Limit Per Day', 'win_limited_per_day')
            ->rules('required')
            ->default($this->existUserDetail['win_limited_per_day'] ?? null)
            ->help($this->getHelpText('0 = unrestricted'));
    }

    protected function suspendField()//: Boolean
    {
        return Boolean::make(__('Suspend'), 'suspend')
            ->default($this->existUserDetail['suspend'] ?? false);
    }

    protected function lockField()//: Boolean
    {
        return Boolean::make('Lock', 'lock')
            ->default($this->existUserDetail['lock'] ?? false);
    }

    protected function headerWrapperField(
        string $name,
        array $fields = [],
        bool $hideWhenCreating = false,
        bool $hideWhenUpdating = false
    ): array
    {
        return [
            Heading::make($name)
                ->hideWhenCreating($hideWhenCreating)
                ->hideWhenUpdating($hideWhenUpdating),

            ...$fields
        ];
    }

    protected function getCurrencyHelpText(
        null|int|float|string $amount,
        string $currency
    ): ?string
    {
        if(is_null($amount)) return null;

        return $this->getHelpText(
            value: 'Current from upline: ' . priceFormat(
                value: $amount,
                prefix: $currency
            )
        );
    }

    protected function getCommissionHelpText(
        null|int|float|string $commission,
    )
    {
        if(is_null($commission)) return null;

        return $this->getHelpText(
            value: 'Current from upline: ' . $commission . '%'
        );
    }

    protected function getHelpText(
        string $value
    ): string
    {
        return view('integration::partials.help-text', ['value' => $value])->render();
    }
}
