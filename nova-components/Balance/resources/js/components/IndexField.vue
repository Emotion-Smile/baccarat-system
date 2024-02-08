<template>
    <span>{{ field.value | numberFormat(field.format) }}</span>
</template>

<script>
import numeral from 'numeral'

export default {
    props: ['resourceName', 'field'],

    filters: {
        numberFormat(number, format) {
            return numeral(number).format('0,0[.]00')
        }
    }
}
</script>
