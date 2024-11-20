<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LPA Login</title>
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <!-- MDB -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.0.0/mdb.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .auth-container {
            max-width: 600px;
            margin: auto;
            border-radius: 10px;
            overflow: hidden;
        }

        .auth-header {
            background-color: #f98c40;
            color: white;
            position: relative;
        }

        .auth-header img {
            max-height: 150px;
        }

        .form-container {
            padding: 2rem;
        }

        /* Password visibility toggle */
        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            padding-right: 40px;
        }

        .password-wrapper .toggle-password {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
            color: #6c757d;
        }

        .password-wrapper .toggle-password:hover {
            color: #495057;
        }

        /* Media Query for Responsive Design */
        @media (max-width: 991.98px) {
            .auth-container {
                width: 90%;
                margin: 20px auto;
            }

            .auth-header img {
                max-height: 100px;
            }

            .form-container {
                padding: 1.5rem;
            }

            .auth-header h5 {
                font-size: 1.2rem;
            }

            .auth-header p {
                font-size: 0.9rem;
            }
        }
    </style>


</head>

<body>
    <div class="d-flex justify-content-center align-items-center min-vh-100 animate__animated animate__zoomIn">
        <div class="auth-container card shadow-lg">
            <div class="row auth-header bg-primary p-4 align-items-center">
                <div class="col-md-6">
                    <h5 class="mb-3">Welcome back!</h5>
                    <p>Log in to continue to Legal Power of Attorney (LPA).</p>

                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ asset('assets/img/profile-img.png') }}" alt="Profile" class="img-fluid">
                </div>
            </div>

            <div class="form-container">
                @if ($errors->any())
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                @endif

                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif

                @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="text" name="username" id="form1Example1" class="form-control @error('username') is-invalid @enderror" />
                        <label class="form-label" for="form1Example1">Username</label>
                        @error('username')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div data-mdb-input-init class="form-outline mb-4 password-wrapper">
                        <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" />
                        <label class="form-label" for="password">Password</label>
                        <span id="togglePassword" class="toggle-password">
                            <i class="fas fa-eye"></i>
                        </span>
                    </div>
                    @error('password')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                    @enderror

                    <div class="row mb-4">
                        <div class="col d-flex justify-content-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="form1Example3" checked />
                                <label class="form-check-label" for="form1Example3">Remember me</label>
                            </div>
                        </div>
                        <div class="col text-end">
                            <a href="#!">Forgot password?</a>
                        </div>
                    </div>

                    <button data-mdb-ripple-init type="submit" class="btn btn-primary btn-block" id="submitButton">
                        <span id="buttonText">Sign in</span>
                        <div id="spinner" class="spinner-border text-light" style="display: none; width: 1.5rem; height: 1.5rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </button>

                </form>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.0.0/mdb.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.1/mdb.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/8.0.0/mdb.umd.min.js"></script>
    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        togglePassword.addEventListener('click', function() {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            this.innerHTML = type === 'password' ?
                '<i class="fas fa-eye"></i>' :
                '<i class="fas fa-eye-slash"></i>';
        });

        const submitButton = document.getElementById('submitButton');
        const spinner = document.getElementById('spinner');
        const buttonText = document.getElementById('buttonText');

        submitButton.addEventListener('click', function(event) {
            buttonText.style.display = 'none';
            spinner.style.display = 'inline-block';


        });
    </script>
</body>

</html>