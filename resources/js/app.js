import './bootstrap';
import navbar from './navbar';
import footer from './footer';

// Inject navbar into the DOM
const navbarContainer = document.getElementById('navbar');
if (navbarContainer) {
    navbarContainer.innerHTML = navbar;
}


// Inject footer into the DOM
const footerContainer = document.getElementById('footer');
if (footerContainer) {
    footerContainer.innerHTML = footer;
}

// Laravel Echo and Pusher setup for real-time messaging
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY || 'your_app_key',
    cluster: process.env.MIX_PUSHER_APP_CLUSTER || 'your_cluster',
    wsHost: window.location.hostname,
    wsPort: 6001,
    forceTLS: false,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});
