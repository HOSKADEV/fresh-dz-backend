importScripts("https://js.pusher.com/beams/service-worker.js");

PusherPushNotifications.onNotificationReceived = ({ pushEvent, payload, handleNotification }) => {
  // Send message to all clients (browser windows/tabs)
  self.clients.matchAll().then(clients => {
    clients.forEach(client => {
      client.postMessage({
        type: 'PLAY_NOTIFICATION_SOUND',
        payload: payload
      });
    });
  });
  pushEvent.waitUntil(handleNotification(payload));
};
