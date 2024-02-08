<template>
    <div 
        class="inline-flex items-center justify-center w-10 h-10 border rounded-full"
        :class="borderClass"
    >
        <icon
          type="arrow"
          width="22"
          height="22"
          viewBox="0 0 20 20"
          :class="iconClass"
        />
    </div>
</template>

<script>
export default {
    props: {
        isWithdraw: {
            type: Boolean,
            default: false
        }
    },

    computed: {
        borderClass() {
            return this.isWithdraw  
                ? 'border-danger rotate-45'
                : 'border-primary rotate-225';
        },

        iconClass() {
            return this.isWithdraw 
                ? 'text-danger' 
                : 'text-primary'; 
        }
    }
}
</script>

<style scoped>
    .rotate-45 {
        transform: rotate(45deg);
    }

    .rotate-225 {
        transform: rotate(225deg);
    }
</style>