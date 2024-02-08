<script>
import HandlesRequest from '@/mixins/HandlesRequest';

export default {
    metaInfo() {
        return {
            title: 'AF88 Win/Lose Report',
        };
    },

    mixins: [
        HandlesRequest
    ],

    data() {
        return {
            items: [],
            total: {},
            userType: {},
            previousUserId: null
        };
    },

    methods: {
        setData(data) {
            this.items = data.report.items;
            this.total = data.report.total;
            this.userType = data.report.userType;
            // this.previousUserId = data.previousUserId; 
        },

        watchParamsForRequest() {
            return (
                this.userId +
                this.date +
                this.from +
                this.to
            );
        },

        onPeriodDateChange(value) {
            this.updateQueryString({
                date: value,
                from: null,
                to: null,
            });
        },

        onDateRangeChange(value) {
            this.updateQueryString({
                ...value,
                date: null,
            });
        }
    },

    computed: {
        requestUrl() {
            return `/nova-vendor/report/af88/win-lose`;
        },

        requestQueryString() {
            let param = {
                userId: this.userId,
                date: this.date,
                from: this.from,
                to: this.to,
            };

            return param;
        },

        userId() {
            return this.$route.query['userId'] || null;
        },

        date() {
            if (this.from === undefined || this.from === null) {
                return this.$route.query['date'] || 'today';
            }

            return null;
        },

        from() {
            return this.$route.query['from'];
        },

        to() {
            return this.$route.query['to'];
        },

        isCompanyType() {
            return this.userType.value === 'company';
        },

        isNotCompanyType() {
            return ! this.isCompanyType;
        },

        isAgentType() {
            return this.userType.value === 'agent';
        },

        isNotAgentType() {
            return ! this.isAgentType;
        }
    }
}
</script>

