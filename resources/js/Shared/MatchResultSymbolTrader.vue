<template>
    <div class="flex-1 w-full h-40 overflow-auto bg-white">
        <div class="w-full h-full bg-white reglahan scrollbar-hide" ref="resultSymbolContainer">
            <template v-for="symbol in symbols">

                <div v-if="symbol.result === 'wala'" class="entry wala">{{ symbol.fightNumber }}</div>
                <div v-else-if="symbol.result === 'meron'" class="entry meron">{{ symbol.fightNumber }}</div>
                <div v-else-if="symbol.result === 'draw'" class="entry draw">{{ symbol.fightNumber }}</div>
                <div v-else-if="symbol.result === 'cancel'" class="entry cancel">{{ symbol.fightNumber }}</div>
                <div v-else-if="symbol.result === 'pending'" class="entry pending">{{ symbol.fightNumber }}</div>
                <div v-else-if="symbol.result === 'null'" class="null">{{ symbol.fightNumber }}</div>

            </template>
        </div>
    </div>
</template>

<script>
import {onMounted, onUpdated, ref} from "vue";

export default {
    name: "MatchResultSymbolTrader",
    props: {
        symbols: Array
    },
    setup() {

        const resultSymbolContainer = ref(null);

        onUpdated(() => {
            resultSymbolContainer.value.scrollLeft = resultSymbolContainer.value.scrollWidth;
        });

        onMounted(() => {
            resultSymbolContainer.value.scrollLeft = resultSymbolContainer.value.scrollWidth;
        });

        return {
            resultSymbolContainer
        }
    }
}
</script>

<style scoped>

</style>
