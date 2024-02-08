<template>
    <div 
        class="px-3"
        :class="[width]"
    >
        <loading-card :loading="loading">
            <card class="relative mb-6 px-6 py-4 card-panel">
                <div class="flex mb-4">
                    <h3 
                        class="mr-3 text-base text-80 font-bold"
                        :class="[labelClass]"
                    >{{ label }}</h3>
                </div>
                <p 
                    class="flex items-center text-4xl mb-4"
                    :class="[valueClass]"
                >{{ value }}</p>
            </card>
        </loading-card>
    </div>
</template>

<script>
export default {
    props: {
        loading: {
            type: Boolean,
            default: true
        },

        label: {
            type: String,
            required: true
        },

        value: {
            type: String,
            required: true
        },

        width: {
            type: String,
            default: 'w-full'
        },

        labelClass: {
            type: String,
            default: ''
        },

        valueClass: {
            type: String,
            default: ''
        },
    }
}
</script>