<template>
    <div>
        <button type="submit" class="block w-full px-4 py-2 text-sm leading-5 text-left transition text-dropdown-link-text hover:bg-dropdown-link-hover focus:outline-none focus:bg-dropdown-link-hover" v-if="as == 'button'">
            <slot></slot>
        </button>

        <a :href="href" class="block px-4 py-2 text-sm leading-5 transition text-dropdown-link-text hover:bg-dropdown-link-hover focus:outline-none focus:bg-dropdown-link-hover" v-else-if="as =='a'">
            <slot></slot>
        </a>

        <Link :href="href" class="block px-4 py-2 text-sm leading-5 transition text-dropdown-link-text hover:bg-dropdown-link-hover focus:outline-none focus:bg-dropdown-link-hover" v-else>
            <slot></slot>
        </Link>
    </div>
</template>

<script>
    import { defineComponent } from 'vue';
    import { Link } from '@inertiajs/inertia-vue3';

    export default defineComponent({
        components: {
            Link,
        },
        props: ['href', 'as']
    })
</script>
