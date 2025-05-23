import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});


        // window.Echo = new Echo({
        //     broadcaster: 'pusher',
        //     key: import.meta.env.VITE_PUSHER_APP_KEY,
        //     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        //     wsHost: 'dde0-157-66-170-88.ngrok-free.app',
        //     wsPort: 443,
        //     wssPort: 443,
        //     forceTLS: true,
        //     encrypted: true,
        //     disableStats: true,
        //     enabledTransports: ['ws', 'wss']
        // });
