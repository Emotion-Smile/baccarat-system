<template>
    <router-link
        :to="to"
        v-tooltip.click="tip"
        :data-testid="`${icon.type}-button`"
        :dusk="`${icon.type}-button`"
        class="cursor-pointer text-70 hover:text-primary mr-3 inline-flex items-center"
    >
        <span class="inline-flex">
            <icon 
                :type="icon.type" 
                :width="icon.width" 
                :height="icon.height" 
                :viewBox="icon.viewBox"
            />
        </span>
    </router-link>
</template>

<script>
export default {
    name: 'LinkIcon',

    props: {
        to: {
            type: Object,
            requied: true
        },

        tip: {
            type: String,
            default: ''
        },

        icon: {
            type: Object,
            default: {
                type: 'view', 
                width: 22, 
                height: 18,
                viewBox: '0 0 22 16'
            }
        }
    }
}
</script>