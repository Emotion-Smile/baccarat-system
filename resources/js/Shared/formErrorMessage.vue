<template>
  <span
    v-if="errorMessage"
    class="text-red-500 text-sm"
  >{{ errorMessage }}</span>
</template>

<script>
export default {
    name: 'FormErrorMessage',
    props: {
        errorMessage: {
            type: String,
            default: ''
        }
    }
};
</script>

<style scoped>

</style>
