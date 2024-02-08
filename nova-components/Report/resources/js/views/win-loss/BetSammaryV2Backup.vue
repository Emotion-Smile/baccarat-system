<template>
    <div class="relative">
        <div class="mb-3"></div>
        <h1 class="mb-6 text-90 font-normal text-2xl">Report Win/Lose (Bet Summary)</h1>
        
        <!-- <filter-menu 
            :filters="{
                startDate,
                endDate
            }"
            @onFilter="onFilter"
        /> -->

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
                                class="table w-full table-default table-grid"
                                data-testid="resource-table"
                                cellpadding="0"
                                cellspacing="0"
                            >
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="text-left">
                                            <span>ACCOUNT</span>
                                        </th>
                                        <th rowspan="2" class="text-left">
                                            <span>CURRENCY</span>
                                        </th>
                                        <th rowspan="2" class="text-left">
                                            <span>CONTACT</span>
                                        </th>
                                        <th rowspan="2" class="text-right">
                                            <span>BET AMOUNT</span>
                                        </th>
                                        <th rowspan="2" class="text-right">
                                            <span>VAILID AMOUNT</span>
                                        </th>
                                        <th colspan="3" class="text-center">
                                            <span>MEMBER</span>
                                        </th>
                                        <th rowspan="2" class="text-center">
                                            <span>PROFIT</span>
                                        </th>
                                        <th rowspan="2" class="text-center">
                                            <span>COMPANY</span>
                                        </th>
                                        <th rowspan="2" class="text-left">&nbsp;</th>
                                    </tr>
                                    <tr>
                                        <th class="text-right">
                                            <span>W/L</span>
                                        </th>
                                        <th class="text-right">
                                            <span>COM</span>
                                        </th>
                                        <th class="text-right">
                                            <span>W/L + COM</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(report, index) in reports.data"
                                        :key="index"
                                        :dusk="`${index}-row`"
                                    >
                                        <td class="text-left">
                                            <span class="whitespace-no-wrap">
                                                {{ report.account }}
                                            </span>
                                        </td>
                                        <td class="text-left">
                                            <span class="whitespace-no-wrap">
                                                USD
                                            </span>
                                        </td>
                                        <td class="text-left">
                                            <span class="whitespace-no-wrap">
                                                {{ report.contact }}
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <span class="whitespace-no-wrap font-bold">
                                                {{ report.betAmount }}
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <span class="whitespace-no-wrap font-bold">
                                                {{ report.vailidAmount }}
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <span 
                                                class="whitespace-no-wrap font-bold"
                                                :class="{ 'text-danger': report.winLose < 0 }"
                                            >
                                                {{ report.winLose }}
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <span 
                                                class="whitespace-no-wrap font-bold"
                                                :class="{ 'text-danger': report.com < 0 }"
                                            >
                                                {{ report.com }}
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <span 
                                                class="whitespace-no-wrap font-bold"
                                                :class="{ 'text-danger': report.winLoseCom < 0 }"
                                            >
                                                {{ report.winLoseCom }}
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <span 
                                                class="whitespace-no-wrap font-bold"
                                                :class="{ 'text-danger': report.profit < 0 }"
                                            >
                                                {{ report.profit }}
                                            </span>
                                        </td>
                                        <td class="text-right">
                                            <span 
                                                class="whitespace-no-wrap font-bold"
                                                :class="{ 'text-danger': report.company < 0 }"
                                            >
                                                {{ report.company }}
                                            </span>
                                        </td>
                                        <td class="text-right pr-6 align-middle">
                                            <div class="inline-flex items-center">
                                                <router-link
                                                    :data-testid="`view-button`"
                                                    :dusk="`view-button`"
                                                    class="cursor-pointer text-70 hover:text-primary mr-3 inline-flex items-center"
                                                    v-tooltip.click="__('View')"
                                                    :to="{
                                                        name: 'report-win-lose',
                                                    }"
                                                >
                                                    <span class="inline-flex">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="18" viewBox="0 0 22 16" aria-labelledby="view" role="presentation" class="fill-current">
                                                            <path d="M16.56 13.66a8 8 0 0 1-11.32 0L.3 8.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95-.01.01zm-9.9-1.42a6 6 0 0 0 8.48 0L19.38 8l-4.24-4.24a6 6 0 0 0-8.48 0L2.4 8l4.25 4.24h.01zM10.9 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path>
                                                        </svg>
                                                    </span>
                                                </router-link>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- <tr 
                                        v-for="report in reports.data"
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
                                                <span class="whitespace-no-wrap">{{ report.name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <p>-</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <span class="whitespace-no-wrap">{{ report.bet_amount }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="text-left">
                                                <span class="whitespace-no-wrap">{{ report.win_lose }}</span>
                                            </div>
                                        </td>
                                        <td class="text-right pr-6 align-middle">
                                            <div v-if="report.userType !== 'member'" class="inline-flex items-center">
                                                <router-link
                                                    :data-testid="`view-button`"
                                                    :dusk="`view-button`"
                                                    class="cursor-pointer text-70 hover:text-primary mr-3 inline-flex items-center"
                                                    v-tooltip.click="__('View')"
                                                    :to="{
                                                        name: 'report-win-lose',
                                                        query: {
                                                            userId: report.id,
                                                            startDate,
                                                            endDate
                                                        },
                                                    }"
                                                >
                                                    <span class="inline-flex">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="18" viewBox="0 0 22 16" aria-labelledby="view" role="presentation" class="fill-current">
                                                            <path d="M16.56 13.66a8 8 0 0 1-11.32 0L.3 8.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95-.01.01zm-9.9-1.42a6 6 0 0 0 8.48 0L19.38 8l-4.24-4.24a6 6 0 0 0-8.48 0L2.4 8l4.25 4.24h.01zM10.9 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path>
                                                        </svg>
                                                    </span>
                                                </router-link>
                                            </div>
                                            <div v-else class="inline-flex items-center">
                                                <router-link
                                                    :data-testid="`view-button`"
                                                    :dusk="`view-button`"
                                                    class="cursor-pointer text-70 hover:text-primary mr-3 inline-flex items-center"
                                                    v-tooltip.click="__('View')"
                                                    :to="{
                                                        name: 'report-win-lose-detail',
                                                        params: {
                                                            memberId: report.id,
                                                        },
                                                        query: {
                                                            startDate,
                                                            endDate
                                                        },
                                                    }"
                                                >
                                                    <span class="inline-flex">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="22" height="18" viewBox="0 0 22 16" aria-labelledby="view" role="presentation" class="fill-current">
                                                            <path d="M16.56 13.66a8 8 0 0 1-11.32 0L.3 8.7a1 1 0 0 1 0-1.42l4.95-4.95a8 8 0 0 1 11.32 0l4.95 4.95a1 1 0 0 1 0 1.42l-4.95 4.95-.01.01zm-9.9-1.42a6 6 0 0 0 8.48 0L19.38 8l-4.24-4.24a6 6 0 0 0-8.48 0L2.4 8l4.25 4.24h.01zM10.9 12a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm0-2a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path>
                                                        </svg>
                                                    </span>
                                                </router-link>
                                            </div>
                                        </td>
                                    </tr> -->
                                    
                                    <!-- <tr dusk="total-row">
                                        <td>
                                        </td>
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
                                        <td></td>
                                    </tr> -->
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

import NoDataView from '../components/NoDataView';
import LoadingView from '../components/LoadingView';
import FilterMenu from '../components/FilterMenu';
import PaginationLinks from '../components/PaginationLinks';
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
            total: {},
            reports: {},
            perPage: 0,
            loading: false,
            canceller: null,
            allMatchingResourceCount: 0,
        };
    },

    methods: {

        getResources() {
            this.loading = true;
            
            this.$nextTick(() => {
                return Minimum(
                    Nova.request().get('/nova-vendor/report-win-lose/bet-summaries', {
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
                userId: this.userId 
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

        userId() {
            return this.$route.query['userId'] || null;
        }
    },

    async created() {
        await this.getResources();

        this.$watch(
            () => {
                return (
                    this.currentPage + 
                    this.startDate + 
                    this.endDate +
                    this.userId
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
