<script>
import { Paginatable } from 'laravel-nova';
import HandlesRequest from '@/mixins/HandlesRequest';

export default {
    metaInfo() {
        return {
            title: 'Yuki Bet Detail',
        };
    },

    mixins: [
        Paginatable,
        HandlesRequest
    ],

    data() {
        return {
            items: [],
            perPage: 0,
            recordCount: 0,
            previousUser: {}
        };
    },

    methods: {
        watchParamsForRequest() {
            return (
                this.currentPage +
                this.date + 
                this.to + 
                this.from
            );
        },

        setData(data) {
            this.items = data.report.data;
            this.perPage = data.report.per_page;
            this.recordCount = data.report.total;
            this.previousUser = data.previousUser;
        },

        selectPage(page) {
            this.updateQueryString({
                [this.pageParameter]: page
            });
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
            return `/nova-vendor/report/t88/win-lose/bet-detail/${this.memberName}`;
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

        previousRouteName() {
            return this.$route.query['previousRouteName'] || 'report-t88-win-lose';
        },

        previousQueryString() {
            const queryString = {
                date: this.date,
                from: this.from,
                to: this.to, 
            };

            if(this.previousRouteName === 'report-t88-win-lose') {
                return {
                    ...queryString,
                    name: this.previousUser ? this.previousUser.name : null
                };
            }
            
            return {
                ...queryString,
                userId: this.previousUser ? this.previousUser.id : null 
            }
        }
    },
}
</script>

<template>
    <div class="relative">
        <div class="flex item-center mb-3">
            <previous-button
                :to="{
                    name: previousRouteName,
                    query: previousQueryString
                }"
            />

            <heading
                :level="1"
                v-html="`Yuki Bet Detail`"
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
                    :hasRecord="recordCount"
                >
                    <template v-slot:header>
                        <tr>
                            <th class="text-left">
                                <span>No</span>
                            </th>

                            <th class="text-left">
                                <span>Date</span>
                            </th>

                            <th class="text-left">
                                <span>Game Code</span>
                            </th>

                            <th class="text-left">
                                <span>Member Name</span>
                            </th>

                            <th class="text-left">
                                <span>Bet</span>
                            </th>

                            <th class="text-left">
                                <span>Bet Amount</span>
                            </th>

                            <th class="text-left">
                                <span>Status</span>
                            </th>

                            <th class="text-left">
                                <span>Win/Lose</span>
                            </th>
                        </tr>
                    </template>

                    <template v-slot:body>
                        <tr
                            v-for="(item, key) in items"
                            :key="item.id"
                        >
                            <td>
                                <span
                                    class="whitespace-no-wrap font-semibold"
                                >
                                    {{ key + 1 }}
                                </span>
                            </td>

                            <td>
                                <span
                                    class="whitespace-no-wrap font-semibold"
                                >
                                    {{ item.date }}
                                </span>
                            </td>

                            <td>
                                <span
                                    class="whitespace-no-wrap font-semibold"
                                >
                                    {{ item.code }}
                                </span>
                            </td>

                            <td>
                                <span
                                    class="whitespace-no-wrap font-semibold"
                                >
                                    {{ item.original_name }}
                                </span>
                            </td>

                            <td>
                                <span
                                    class="whitespace-no-wrap font-semibold uppercase"
                                >
                                    {{ item.bet_on }}
                                </span>
                            </td>

                            <td>
                                <span
                                    class="whitespace-no-wrap font-semibold"
                                    :class="{
                                        'text-primary': item.status === 'WIN',
                                        'text-danger': item.status === 'LOST'
                                    }"
                                >
                                    {{ item.amount }}
                                </span>
                            </td>

                            <td>
                                <span
                                    class="whitespace-no-wrap font-semibold capitalize"
                                    :class="{
                                        'text-primary': item.status === 'WIN',
                                        'text-danger': item.status === 'LOST'
                                    }"
                                >
                                    {{ item.status }}
                                </span>
                            </td>

                            <td>
                                <span
                                    class="whitespace-no-wrap font-semibold"
                                    :class="{
                                        'text-primary': item.status === 'WIN',
                                        'text-danger': item.status === 'LOST'
                                    }"
                                >
                                    {{ item.win_lose }}
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

