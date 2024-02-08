<template>
    <panel-item :field="formattedField" />
</template>

<script>
import numeral from 'numeral'

export default {
    props: ['resource', 'resourceName', 'resourceId', 'field'],

    mounted() {
        this.formattedField.value = numeral(this.field.value).format('0,0[.]00')
    },

    data() {
        return {
            formattedField: this.field
        }
    }
}
</script>
