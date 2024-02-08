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
                v-html="'Win/Loss Report (Bet Summary)'"
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
                    :hasRecord="true"
                    class="table w-full table-default table-grid"
                >
                    <template v-slot:header>
                        <tr>
                            <th rowspan="2" class="text-left">
                                <span>ACCOUNT</span>
                            </th>
                            <th rowspan="2" class="text-left">
                                <span>CURRENCY</span>
                            </th>
                            <th rowspan="2" class="text-left">
                                <span>GAME SITES</span>
                            </th>
                            <th rowspan="2" class="text-left">
                                <span>CONTACT</span>
                            </th>
                            <th rowspan="2" class="text-right">
                                <span>BET AMOUNT</span>
                            </th>
                            <th colspan="3" class="text-center">
                                <span>MEMBER</span>
                            </th>
                            <th colspan="3" class="text-center">
                                <span>PROFIT</span>
                            </th>
                            <th colspan="3" class="text-center">
                                <span>UPLINE PROFIT</span>
                            </th>
                            <th rowspan="2" class="text-left">&nbsp;</th>
                        </tr>
                        <tr>
                            <template
                                v-for="n in 3"
                            >
                                <th class="text-right">
                                    <span>W/L</span>
                                </th>
                                <th class="text-right">
                                    <span>COM</span>
                                </th>
                                <th class="text-right">
                                    <span>W/L + COM</span>
                                </th>
                            </template>
                        </tr>
                    </template>

                    <template v-slot:body>
                        <tr
                            v-for="report in reports"
                            :key="report.id"
                            :dusk="`${report.id}-row`"
                        >
                            <td class="text-left">
                                <span class="whitespace-no-wrap">
                                    {{ report.name }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span class="whitespace-no-wrap">
                                    {{ report.currency }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span class="whitespace-no-wrap">
                                    &nbsp;
                                </span>
                            </td>
                            <td class="text-left">
                                <span class="whitespace-no-wrap">
                                    {{ report.contact }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span 
                                    class="whitespace-no-wrap"
                                    :class="winLoseClass(report.betAmount.value)"
                                >
                                    {{ report.betAmount.label }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span 
                                    class="whitespace-no-wrap"
                                    :class="winLoseClass(report.members.winLose.value)"
                                >
                                    {{ report.members.winLose.label }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span class="whitespace-no-wrap">
                                    {{ report.members.com }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span class="whitespace-no-wrap">
                                    {{ report.members.winLoseAndCom }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span 
                                    class="whitespace-no-wrap"
                                    :class="winLoseClass(report.profits.winLose.value)"
                                >
                                    {{ report.profits.winLose.label }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span class="whitespace-no-wrap">
                                    {{ report.profits.com }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span class="whitespace-no-wrap">
                                    {{ report.profits.winLoseAndCom }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span 
                                    class="whitespace-no-wrap"
                                    :class="winLoseClass(report.uplineProfits.winLose.value)"
                                >
                                    {{ report.uplineProfits.winLose.label }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span class="whitespace-no-wrap">
                                    {{ report.uplineProfits.com }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span class="whitespace-no-wrap">
                                    {{ report.uplineProfits.winLoseAndCom }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span class="whitespace-no-wrap">
                                    &nbsp;
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td colspan="4" class="text-left">
                                <span class="whitespace-no-wrap">
                                    &nbsp;
                                </span>
                            </td>
                            <td class="text-left">
                                <span class="whitespace-no-wrap">
                                    {{ total.betAmount.label }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span class="whitespace-no-wrap">
                                    {{ total.members.winLose.label }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span class="whitespace-no-wrap">
                                    {{ total.members.com.label }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span class="whitespace-no-wrap">
                                    {{ total.members.winLoseAndCom.label }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span class="whitespace-no-wrap">
                                    {{ total.profits.winLose.label }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span class="whitespace-no-wrap">
                                    {{ total.profits.com.label }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span class="whitespace-no-wrap">
                                    {{ total.profits.winLoseAndCom.label }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span class="whitespace-no-wrap">
                                    {{ total.uplineProfits.winLose.label }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span class="whitespace-no-wrap">
                                    {{ total.uplineProfits.com.label }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span class="whitespace-no-wrap">
                                    {{ total.uplineProfits.winLoseAndCom.label }}
                                </span>
                            </td>
                            <td class="text-left">
                                <span class="whitespace-no-wrap">
                                    &nbsp;
                                </span>
                            </td>
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
            title: 'Win/Loss Report',
        };
    },

    mixins: [
        Paginatable,
        HandlesRequest
    ],

    data() {
        return {
            total: {},
            reports: {},
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
            this.groups = data.groups
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

        winLoseClass(value) {
            return {
                'text-danger': value < 0,
                'text-primary': value > 0
            };
        }

    },

    computed: {

        requestUrl() {
            return `/nova-vendor/report/v2/bet-summaries`;
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
        
    }
    
}
</script>
