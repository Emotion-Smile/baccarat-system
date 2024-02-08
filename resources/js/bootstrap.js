// eslint-disable-next-line no-undef
window._ = require('lodash');

// eslint-disable-next-line no-undef
window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';
import mitt from 'mitt';

window.EventBus = mitt();

// eslint-disable-next-line no-undef
window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster: 'pusher',
    // eslint-disable-next-line no-undef
    key: process.env.MIX_PUSHER_APP_KEY,
    // eslint-disable-next-line no-undef
    wsHost: process.env.MIX_PUSHER_HOST,
    // eslint-disable-next-line no-undef
    wsPort: process.env.MIX_PUSHER_PORT,
    // eslint-disable-next-line no-undef
    wssPort: process.env.MIX_PUSHER_PORT,
    forceTLS: false,
    encrypted: true,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});



