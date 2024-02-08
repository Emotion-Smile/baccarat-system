<script>
import HandlesRequest from '@/mixins/HandlesRequest';

export default {
    metaInfo() {
        return {
            title: 'Yuki Win/Lose',
        };
    },

    mixins: [
        HandlesRequest
    ],

    data() {
        return {
            report: {},
            reportItems: [],
            currentUser: {},
            previousUser: ''
        };
    },

    methods: {
        setData(data) {
            this.report = data.report;
            this.reportItems = this.report.data;
            this.currentUser = data.currentUser;
            this.previousUser = data.previousUser;
        },

        watchParamsForRequest() {
            return (
                this.date +
                this.name + 
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
            return `/nova-vendor/report/t88/win-lose`;
        },

        requestQueryString() {
            let param = {
                date: this.date,
                name: this.name,
                from: this.from,
                to: this.to,
            }

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

        name() {
            return this.$route.query['name'];
        },

        currentUserType() {
            return this.currentUser.type.replace('_', ' ');
        },

        isCompanyType() {
            return this.currentUser.type === 'company';
        },

        isNotCompanyType() {
            return ! this.isCompanyType;
        },
    }
}
</script>

<template>
    <div class="relative">
        <div class="flex item-center mb-3">
            <previous-button
                v-if="name"
                :to="{
                    name: 'report-t88-win-lose',
                    query: {
                        date,
                        name: previousUser ? previousUser.name : null
                    }
                }"
            />

            <heading
                :level="1"
                v-html="`Yuki Win/Lose`"
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
                    :hasRecord="reportItems.length > 0"
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
                                <span>Site</span>
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
                                <span>{{ `${currentUserType} Profit` }}</span>
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
                        <tr 
                            v-for="(item, key) in reportItems"
                            :key="key"
                        >
                            <td 
                                class="text-left border-r"
                            >
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ key + 1 }}
                                </span>
                            </td>

                            <td 
                                class="text-left border-r"
                            >
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.account_name }}
                                </span>
                            </td>

                            <td 
                                class="text-left border-r"
                            >
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.user_currency }}
                                </span>
                            </td>

                            <td 
                                class="text-left border-r"
                            >
                                <span class="whitespace-no-wrap font-semibold">
                                    T88
                                </span>
                            </td>

                            <td 
                                class="text-left border-r"
                            >
                                <span class="whitespace-no-wrap font-semibold">
                                    Yuki
                                </span>
                            </td>

                            <td 
                                class="text-left border-r"
                            >
                                <span 
                                    class="whitespace-no-wrap font-semibold text-primary"
                                >
                                    {{ item.bet_amount }}
                                </span>
                            </td>

                            <td 
                                class="text-left border-r"
                            >
                                <span 
                                    class="whitespace-no-wrap font-semibold text-primary"
                                >
                                    {{ item.valid_amount }}
                                </span>
                            </td>

                            <td 
                                class="text-right border-r"
                            >
                                <span 
                                    class="whitespace-no-wrap font-semibold"
                                    :class="{
                                        'text-danger': item.down_line_win_lose_value < 0,
                                        'text-primary': item.down_line_win_lose_value > 0
                                    }"
                                >
                                    {{ item.down_line_win_lose }}
                                </span>
                            </td>

                            <td 
                                class="text-right border-r"
                            >
                                <span 
                                    class="whitespace-no-wrap font-semibold"
                                    :class="{
                                        'text-danger': item.down_line_commission_value < 0,
                                        'text-primary': item.down_line_commission_value > 0
                                    }"
                                >
                                    {{ item.down_line_commission }}
                                </span>
                            </td>

                            <td 
                                class="text-right border-r"
                            >
                                <span 
                                    class="whitespace-no-wrap font-semibold"
                                    :class="{
                                        'text-danger': item.down_line_win_lose_commission_value < 0,
                                        'text-primary': item.down_line_win_lose_commission_value > 0
                                    }"
                                >
                                    {{ item.down_line_win_lose_commission }}
                                </span>
                            </td>

                            <td 
                                class="text-right border-r"
                            >
                                <span 
                                    class="whitespace-no-wrap font-semibold"
                                    :class="{
                                        'text-danger': item.mid_line_win_lose_value < 0,
                                        'text-primary': item.mid_line_win_lose_value > 0
                                    }"
                                >
                                    {{ item.mid_line_win_lose }}
                                </span>
                            </td>

                            <td 
                                class="text-right border-r"
                            >
                                <span 
                                    class="whitespace-no-wrap font-semibold"
                                    :class="{
                                        'text-danger': item.mid_line_commission_value < 0,
                                        'text-primary': item.mid_line_commission_value > 0
                                    }"
                                >
                                    {{ item.mid_line_commission }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span 
                                    class="whitespace-no-wrap font-semibold"
                                    :class="{
                                        'text-danger': item.mid_line_win_lose_commission_value < 0,
                                        'text-primary': item.mid_line_win_lose_commission_value > 0
                                    }"
                                >
                                    {{ item.mid_line_win_lose_commission }}
                                </span>
                            </td>

                            <template v-if="isNotCompanyType">
                                <td class="text-right border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-danger': item.up_line_win_lose_value < 0,
                                            'text-primary': item.up_line_win_lose_value > 0
                                        }"
                                    >
                                        {{ item.up_line_win_lose }}
                                    </span>
                                </td>

                                <td 
                                    class="text-right border-r"
                                >
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-danger': item.up_line_commission_value < 0,
                                            'text-primary': item.up_line_commission_value > 0
                                        }"
                                    >
                                        {{ item.up_line_commission }}
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span 
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-danger': item.up_line_win_lose_commission_value < 0,
                                            'text-primary': item.up_line_win_lose_commission_value > 0
                                        }"
                                    >
                                        {{ item.up_line_win_lose_commission }}
                                    </span>
                                </td>
                            </template>

                            <td class="text-center border-r">
                                <span class="whitespace-no-wrap font-semibold">
                                    <template
                                        v-if="currentUser.type === 'agent'"
                                    >
                                        <link-icon
                                            tip="View Balance Statement"
                                            :icon="{
                                                type: 'balance',
                                                width: 22,
                                                height: 18,
                                                viewBox: '0 0 640 512'
                                            }"
                                            :to="{
                                                name: 'report-t88-win-lose-balance-statement',
                                                params: {
                                                    name: item.account_name,
                                                },
                                                query: {
                                                    date,
                                                    from,
                                                    to,
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
                                                name: 'report-t88-win-lose-bet-detail',
                                                params: {
                                                    name: item.account_name,
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
                                            name: 'report-t88-win-lose',
                                            query: {
                                                date,
                                                from,
                                                to,
                                                name: item.account_name
                                            },
                                        }"
                                        :tip="__('View')"
                                    />
                                </span>
                            </td>
                        </tr>
                
                        <tr dusk="total-row">
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
                                    {{ report.bet_amount_total }}
                                </span>
                            </td>

                            <td class="text-left border-r">
                                <span class="whitespace-no-wrap font-bold text-primary">
                                    {{ report.valid_amount_total }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span 
                                    class="whitespace-no-wrap font-bold"
                                    :class="{
                                        'text-danger': report.down_line_win_lose_total_value < 0,
                                        'text-primary': report.down_line_win_lose_total_value > 0
                                    }"
                                >
                                    {{ report.down_line_win_lose_total }}
                                </span> 
                            </td>

                            <td class="text-right border-r">
                                <span
                                    class="whitespace-no-wrap font-bold"
                                    :class="{
                                        'text-danger': report.down_line_commission_total_value < 0,
                                        'text-primary': report.down_line_commission_total_value > 0
                                    }"
                                >
                                    {{ report.down_line_commission_total }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span
                                    class="whitespace-no-wrap font-bold"
                                    :class="{
                                        'text-danger': report.down_line_win_loss_commission_total_value < 0,
                                        'text-primary': report.down_line_win_loss_commission_total_value > 0
                                    }"
                                >
                                    {{ report.down_line_win_loss_commission_total }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span 
                                    class="whitespace-no-wrap font-bold"
                                    :class="{
                                        'text-danger': report.mid_line_win_lose_total_value < 0,
                                        'text-primary': report.mid_line_win_lose_total_value > 0
                                    }"
                                >
                                    {{ report.mid_line_win_lose_total }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span
                                    class="whitespace-no-wrap font-bold"
                                    :class="{
                                        'text-danger': report.mid_line_commission_total_value < 0,
                                        'text-primary': report.mid_line_commission_total_value > 0
                                    }"
                                >
                                    {{ report.mid_line_commission_total }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span 
                                    class="whitespace-no-wrap font-bold"
                                    :class="{
                                        'text-danger': report.mid_line_win_loss_commission_total_value < 0,
                                        'text-primary': report.mid_line_win_loss_commission_total_value > 0
                                    }"
                                >
                                    {{ report.mid_line_win_loss_commission_total }}
                                </span>
                            </td>

                            <template v-if="isNotCompanyType">
                                <td class="text-right border-r">
                                    <span
                                        class="whitespace-no-wrap font-bold"
                                        :class="{
                                            'text-danger': report.up_line_win_lose_total_value < 0,
                                            'text-primary': report.up_line_win_lose_total_value > 0
                                        }"
                                    >
                                        {{ report.up_line_win_lose_total }}
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span
                                        class="whitespace-no-wrap font-bold"
                                        :class="{
                                            'text-danger': report.up_line_commission_total_value < 0,
                                            'text-primary': report.up_line_commission_total_value > 0
                                        }"
                                    >
                                        {{ report.up_line_commission_total }}
                                    </span>
                                </td>

                                <td class="text-right border-r">
                                    <span 
                                        class="whitespace-no-wrap font-bold"
                                        :class="{
                                            'text-danger': report.up_line_win_loss_commission_total_value < 0,
                                            'text-primary': report.up_line_win_loss_commission_total_value > 0
                                        }"
                                    >
                                        {{ report.up_line_win_loss_commission_total }}
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
