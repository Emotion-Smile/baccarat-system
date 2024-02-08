<template>
    <div
        class="items-center flex-1 hidden h-auto py-3 rounded bg-fill md:flex max-h-24 justify-evenly lg:py-4"
    >
        <div class="space-y-2">
            <div
                class="flex items-center justify-center w-8 h-8 text-sm font-black text-white rounded-full shadow-lg bg-meron"
            >
                {{ resultCount.meron }}
            </div>
            <div class="text-sm font-black text-match-result-text">Meron</div>
        </div>
        <div class="space-y-2">
            <div
                class="flex items-center justify-center w-8 h-8 text-sm font-black text-white rounded-full shadow-lg bg-wala"
            >
                {{ resultCount.wala }}
            </div>
            <div class="text-sm font-black text-match-result-text">Wala</div>
        </div>
        <div class="space-y-2">
            <div
                class="flex items-center justify-center w-8 h-8 text-sm font-black text-white rounded-full shadow-lg bg-win"
            >
                {{ resultCount.draw }}
            </div>
            <div class="text-sm font-black text-match-result-text">
                {{ __("draw") }}
            </div>
        </div>
        <div class="space-y-2">
            <div
                class="flex items-center justify-center w-8 h-8 text-sm font-black text-white bg-yellow-500 rounded-full shadow-lg"
            >
                {{ resultCount.cancel }}
            </div>
            <div class="text-sm font-black text-match-result-text">
                {{ __("cancel") }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "MatchResultCountWeb",
    props: { resultCount: Object },
};
</script>

<style scoped></style>
