<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page 404</title>

    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.0.0/mdb.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" rel="stylesheet">

    <style>
     
        /* Container to center the content */
        .full-screen-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            padding: 0 20px;
        }

        /* Image styling */
        .img-fluid {
            max-width: 100%;
            height: auto;
        }

        /* Responsive text */
        .h4 {
            font-size: 24px;
            margin-bottom: 30px;
        }

        /* Button styling */
        .btn-primary {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            font-size: 18px;
            border-radius: 5px;
            text-decoration: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }

        /* Media queries for responsiveness */
        @media (max-width: 768px) {
            .h4 {
                font-size: 20px;
            }

            .btn-primary {
                font-size: 16px;
                padding: 8px 16px;
            }
        }

        @media (max-width: 480px) {
            .h4 {
                font-size: 18px;
                margin-bottom: 20px;
            }

            .btn-primary {
                font-size: 14px;
                padding: 6px 12px;
            }
        }
    </style>

</head>

<body>

    <div class="full-screen-container">
        <div class="text-center">
            <!-- 404 Image -->
            <img src="{{ asset('assets/img/404.svg') }}" class="img-fluid mb-4 animate__animated animate__fadeInLeft" alt="404 Not Found">

            <!-- Message Text -->
            <div class="h4 mb-4 animate__animated animate__fadeInRight">
                Oups ! La page que vous recherchez n'existe pas. Pas de panique, vous pouvez revenir à la page principale en cliquant sur le bouton ci-dessous.
            </div>

            <!-- Return Button -->
            <a href="{{ url('/') }}" class="btn btn-primary animate__animated animate__fadeInUp">Retour à la page principale</a>
        </div>
    </div>

    <!-- JS and other necessary scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.0.0/mdb.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.1/mdb.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.0.0/mdb.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script>
        AOS.init();
    </script>
</body>

</html>
