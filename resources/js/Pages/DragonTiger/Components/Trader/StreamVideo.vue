<script setup>
import {defineOptions} from 'vue';

defineOptions({
    inheritAttrs: false
});
</script>

<template>
    <div class="aspect-w-16 aspect-h-9 relative bg-dragon-blue">
        <iframe
            class="inserted absolute"
            v-bind="$attrs"
            title="YouTube video player"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            allowfullscreen
        >
        </iframe>
    </div>
</template>

