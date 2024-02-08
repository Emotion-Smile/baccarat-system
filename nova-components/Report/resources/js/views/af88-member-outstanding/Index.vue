<script>
import HandlesRequest from '@/mixins/HandlesRequest';

export default {
    metaInfo() {
        return {
            title: 'AF88 Member Outstanding',
        };
    },

    mixins: [
        HandlesRequest
    ],

    data() {
        return {
            currency: '',
            totalOutstanding: '',
            totalAvailableCredit: '',
            memberOutstanding: [],
        };
    },

    methods: {
        setData({ report }) {
            this.currency = report.currency;
            this.totalOutstanding = report.totalOutstanding; 
            this.totalAvailableCredit = report.totalAvailableCredit;
            this.memberOutstanding = report.memberOutstanding;
        }
    },

    computed: {
        requestUrl() {
           return `/nova-vendor/report/af88/member/outstanding`;
        }
    },
}
</script>

<template>
    <div class="relative">

        <heading
            :level="1"
            v-html="'AF88 Member Outstanding'"
            class="mb-6"
        />

        <card>
            <loading-view
                :loading="memberOutstanding.length <= 0"
            >
                <table-report
                    :hasRecord="memberOutstanding.length > 0"
                >
                    <template v-slot:header>
                        <tr>
                            <th class="text-left border-r">
                                <span>Account</span>
                            </th>

                            <th class="text-left border-r">
                                <span>Currency</span>
                            </th>

                            <th class="text-right border-r">
                                <span>Outstanding</span>
                            </th>

                            <th class="text-right border-r">
                                <span>Available Credit</span>
                            </th>
                        </tr>
                    </template>

                    <template v-slot:body>
                        <tr
                            v-for="item in memberOutstanding"
                            :key="item.accountId"
                        >
                            <td class="text-left border-r">
                                <span class="whitespace-no-wrap font-medium">
                                    {{ item.account }}
                                </span>
                            </td>

                            <td class="text-left border-r">
                                <span class="whitespace-no-wrap font-medium">
                                    {{ item.currency }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <router-link
                                    v-if="item.outstanding.value > 0"
                                    :to="{
                                        name: 'report-af88-member-outstanding-detail',
                                        params: {
                                            accountId: item.accountId,
                                        }
                                    }"
                                    class="cursor-pointer text-primary inline-flex items-center"
                                >
                                    <span class="whitespace-no-wrap font-bold">
                                        {{ item.outstanding.text }}
                                    </span>
                                </router-link>

                                <span
                                    v-else 
                                    class="whitespace-no-wrap font-bold"
                                >
                                    {{ item.outstanding.text }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span class="whitespace-no-wrap font-bold">
                                    {{ item.availableCredit.text }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <td class="text-left border-r"></td>

                            <td class="text-left border-r">
                                <span class="whitespace-no-wrap font-medium">
                                    {{ currency }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span class="whitespace-no-wrap font-bold">
                                    {{ totalOutstanding }}
                                </span>
                            </td>

                            <td class="text-right border-r">
                                <span class="whitespace-no-wrap font-bold">
                                    {{ totalAvailableCredit }}
                                </span>
                            </td>
                        </tr>
                    </template>
                </table-report>
            </loading-view>
        </card>
    </div>
</template>

