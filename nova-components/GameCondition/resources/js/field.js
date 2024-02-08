Nova.booting((Vue, router, store) => {
  Vue.component('index-game-condition', require('./components/IndexField'))
  Vue.component('detail-game-condition', require('./components/DetailField'))
  Vue.component('form-game-condition', require('./components/FormField'))
})
