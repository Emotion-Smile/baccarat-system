Nova.booting((Vue, router, store) => {
  Vue.component('index-balance', require('./components/IndexField'))
  Vue.component('detail-balance', require('./components/DetailField'))
  Vue.component('form-balance', require('./components/FormField'))
})
