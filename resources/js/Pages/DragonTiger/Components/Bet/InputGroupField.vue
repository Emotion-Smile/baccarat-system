<script setup>
defineEmits(["update:modelValue", "click"]);
defineProps({
    modelValue: {
        type: Number,
        default: 0,
    },
    inputAttrs: {
        type: Object,
        default: () => ({}),
    },
    buttonAttrs: {
        type: Object,
        default: () => ({}),
    },
});
</script>

<template>
    <div class="shadow-shadow-lg flex h-9 rounded-md">
        <div class="relative flex flex-grow items-stretch focus-within:z-10">
            <input
                id="amount"
                v-bind="inputAttrs"
                name="amount"
                placeholder="ENTER AMOUNT"
                :value="modelValue"
                @input="$emit('update:modelValue', $event.target.value)"
            />
        </div>
        <button
            type="button"
            v-bind="buttonAttrs"
            @click.prevent="$emit('click')"
        >
            Clear
        </button>
    </div>
</template>

<style scoped></style>
