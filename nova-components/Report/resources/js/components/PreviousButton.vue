<template>
    <div class="font-normal text-90 text-2xl mr-3">
        <router-link
            :to="to"
            class="no-underline text-primary font-bold dim"
            data-testid="lens-back"
        >&larr;</router-link>
    </div>
</template>

<script>
export default {
    name: 'PreviousButton',
    
    props: {
        to: {
            type: Object,
            required: true
        }
    }
}
</script>