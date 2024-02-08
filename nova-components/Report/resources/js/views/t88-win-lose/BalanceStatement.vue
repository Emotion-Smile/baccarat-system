<script>
import { Paginatable } from 'laravel-nova';
import HandlesRequest from '@/mixins/HandlesRequest';

export default {

    metaInfo() {
        return {
            title: 'Balance Statement Report',
        };
    },

    mixins: [
        Paginatable,
        HandlesRequest
    ],

    data() {
        return {
            perPage: 0,
            recordCount: 0,
            currentBalance: 0,
            statementBalances: [],
            previousUser: {}
        };
    },

    methods: {
        watchParamsForRequest() {
            return (
                this.currentPage +
                this.date +
                this.from +
                this.to
            );
        },

        setData(data) {
            this.currentBalance = data.currentBalance;
            this.perPage = data.balanceStatement.per_page;
            this.statementBalances = data.balanceStatement.data;
            this.recordCount = data.balanceStatement.total;
            this.previousUser = data.previousUser;
        },

        selectPage(page) {
            this.updateQueryString({[this.pageParameter]: page});
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

        statementClass(item) {
            return (item.status === 'deposit' || item.status === 'payout' || item.status === 'refund')
                ? 'text-primary' : 'text-danger';
        },

        exceptWithdrawAndDeposit(item) {
            return item.status !== 'withdraw' && item.status !== 'deposit';
        }
    },

    computed: {
        requestUrl() {
            return `/nova-vendor/report/t88/win-lose/balance-statement/${this.memberName}`;
        },

        requestQueryString() {
            return {
                page: this.currentPage,
                date: this.date,
                from: this.from,
                to: this.to,
            }
        },

        pageParameter() {
            return 'page'
        },

        totalPages() {
            return Math.ceil(this.recordCount / this.perPage)
        },

        date() {
            return this.$route.query['date'];
        },

        from() {
            return this.$route.query['from'];
        },

        to() {
            return this.$route.query['to'];
        },

        memberName() {
            return this.$route.params.name;
        },
    },
}
</script>

<template>
    <div class="relative">
        <div class="flex item-center mb-3">
            <previous-button
                :to="{
                    name: 'report-t88-win-lose',
                    query: {
                        date,
                        from,
                        to,
                        name: previousUser ? previousUser.name : null
                    }
                }"
            />

            <heading
                :level="1"
                v-html="'Balance Statement Report'"
            />

            <div
                v-if="date === 'today'"
                class="flex items-center ml-3"
            >
                <span class="font-semibold mr-3">
                    ( Current Balance: <span class="font-bold">{{ currentBalance }}</span> )
                </span>
            </div>

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
                    :hasRecord="recordCount"
                >
                    <template v-slot:header>
                        <tr>
                            <th class="text-left">
                                <span>&nbsp;</span>
                            </th>
                            <th class="text-left">
                                <span>GAME</span>
                            </th>
                            <th class="text-left">
                                <span>TABLE</span>
                            </th>
                            <th class="text-left">
                                <span>USER NAME</span>
                            </th>
                            <th class="text-left">
                                <span>DATE</span>
                            </th>
                            <th class="text-left">
                                <span>Bet Id</span>
                            </th>
                            <th class="text-left">
                                <span>Fight/Game No</span>
                            </th>
                            <th class="text-left">
                                <span>BEFORE BALANCE</span>
                            </th>
                            <th class="text-left">
                                <span>AMOUNT</span>
                            </th>
                            <th class="text-left">
                                <span>CURRENT BALANCE</span>
                            </th>
                            <th class="text-left">
                                <span>STATEMENT</span>
                            </th>
                            <th class="text-left">
                                <span>NOTE</span>
                            </th>
                        </tr>
                    </template>

                    <template v-slot:body>
                        <tr
                            v-for="item in statementBalances"
                            :key="item.id"
                        >
                            <td>
                                <span class="whitespace-no-wrap">
                                    <icon-transaction
                                        :isWithdraw="item.status !== 'deposit' && item.status !== 'payout' && item.status !== 'refund'"
                                    />
                                </span>
                            </td>

                            <td>
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.game }}
                                </span>
                            </td>

                            <td>
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.group }}
                                </span>
                            </td>

                            <td>
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.username }}
                                </span>
                            </td>

                            <td>
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.date }} {{ item.time }}
                                </span>
                            </td>

                            <!-- <td>
                                <span v-if="exceptWithdrawAndDeposit(item)" class="whitespace-no-wrap">
                                    <template v-if="item.game.toLowerCase() === 'sport'">
                                        {{ item.fight_number }}
                                    </template>

                                    <template v-else>
                                        {{ item.fight_number }} - {{ item.bet_id }}
                                    </template>
                                </span>
                            </td> -->

                            <td>
                                <span 
                                    v-if="exceptWithdrawAndDeposit(item)" 
                                    class="whitespace-no-wrap"
                                >
                                    {{ item.bet_id }}
                                </span>
                            </td>

                            <td>
                                <span 
                                    v-if="exceptWithdrawAndDeposit(item)" 
                                    class="whitespace-no-wrap"
                                >
                                    {{ item.fight_number }}
                                </span>
                            </td>

                            <td>
                                <span class="whitespace-no-wrap font-semibold">
                                    {{ item.before_balance }}
                                </span>
                            </td>

                            <td>
                                <span
                                    class="whitespace-no-wrap font-semibold capitalize"
                                    :class="statementClass(item)"
                                >
                                    {{ item.amount }}
                                </span>
                            </td>

                            <td>
                                <span
                                    class="whitespace-no-wrap font-semibold capitalize"
                                >
                                    {{ item.current_balance }}
                                </span>
                            </td>

                            <td>
                                <span
                                    class="whitespace-no-wrap font-semibold capitalize"
                                    :class="statementClass(item)"
                                >
                                    {{ item.status }}
                                </span>
                            </td>

                            <td>
                                <span
                                    class="whitespace-no-wrap font-semibold capitalize"
                                >
                                    {{ item.note }}
                                </span>
                            </td>
                        </tr>
                    </template>

                    <template v-slot:pagination>
                        <pagination-links
                            :page="currentPage"
                            :pages="totalPages"
                            :per-page="perPage"
                            @page="selectPage"
                        />
                    </template>
                </table-report>
            </loading-view>
        </card>
    </div>
</template>
