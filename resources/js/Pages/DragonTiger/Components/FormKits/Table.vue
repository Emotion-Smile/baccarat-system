<script setup>
import { computed } from "vue";

const props = defineProps({
    headers: {
        type: Array,
        required: true,
    },
    data: {
        type: Array,
        default: () => [],
    },
});
const keys = computed(() => props.headers.map((item) => item.key));
</script>

<template>
    <div
        class="flex flex-none items-stretch bg-black py-1 px-1 text-[10px] font-bold uppercase text-white 2xl:text-sm"
    >
        <div
            v-for="(header, index) in headers"
            :key="index"
            v-bind="header.attrs"
        >
            {{ header.title }}
        </div>
    </div>
    <div
        class="h-full flex-1 divide-y divide-black/50 overflow-y-auto bg-white px-1 pb-14"
    >
        <div
            v-for="(item, i) in data"
            :key="i"
            class="flex items-stretch py-2 px-1 text-[10px] font-bold text-gray-800 2xl:text-xs"
        >
            <template v-for="(key, index) in keys" :key="index">
                <slot :name="key" :row="{ ...item }">
                    <div class="w-1/12" v-bind="item.attrs">
                        {{ item[key] }}
                    </div>
                </slot>
            </template>
        </div>
    </div>
</template>

<style scoped></style>
