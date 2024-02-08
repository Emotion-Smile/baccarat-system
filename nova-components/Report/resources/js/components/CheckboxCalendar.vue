<template>
    <div class="flex items-center">
        <input 
          type="checkbox" 
          class="checkbox mr-3"
          v-model="model" 
        />

        <icon
          type="calendar"
          width="22"
          height="18"
          viewBox="0 0 448 512"
        />
    </div>
</template>

<script>
export default {
    name: 'CheckboxCalendar',

    props: {
        value: {
            type: Boolean,
            default: false
        }
    },

    computed: {

      model: {

        get() {
          return this.value
        },

        set(value) {
          this.$emit('input', value)
        }

      }
    }
}
</script>

<style>

</style>