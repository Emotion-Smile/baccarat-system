<template>
    <div class="space-y-4 md:hidden">
        <div class="w-auto h-[161px] min-w-full overflow-x-scroll bg-fill ">
            <div class="h-full bg-fill reglahan scrollbar-hide" ref="resultSymbolContainer">
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

        <div class="flex items-center h-auto py-3 rounded bg-fill md:hidden max-h-40 justify-evenly">
            <div class="space-y-2">
                <div
                    class="flex items-center justify-center w-8 h-8 text-sm font-black text-white rounded-full shadow-lg bg-meron">
                    {{ resultCount.meron }}
                </div>
                <div class="text-sm font-black text-match-result-text">
                    Meron
                </div>
            </div>
            <div class="space-y-2">
                <div
                    class="flex items-center justify-center w-8 h-8 text-sm font-black text-white rounded-full shadow-lg bg-wala">
                    {{ resultCount.wala }}
                </div>
                <div class="text-sm font-black text-match-result-text">
                    Wala
                </div>
            </div>
            <div class="space-y-2">
                <div
                    class="flex items-center justify-center w-8 h-8 text-sm font-black text-white rounded-full shadow-lg bg-win">
                    {{ resultCount.draw }}
                </div>
                <div class="text-sm font-black text-match-result-text">
                    {{ __('draw') }}
                </div>
            </div>
            <div class="space-y-2">
                <div
                    class="flex items-center justify-center w-8 h-8 text-sm font-black text-white bg-yellow-500 rounded-full shadow-lg">
                    {{ resultCount.cancel }}
                </div>
                <div class="text-sm font-black text-match-result-text">
                    {{ __('cancel') }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {onMounted, onUpdated, ref} from "vue";

export default {
    name: "MatchResultSymbolMobile",
    props: {
        resultCount: Object,
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
