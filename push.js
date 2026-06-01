const publicVapidKey = 'BGFSDiBrq_74MbchkVkpdgci3pbg46BypMTuBytjSDpTqBmQNxJrcrKYEPm4qxspQVFJnX1myy8aurgQP3W_byE';

function urlBase64ToUint8Array(base64String) {
    const padding = '='.repeat((4 - base64String.length % 4) % 4);
    const base64 = (base64String + padding)
        .replace(/-/g, '+')
        .replace(/_/g, '/');

    const rawData = window.atob(base64);
    const outputArray = new Uint8Array(rawData.length);

    for (let i = 0; i < rawData.length; ++i) {
        outputArray[i] = rawData.charCodeAt(i);
    }

    return outputArray;
}

async function activarNotificaciones() {
    if (!('serviceWorker' in navigator)) {
        alert('Tu navegador no soporta Service Workers.');
        return;
    }

    if (!('PushManager' in window)) {
        alert('Tu navegador no soporta notificaciones push.');
        return;
    }

    const permission = await Notification.requestPermission();

    if (permission !== 'granted') {
        alert('No has permitido las notificaciones.');
        return;
    }

    const registration = await navigator.serviceWorker.register('/service-worker.js');

    const subscription = await registration.pushManager.subscribe({
        userVisibleOnly: true,
        applicationServerKey: urlBase64ToUint8Array(publicVapidKey)
    });

    const response = await fetch('/guardar-suscripcion.php', {
        method: 'POST',
        body: JSON.stringify(subscription),
        headers: {
            'Content-Type': 'application/json'
        }
    });

    const result = await response.json();

    if (result.success) {
        alert('Notificaciones activadas correctamente.');
    } else {
        alert('Error al guardar la suscripción.');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const boton = document.getElementById('activarNotificaciones');

    if (boton) {
        boton.addEventListener('click', activarNotificaciones);
    }
});