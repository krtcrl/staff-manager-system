import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});

window.Echo.channel('notifications')
    .listen('.NewNotification', (event) => {  // Notice the dot before NewNotification
        console.log("🔔 New Notification:", event.message);
        alert(event.message);
    });
