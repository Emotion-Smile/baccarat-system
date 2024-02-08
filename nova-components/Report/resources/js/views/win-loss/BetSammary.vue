<template>
    <div class="relative">
        <div class="flex item-center mb-3">
            <previous-button
                v-if="userId"
                :to="{
                    name: 'report-win-lose',
                    query: {
                        userId: preventUserId,
                        date,
                        from,
                        to
                    }
                }"
            />

            <heading
                :level="1"
                v-html="'CF Win/Lose'"
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
                <div class="flex pl-6 pt-6">
                    <label class="inline-block text-80 pt-2 leading-tight pr-6">Table: </label>
                    <select @change.prevent="switchGroup" class="form-control form-select" v-model="groupSelected">
                        <option value="0">All</option>
                        <option 
                            v-for="group in groups"
                            :key="group.id" 
                            :value="group.id"
                        >{{ group.name }}</option>
                    </select>
                </div>


                <table-report
                    :hasRecord="recordCount"
                >
                    <template v-slot:header>
                        <tr>
                            <th class="text-left">
                                <span>ID</span>
                            </th>
                            <th class="text-left">
                                <span>NAME</span>
                            </th>
                            <th class="text-left">
                                <span>CONTACT</span>
                            </th>
                            <th class="text-left">
                                <span>BET AMOUNT</span>
                            </th>
                            <th class="text-left">
                                <span>PLAYER WIN/LOSE</span>
                            </th>
                            <th class="text-left">&nbsp;</th>
                        </tr>
                    </template>

                    <template v-slot:body>
                        <tr
                            v-for="report in reports"
                            :key="report.id"
                            :dusk="`${report.id}-row`"
                        >
                            <td>
                                <div class="text-left">
                                    <p>{{ report.id }}</p>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ report.name }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-semibold">
                                        {{ report.contact }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span
                                        class="whitespace-no-wrap font-semibold"
                                        :class="{
                                            'text-danger': report.bet_amount.value < 0,
                                            'text-primary': report.bet_amount.value > 0
                                        }"
                                    >
                                        {{ report.bet_amount.label }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                        <span
                                            class="whitespace-no-wrap font-semibold"
                                            :class="{
                                                'text-danger': report.win_lose.value < 0,
                                                'text-primary': report.win_lose.value > 0
                                            }"
                                        >
                                            {{ report.win_lose.label }}
                                        </span>
                                </div>
                            </td>
                            <td class="td-fit text-right pr-6 align-middle">
                                <div class="inline-flex items-center">
                                    <link-icon
                                        v-if="report.userType !== 'member'"
                                        :to="{
                                            name: 'report-win-lose',
                                            query: {
                                                userId: report.id,
                                                date,
                                                from,
                                                to
                                            },
                                        }"
                                        :tip="__('View')"
                                    />

                                    <template v-else>
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
                                                    memberId: report.id,
                                                },
                                                query: {
                                                    date,
                                                    from,
                                                    to
                                                },
                                            }"
                                        />
                                        <link-icon
                                            tip="View Win/Loss"
                                            :icon="{
                                                type: 'book',
                                                width: 22,
                                                height: 18,
                                                viewBox: '0 0 576 512'
                                            }"
                                            :to="{
                                                name: 'report-win-lose-detail',
                                                params: {
                                                    memberId: report.id,
                                                },
                                                query: {
                                                    date,
                                                    from,
                                                    to
                                                },
                                            }"
                                        />
                                    </template>
                                </div>
                            </td>
                        </tr>

                        <tr dusk="total-row">
                            <td>
                            </td>
                            <td>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-bold">
                                        Total
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span
                                        class="whitespace-no-wrap font-bold"
                                        :class="{
                                            'text-danger': total.bet_amount.value < 0,
                                            'text-primary': total.bet_amount.value > 0
                                        }"
                                    >
                                        {{ total.bet_amount.label }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span
                                        class="whitespace-no-wrap font-bold"
                                        :class="{
                                            'text-danger': total.win_lose.value < 0,
                                            'text-primary': total.win_lose.value > 0
                                        }"
                                    >
                                        {{ total.win_lose.label }}
                                    </span>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                    </template>

                    <!-- <template v-slot:pagination>
                        <pagination-links
                            :page="currentPage"
                            :pages="totalPages"
                            :per-page="perPage"
                            @page="selectPage"
                        />
                    </template> -->

                </table-report>
            </loading-view>
        </card>
    </div>
</template>

<script>
import { Paginatable } from 'laravel-nova';
import HandlesRequest from '@/mixins/HandlesRequest';

export default {

    metaInfo() {
        return {
            title: 'CF Win/Lose',
        };
    },

    mixins: [
        Paginatable,
        HandlesRequest
    ],

    data() {
        return {
            total: {},
            reports: [],
            preventUserId: 0,
            groups: {},
            groupSelected: 0,
            recordCount: 0,
            perPage: 0,
        };
    },
    methods: {
    
        watchParamsForRequest() {
            return (
                this.currentPage +
                this.userId +
                this.date +
                this.from +
                this.to +
                this.group
            );
        },

        setData(data) {
            this.reports = data.reports;
            this.total = data.total;
            this.perPage = data.reports.per_page;
            this.preventUserId = data.preventUserId;
            this.recordCount = data.reports.length;
            this.groups = data.groups;
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
            this.date = null;
            this.updateQueryString({
                ...value,
                date: null,
            });
        },

        switchGroup(event) {
            this.groupSelected = event.target.value;
            this.updateQueryString({
                ...this.$route.query,
                group: event.target.value
            })
        },

    },

    computed: {

        requestUrl() {
            return `/nova-vendor/report/bet-summaries`;
        },

        requestQueryString() {

            let param = {
                page: this.currentPage,
                userId: this.userId,
                date: this.date,
                from: this.from,
                to: this.to,
                group: this.group
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

        pageParameter() {
            return 'page'
        },

        totalPages() {
            return Math.ceil(this.recordCount / this.perPage)
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

        group() {
            this.groupSelected = this.$route.query['group'] || 0;
            return this.$route.query['group'] || null;
        }
        
    },
}
</script>
