import {defineStore} from 'pinia';

export const useGlobalStore = defineStore('globalStore', {
    state: () => ({  }),
    getters: {
        hasError:(state)=>Object.keys(state.errors)>0,
        getErrors:(state)=>state.errors
    },
    actions: {
    },
});
