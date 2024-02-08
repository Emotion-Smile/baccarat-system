<script setup>
import {reactive, ref} from 'vue';
import Table from '@/Pages/DragonTiger/Components/FormKits/Table.vue';
import { useUserStore } from '@/Stores/DragonTigers/UserStore';
import {Inertia} from '@inertiajs/inertia';

const activeTab = ref('current-bet');
const switchTab = (tab) => {
    activeTab.value = tab;
    Inertia.reload({
        only: [activeTab.value !== 'current-bet'? 'todayTickets': 'outstandingTickets'],
        onSuccess: () =>{
            userStore.loadTodayTickets();
        }});
};
const getTabClass = (tab) =>
    activeTab.value === tab ? 'text-[#cd943b]' : 'text-white';

const headers = reactive([
    { title: 'G.#', key: 'fight', attrs: { class: ['w-1/12'] } },
    { title: 'Bet', key: 'bet', attrs: { class: ['w-2/12', 'text-center'] } },
    { title: 'Win/loss', key: 'winLoss', attrs: { class: ['w-3/12'] } },
    { title: 'Time', key: 'time', attrs: { class: ['w-3/12', 'text-center'] } },
    { title: 'Status', key: 'status', attrs: { class: ['w-2/12', 'text-center'] }, },
    // {
    //     title: 'Ticket',
    //     key: 'ticket',
    //     attrs: { class: ['w-1/12', 'text-right'] },
    // },
]);

const userStore = useUserStore();

const getResultTextColorClass = (betValue) => {
    switch (betValue) {
    case 'win':
        return 'text-[#4099de]';
    case 'cancel':
        return 'text-gray-500';
    case undefined:
        return;
    default:
        return 'text-[#e74444]';
    }
};

</script>

<template>
    <div
        class="mt-3 h-44 overflow-hidden rounded-md bg-black/10 2xl:h-[220px] 3xl:h-[15.3rem]"
    >
        <div class="flex h-8 flex-none items-stretch">
            <div class="bg-navbar flex w-full items-stretch">
                <div
                    :class="[ getTabClass('current-bet'), 'flex h-full w-full flex-1 cursor-pointer items-center justify-center text-xs 2xl:text-base']"
                    @click="switchTab('current-bet')"
                >
                    Current Bet
                </div>
                <div
                    :class="[
                        getTabClass('today-ticket'),
                        'flex h-full w-full flex-1 cursor-pointer items-center justify-center text-xs 2xl:text-base',
                    ]"
                    @click="switchTab('today-ticket')"
                >
                    Bet History
                </div>
            </div>
        </div>

        <transition name="fade" mode="out-in">
            <div
                :key="activeTab"
                class="h-full flex-1 divide-y divide-white/50 pb-14"
            >
                <Table :headers="headers" :data="userStore.getUserBetRecords(activeTab)">
                    <template #bet="{ row }">
                        <div
                            class="w-2/12 text-center font-rodfat font-black capitalize"
                        >
                            {{ row.bet }}
                        </div>
                    </template>

                    <template #winLoss="{ row }">
                        <div class="w-3/12" :class="getResultTextColorClass(row.ticketResult)"
                        >
                            {{ row.winLoss }}
                        </div>
                    </template>

                    <template #time="{ row }">
                        <div class="w-3/12 text-center">{{ row.time }}</div>
                    </template>

                    <template #status="{ row }">
                        <div class="w-2/12 text-center uppercase">
                            {{ row.status }}
                        </div>
                    </template>

                    <!--                    <template #ticket="{ row }">-->
                    <!--                        <div class="w-1/12 text-right">Print</div>-->
                    <!--                    </template>-->
                </Table>
            </div>
        </transition>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.5s;
}

.fade-enter,
.fade-leave-to {
    opacity: 0;
}
</style>
