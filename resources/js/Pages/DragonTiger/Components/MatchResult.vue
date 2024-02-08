<script setup>
import ResultSymbol from '@/Pages/DragonTiger/Components/ResultSymbol.vue';
import BobbleSymbol from '@/Pages/DragonTiger/Components/FormKits/BobbleSymbol.vue';
import { useGameStore } from '@/Stores/DragonTigers/GameStore';
import { onMounted, ref, defineEmits, defineProps} from 'vue';
import { useEventListener } from '@vueuse/core';
defineProps({
    objectKey:{
        type: String,
        default: 'result'
    },
    firstLetter:{
        type: Boolean,
        default: false
    },
    showScoreCount:{
        type: Boolean,
        default: false
    }
});
const emit = defineEmits(['update-result']);
const gameStore = useGameStore();
const matchResultRef = ref(null);
gameStore.$subscribe(()=>{
    if (gameStore.isResultSubmitted){
        autoScrollToTheLeft();
    }
});

useEventListener('visibilitychange', function(){
    if (!document.hidden){
        autoScrollToTheLeft();
    }
});


onMounted(autoScrollToTheLeft);
function autoScrollToTheLeft(){
    if (matchResultRef.value.scrollLeft < matchResultRef.value?.scrollWidth)
    {
        matchResultRef.value.scrollLeft = matchResultRef.value?.scrollWidth;
    }
}

</script>

<template>
    <div class="flex-none">
        <div class="h-[193px] bg-white">
            <div ref="matchResultRef" class="dragon-box-result-table h-full bg-fill scrollbar-hide overflow-auto will-change-scroll">
                <template v-for="(symbols, counter) in gameStore.getGameResultMatrix">
                    <BobbleSymbol
                        v-for="(symbol, index) in symbols"
                        :key="counter+'_'+index"
                        :class="symbol.cssClass"
                        class="cursor-pointer"
                        @click="()=>emit('update-result', symbol)"
                    >
                        <span v-if="symbol[objectKey]" class="uppercase">{{ firstLetter? symbol[objectKey][0] : symbol[objectKey] }}</span>
                    </BobbleSymbol>
                </template>
            </div>
        </div>
    </div>
    <result-symbol v-if="showScoreCount" dragon="1" tiger="10" tie="20" cancel="50" />
</template>

<style scoped></style>
