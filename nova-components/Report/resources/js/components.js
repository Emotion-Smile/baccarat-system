export default {
    addComponents(Vue) {
        Vue.component('date-picker', require('@/components/DatePicker'));
        Vue.component('date-range-picker', require('@/components/DateRangePicker'));
        Vue.component('period-date-picker', require('@/components/PeriodDatePicker'));
        Vue.component('checkbox-calender', require('@/components/CheckboxCalendar'));
        Vue.component('previous-button', require('@/components/PreviousButton'));
        Vue.component('icon-arrow', require('@/components/Icons/Arrow'));
        Vue.component('icon-calendar', require('@/components/Icons/Calendar'));
        Vue.component('icon-transaction', require('@/components/Icons/Transaction'));
        Vue.component('icon-balance', require('@/components/Icons/Balance'));
        Vue.component('icon-book', require('@/components/Icons/Book'));
        Vue.component('table-report', require('@/components/TableReport'));
        Vue.component('no-record-view', require('@/components/NoRecordView'));
        Vue.component('link-icon', require('@/components/LinkIcon'));
    }
}
