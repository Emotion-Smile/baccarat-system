<template>

    <Head :title="printTitle"/>

    <div class="min-h-screen">
        <div class="container px-4 pt-4 mx-auto xl:top-14 2xl:pt-16">
            <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                <div>
                    <div class="px-3 pt-3 pb-6 border border-gray-400 bg-fill">
                        <h1 class="text-xl font-bold text-center underline uppercase">CockFight Online </h1>
                        <p class="text-center">[{{ ticket.table }}]</p>
                        <div class="py-6 my-6 border-t-2 border-b-2 border-gray-500 border-dashed">
                            <table class="w-full table-fixed">
                                <tbody>
                                <tr>
                                    <td class="w-2/5 py-3 font-medium">Fight #:</td>
                                    <td class="w-3/5 py-3 font-medium">{{ ticket.fight_number }}</td>
                                </tr>
                                <tr>
                                    <td class="w-2/5 py-3 font-medium">Date:</td>
                                    <td class="w-3/5 py-3 font-medium">{{ ticket.time }}</td>
                                </tr>
                                <tr>
                                    <td class="w-2/5 py-3 font-medium">User:</td>
                                    <td class="w-3/5 py-3 font-medium">{{ ticket.user }}</td>
                                </tr>
                                <tr>
                                    <td class="w-2/5 py-3 font-medium">Bet:</td>
                                    <td class="w-3/5 py-3 font-medium">{{ ticket.bet_on }}</td>
                                </tr>
                                <tr>
                                    <td class="w-2/5 py-3 font-medium">Bet Amount:</td>
                                    <td class="w-3/5 py-3 font-medium">
                                        <span class=" text-meron">{{ ticket.currency }}</span>
                                        {{ ticket.amount }}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="w-2/5 py-3 font-medium">Odds:</td>
                                    <td class="w-3/5 py-3 font-medium">{{ ticket.payout_rate }}</td>
                                </tr>
                                <tr>
                                    <td class="w-2/5 py-3 font-medium">Est./Payout:</td>
                                    <td class="w-3/5 py-3 font-medium">
                                        <span class=" text-meron">{{ ticket.currency }}</span>
                                        {{ ticket.payout_est }}
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="flex items-center justify-center mt-8 space-x-6 print-hidden">
                        <!--                        <button @click="cancel" type="button"-->
                        <!--                                class="inline-flex justify-center w-full px-6 py-3.5 text-sm font-medium uppercase bg-transparent border border-black rounded-full hover:bg-black hover:text-white focus:outline-none focus-visible:ring-3.5 focus-visible:ring-offset-2 focus-visible:ring-blue-500">-->
                        <!--                            cancel-->
                        <!--                        </button>-->
                        <button @click="print" type="button"
                                class="inline-flex justify-center w-full px-6 py-3.5 text-sm font-medium text-white uppercase bg-black border border-black rounded-full hover:bg-black focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500">
                            print
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {Head} from '@inertiajs/inertia-vue3';
import {onMounted} from "vue";

const props = defineProps({
    ticket: Object
})

let printTitle = `ticket-${props.ticket.fight_number}`;

onMounted(() => {
    print();
});

function print() {
    window.print();
    window.onafterprint = window.close;
}
</script>

<style scoped>

.print-hidden {

}

@media print {
    .print-hidden {
        display: none
    }
}
</style>
