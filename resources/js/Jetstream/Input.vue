<template>
    <input
        class="bg-transparent border-gray-300 rounded-md shadow-sm text-label/50 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        ref="input"
    />
</template>

<script>
import { defineComponent } from "vue";
export default defineComponent({
    props: ["modelValue"],
    emits: ["update:modelValue"],
    methods: {
        focus() {
            this.$refs.input.focus();
        },
    },
});
</script>