<template>
    <div class="relative">
        <div class="flex item-center mb-3">
            <previous-button
                v-if="userId"
                :to="{
                    name: 'report-win-lose',
                    query: {
                        to,
                        date,
                        from,
                        userId: previousUserId,
                    }
                }"
            />

            <heading
                :level="1"
                v-html="`AF88 Win/Lose Report`"
            />
        </div>

        <card class="mb-6">
            <div class="p-3 flex items-center border-b border-50 justify-between">

                <period-date-picker
                    :selected="date"
                    @change="onPeriodDateChange"
                />

                <date-range-picker
                    :to="to"
                    :from="from"
                    @change="onDateRangeChange"
                />

            </div>
        </card>

        <card>
            <loading-view
                :loading="loading"
                dusk="report-win-lose-index-component"
            >
                <table-report
                    :hasRecord="items.length > 0"
                >
                    <template v-slot:header>
                        <tr>
                            <th 
                                class="text-left border-r"
                                rowspan="2"
                            >
                                <span>No</span>
                            </th>

                            <th 
                                class="text-left border-r"
                                rowspan="2"
                            >
                                <span>Account</span>
                            </th>

                            <th 
                                class="text-left border-r"
                                rowspan="2"
                            >
                                <span>Currency</span>
                            </th>

                            <th 
                                class="text-left border-r"
                                rowspan="2"
                            >
                                <span>Game Type</span>
                            </th>

                            <th 
                                class="text-left border-r"
                                rowspan="2"
                            >
                                <span>Bet Amount</span>
                            </th>

                            <th 
                                class="text-left border-r"
                                rowspan="2"
                            >
                                <span>Valid Amount</span>
                            </th>

                            <th 
                                class="text-center border-r"
                                colspan="3"
                            >
                                <span>Members</span>
                            </th>

                            <th 
                                class="text-center border-r"
                                colspan="3"
                            >
                                <span>{{ `${userType.label} Profit` }}</span>
                            </th>

                            <template v-if="isNotCompanyType">
                                <th 
                                    class="text-center border-r"
                                    colspan="3"
                                >
                                    <span>Upline Profit</span>
                                </th>
                            </template>

                            <th
                                class="text-center"
                                rowspan="2"
                            >
                                &nbsp;
                            </th>

                            <th
                                v-if="isAgentType"
                                class="text-center"
                                rowspan="2"
                            >
                                &nbsp;
                            </th>
                        </tr>

                        <tr>
                            <th class="text-right border-r">
                                <span>W/L</span>
                            </th>

                            <th class="text-right border-r">
                                <span>Com</span>
                            </th>

                            <th class="text-right border-r">
                                <span>W/L + Com</span>
                            </th>

                            <th class="text-right border-r">
                                <span>W/L</span>
                            </th>

                            <th class="text-right border-r">
                                <span>Com</span>
                            </th>

                            <th class="text-right border-r">
                                <span>W/L + Com</span>
                            </th>

                            <template v-if="isNotCompanyType">
                                <th class="text-right border-r">
                                    <span>W/L</span>
                                </th>

                                <th class="text-right border-r">
                                    <span>Com</span>
                                </th>

                                <th class="text-right border-r">
                                    <span>W/L + Com</span>
                                </th>
                            </template>
                        </tr>
                    </template>

                    <template v-slot:body>
                        <template 
                            v-for="(item, key) in items"
                        >
                            <tr 
                                :key="key"
                            >
                                <td 
                                    class="text-left border-r"
                                    rowspan="2"
                                >
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ key + 1 }}
                                    </span>
                                </td>

                                <td 
                                    class="text-left border-r"
                                    rowspan="2"
                                >
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ item.account }}
                                    </span>
                                </td>

                                <td 
                                    class="text-left border-r"
                                    rowspan="2"
                                >
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ item.currency }}
                                    </span>
                                </td>

                                <td class="text-left border-r">
                                    <span class="whitespace-no-wrap font-semibold">
                                        Single Bet
                                    </span>
                                </td>

                                <td class="text-left border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-primary': item.singleBet && item.singleBet.betAmount.value > 0,
                                            'text-danger': item.singleBet && item.singleBet.betAmount.value < 0
                                        }"
                                    >
                                        {{ item.singleBet && item.singleBet.betAmount.text }}   
                                    </span>
                                </td>

                                <td class="text-left border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-primary': item.singleBet && item.singleBet.validAmount.value > 0,
                                            'text-danger': item.singleBet && item.singleBet.validAmount.value < 0
                                        }"
                                    > 
                                        {{ item.singleBet && item.singleBet.validAmount.text }}       
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-primary': item.singleBet && item.singleBet.memberProfit.winLose.value > 0,
                                            'text-danger': item.singleBet && item.singleBet.memberProfit.winLose.value < 0
                                        }"
                                    >
                                        {{ item.singleBet && item.singleBet.memberProfit.winLose.text }}   
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-primary': item.singleBet && item.singleBet.memberProfit.com.value > 0,
                                            'text-danger': item.singleBet && item.singleBet.memberProfit.com.value < 0
                                        }"
                                    >
                                        {{ item.singleBet && item.singleBet.memberProfit.com.text }}       
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-primary': item.singleBet && item.singleBet.memberProfit.winLoseCom.value > 0,
                                            'text-danger': item.singleBet && item.singleBet.memberProfit.winLoseCom.value < 0
                                        }"
                                    >  
                                        {{ item.singleBet && item.singleBet.memberProfit.winLoseCom.text }}     
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-primary': item.singleBet && item.singleBet.currentProfit.winLose.value > 0,
                                            'text-danger': item.singleBet && item.singleBet.currentProfit.winLose.value < 0
                                        }"
                                    > 
                                        {{ item.singleBet && item.singleBet.currentProfit.winLose.text }}     
                                    </span> 
                                </td>

                                <td class="text-right border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-primary': item.singleBet && item.singleBet.currentProfit.com.value > 0,
                                            'text-danger': item.singleBet && item.singleBet.currentProfit.com.value < 0
                                        }"
                                    >
                                        {{ item.singleBet && item.singleBet.currentProfit.com.text }}   
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span  
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-primary': item.singleBet && item.singleBet.currentProfit.winLoseCom.value > 0,
                                            'text-danger': item.singleBet && item.singleBet.currentProfit.winLoseCom.value < 0
                                        }"
                                    >
                                        {{ item.singleBet && item.singleBet.currentProfit.winLoseCom.text }}     
                                    </span>
                                </td>

                                <template v-if="isNotCompanyType">
                                    <td class="text-right border-r">
                                        <span 
                                            class="whitespace-no-wrap font-semibold"
                                            :class="{
                                                'text-primary': item.singleBet && item.singleBet.uplineProfit.winLose.value > 0,
                                                'text-danger': item.singleBet && item.singleBet.uplineProfit.winLose.value < 0
                                            }"
                                        >  
                                            {{ item.singleBet && item.singleBet.uplineProfit.winLose.text }}  
                                        </span>
                                    </td>

                                    <td class="text-right border-r">
                                        <div class="text-right">
                                            <span 
                                                class="whitespace-no-wrap font-semibold"
                                                :class="{
                                                    'text-primary': item.singleBet && item.singleBet.uplineProfit.com.value > 0,
                                                    'text-danger': item.singleBet && item.singleBet.uplineProfit.com.value < 0
                                                }"
                                            >  
                                                {{ item.singleBet && item.singleBet.uplineProfit.com.text }}   
                                            </span>
                                        </div>
                                    </td>

                                    <td class="text-right border-r">
                                        <span 
                                            class="whitespace-no-wrap font-semibold"
                                            :class="{
                                                'text-primary': item.singleBet && item.singleBet.uplineProfit.winLoseCom.value > 0,
                                                'text-danger': item.singleBet && item.singleBet.uplineProfit.winLoseCom.value < 0
                                            }"
                                        >  
                                            {{ item.singleBet && item.singleBet.uplineProfit.winLoseCom.text }} 
                                        </span>
                                    </td>
                                </template>

                                <td 
                                    v-if="isNotAgentType"
                                    class="td-fit text-center pr-6 align-middle"
                                    rowspan="2"
                                >
                                    <div class="inline-flex items-center">
                                        <link-icon
                                            :to="{
                                                name: 'report-win-lose',
                                                query: {
                                                    to,
                                                    date,
                                                    from,
                                                    userId: item.accountId
                                                },
                                            }"
                                            :tip="__('View')"
                                        />
                                    </div>
                                </td>

                                <template
                                    v-else
                                >
                                    <td class="td-fit text-center pr-6 align-middle">
                                        <div class="inline-flex items-center">
                                            <link-icon
                                                :to="{
                                                    name: 'report-af88-win-lose-single-bet-detail',
                                                    params: {
                                                        name: item.account,
                                                    },
                                                    query: {
                                                        to,
                                                        date,
                                                        from,
                                                    },
                                                }"
                                                :tip="__('View')"
                                            />
                                        </div>
                                    </td>

                                    <td
                                        class="td-fit text-center pr-6 align-middle border-l"
                                        rowspan="2"
                                    >
                                        <div class="inline-flex items-center">
                                            <link-icon
                                                :to="{
                                                    name: 'report-af88-win-lose-balance-statement',
                                                    params: {
                                                        name: item.account,
                                                    },
                                                    query: {
                                                        to,
                                                        date,
                                                        from
                                                    },
                                                }"
                                                :icon="{
                                                    type: 'balance',
                                                    width: 22,
                                                    height: 18,
                                                    viewBox: '0 0 640 512'
                                                }"
                                                :tip="__('View Balance Statement')"
                                            />
                                        </div>
                                    </td>
                                </template>
                            </tr>

                            <tr :key="key">
                                <td 
                                    class="text-left border-r"
                                >
                                    <span class="whitespace-no-wrap font-semibold">
                                        Parlay Bet
                                    </span>
                                </td>

                                <td class="text-left border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-primary': item.mixParlayBet && item.mixParlayBet.betAmount.value > 0,
                                            'text-danger': item.mixParlayBet && item.mixParlayBet.betAmount.value < 0
                                        }"
                                    >
                                        {{ item.mixParlayBet && item.mixParlayBet.betAmount.text }} 
                                    </span>
                                </td>

                                <td class="text-left border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-primary': item.mixParlayBet && item.mixParlayBet.validAmount.value > 0,
                                            'text-danger': item.mixParlayBet && item.mixParlayBet.validAmount.value < 0
                                        }"
                                    > 
                                        {{ item.mixParlayBet && item.mixParlayBet.validAmount.text }}       
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-primary': item.mixParlayBet && item.mixParlayBet.memberProfit.winLose.value > 0,
                                            'text-danger': item.mixParlayBet && item.mixParlayBet.memberProfit.winLose.value < 0
                                        }"
                                    >
                                        {{ item.mixParlayBet && item.mixParlayBet.memberProfit.winLose.text }}     
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-primary': item.mixParlayBet && item.mixParlayBet.memberProfit.com.value > 0,
                                            'text-danger': item.mixParlayBet && item.mixParlayBet.memberProfit.com.value < 0
                                        }"
                                    >
                                        {{ item.mixParlayBet && item.mixParlayBet.memberProfit.com.text }}    
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-primary': item.mixParlayBet && item.mixParlayBet.memberProfit.winLoseCom.value > 0,
                                            'text-danger': item.mixParlayBet && item.mixParlayBet.memberProfit.winLoseCom.value < 0
                                        }"
                                    >  
                                        {{ item.mixParlayBet && item.mixParlayBet.memberProfit.winLoseCom.text }}   
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-primary': item.mixParlayBet && item.mixParlayBet.currentProfit.winLose.value > 0,
                                            'text-danger': item.mixParlayBet && item.mixParlayBet.currentProfit.winLose.value < 0
                                        }"
                                    > 
                                        {{ item.mixParlayBet && item.mixParlayBet.currentProfit.winLose.text }}     
                                    </span> 
                                </td>

                                <td class="text-right border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-primary': item.mixParlayBet && item.mixParlayBet.currentProfit.com.value > 0,
                                            'text-danger': item.mixParlayBet && item.mixParlayBet.currentProfit.com.value < 0
                                        }"
                                    >
                                        {{ item.mixParlayBet && item.mixParlayBet.currentProfit.com.text }}  
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span  
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-primary': item.mixParlayBet && item.mixParlayBet.currentProfit.winLoseCom.value > 0,
                                            'text-danger': item.mixParlayBet && item.mixParlayBet.currentProfit.winLoseCom.value < 0
                                        }"
                                    >
                                        {{ item.mixParlayBet && item.mixParlayBet.currentProfit.winLoseCom.text }}     
                                    </span>
                                </td>

                                <template v-if="isNotCompanyType">
                                    <td class="text-right border-r">
                                        <span 
                                            class="whitespace-no-wrap font-semibold"
                                            :class="{
                                                'text-primary': item.mixParlayBet && item.mixParlayBet.uplineProfit.winLose.value > 0,
                                                'text-danger': item.mixParlayBet && item.mixParlayBet.uplineProfit.winLose.value < 0
                                            }"
                                        >  
                                            {{ item.mixParlayBet && item.mixParlayBet.uplineProfit.winLose.text }} 
                                        </span>
                                    </td>

                                    <td class="text-right border-r">
                                        <div class="text-right">
                                            <span 
                                                class="whitespace-no-wrap font-semibold"
                                                :class="{
                                                    'text-primary': item.mixParlayBet && item.mixParlayBet.uplineProfit.com.value > 0,
                                                    'text-danger': item.mixParlayBet && item.mixParlayBet.uplineProfit.com.value < 0
                                                }"
                                            >  
                                                {{ item.mixParlayBet && item.mixParlayBet.uplineProfit.com.text }}  
                                            </span>
                                        </div>
                                    </td>

                                    <td class="text-right border-r">
                                        <span 
                                            class="whitespace-no-wrap font-semibold"
                                            :class="{
                                                'text-primary': item.mixParlayBet && item.mixParlayBet.uplineProfit.winLoseCom.value > 0,
                                                'text-danger': item.mixParlayBet && item.mixParlayBet.uplineProfit.winLoseCom.value < 0
                                            }"
                                        >  
                                            {{ item.mixParlayBet && item.mixParlayBet.uplineProfit.winLoseCom.text }}  
                                        </span>
                                    </td>
                                </template>

                                <td 
                                    v-if="isAgentType"
                                    class="td-fit text-center pr-6 align-middle"
                                >
                                    <div class="inline-flex items-center">
                                        <link-icon
                                            :to="{
                                                name: 'report-af88-win-lose-mix-parlay-bet-detail',
                                                params: {
                                                    name: item.account,
                                                },
                                                query: {
                                                    to,
                                                    date,
                                                    from,
                                                },
                                            }"
                                            :tip="__('View')"
                                        />
                                    </div>
                                </td>
                            </tr>
                        </template>

                        <tr dusk="total-row">
                            <td></td>

                            <td></td>

                            <td></td>

                            <td class="text-left border-r">
                                <span class="whitespace-no-wrap font-bold">
                                    Total :
                                </span>
                            </td>

                            <td class="text-left border-r">
                                <span
                                    class="whitespace-no-wrap font-bold"
                                    :class="{
                                        'text-primary': total.betAmount.value > 0,
                                        'text-danger': total.betAmount.value < 0
                                    }"
                                >
                                    {{ total.betAmount.text }}
                                </span>
                            </td>

                            <td class="text-left border-r">
                                <span
                                    class="whitespace-no-wrap font-bold"
                                    :class="{
                                        'text-primary': total.validAmount.value > 0,
                                        'text-danger': total.validAmount.value < 0
                                    }"
                                >
                                    {{ total.validAmount.text }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span 
                                    class="whitespace-no-wrap font-bold"
                                    :class="{
                                        'text-primary': total.memberProfit.winLose.value > 0,
                                        'text-danger': total.memberProfit.winLose.value < 0
                                    }"
                                >
                                    {{ total.memberProfit.winLose.text }}
                                </span> 
                            </td>

                            <td class="text-right border-r">
                                <span
                                    class="whitespace-no-wrap font-bold"
                                    :class="{
                                        'text-primary': total.memberProfit.com.value > 0,
                                        'text-danger': total.memberProfit.com.value < 0
                                    }"
                                >
                                    {{ total.memberProfit.com.text }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span
                                    class="whitespace-no-wrap font-bold"
                                    :class="{
                                        'text-primary': total.memberProfit.winLoseCom.value > 0,
                                        'text-danger': total.memberProfit.winLoseCom.value < 0
                                    }"
                                >
                                    {{ total.memberProfit.winLoseCom.text }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span 
                                    class="whitespace-no-wrap font-bold"
                                    :class="{
                                        'text-primary': total.currentProfit.winLose.value > 0,
                                        'text-danger': total.currentProfit.winLose.value < 0
                                    }"
                                >
                                    {{ total.currentProfit.winLose.text }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span
                                    class="whitespace-no-wrap font-bold"
                                    :class="{
                                        'text-primary': total.currentProfit.com.value > 0,
                                        'text-danger': total.currentProfit.com.value < 0
                                    }"
                                >
                                    {{ total.currentProfit.com.text }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span 
                                    class="whitespace-no-wrap font-bold"
                                    :class="{
                                        'text-primary': total.currentProfit.winLoseCom.value > 0,
                                        'text-danger': total.currentProfit.winLoseCom.value < 0
                                    }"
                                >
                                    {{ total.currentProfit.winLoseCom.text }}
                                </span>
                            </td>

                            <template v-if="isNotCompanyType">
                                <td class="text-right border-r">
                                    <span
                                        class="whitespace-no-wrap font-bold"
                                        :class="{
                                            'text-primary': total.uplineProfit.winLose.value > 0,
                                            'text-danger': total.uplineProfit.winLose.value < 0
                                        }"
                                    >
                                        {{ total.uplineProfit.winLose.text }}
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span
                                        class="whitespace-no-wrap font-bold"
                                        :class="{
                                            'text-primary': total.uplineProfit.com.value > 0,
                                            'text-danger': total.uplineProfit.com.value < 0
                                        }"
                                    >
                                        {{ total.uplineProfit.com.text }}
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span 
                                        class="whitespace-no-wrap font-bold"
                                        :class="{
                                            'text-primary': total.uplineProfit.winLoseCom.value > 0,
                                            'text-danger': total.uplineProfit.winLoseCom.value < 0
                                        }"
                                    >
                                        {{ total.uplineProfit.winLoseCom.text }}
                                    </span>
                                </td>
                            </template>

                            <td></td>
                        </tr>
                    </template>
                </table-report>
            </loading-view>
        </card>
    </div>
</template>
