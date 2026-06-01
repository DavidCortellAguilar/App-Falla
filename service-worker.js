self.addEventListener('push', function (event) {
    const data = event.data ? event.data.json() : {};

    const title = data.title || 'Falla App';
    const options = {
        body: data.body || 'Tienes una nueva notificación',
        icon: '/img/icon-192.png',
        badge: '/img/icon-192.png',
        data: {
            url: data.url || '/'
        }
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    const url = event.notification.data.url || '/';

    event.waitUntil(
        clients.openWindow(url)
    );
});