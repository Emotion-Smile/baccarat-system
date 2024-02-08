<template>
    <Link :href="'/'">
        <img 
            class="transform -translate-y-0" 
            :src="logo ? logo : asset('images/cockfightic.png')"
            alt="logo"
        >
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/inertia-vue3';

defineProps({
    logo: String
});
</script>
