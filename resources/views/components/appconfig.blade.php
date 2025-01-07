<!-- resources/views/components/appconfig.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>LPA Dashboard</title>

    <!-- Font Awesome -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"
        rel="stylesheet" />
    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap"
        rel="stylesheet" />
    <!-- MDB -->
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.0.0/mdb.min.css"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-3d"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>




    <style>
        body {
            background-color: #fbfbfb;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            padding-top: 58px;
            width: auto;
            z-index: 600;
            background-color: #ffff;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1), 0 2px 10px rgba(0, 0, 0, 0.1);
        }


        .sidebar .active {
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.16), 0 2px 10px rgba(0, 0, 0, 0.12);
        }

        /* Sidebar sticky */
        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: 0.5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }

        /* Main content layout */
        main {
            padding-left: 240px;
            margin-left: 50px;
        }

        .custom-modal {
            max-width: 50% !important;
            /* Adjust the width as needed */
        }

        /* Offcanvas for small screens */
        @media (max-width: 991.98px) {
            .sidebar {
                width: 70%;
                height: 100%;
                position: fixed;
                top: 0;
                left: -80%;
                transition: 0.3s;
            }

            .sidebar.open {
                left: 0;
            }

            main {
                padding-left: 0;
                margin-left: 0;
            }

            .custom-modal {
                max-width: 100% !important;
                /* Adjust the width as needed */
            }
        }

        .list-group-item.active {
            background-color: #00008B !important;
            color: white !important;
            border: none;
            border-radius: 5px;
        }

        .list-group-item.active,
        .list-group-item:hover {
            background-color: #00008B !important;
            color: white !important;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

        /* Colors */
        .orange {
            color: #00008B;
            font-size: 1rem;
        }

        .navbar-brand span:last-child {
            color: red;
            font-size: 1rem;
        }

        /* Pop animation */
        .pop {
            font-size: 1.5rem;
            display: inline-block;
            animation: popAnimation 0.5s ease-in-out;
        }

        @keyframes popAnimation {
            0% {
                transform: scale(0.5);
                opacity: 0;
            }

            50% {
                transform: scale(1.2);
                opacity: 1;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
</head>

<body>
    <!-- Main Navigation -->
    @include('components.appbar') <!-- Include appbar -->


    <!-- Sidebar -->
    @include('components.sidebar') <!-- Include sidebar -->

    <!-- Main content -->
    <main style="margin-top: 58px;" id="content-area">

        @yield('content') <!-- This is where the page-specific content will be injected -->

    </main>

    <!-- <script type="module">
        // Importer les fonctions nécessaires depuis les SDKs Firebase
        import {
            initializeApp
        } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
        import {
            getMessaging,
            getToken,
            onMessage
        } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-messaging.js";
        import {
            getAnalytics
        } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-analytics.js";

        // Configuration de votre application web Firebase
        const firebaseConfig = {
            apiKey: "AIzaSyAPOFo63-C60VgjxGTKZVdqcS7kTJ78j8A",
            authDomain: "lpadev.firebaseapp.com",
            projectId: "lpadev",
            storageBucket: "lpadev.appspot.com",
            messagingSenderId: "813489438516",
            appId: "1:813489438516:web:03b3c24d683adfdbc76f61",
            measurementId: "G-D78HKCX5P8"
        };

        // Initialiser Firebase
        const app = initializeApp(firebaseConfig);
        const analytics = getAnalytics(app);
        const messaging = getMessaging(app);

        // Enregistrer le Service Worker
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/firebase-messaging-sw.js')
                .then((registration) => {
                    console.log('Service Worker enregistré avec succès:', registration);

                    // Après l'enregistrement du Service Worker, demander la permission
                    Notification.requestPermission().then((permission) => {
                        if (permission === 'granted') {
                            console.log('Notification permission granted.');

                            getToken(messaging, {
                                    vapidKey: 'BIaGTk2wEpWRjpAHqtAxLnshrwY9ovAeSbJlaZe7sioESA2CEnT8a97ITFcoX5xwq9wruYMes4F4ZPliD0Uv80A',
                                    serviceWorkerRegistration: registration
                                })
                                .then((currentToken) => {
                                    if (currentToken) {
                                        // Envoi du token au serveur
                                        fetch('/save-token', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                                },
                                                body: JSON.stringify({
                                                    token: currentToken
                                                })
                                            })
                                            .then(response => response.json())
                                            .then(data => console.log('Token envoyé:', currentToken, ))
                                            .catch(err => console.error('Erreur lors de l\'envoi du token:', err));
                                    } else {
                                        console.log('Aucun token d\'enregistrement disponible.');
                                    }
                                })
                                .catch(err => console.error('Erreur lors de la récupération du token:', err));
                        } else {
                            console.log('Notification permission denied.');
                        }
                    });
                })
                .catch(err => console.error('Erreur lors de l\'enregistrement du Service Worker:', err));
        }

        

        // Gestion des messages reçus
        onMessage(messaging, (payload) => {
            
            console.log('Message reçu :', payload);

            // Vérifier si la notification est présente dans le payload
            if (!payload.notification) {
                console.error('Pas de données de notification dans le payload');
                return;
            }

            const {
                title,
                body,
                icon
            } = payload.notification;

            // Vérifier si le navigateur supporte les notifications
            if (!("Notification" in window)) {
                console.error("Ce navigateur ne supporte pas les notifications desktop");
                return;
            }

            // Vérifier la permission des notifications
            if (Notification.permission === "granted") {
                try {
                    // Créer et afficher directement une nouvelle notification
                    new Notification(title, {
                        body: body,
                        icon: icon,
                        requireInteraction: true
                    });
                } catch (error) {
                    console.error('Erreur lors de la création de la notification:', error);

                    // Fallback : utiliser showNotification via le service worker
                    navigator.serviceWorker.ready.then(registration => {
                        registration.showNotification(title, {
                            body: body,
                            icon: icon,
                            requireInteraction: true
                        }).catch(err => console.error('Erreur showNotification:', err));
                    });
                }
            } else {
                console.warn('Permission de notification non accordée');
            }
        });
    </script> -->


    <!-- JS and other necessary scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.0.0/mdb.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.1/mdb.min.js"></script>
    <!-- MDB -->
    <script
        type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.0.0/mdb.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init();
    </script>

    <script>
        // Toggle sidebar visibility for mobile devices
        const sidebar = document.getElementById('sidebarMenu');
        const toggleButton = document.getElementById('sidebarToggle');

        toggleButton.addEventListener('click', () => {
            sidebar.classList.toggle('open'); // Toggle sidebar visibility on mobile
        });
    </script>

    <script>
        // Fonction pour marquer l'élément actif basé sur l'URL
        document.addEventListener('DOMContentLoaded', function() {
            // Récupérer tous les liens dans la barre de navigation
            const navItems = document.querySelectorAll('.list-group-item');

            // Supprimer la classe 'active' de tous les éléments
            navItems.forEach(item => item.classList.remove('active'));

            // Vérifier l'URL actuelle et ajouter la classe 'active' à l'élément correspondant
            navItems.forEach(item => {
                // Vérifier si le href de l'élément correspond à l'URL actuelle
                if (item.getAttribute('href') === window.location.pathname) {
                    item.classList.add('active');
                }
            });
        });
    </script>

    <!-- login des buton -->

    <script>
        const submitButton = document.getElementById('submitButton');
        const spinner = document.getElementById('spinner');
        const buttonText = document.getElementById('buttonText');

        submitButton.addEventListener('click', function(event) {
            buttonText.style.display = 'none';
            spinner.style.display = 'inline-block';


        });

        const deleteButton = document.getElementById('deleteButton'); // Bouton de suppression
        const spinnerdelete = document.getElementById('deleteSpinner'); // Spinner pour le bouton
        const buttonTextdelete = document.getElementById('deleteButtonText'); // Texte du bouton

        // Ajout de l'événement sur le bouton
        deleteButton.addEventListener('click', function(event) {
            // Affiche le spinner et masque le texte
            buttonTextdelete.style.display = 'none'; // Masque le texte du bouton
            spinnerdelete.style.display = 'inline-block'; // Affiche le spinner
        });

        const eiditButton = document.getElementById('editButton'); // Bouton de suppression
        const spinneredit = document.getElementById('editSpinner'); // Spinner pour le bouton
        const buttonTextedit = document.getElementById('editButtonText'); // Texte du bouton

        // Ajout de l'événement sur le bouton
        eiditButton.addEventListener('click', function(event) {
            // Affiche le spinner et masque le texte
            buttonTextedit.style.display = 'none'; // Masque le texte du bouton
            spinneredit.style.display = 'inline-block'; // Affiche le spinner
        });
    </script>





</body>

</html>