<script setup>
const results = [
    {
        id: 1,
        fight: '001',
        bet: 'dragon',
        winLose: 'cancel',
        time: '09:41:02',
        status: true,
        result: 'cancel',
    },
    {
        id: 2,
        fight: '002',
        bet: 'tiger',
        winLose: '1,000 * 0.92: ????',
        time: '09:41:02',
        status: true,
        result: 'dragon',
    },
    {
        id: 3,
        fight: '003',
        bet: 'tie',
        winLose: '1,000 * 0.92: ????',
        time: '09:41:02',
        status: false,
        result: 'tiger',
    },
    {
        id: 4,
        fight: '004',
        bet: 'dragon',
        winLose: '1,000 * 0.92: ????',
        time: '09:41:02',
        status: true,
        result: 'tie',
    },
    {
        id: 5,
        fight: '005',
        bet: 'tiger',
        winLose: 'cancel',
        time: '09:41:02',
        status: false,
        result: 'cancel',
    },
];

const getTextColorClass = (betValue) => {
    switch (betValue) {
    case 'dragon':
        return 'text-dragon-red';
    case 'tiger':
        return 'text-dragon-blue';
    default:
        return 'text-dragon-tie';
    }
};
const getResultTextColorClass = (betValue) => {
    switch (betValue) {
    case 'dragon':
        return 'text-dragon-red';
    case 'tiger':
        return 'text-dragon-blue';
    case 'tie':
        return 'text-dragon-tie';
    case 'cancel':
        return 'text-dragon-primary';
    default:
        return 'text-gray-500';
    }
};
</script>

<template>
    <div
        v-for="result in results"
        :key="result.id"
        class="flex items-stretch py-1 px-1 text-[10px] font-bold text-white 2xl:text-xs"
    >
        <div class="w-1/12">{{ result.fight }}</div>
        <div
            class="w-2/12 text-center font-rodfat font-black capitalize"
            :class="getTextColorClass(result.bet)"
        >
            {{ result.bet }}
        </div>
        <div class="w-3/12" :class="getResultTextColorClass(result.result)">
            {{ result.winLose }}
        </div>
        <div class="w-3/12 text-center">{{ result.time }}</div>
        <div class="w-2/12 text-center uppercase">
            {{ result.status === true ? "Accept" : "reject" }}
        </div>
        <div class="w-1/12 text-right">Print</div>
    </div>
</template>

<style scoped></style>
