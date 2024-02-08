<template>
    <div class="relative">
        <div class="mb-3"></div>
        <h1 class="mb-6 text-90 font-normal text-2xl">Report Win/Loss (Bet Detail)</h1>
        
        <filter-menu 
            :filters="{
                startDate,
                endDate
            }"
            @onFilter="onFilter"
        />

        <div class="card">
            <div class="py-3 px-3 flex items-center border-b border-50"></div>
            <div class="relative">
                <loading-view
                    :loading="loading"
                    :dusk="'report-win-lose-index-component'"
                >
                    <template v-if="allMatchingResourceCount">
                        <div class="overflow-hidden overflow-x-auto relative">
                            
                            <table
                                class="table w-full table-default"
                                cellpadding="0"
                                cellspacing="0"
                                data-testid="resource-table"
                            >
                                <thead>
                                    <tr>
                                        <!-- <th class="text-left">
                                            <span>BET ID</span>
                                        </th> -->
                                        <th class="text-left">
                                            <span>FIGHT NO</span>
                                        </th>
                                        <th class="text-left">
                                            <span>USER</span>
                                        </th>
                                        <th class="text-left">
                                            <span>BET TYPE</span>
                                        </th>
                                        <th class="text-left">
                                            <span>RESULT</span>
                                        </th>
                                        <th class="text-left">
                                            <span>PREVIOUS BALANCE</span>
                                        </th>
                                        <th class="text-left">
                                            <span>BET AMOUNT</span>
                                        </th>
                                        <th class="text-left">
                                            <span>PLAYER WIN/LOSE</span>
                                        </th>
                                        <th class="text-left">
                                            <span>CURRENT BALANCE</span>
                                        </th>
                                        <th class="text-left">
                                            <span>BET DATE</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr 
                                        v-for="report in reports.data"
                                        :key="report.betId"
                                        :dusk="`${report.betId}-row`"
                                        :class="{'bg-danger-light': report.result == 'loss'}"
                                    >
                                        <!-- <td>
                                            <div class="text-left">
                                                <p>{{ report.betId }}</p>
                                            </div>
                                        </td> -->
                                        <td>
                                            <div class="text-left">
                                                <span class="whitespace-no-wrap">{{ report.fightNo }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <span class="whitespace-no-wrap">{{ report.userName }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <span class="whitespace-no-wrap">{{ report.betType }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <span 
                                                    class="whitespace-no-wrap font-bold capitalize"
                                                    :class="{
                                                        'text-danger': report.result == 'loss',
                                                        'text-primary': report.result == 'win',
                                                    }"
                                                >{{ report.result }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <span class="whitespace-no-wrap">{{ report.previousBalance }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <span class="whitespace-no-wrap">{{ report.betAmount }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <span 
                                                    class="whitespace-no-wrap font-bold"
                                                    :class="{
                                                        'text-danger': report.result == 'loss',
                                                        'text-primary': report.result == 'win',
                                                    }"
                                                >{{ report.playerWinLose }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <span class="whitespace-no-wrap">{{ report.currentBalance }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <span class="whitespace-no-wrap">{{ report.betDate }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                    
                                    <tr dusk="total-row">
                                        <td colspan="4"></td>
                                        <td>
                                            <div class="text-left">
                                                <span class="whitespace-no-wrap font-bold">Total</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <span class="whitespace-no-wrap font-bold">{{ total.bet_amount }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <span class="whitespace-no-wrap font-bold">{{ total.win_lose }}</span>
                                            </div>
                                        </td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tbody>
                            </table>
    
                        </div>
                        
                        <pagination-links 
                            :page="currentPage"
                            :pages="totalPages"
                            :per-page="perPage"
                            @page="selectPage"
                        />
                    </template>

                    <no-data-view v-else />
                </loading-view>
            </div>
        </div>
    </div>
</template>

<script>
import {
    Minimum,
    Paginatable,
    InteractsWithQueryString,
} from 'laravel-nova';

import NoDataView from '../../components/NoDataView';
import LoadingView from '../../components/LoadingView';
import FilterMenu from '../../components/FilterMenu';
import PaginationLinks from '../../components/PaginationLinks';
import { CancelToken, Cancel } from 'axios';

export default {

    metaInfo() {
        return {
          title: 'ReportWinLose',
        };
    },

    mixins: [
        Paginatable,
        InteractsWithQueryString
    ],

    components: {
        FilterMenu,
        NoDataView,
        LoadingView,
        PaginationLinks
    },

    data() {
        return {
            canceller: null,
            loading: false,
            reports: {},
            total: {},
            allMatchingResourceCount: 0,
            perPage: 0,
        };
    },

    methods: {

        getResources() {
            this.loading = true;
            
            this.$nextTick(() => {
                return Minimum(
                    Nova.request().get('/nova-vendor/report-win-lose/bet-detail/' + this.memberId, {
                        params: this.resourceRequestQueryString,
                        cancelToken: new CancelToken(canceller => {
                            this.canceller = canceller
                        }),
                    }),
                    300
                )
                .then(({ data }) => {
    
                    this.reports = data.reports;
                    this.total = data.total;

                    this.perPage = data.reports.per_page;
                    this.allMatchingResourceCount = data.reports.total

                    this.loading = false;

                    Nova.$emit('resources-loaded')
                })
                .catch(e => {
                    if (e instanceof Cancel) {
                        return
                    }

                    this.loading = false;
                
                    throw e;
                });
            })
        },

        selectPage(page) {
            this.updateQueryString({ [this.pageParameter]: page });
        },

        onFilter(filters) {
            this.updateQueryString(filters);
        },
    },

    computed: {

        resourceRequestQueryString() {
            return {
                page: this.currentPage,
                startDate: this.startDate,
                endDate: this.endDate,
            }
        },

        pageParameter() {
            return 'page'
        },

        totalPages() {
            return Math.ceil(this.allMatchingResourceCount / this.perPage)
        },

        startDate() {
            return this.$route.query['startDate'] || null;
        },

        endDate() {
            return this.$route.query['endDate'] || null;
        },

        memberId() {
            return this.$route.params.memberId;
        }
    },

    async created() {
        await this.getResources();

        this.$watch(
            () => {
                return (
                    this.currentPage + 
                    this.startDate + 
                    this.endDate
                )
            },
            () => {
                if (this.canceller !== null) this.canceller()

                this.getResources();
            }
        )

        Nova.$on('refresh-resources', () => {
            this.getResources();
        });
    },
}
</script>
