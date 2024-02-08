<template>
    <Link
        :href="page.url"
        :class="classes"
    >
        <template v-if="isPrevious">
            <span class="sr-only">{{ __('previous') }}</span>
            <ChevronLeftIcon
                class="w-5 h-5"
                aria-hidden="true"
            />
        </template>

        <template v-else-if="isNext">
            <span class="sr-only">{{ __('next') }}</span>
            <ChevronRightIcon
                class="w-5 h-5"
                aria-hidden="true"
            />
        </template>

        <template v-else>
            <span v-html="page.label"></span>
        </template>
    </Link>
</template>

<script>
import {Link} from '@inertiajs/inertia-vue3'
import {ChevronLeftIcon, ChevronRightIcon} from '@heroicons/vue/solid'

export default {
    name: 'PaginationButton',

    components: {
        Link,
        ChevronLeftIcon,
        ChevronRightIcon,
    },

    props: {
        page: {
            type: Object,
            required: true
        }
    },

    computed: {
        classes() {
            return {
                'items-center': true,
                'inline-flex': true,
                'py-2': true,
                'text-sm': true,
                'font-medium': true,
                'bg-white': true,
                'border': true,
                'border-gray-300': true,
                'text-gray-700': this.page.label === '...',
                'text-gray-500': this.page.label === '...',
                'text-white': this.page.active,
                'hover:bg-gray-50': this.page.label !== '...' && !this.page.active,
                'border-[#818E94]': this.page.active,
                'bg-[#818E94]': this.page.active,
                'z-10': this.page.active,
                'rounded-l-md': this.isPrevious,
                'rounded-r-md': this.isNext,
                'px-2': this.isPreviousOrNext,
                'px-4': !this.isPreviousOrNext,
            };
        },

        isPrevious() {
            return this.page.label === '&laquo; Previous';
        },

        isNext() {
            return this.page.label === 'Next &raquo;';
        },

        isPreviousOrNext() {
            return this.isPrevious || this.isNext;
        }
    }
};
</script>
