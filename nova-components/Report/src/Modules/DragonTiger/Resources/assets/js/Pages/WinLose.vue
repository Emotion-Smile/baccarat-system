<script>
import HandlesRequest from '@/mixins/HandlesRequest';

export default {
    metaInfo() {
        return {
            title: 'D&T Win/Lose',
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
            previousUserId: 0
        };
    },

    methods: {
        setData({ items, total, userType, previousUserId }) {
            this.items = items;
            this.total = total;
            this.userType = userType;
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
            this.updateQueryString({
                ...value,
                date: null,
            });
        },
    },

    computed: {
        requestUrl() {
            return `/nova-vendor/report/dragon-tiger/win-lose`;
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
                label: '&nbsp;',
                rowspan: 2,
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
                    name: 'report-dragon-tiger-win-lose',
                    query: {
                        date,
                        userId: previousUserId
                    }
                }"
            />

            <heading
                :level="1"
                v-html="`D&T Win/Lose`"
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
                        <tr 
                            v-for="(item, key) in items"
                            :key="key"
                        >
                            <td class="text-left border-r">
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ key + 1 }}
                                </span>
                            </td>

                            <td class="text-left border-r">
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.account }}
                                </span>
                            </td>

                            <td class="text-left border-r">
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.currency }}
                                </span>
                            </td>

                            <td class="text-left border-r">
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.site }}
                                </span>
                            </td>

                            <td class="text-left border-r">
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.gameType }}
                                </span>
                            </td>

                            <td class="text-left border-r">
                                <span class="whitespace-no-wrap font-semibold text-primary">
                                    {{ item.betAmount.text }}
                                </span>
                            </td>

                            <td class="text-left border-r">
                                <span class="whitespace-no-wrap font-semibold text-primary">
                                    {{ item.validAmount.text }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span 
                                    class="whitespace-no-wrap font-semibold"
                                    :class="{
                                        'text-danger': item.memberProfit.winLose.value < 0,
                                        'text-primary': item.memberProfit.winLose.value > 0
                                    }"
                                >
                                    {{ item.memberProfit.winLose.text }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span 
                                    class="whitespace-no-wrap font-semibold"
                                    :class="{
                                        'text-danger': item.memberProfit.com.value < 0,
                                        'text-primary': item.memberProfit.com.value > 0
                                    }"
                                >
                                    {{ item.memberProfit.com.text }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span 
                                    class="whitespace-no-wrap font-semibold"
                                    :class="{
                                        'text-danger': item.memberProfit.winLoseCom.value < 0,
                                        'text-primary': item.memberProfit.winLoseCom.value > 0
                                    }"
                                >
                                    {{ item.memberProfit.winLoseCom.text }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span 
                                    class="whitespace-no-wrap font-semibold"
                                    :class="{
                                        'text-danger': item.currentProfit.winLose.value < 0,
                                        'text-primary': item.currentProfit.winLose.value > 0
                                    }"
                                >
                                    {{ item.currentProfit.winLose.text }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span 
                                    class="whitespace-no-wrap font-semibold"
                                    :class="{
                                        'text-danger': item.currentProfit.com.value < 0,
                                        'text-primary': item.currentProfit.com.value > 0
                                    }"
                                >
                                    {{ item.currentProfit.com.text }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span 
                                    class="whitespace-no-wrap font-semibold"
                                    :class="{
                                        'text-danger': item.currentProfit.winLoseCom.value < 0,
                                        'text-primary': item.currentProfit.winLoseCom.value > 0
                                    }"
                                >
                                    {{ item.currentProfit.winLoseCom.text }}
                                </span>
                            </td>

                            <template v-if="notOnCompanyType">
                                <td class="text-right border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-danger': item.upLineProfit.winLose.value < 0,
                                            'text-primary': item.upLineProfit.winLose.value > 0
                                        }"
                                    >
                                        {{ item.upLineProfit.winLose.text }}
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-danger': item.upLineProfit.com.value < 0,
                                            'text-primary': item.upLineProfit.com.value > 0
                                        }"
                                    >
                                        {{ item.upLineProfit.com.text }}
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-danger': item.upLineProfit.winLoseCom.value < 0,
                                            'text-primary': item.upLineProfit.winLoseCom.value > 0
                                        }"
                                    >
                                        {{ item.upLineProfit.winLoseCom.text }}
                                    </span>
                                </td>
                            </template>

                            <td class="text-center border-r">
                                <span class="whitespace-no-wrap font-semibold">
                                    <template v-if="onAgentType">
                                        <link-icon
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
                                                    date,
                                                    from,
                                                    to,
                                                    previousRouteName: 'report-dragon-tiger-win-lose'
                                                },
                                            }"
                                        />

                                        <link-icon
                                            tip="View Bet Detail"
                                            :icon="{
                                                type: 'book',
                                                width: 22,
                                                height: 18,
                                                viewBox: '0 0 576 512'
                                            }"
                                            :to="{
                                                name: 'report-dragon-tiger-bet-detail',
                                                params: {
                                                    memberId: item.accountId,
                                                },
                                                query: {
                                                    date,
                                                    from,
                                                    to,
                                                },
                                            }"
                                        />
                                    </template>

                                    <link-icon
                                        v-else
                                        :to="{
                                            name: 'report-dragon-tiger-win-lose',
                                            query: {
                                                date,
                                                from,
                                                to,
                                                userId: item.accountId
                                            },
                                        }"
                                        :tip="__('View As This User')"
                                    />
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td></td>

                            <td></td>

                            <td></td>

                            <td></td>

                            <td class="text-left border-r">
                                <span class="whitespace-no-wrap font-bold">
                                    Total :
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
                        </tr>
                    </template> 
                </table-report>      
            </loading-view>
        </card>
    </div>
</template>

