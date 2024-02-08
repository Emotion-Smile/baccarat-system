<script setup>
defineOptions({
    inheritAttrs: false
});
</script>

<template>
    <div
        class="flex flex-1 flex-col items-center justify-center gap-1"
    >
        <div
            v-bind="$attrs"
            class="w-20 -translate-x-[50%] transition-all duration-500 ease-in-out md:w-[5.5rem] lg:w-36 2xl:w-40 2xl:-translate-x-[70%]"
        >
            <slot />
        </div>
    </div>
</template>

<style scoped>

</style>
