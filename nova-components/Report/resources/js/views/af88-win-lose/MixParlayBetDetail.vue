<script>
import { Paginatable } from 'laravel-nova';
import HandlesRequest from '@/mixins/HandlesRequest';

export default {
    metaInfo() {
        return {
            title: 'AF88 Mix Parlay Bet Detail Report',
        };
    },

    mixins: [
        Paginatable,
        HandlesRequest
    ],

    data() {
        return {
            perPage: 0,
            tickets: [],
            recordCount: 0,
            previousUserId: 0,
        };
    },

    methods: {
        setData({ report }) {
            this.tickets = report.data;
            this.recordCount = report.meta.total;
            this.perPage = report.meta.per_page;
            // this.previousUserId = data.previousUserId;
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

        watchParamsForRequest() {
            return (
                this.currentPage +
                this.date +
                this.from +
                this.to
            );
        },

        statusClass(value) {
            if(value === 5) return 'text-success';
            if(value === 6) return 'text-warning';
            if([1, 3].includes(value)) return 'text-primary';
            if([2, 4].includes(value)) return 'text-danger';

            return 'text-black';
        }
    },

    filters: {
        status(value) {
            const status = {
                0: 'None',
                1: 'Win',
                2: 'Lose',
                3: 'Win Half',
                4: 'Lose Half',
                5: 'Draw',
                6: 'Refund'
            };

            return status[value];
        }
    },

    computed: {
        requestUrl() {
            return `/nova-vendor/report/af88/win-lose/mix-parlay-bet/detail/${this.username}`;
        },

        requestQueryString() {
            let param = {
                page: this.currentPage,
                date: this.date,
                from: this.from,
                to: this.to,
            };

            return param;
        },

        username() {
            return this.$route.params.name;
        },

        pageParameter() {
            return 'page';
        },

        totalPages() {
            return Math.ceil(this.recordCount / this.perPage);
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
    }
}
</script>

<template>
    <div class="relative">
        <div class="flex item-center mb-3">
            <previous-button
                :to="{
                    name: 'report-af88-win-lose',
                    query: {
                        to,
                        date,
                        from,
                    }
                }"
            />

            <heading
                :level="1"
                v-html="`AF88 Mix Parlay Detail Report`"
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
                dusk="report-win-lose-detail"
            >
                <table-report
                    :hasRecord="recordCount"
                >
                    <template v-slot:header>
                        <tr>
                            <th class="text-center border-r">
                                <span>No</span>
                            </th>

                            <th class="text-left border-r">
                                <span>Date</span>
                            </th>

                            <th class="text-left border-r">
                                <span>Detail</span>
                            </th>

                            <th class="text-left border-r">
                                <span>Bet Amount</span>
                            </th>

                            <th class="text-left border-r">
                                <span>WIN/LOSE</span>
                            </th>

                            <th class="text-left border-r">
                                <span>COM</span>
                            </th>

                            <th class="text-center border-r">
                                <span>RESULT</span>
                            </th>
                        </tr>
                    </template>

                    <template v-slot:body>
                        <tr
                            v-for="(ticket, index) in tickets"
                            :key="index" 
                        >
                            <td class="text-center border-r">
                                <span class="whitespace-no-wrap">
                                    <h5>{{ index + 1 }}</h5>
                                </span>
                            </td>

                            <td class="border-r">
                                <span class="whitespace-no-wrap">
                                    <h5 class="text-sm">{{ ticket.sNo }}</h5>
                                    <h5 class="font-light">{{ ticket.date }}</h5>
                                    <h5 class="font-light">{{ ticket.time }}</h5>
                                </span>
                            </td>

                            <td class="border-r">
                                <span class="whitespace-no-wrap">
                                    <h5 class="flex flex-col items-start">
                                        <div>
                                            <a
                                                :href="`/admin/report/win-lose/mix-parlay/detail/${ticket.id}`"
                                                class="text-success"
                                                target="_blank"
                                            >
                                                MIX PARLAY
                                            </a>

                                            <span
                                                class="font-bold" 
                                                :class="{ 'text-danger' : ticket.isSpecialType }"
                                            >
                                                ({{ ticket.type }})
                                            </span>
                                        </div>

                                        <div>
                                            <span>
                                                <a
                                                    href="javascript:void(0);"
                                                    class="text-primary"
                                                >
                                                    @ {{ ticket.odds }}
                                                </a>
                                            </span>
                                            
                                            <span class="ml-2">
                                                (Inet)
                                            </span>
                                        </div>
                                    </h5>
                                </span>
                            </td>

                            <td class="border-r">
                                <span class="whitespace-no-wrap">
                                    <h4>{{ ticket.betAmount }}</h4>
                                </span>
                            </td>

                            <td class="border-r">
                                <span class="whitespace-no-wrap">
                                    <h4 
                                        :class="{ 
                                            'text-primary': ticket.winLose.value > 0,
                                            'text-danger': ticket.winLose.value < 0,
                                        }"
                                    >
                                        {{ ticket.winLose.text }}
                                    </h4>
                                </span>
                            </td>

                            <td class="border-r">
                                <span class="whitespace-no-wrap">
                                    <h4>
                                        {{ ticket.com.text }}
                                    </h4>
                                </span>
                            </td>

                            <td class="text-center border-r">
                                <span class="whitespace-no-wrap">
                                    <h5 
                                        v-if="ticket.isSettled"
                                        class="text-gray-600"
                                    >
                                        Settled
                                    </h5>

                                    <h5 v-else>
                                        ...
                                    </h5>
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