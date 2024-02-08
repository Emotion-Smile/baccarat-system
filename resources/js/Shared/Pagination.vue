<template>
    <div 
        v-if="total > 0"
        class="flex items-center justify-between px-4 py-3 bg-white sm:px-6 xl:py-6"
    >
        <div class="flex justify-between flex-1 sm:hidden">
            <a href="#"
                class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                {{ __('previous') }}
            </a>
            <a href="#"
                class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                {{ __('next') }}
            </a>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    {{ __('showing') }}
                    {{ ' ' }}
                    <span class="font-medium">{{ from }}</span>
                    {{ ' ' }}
                    {{ __('to') }}
                    {{ ' ' }}
                    <span class="font-medium">{{ to }}</span>
                    {{ ' ' }}
                    {{ __('of') }}
                    {{ ' ' }}
                    <span class="font-medium">{{ total }}</span>
                    {{ ' ' }}
                    {{ __('results') }}
                </p>
            </div>
            <div>
                <nav class="relative z-0 inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                    <PaginationButton 
                        v-for="(item, key) in links"
                        :key="key"
                        :page="item"
                    />
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
import PaginationButton from '@/Shared/PaginationButton'

export default {
    name: "Pagination",

    props: {
        from: {
            type: Number,
            required: true
        },

        to: {
            type: Number,
            required: true
        },

        total: {
            type: Number,
            required: true
        },

        links: {
            type: Array,
            required: true
        }
    }, 
    
    components: {
        PaginationButton
    }
}
</script>