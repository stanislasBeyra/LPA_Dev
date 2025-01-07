<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Pusher</title>
    
    <!-- Ensure CSRF token is present -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://js.pusher.com/7.2/pusher.min.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.0.0/mdb.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">
</head>
<body>
    
    <section>
        <div class="container py-5">
            <div class="row d-flex justify-content-center">
                <div class="col-md-8 col-lg-6 col-xl-4">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center p-3" style="border-top: 4px solid #ffa900;">
                            <h5 class="mb-0">Chat messages</h5>
                            <div class="d-flex flex-row align-items-center">
                                <span class="badge bg-warning me-3">20</span>
                                <i class="fas fa-minus me-3 text-muted fa-xs"></i>
                                <i class="fas fa-comments me-3 text-muted fa-xs"></i>
                                <i class="fas fa-times text-muted fa-xs"></i>
                            </div>
                        </div>
                        <div class="card-body" style="position: relative; height: 400px; overflow-y: auto;">
                            <div id="chat-messages"></div>
                        </div>
                        <div class="card-footer text-muted d-flex justify-content-start align-items-center p-3">
                            <div class="input-group mb-0">
                                <input type="text" id="messageInput" class="form-control" placeholder="Type message" aria-label="Recipient's username" aria-describedby="button-addon2" />
                                <button id="sendEventButton" class="btn btn-warning" type="button" style="padding-top: .55rem;">Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- JS and other necessary scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.0.0/mdb.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init();
    </script>

    <script>
        $(document).ready(function() {
            // Pusher Configuration - REPLACE WITH YOUR ACTUAL VALUES
            const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
                cluster: "{{ env('PUSHER_APP_CLUSTER') }}",
                encrypted: true
            });

            // Abonnement au canal
            const channel = pusher.subscribe('my-channel');

            // Écoute de l'événement "my-event"
            channel.bind('my-event', function(data) {
                console.log(data);
                $('#chat-messages').append(`
                    <div class="d-flex justify-content-between">
                        <p class="small mb-1">${data.message.username}</p>
                        <p class="small mb-1 text-muted">${data.message.date}</p>
                    </div>
                    <div class="d-flex flex-row justify-content-end mb-4 pt-1">
                        <div>
                            <p class="small p-2 me-3 mb-3 text-white rounded-3 bg-warning">${data.message.message}</p>
                        </div>
                        <img src="https://i.pinimg.com/736x/49/5b/05/495b05ecb1d343f70453dee99eebb6f3.jpg" alt="avatar" style="width: 45px; height: 100%;">
                    </div>
                `);
            });

            // Gestion des erreurs de connexion Pusher
            pusher.connection.bind('error', function(err) {
                console.error('Erreur de connexion Pusher:', err);
                alert('Erreur de connexion');
            });

            // Définition de la fonction pour envoyer un événement
            function sendEvent() {
                // Récupérer le token CSRF de manière sécurisée
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                if (!csrfToken) {
                    console.error('CSRF token not found');
                    alert('Erreur de sécurité: Token CSRF manquant');
                    return;
                }

                const message = $('#messageInput').val();

                if (message) {
                    $.ajax({
                        url: "{{ route('send.event') }}",
                        type: "GET",
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        data: {
                            message: message
                        },
                        success: function(response) {
                            console.log(response);
                            $('#messageInput').val(''); // Clear the input field
                        },
                        error: function(xhr, status, error) {
                            console.error('Erreur :', status, error);
                            alert('Erreur lors de l\'envoi du message');
                        }
                    });
                }
            }

            // Ajouter l'événement au bouton
            $('#sendEventButton').on('click', sendEvent);
        });
    </script>

</body>
</html>
