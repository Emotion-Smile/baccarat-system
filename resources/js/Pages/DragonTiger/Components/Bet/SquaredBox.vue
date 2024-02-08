<script setup>
defineEmits(['click']);
</script>

<template>
    <div
        class="border-2 pt-3"
        v-bind="$attrs"
        @click.prevent="$emit('click')"
    >
        <div class="relative">
            <div class="flex w-full px-3">
                <slot name="small-box-title" />
            </div>
            <slot name="icon" />
        </div>
        <slot name="title" />
        <slot name="coin" />
        <slot name="card" />
    </div>
</template>

<style scoped>

</style>
