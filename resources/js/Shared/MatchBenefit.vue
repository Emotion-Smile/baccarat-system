<template>
    <div class="grid h-full grid-cols-2 gap-3 overflow-hidden lg:flex-1">
        <div class="h-full bg-white">
            <div class="py-2 text-center text-white uppercase bg-black">
                If meron win
            </div>
            <div class="p-3">
                <div class="py-3 text-2xl font-medium text-center text-white uppercase bg-meron">
                    {{ benefit.meron_benefit }}
                </div>

            </div>
        </div>
        <div class="h-full bg-white">
            <div class="py-2  text-center text-white uppercase bg-black">
                If wala win
            </div>
            <div class="p-3">
                <div class="py-3 text-2xl font-medium text-center text-white uppercase bg-wala">
                    {{ benefit.wala_benefit }}
                </div>

                <div class="text-xl font-black"></div>

            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "MatchBenefit",
    props: {
        benefit: Object
    }
}
</script>

<style scoped>

</style>
