importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging-compat.js');

firebase.initializeApp({
  apiKey: "AIzaSyAPOFo63-C60VgjxGTKZVdqcS7kTJ78j8A",
  authDomain: "lpadev.firebaseapp.com",
  projectId: "lpadev",
  storageBucket: "lpadev.firebasestorage.app",
  messagingSenderId: "813489438516",
  appId: "1:813489438516:web:03b3c24d683adfdbc76f61",
  measurementId: "G-D78HKCX5P8"
});

const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
  console.log('Message en arrière-plan reçu:', payload);
  
  const notificationTitle = payload.notification.title;
  const notificationOptions = {
    body: payload.notification.body,
    icon: payload.notification.icon
  };

  return self.registration.showNotification(notificationTitle, notificationOptions);
});