<script>
export default {
    props: {
        label: String,
        currency: String,
        value: Number,
        error: String,
        readonly: false
    },
    name: "KvInput"
}
</script>

<template>
    <div class="flex border-b border-40">
        <div class="px-8 py-6 w-2/5">
            <label for=""
                   class="inline-block text-80 pt-2 leading-tight">
                {{ label }}&nbsp;<span class="text-danger text-sm"> ({{ currency }}) *</span>
            </label>
        </div>

        <div class="py-6 px-8 w-3/5">
            <input
                :readonly="readonly"
                :value="value"
                @input="$emit('input', $event.target.value)"
                type="number"
                class="w-full form-control form-input form-input-bordered">
            <span class="text-danger text-sm">{{ error }}</span>
        </div>
    </div>
</template>

<style scoped>

</style>
