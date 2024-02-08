Nova.booting((Vue, router, store) => {
    Vue.component('KvModal', require('./components/KvModal.vue'))
    Vue.component('KvInput', require('./components/KvInput.vue'))
    Vue.component('index-bet-condition', require('./components/IndexField'))
    Vue.component('detail-bet-condition', require('./components/DetailField'))
    Vue.component('form-bet-condition', require('./components/FormField'))
})
