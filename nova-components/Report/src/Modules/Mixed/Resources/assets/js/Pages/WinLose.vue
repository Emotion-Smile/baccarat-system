<script>
import HandlesRequest from '@/mixins/HandlesRequest';

export default {
    metaInfo() {
        return {
            title: 'Win/Lose Detail (Mixed All Report)',
        };
    },

    mixins: [
        HandlesRequest
    ],

    data() {
        return {
            previousUserId: 0,
            userType: '',
            items: [],
            total: {}
        };
    },

    methods: {
        setData({ items, total, userType, previousUserId }) {
            this.userType = userType;
            this.items = items;
            this.total = total;
            this.previousUserId = previousUserId;
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
            this.date = null;
            this.updateQueryString({
                ...value,
                date: null,
            });
        },
    },

    computed: {
        requestUrl() {
            return `/nova-vendor/report/mixed/win-lose`;
        },

        requestQueryString() {
            let param = {
                userId: this.userId,
                date: this.date,
                from: this.from,
                to: this.to,
            };

            if (this.from === undefined || this.from === null) {
                delete param.from;
                delete param.to;
            } else {
                delete param.date;
                this.date = null;
            }

            return param;
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

        userId() {
            return this.$route.query['userId'] || null;
        },

        onCompanyType() {
            return this.userType.value === 'company';
        },

        notOnCompanyType() {
            return !this.onCompanyType;
        },

        onAgentType() {
            return this.userType.value === 'agent';
        },

        headerColumns() {
            const columns = [
                {
                    label: 'no',
                    rowspan: 2,
                    textAlign: 'left'
                },
                {
                    label: 'account',
                    rowspan: 2,
                    textAlign: 'left'
                },
                {
                    label: 'contact',
                    rowspan: 2,
                    textAlign: 'left'
                },
                {
                    label: 'currency',
                    rowspan: 2,
                    textAlign: 'left'
                },
                {
                    label: 'site',
                    rowspan: 2,
                    textAlign: 'left'
                },
                {
                    label: 'game type',
                    rowspan: 2,
                    textAlign: 'left'
                },
                {
                    label: 'bet amount',
                    rowspan: 2,
                    textAlign: 'right'
                },
                {
                    label: 'valid amount',
                    rowspan: 2,
                    textAlign: 'right'
                },
                {
                    label: 'members',
                    colspan: 3,
                    textAlign: 'center'
                },
                {
                    label: `${this.userType.text} profit`,
                    colspan: 3,
                    textAlign: 'center'
                },
            ];

            if(this.notOnCompanyType) {
                columns.push({
                    label: 'upline profit',
                    colspan: 3,
                    textAlign: 'center'
                });
            }

            columns.push({
                label: 'sub total',
                rowspan: 2,
                textAlign: 'center'
            });

            columns.push({
                label: '&nbsp;',
                rowspan: 2,
                colspan: this.onAgentType ? 2 : 0,
                textAlign: 'center'
            });
        
            return columns;
        },

        profitColumnLength() {
            return this.onCompanyType ? 2 : 3;
        },

        hasRecord() {
            return this.items.length > 0;
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
                    name: 'report-mixed-win-lose',
                    query: {
                        date: date,
                        from: from,
                        to: to,
                        userId: previousUserId
                    }
                }"
            />

            <heading
                :level="1"
                v-html="`Win/Lose Detail (Mixed All Report)`"
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
            >
                 <table-report
                    :hasRecord="hasRecord"
                >
                    <template v-slot:header>
                        <tr>
                            <th 
                                v-for="(headerColumn, index) in headerColumns"
                                :key="index"
                                :rowspan="headerColumn.rowspan"
                                :colspan="headerColumn.colspan" 
                                :class="{
                                    'border-b': headerColumn.colspan,
                                    [`text-${headerColumn.textAlign}`]: true
                                }"
                                class="border-r"
                            >
                                <span v-html="headerColumn.label"></span>
                            </th>
                        </tr>  

                        <tr>
                            <template 
                                v-for="key in profitColumnLength"
                            >
                                <th
                                    :key="key"
                                    class="text-right border-r"
                                >
                                    <span>W/L</span>
                                </th>

                                <th 
                                    :key="key"
                                    class="text-right border-r"
                                >
                                    <span>Com</span>
                                </th>

                                <th 
                                    :key="key"
                                    class="text-right border-r"
                                >
                                    <span>W/L + Com</span>
                                </th>
                            </template>
                        </tr> 
                    </template> 

                    <template v-slot:body>
                        <template v-for="(item, itemKey) in items">
                            <tr 
                                :key="itemKey"
                                :class="{ 'border-b-3 border-60': itemKey > 0 }"
                            >
                                <td
                                    :rowspan="item.games.length + 1" 
                                    class="text-left border-r"
                                >
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ itemKey + 1 }}
                                    </span>
                                </td>

                                <td 
                                    :rowspan="item.games.length + 1"
                                    class="text-left border-r"
                                >
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ item.account }}
                                    </span>
                                </td>

                                <td 
                                    :rowspan="item.games.length + 1"
                                    class="text-left border-r"
                                >
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ item.contact }}
                                    </span>
                                </td>

                                <td 
                                    :rowspan="item.games.length + 1"
                                    class="text-left border-r"
                                >
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ item.currency }}
                                    </span>
                                </td>
                            </tr>

                            <tr 
                                v-for="(game, gameKey) in item.games" 
                                :key="gameKey"
                                :class="{ 
                                    'bg-30': gameKey % 2 !== 0, 
                                    'border-b-3 border-60': true 
                                }"
                                
                            >
                                <td class="text-left border-r">
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ game.site }}
                                    </span>
                                </td>

                                <td class="text-left border-r">
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ game.type }}
                                    </span>
                                </td>

                                <td class="text-left border-r">
                                    <span class="whitespace-no-wrap font-semibold text-primary">
                                        {{ game.betAmount.text }}
                                    </span>
                                </td>

                                <td class="text-left border-r">
                                    <span class="whitespace-no-wrap font-semibold text-primary">
                                        {{ game.validAmount.text }}
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-danger': game.memberProfit.winLose.value < 0,
                                            'text-primary': game.memberProfit.winLose.value > 0
                                        }"
                                    >
                                        {{ game.memberProfit.winLose.text }}
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-danger': game.memberProfit.com.value < 0,
                                            'text-primary': game.memberProfit.com.value > 0
                                        }"
                                    >
                                        {{ game.memberProfit.com.text }}
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-danger': game.memberProfit.winLoseCom.value < 0,
                                            'text-primary': game.memberProfit.winLoseCom.value > 0
                                        }"
                                    >
                                        {{ game.memberProfit.winLoseCom.text }}
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-danger': game.currentProfit.winLose.value < 0,
                                            'text-primary': game.currentProfit.winLose.value > 0
                                        }"
                                    >
                                        {{ game.currentProfit.winLose.text }}
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-danger': game.currentProfit.com.value < 0,
                                            'text-primary': game.currentProfit.com.value > 0
                                        }"
                                    >
                                        {{ game.currentProfit.com.text }}
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-danger': game.currentProfit.winLoseCom.value < 0,
                                            'text-primary': game.currentProfit.winLoseCom.value > 0
                                        }"
                                    >
                                        {{ game.currentProfit.winLoseCom.text }}
                                    </span>
                                </td>
                                
                                <template v-if="notOnCompanyType">
                                    <td class="text-right border-r">
                                        <span 
                                            class="whitespace-no-wrap font-semibold"
                                            :class="{
                                                'text-danger': game.upLineProfit.winLose.value < 0,
                                                'text-primary': game.upLineProfit.winLose.value > 0
                                            }"
                                        >
                                            {{ game.upLineProfit.winLose.text }}
                                        </span>
                                    </td>

                                    <td class="text-right border-r">
                                        <span 
                                            class="whitespace-no-wrap font-semibold"
                                            :class="{
                                                'text-danger': game.upLineProfit.com.value < 0,
                                                'text-primary': game.upLineProfit.com.value > 0
                                            }"
                                        >
                                            {{ game.upLineProfit.com.text }}
                                        </span>
                                    </td>

                                    <td class="text-right border-r">
                                        <span 
                                            class="whitespace-no-wrap font-semibold"
                                            :class="{
                                                'text-danger': game.upLineProfit.winLoseCom.value < 0,
                                                'text-primary': game.upLineProfit.winLoseCom.value > 0
                                            }"
                                        >
                                            {{ game.upLineProfit.winLoseCom.text }}
                                        </span>
                                    </td>         
                                </template>

                                <td 
                                    v-if="gameKey === 0"
                                    class="text-right border-r"
                                    :rowspan="item.games.length"
                                >
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-danger': item.subTotal.value < 0,
                                            'text-primary': item.subTotal.value > 0
                                        }"
                                    >
                                        {{ item.subTotal.text }}
                                    </span>
                                </td>
                                
                                <td
                                    v-if="onAgentType"
                                    class="text-center border-r"
                                >
                                    <span class="whitespace-no-wrap font-semibold">
                                        <link-icon
                                            tip="View Bet Detail"
                                            :icon="{
                                                type: 'book',
                                                width: 22,
                                                height: 18,
                                                viewBox: '0 0 576 512'
                                            }"
                                            :to="{
                                                name: game.routeName,
                                                params: {
                                                    [game.routeName === 'report-t88-win-lose-bet-detail' ? 'name' : 'memberId']: game.routeName === 'report-t88-win-lose-bet-detail' ? item.account : item.accountId,
                                                },
                                                query: {
                                                    date: date,
                                                    from: from,
                                                    to: to,
                                                    previousRouteName: 'report-mixed-win-lose'
                                                },
                                            }"
                                        />
                                    </span>
                                </td>

                                <td 
                                    v-if="gameKey === 0"
                                    :rowspan="item.games.length"
                                    class="text-center border-r"
                                >
                                    <span class="whitespace-no-wrap font-semibold">
                                        <link-icon
                                            v-if="onAgentType"
                                            tip="View Balance Statement"
                                            :icon="{
                                                type: 'balance',
                                                width: 22,
                                                height: 18,
                                                viewBox: '0 0 640 512'
                                            }"
                                            :to="{
                                                name: 'report-core-balance-statement',
                                                params: {
                                                    memberId: item.accountId,
                                                },
                                                query: {
                                                    date: date,
                                                    from: from,
                                                    to: to,
                                                    previousRouteName: 'report-mixed-win-lose'
                                                },
                                            }"
                                        />

                                        <link-icon
                                            v-else
                                            :to="{
                                                name: 'report-mixed-win-lose',
                                                query: {
                                                    userId: item.accountId,
                                                    date: date,
                                                    from: from,
                                                    to: to
                                                },
                                            }"
                                            :tip="__('View As This User')"
                                        />
                                    </span>
                                </td>
                            </tr>
                        </template>

                        <tr>
                            <td></td>

                            <td></td>

                            <td></td>

                            <td></td>

                            <td></td>

                            <td class="text-left border-r">
                                <span class="whitespace-no-wrap font-bold">
                                    Grand Total :
                                </span>
                            </td>

                            <td class="text-left border-r">
                                <span class="whitespace-no-wrap font-bold text-primary">
                                    {{ total.betAmount.text }}
                                </span>
                            </td>

                            <td class="text-left border-r">
                                <span class="whitespace-no-wrap font-bold text-primary">
                                    {{ total.validAmount.text }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span 
                                    class="whitespace-no-wrap font-bold"
                                    :class="{
                                        'text-danger': total.memberProfit.winLose.value < 0,
                                        'text-primary': total.memberProfit.winLose.value > 0
                                    }"
                                >
                                    {{ total.memberProfit.winLose.text }}
                                </span> 
                            </td>

                            <td class="text-right border-r">
                                <span
                                    class="whitespace-no-wrap font-bold"
                                    :class="{
                                        'text-danger': total.memberProfit.com.value < 0,
                                        'text-primary': total.memberProfit.com.value > 0
                                    }"
                                >
                                    {{ total.memberProfit.com.text }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span
                                    class="whitespace-no-wrap font-bold"
                                    :class="{
                                        'text-danger': total.memberProfit.winLoseCom.value < 0,
                                        'text-primary': total.memberProfit.winLoseCom.value > 0
                                    }"
                                >
                                    {{ total.memberProfit.winLoseCom.text }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span 
                                    class="whitespace-no-wrap font-bold"
                                    :class="{
                                        'text-danger': total.currentProfit.winLose.value < 0,
                                        'text-primary': total.currentProfit.winLose.value > 0
                                    }"
                                >
                                    {{ total.currentProfit.winLose.text }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span
                                    class="whitespace-no-wrap font-bold"
                                    :class="{
                                        'text-danger': total.currentProfit.com.value < 0,
                                        'text-primary': total.currentProfit.com.value > 0
                                    }"
                                >
                                    {{ total.currentProfit.com.text }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span 
                                    class="whitespace-no-wrap font-bold"
                                    :class="{
                                        'text-danger': total.currentProfit.winLoseCom.value < 0,
                                        'text-primary': total.currentProfit.winLoseCom.value > 0
                                    }"
                                >
                                    {{ total.currentProfit.winLoseCom.text }}
                                </span>
                            </td>

                            <template v-if="notOnCompanyType">
                                <td class="text-right border-r">
                                    <span
                                        class="whitespace-no-wrap font-bold"
                                        :class="{
                                            'text-danger': total.upLineProfit.winLose.value < 0,
                                            'text-primary': total.upLineProfit.winLose.value > 0
                                        }"
                                    >
                                        {{ total.upLineProfit.winLose.text }}
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span
                                        class="whitespace-no-wrap font-bold"
                                        :class="{
                                            'text-danger': total.upLineProfit.com.value < 0,
                                            'text-primary': total.upLineProfit.com.value > 0
                                        }"
                                    >
                                        {{ total.upLineProfit.com.text }}
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span 
                                        class="whitespace-no-wrap font-bold"
                                        :class="{
                                            'text-danger': total.upLineProfit.winLoseCom.value < 0,
                                            'text-primary': total.upLineProfit.winLoseCom.value > 0
                                        }"
                                    >
                                        {{ total.upLineProfit.winLoseCom.text }}
                                    </span>
                                </td>
                            </template>

                            <td></td>

                            <td></td>
                        </tr>
                    </template> 
                </table-report>      
            </loading-view>
        </card>
    </div>
</template>

<style scoped>
    .border-b-3 {
        border-bottom-width: 3px;
    }
</style>

