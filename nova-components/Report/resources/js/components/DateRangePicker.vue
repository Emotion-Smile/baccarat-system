<template>
    <div class="flex items-center">
        <h3 class="text-sm tracking-wide text-80 mr-3">
            From:
        </h3>
        <div class="flex items-center mr-3">
            <input
                type="text"
                ref="fromDatePicker"
                class="w-full form-control form-input form-input-bordered"
                :value="fromDate"
                :disabled="disabled"
                :class="{ '!cursor-not-allowed': disabled }"
            />
        </div>
        <h3 class="text-sm tracking-wide text-80 mr-3">
            To:
        </h3>
        <div class="flex items-center mr-3">
            <input
                type="text"
                ref="toDatePicker"
                class="w-full form-control form-input form-input-bordered"
                :value="toDate"
                :disabled="disabled"
                :class="{ '!cursor-not-allowed': disabled }"
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
    name: 'DateRangePicker',
    
    props: {
        from: {
            required: false,
        },
        
        to: {
            required: false, 
        }
    },

    watch: {
        from: function (newValue, oldValue) {
            if (this.fromDateEl) {
                this.fromDate = newValue;
                this.fromDateEl.setDate(newValue);
            }
        },

        to: function (newValue, oldValue) {
            if (this.toDateEl) {
                this.toDate = newValue;
                this.toDateEl.setDate(newValue);
            }
        },
    },

    data() { 
        return {
            fromDateEl: null,
            toDateEl: null,
            fromDate: this.from,
            toDate: this.to,
            disabled: false
        };
    },

    mounted() {
        this.$nextTick(() => this.createFlatpickr());
    },

    computed: {
        disableSubmit() {
            return !(this.fromDate && this.toDate);
        }
    },

    methods: {
        createFlatpickr() {
            const options = {
                enableTime: false,
                enableSeconds: false,
                dateFormat: 'Y-m-d',
                altFormat: 'Y-m-d',
                altInput: false,
                allowInput: false,
            };

            this.fromDateEl = flatpickr(this.$refs.fromDatePicker, {
                ...options,
                onOpen: this.onFromDateElOpen,
                onClose: this.onFromDateElClose,
                onChange: this.onFromDateElChange, 
            });

            this.toDateEl = flatpickr(this.$refs.toDatePicker, {
                ...options,
                onOpen: this.onToDateElOpen,
                onClose: this.onToDateElClose,
                onChange: this.onToDateElChange, 
            });
        },

        onFromDateElOpen(event) {
            // Nova.$emit('datepicker-opened', event);
        },

        onFromDateElClose(event) {
            this.onFromDateElChange(event);
            // Nova.$emit('datepicker-closed', event);
        },

        onFromDateElChange(event) {
            this.fromDate = this.$refs.fromDatePicker.value;
            this.toDate = null;  

            let currentDate = moment(this.fromDate);
            let futureMonth = moment(currentDate).add(1, 'M');
            let futureMonthEnd = moment(futureMonth).endOf('month');

            if(currentDate.date() != futureMonth.date() && futureMonth.isSame(futureMonthEnd.format('YYYY-MM-DD'))) {
                futureMonth = futureMonth.add(1, 'd');
            }

            this.toDateEl.clear();
            this.toDateEl.set('enable', [
                {
                    from: currentDate.format('YYYY-MM-DD'),
                    to: futureMonth.format('YYYY-MM-DD')
                }
            ]);
        },

        onToDateElOpen(event) {
            // Nova.$emit('datepicker-opened', event);
        },

        onToDateElClose(event) {
            this.onToDateElChange(event);
            // Nova.$emit('datepicker-closed', event);
        },

        onToDateElChange(event) {
            this.toDate = this.$refs.toDatePicker.value;
        },

        oSubmit() {
            this.$emit('change', {
                from: this.fromDate,
                to: this.toDate
            });
        },

        clear() {
            this.fromDateEl.clear();
            this.toDateEl.clear();
        }
    },

    beforeDestroy() {
        this.fromDateEl.destroy();
        this.toDateEl.destroy();
    },
}
</script>

<style scoped>
.\!cursor-not-allowed {
  cursor: not-allowed !important;
}
</style>