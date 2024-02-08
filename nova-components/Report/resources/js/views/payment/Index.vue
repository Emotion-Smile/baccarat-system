<template>
    <div class="relative">
        <div class="flex flex-wrap -mx-3 mb-3">
            <TotalPaymentCard 
                :loading="loading"
                width="w-1/2"
                label="Total Deposit"
                :value="totalDeposit"
                valueClass="text-primary"
            />

            <TotalPaymentCard 
                :loading="loading"
                width="w-1/2"
                label="Total Withdraw"
                :value="totalWithdraw"
                valueClass="text-danger"
            />
        </div>

        <div class="flex items-center mb-6">
            <previous-button
                v-if="userId"
                :to="preventTo"
            />

            <heading 
                :level="1" 
                v-html="'Payments Report'" 
            />

            <div class="ml-3">
                <span class="font-semibold mr-2">
                    ( View As: <span class="font-bold"> {{ viewAs }} </span> )
                </span>
            </div>
        </div>
        
        <div class="relative h-9 flex-no-shrink mb-6">
            <icon 
                type="search" 
                class="absolute search-icon-center ml-3 text-70" 
            />

            <input
                type="search"
                v-model="search"
                :placeholder="__('Search')"
                @keydown.stop="performSearch"
                @search="performSearch"
                spellcheck="false"
                dusk="search"
                data-testid="search-input"
                class="appearance-none form-search w-search pl-search shadow"
            />
        </div>

        <card class="mb-6">
            <div class="p-3 flex items-center border-b border-50 justify-between">
                <period-date-picker
                    :selected="currentDate"
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
            <div class="flex items-center py-3 border-b border-50">
                <div class="flex items-center"></div>
                <div class="flex items-center ml-auto px-3">
                    <label class="inline-block text-80 leading-tight mr-3">
                        Per Page: 
                    </label>

                    <select  
                        class="form-control form-select"
                        v-model="perPageQuerySting"
                        @change="perPageChange"
                    >
                        <option 
                            v-for="value in perPageOptions" 
                            :value="value"
                            :key="value"
                        >
                            {{ value }}
                        </option>
                    </select>
                </div>
            </div>

            <loading-view
                :loading="loading"
                dusk="report-win-lose-index-component"
            >
                <table-report 
                    :hasRecord="recordCount"
                    :hasBorder="false"
                >
                    <template v-slot:header>
                        <tr>
                            <th class="text-left">
                                <span>&nbsp;</span>
                            </th>
                            <th class="text-left">
                                <span>IP</span>
                            </th>
                            <th class="text-left">
                                <span>DATE</span>
                            </th>
                            <th class="text-left">
                                <span>USER NAME</span>
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
                                <span>REMARK</span>
                            </th>
                            <th class="text-left">&nbsp;</th>
                        </tr>
                    </template>
                    <template v-slot:body>
                        <tr 
                            v-for="payment in payments.data"
                            :key="payment.id"
                            :dusk="`${payment.id}-row`"
                        >
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap">
                                        <icon-transaction
                                            :isWithdraw="payment.statement !== 'deposit'" 
                                        />
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-medium">
                                        {{ payment.ip }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-medium">
                                        {{ payment.date }} {{ payment.time }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-medium">
                                        {{ payment.userName }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-bold text-success-dark">
                                        {{ payment.beforeBalance.label }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span 
                                        class="whitespace-no-wrap font-bold"
                                        :class="payment.statement !== 'deposit' ? 'text-danger': 'text-primary'"
                                    >
                                        {{ payment.amount.label }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-bold">
                                        {{ payment.currentBalance.label }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span 
                                        class="whitespace-no-wrap font-bold capitalize"
                                        :class="payment.statement !== 'deposit' ? 'text-danger': 'text-primary'"
                                    >
                                        {{ payment.statement }}
                                    </span>
                                </div>
                            </td>
                            <td>
                                <div class="text-left">
                                    <span class="whitespace-no-wrap font-medium">
                                        {{ payment.remark }}
                                    </span>
                                </div>
                            </td>
                            <td class="td-fit text-right pr-6 align-middle">
                                <div 
                                    v-if="!payment.isMember" 
                                    class="inline-flex items-center"
                                >
                                    <link-icon
                                        :to="{
                                            name: 'sub-user-payments-report',
                                            params: {
                                                userId: payment.userId,
                                            },
                                        }"
                                        :tip="`View As ${payment.userName}`"
                                    />
                                </div>
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

<script>
import { Paginatable } from 'laravel-nova';
import HandlesRequest from '@/mixins/HandlesRequest';
import TotalPaymentCard from '@/components/Card';

export default {

    metaInfo() {
        return {
          title: 'Payments Report',
        };
    },

    components: {
        TotalPaymentCard
    },

    props: {
        userId: {
            type: Number,
            default: null
        }
    },

    mixins: [
        Paginatable,
        HandlesRequest
    ],

    data() {
        return {
            search: '',
            viewAs: '',
            payments: {},
            totalDeposit: 0,
            totalWithdraw: 0,
            perPage: 0,
            recordCount: 0,
            debouncer: null,
            preventUserId: null,
            perPageOptions: [
                10,
                25,
                50,
                100
            ]
        };
    },

    methods: {

        initializeBeforeRequestOnCreated() {
            this.debouncer = _.debounce(
                callback => callback(),
                500
            );

            this.initializeSearchFromQueryString();
        },

        watchParamsForRequest() {
            return (
                this.currentPage +
                this.currentDate +
                this.currentSearch +
                this.userId + 
                this.from +
                this.to + 
                this.perPageQuerySting
            );
        },

        setData(data) {
            this.viewAs = data.viewAs;
            this.payments = data.payments;
            this.perPage = data.payments.per_page;
            this.recordCount = data.payments.total;
            this.preventUserId = data.preventUserId;
            this.totalDeposit = data.totalDeposit;
            this.totalWithdraw = data.totalWithdraw;
        },

        selectPage(page) {
            this.updateQueryString({ [this.pageParameter]: page });
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

        perPageChange(e) {
            const perPage = e.target.value;
            this.updateQueryString({
                perPage,
                page: null
            });
        },

        performSearch(event) {
            this.debouncer(() => {
                // Only search if we're not tabbing into the field
                if (event.which != 9) {
                    this.updateQueryString({
                        [this.pageParameter]: 1,
                        search: this.search,
                    });
                }
            })
        },

        initializeSearchFromQueryString() {
            this.search = this.currentSearch
        },
        
    },

    computed: {

        requestUrl() {
            return [
                '/nova-vendor/report/payment-reports', 
                this.userId
            ].join('/'); 
        },

        requestQueryString() {
            let param = {
                page: this.currentPage,
                date: this.currentDate,
                search: this.currentSearch,
                from: this.from,
                to: this.to,
                perPage: this.perPageQuerySting
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

        currentDate() {
            if (this.from === undefined || this.from === null) {
                return this.$route.query['date'] || 'today';
            }

            return null;
        },

        currentSearch() {
            return this.$route.query['search'] || '';
        },

        preventTo() {
            let to = { name: 'report-payments'};

            if(this.preventUserId) {
                to = {
                    name: 'sub-user-payments-report',
                    params: {
                        userId: this.preventUserId,
                    },
                }
            }

            return to;
        },

        from() {
            return this.$route.query['from'];
        },

        to() {
            return this.$route.query['to'];
        },

        perPageQuerySting() {
            return this.$route.query['perPage'] || 10;
        }
    }
}
</script>
