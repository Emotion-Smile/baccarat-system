<template>
    <div class="grid grid-cols-4 md:grid-cols-2 p-3 md:p-0 bg-white w-full md:w-44">
        <div class="flex flex-col items-center justify-center space-y-1">
            <div
                class="flex items-center justify-center w-8 h-8 text-sm font-black text-white bg-red-500 rounded-full shadow-lg">
                {{ resultCount.meron }}
            </div>
            <div class="text-sm font-black">
                Meron
            </div>
        </div>
        <div class="flex flex-col items-center justify-center space-y-1">
            <div
                class="flex items-center justify-center w-8 h-8 text-sm font-black text-white bg-blue-500 rounded-full shadow-lg">
                {{ resultCount.wala }}
            </div>
            <div class="text-sm font-black">
                Wala
            </div>
        </div>
        <div class="flex flex-col items-center justify-center space-y-1">
            <div
                class="flex items-center justify-center w-8 h-8 text-sm font-black text-white bg-green-500 rounded-full shadow-lg">
                {{ resultCount.draw }}
            </div>
            <div class="text-sm font-black">
                Draw
            </div>
        </div>
        <div class="flex flex-col items-center justify-center space-y-1">
            <div
                class="flex items-center justify-center w-8 h-8 text-sm font-black text-white bg-yellow-500 rounded-full shadow-lg">
                {{ resultCount.cancel }}
            </div>
            <div class="text-sm font-black">
                Cancel
            </div>
        </div>

<!--        <div class="flex flex-col items-center justify-center space-y-1">-->
<!--            <div-->
<!--                class="flex items-center justify-center w-8 h-8 text-sm font-black text-white bg-gray-700 rounded-full shadow-lg">-->
<!--                {{ resultCount.pending }}-->
<!--            </div>-->
<!--            <div class="text-sm font-black">-->
<!--                Pending-->
<!--            </div>-->
<!--        </div>-->
    </div>
</template>

<script>
export default {
    name: "MatchResultCountTrader",
    props: {resultCount: Object}
}
</script>

<style scoped>

</style>
