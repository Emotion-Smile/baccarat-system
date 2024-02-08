<template>
    <div class="flex items-center">
        <div class="flex items-center mr-3">
            <date-time-picker 
                :value="value"
                :enableTime="false"
                :enableSeconds="false"
                dateFormat="Y-m-d"
                altFormat="Y-m-d"
                :altInput="false"
                :allowInput="false"
                :placeholder="placeholder"
                @change="handleDateChange"
                class="w-full form-control form-input form-input-bordered"
            />
        </div>

        <div class="flex-no-shrink ml-auto">
            <button 
                type="button" 
                class="btn btn-default btn-primary" 
                dusk="filter-button"
                :disabled="disableSubmit"
                @click.prevent="oSubmit"
            >Submit</button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'DatePicker',

    props: {
        value: {
            required: false,
        },

        placeholder: {
            type: String,
            default: () => {
                return moment().format('YYYY-MM-DD')
            }
        }
    },

    methods: {
        oSubmit() {
            this.$emit('change', this.value);
        },

        handleDateChange(value) {
            this.value = value;
        }
    },

    computed: {
        disableSubmit() {
            return !this.value;
        }
    }
}
</script>