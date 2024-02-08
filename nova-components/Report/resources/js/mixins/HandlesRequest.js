
import { Minimum, InteractsWithQueryString } from 'laravel-nova';
import { CancelToken, Cancel } from 'axios';

export default {
    mixins: [
        InteractsWithQueryString
    ],
    
    data() {
        return {
            loading: false,
            canceller: null,
        }
    },

    methods: {
        requestData() {
            this.loading = true;
            
            this.$nextTick(() => {
                return Minimum(
                    Nova.request().get(this.requestUrl, {
                        params: this.requestQueryString,
                        cancelToken: new CancelToken(canceller => {
                            this.canceller = canceller
                        }),
                    }),
                    300
                )
                .then(({ data }) => {
                    this.setData(data);
                    this.loading = false;

                    Nova.$emit('resources-loaded');
                })
                .catch(e => {
                    if (e instanceof Cancel) return;
                    this.loading = false;
                    throw e;
                });
            })
        },

        setData(data) {},

        initializeBeforeRequestOnCreated() {},

        watchParamsForRequest() {}
    },

    computed: {

        requestUrl() {
            return;
        },

        requestQueryString() {
            return {};
        },
    },

    async created() {
        this.initializeBeforeRequestOnCreated();

        await this.requestData();

        this.$watch(
            () => this.watchParamsForRequest(),
            () => {
                if (this.canceller !== null) this.canceller();
                this.requestData();
            }
        )

        Nova.$on('refresh-resources', () => {
            this.requestData();
        });
    },

    beforeUnmount() {
        if (this.canceller !== null) this.canceller();
    },
};
