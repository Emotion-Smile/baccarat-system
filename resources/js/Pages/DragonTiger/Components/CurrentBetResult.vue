<script setup>
const betting = [
    {
        id: 1,
        fight: '001',
        bet: 'dragon',
        winLose: '1,000 * 0.92: ????',
        time: '09:41:02',
        status: true,
    },
    {
        id: 2,
        fight: '002',
        bet: 'tiger',
        winLose: '1,000 * 0.92: ????',
        time: '09:41:02',
        status: true,
    },
    {
        id: 3,
        fight: '003',
        bet: 'tie',
        winLose: '1,000 * 0.92: ????',
        time: '09:41:02',
        status: false,
    },
    {
        id: 4,
        fight: '004',
        bet: 'dragon',
        winLose: '1,000 * 0.92: ????',
        time: '09:41:02',
        status: true,
    },
    {
        id: 5,
        fight: '005',
        bet: 'tiger',
        winLose: '1,000 * 0.92: ????',
        time: '09:41:02',
        status: false,
    },
    {
        id: 6,
        fight: '006',
        bet: 'tie',
        winLose: '1,000 * 0.92: ????',
        time: '09:41:02',
        status: true,
    },
    {
        id: 7,
        fight: '007',
        bet: 'dragon',
        winLose: '1,000 * 0.92: ????',
        time: '09:41:02',
        status: false,
    },
    {
        id: 8,
        fight: '008',
        bet: 'tiger',
        winLose: '1,000 * 0.92: ????',
        time: '09:41:02',
        status: true,
    },
    {
        id: 9,
        fight: '009',
        bet: 'tie',
        winLose: '1,000 * 0.92: ????',
        time: '09:41:02',
        status: false,
    },
    {
        id: 10,
        fight: '010',
        bet: 'dragon',
        winLose: '1,000 * 0.92: ????',
        time: '09:41:02',
        status: true,
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
</script>

<template>
    <div
        v-for="bet in betting"
        :key="bet.id"
        class="flex items-stretch py-1 px-1 text-[10px] font-bold text-white 2xl:text-xs"
    >
        <div class="w-1/12">{{ bet.fight }}</div>
        <div
            class="w-2/12 text-center font-rodfat font-black capitalize"
            :class="getTextColorClass(bet.bet)"
        >
            {{ bet.bet }}
        </div>
        <div class="w-3/12">{{ bet.winLose }}</div>
        <div class="w-3/12 text-center">{{ bet.time }}</div>
        <div class="w-2/12 text-center uppercase">
            {{ bet.status === true ? "Accept" : "reject" }}
        </div>
        <div class="w-1/12 text-right">Print</div>
    </div>
</template>

<style scoped></style>
