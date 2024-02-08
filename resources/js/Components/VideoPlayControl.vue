<template>
    <button @click.prevent="tooglePlay" class=" inline-flex justify-center py-2 bg-transparent">
        <button class="inline-flex justify-center py-2 bg-transparent">
            <svg v-if="isPaused" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 xl:w-6 xl:h-6 text-white hover:text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
            </svg>

            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 xl:w-6 xl:h-6 text-white hover:text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
            </svg>
        </button>
    </button>
</template> 

<script>
import { ref } from 'vue';

export default {
    props: {
        defaultValue: Boolean
    },

    setup(props, context) {
        const isPaused = ref(props.defaultValue);

        const tooglePlay = () => {
            isPaused.value = !isPaused.value;

            context.emit('playChange');
        };

        return {
            isPaused,
            tooglePlay
        };
    }
}
</script>