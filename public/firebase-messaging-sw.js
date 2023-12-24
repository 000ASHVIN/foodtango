if ("serviceWorker" in navigator) {
    navigator.serviceWorker
        .register("../firebase-messaging-sw.js")
        .then(function (registration) {
            console.log(
                "Registration successful, scope is:",
                registration.scope
            );
        })
        .catch(function (err) {
            console.log("Service worker registration failed, error:", err);
        });
}

importScripts("https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js");
importScripts(
    "https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js"
);

firebase.initializeApp({
    apiKey: "AIzaSyBceikp93Z0LwiAyikKDXzL6qti_MQCvYM",
    authDomain: "foodtango-9ece1.firebaseapp.com",
    projectId: "foodtango-9ece1",
    storageBucket: "foodtango-9ece1.appspot.com",
    messagingSenderId: "629748764581",
    appId: "1:629748764581:web:e7f1c5a32564aba9d4224c",
    measurementId: "G-8BZRE1QQHD",
    //   databaseURL: "...",
});

const messaging = firebase.messaging();

messaging.setBackgroundMessageHandler(function (payload) {
    const promiseChain = clients
        .matchAll({
            type: "window",
            includeUncontrolled: true,
        })
        .then((windowClients) => {
            for (let i = 0; i < windowClients.length; i++) {
                const windowClient = windowClients[i];
                windowClient.postMessage(payload);
            }
        })
        .then(() => {
            const title = payload.notification.title;
            const options = {
                body: payload.notification.score,
            };
            return registration.showNotification(title, options);
        });
    return promiseChain;
});

self.addEventListener("notificationclick", function (event) {
    console.log("notification received: ", event);
});
