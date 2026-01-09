import axios from 'axios';
import Pusher from 'pusher-js';
import Echo from 'laravel-echo';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Initialize Pusher + Echo when bundled via Vite
try {
	// expose Pusher globally for compatibility
	window.Pusher = Pusher;

	// Wait for meta tags placed by Blade to supply keys
	const pusherMeta = document.querySelector('meta[name="pusher-key"]');
	const clusterMeta = document.querySelector('meta[name="pusher-cluster"]');

	const pusherKey = pusherMeta ? pusherMeta.getAttribute('content') : null;
	const pusherCluster = clusterMeta ? clusterMeta.getAttribute('content') : null;

	const pusherHost = document.querySelector('meta[name="pusher-host"]')?.getAttribute('content') || null;
	const pusherPort = document.querySelector('meta[name="pusher-port"]')?.getAttribute('content') || null;
	const pusherScheme = document.querySelector('meta[name="pusher-scheme"]')?.getAttribute('content') || null;

	if (pusherKey) {
		const forceTLS = pusherScheme === 'https' || (pusherCluster && pusherCluster.length > 0 && pusherCluster !== '');

		const echoOptions = {
			broadcaster: 'pusher',
			key: pusherKey,
			forceTLS: !!forceTLS,
			authEndpoint: document.querySelector('meta[name="broadcast-auth-endpoint"]')?.getAttribute('content') || '/broadcasting/auth',
			auth: {
				headers: {
					'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
				}
			},
			disableStats: true
		};

		if (pusherHost) {
			echoOptions.wsHost = pusherHost;
			echoOptions.wsPort = pusherPort ? parseInt(pusherPort, 10) : 6001;
			echoOptions.wssPort = pusherPort ? parseInt(pusherPort, 10) : 6001;
			echoOptions.forceTLS = pusherScheme === 'https';
			echoOptions.enabledTransports = ['ws', 'wss'];
		} else if (pusherCluster) {
			echoOptions.cluster = pusherCluster;
		}
		window.Echo = new Echo(echoOptions);

		// Diagnostic logs: expose Echo and Pusher connection events
		try {
			console.debug('Echo initialized with options', echoOptions);
			const pusherInstance = window.Echo && window.Echo.connector && window.Echo.connector.pusher;
			if (pusherInstance) {
				// Enable pusher debug logging in console
				try { pusherInstance.logToConsole = true; } catch (e) {}

				pusherInstance.connection.bind('connected', () => {
					console.info('Pusher connected:', pusherInstance.connection.socket_id || '(no socket id)');
				});

				pusherInstance.connection.bind('disconnected', () => {
					console.warn('Pusher disconnected');
				});

				pusherInstance.connection.bind('error', (err) => {
					console.error('Pusher connection error:', err);
				});
			} else {
				console.debug('Pusher instance not available on Echo.connector yet');
			}
		} catch (err) {
			console.debug('Error attaching pusher diagnostics', err);
		}
	}
} catch (err) {
	console.debug('Echo bundling/init skipped or failed:', err);
}
