@extends('components.appconfig') <!-- Extending the appconfig layout -->

@section('content')

<style>
    .avatar-placeholder {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background-color: #f0f0f0;
        font-size: 24px;
        color: #333;
    }
</style>

<section>
    <div class="container py-5">
        <div class="d-flex animate__animated animate__zoomIn mb-5">
            @if(session('error'))
            <div class="alert alert-danger mb-0 me-3" id="error-message">
                {{ session('error') }}
            </div>
            @endif

            @if(session('success'))
            <div class="alert alert-success mb-0 me-3" id="success-message">
                {{ session('success') }}
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger mb-0 me-3" id="error-list">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>

        @foreach($employees as $employee)

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-warning justify-content-center">
                        <h5 class="modal-title text-white" id="exampleModalLabel">Update Username or Password</h5>
                    </div>
                    <form action="{{ route('update.employee.info') }}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="row justify-content-center">
                                <div class="form-outline" style=" display:none;">
                                    <input type="hidden" name="employeeid" value="{{$employee->id}}" class="form-control" />
                                    <label class="form-label" for="usernameInput"></label>
                                </div>
                                <div class="row mt-3 mb-4" id="usernameField" style="display: none;">
                                    <div class="form-outline" @if(auth()->user()->role == 5) style="display: none;" @endif>
                                        <input type="text" id="username" name="username" value="{{$employee->username??''}}" class="form-control" />
                                        <label class="form-label" for="usernameInput">Username</label>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="reset_password" value="true" id="resetPassword" />
                                        <label class="form-check-label" for="resetPassword">Reset password</label>
                                    </div>
                                </div>
                                <div class="col-auto" @if(auth()->user()->role == 5) style="display: none;" @endif>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="reset_username" value="true" id="resetUsername" />
                                        <label class="form-check-label" for="resetUsername">Reset username</label>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="form-check" @if(auth()->user()->role == 5) style="display: none;" @endif>
                                        <input class="form-check-input" type="checkbox" name="reset_all" value="true" id="resetAll" />
                                        <label class="form-check-label" for="resetAll">Reset All</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal" id="closeButton">Close</button>
                            <button type="submit" class="btn btn-warning" id="resetPasswordButton" style="display: none;">Reset Password</button>
                            <button type="submit" class="btn btn-warning" id="resetUsernameButton" style="display: none;">Reset Username</button>
                            <button type="submit" class="btn btn-warning" id="resetAllButton" style="display: none;">Reset All</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="row animate__animated animate__zoomIn">
            <div class="col">
                <nav aria-label="breadcrumb" class="bg-body-tertiary rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">User</a></li>
                        <li class="breadcrumb-item active" aria-current="page">User Profile</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row animate__animated animate__zoomIn">
            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-body text-center">
                        @if (!$employee->avatar || !file_exists(public_path('app/' . $employee->avatar)))
                        <div class="avatar-placeholder d-flex align-items-center justify-content-center mx-auto">
                            <span class="orange pop text-warning">LP</span><span class="pop text-primary">A</span>
                        </div>
                        @else
                        <img src="{{ asset('app/' . $employee->avatar) }}"
                            alt="avatar"
                            class="rounded-circle img-fluid"
                            style="width: 130px; height: 130px; cursor: pointer;">
                        @endif
                        <h5 class="my-3">{{ $employee->username ?? 'NA' }}</h5>
                        <p class="text-muted mb-1">{{ $employee->agences->agent_name ?? '' }}</p>
                        <p class="text-muted mb-4">{{ $employee->mobile ?? '' }} / {{ $employee->mobile2 ?? '' }}</p>
                        <div class="d-flex justify-content-center mb-2">
                            <button type="button" class="btn btn-warning" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#exampleModal">
                                Update
                            </button>
                        </div>
                    </div>
                </div>

                <!-- <div class="card mb-4">
                    <div class="card-body text-center">
                        <img src="{{ $employee->avatar ? asset('app/' . $employee->avatar) : 'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp' }}"
                            alt="avatar"
                            style="width: 130px; height:130px; cursor: pointer;"
                            class="rounded-circle img-fluid" style="width: 150px;">
                        <h5 class="my-3">{{$employee->username??'NA'}}</h5>
                        <p class="text-muted mb-1">{{$employee->agences->agent_name??''}}</p>
                        <p class="text-muted mb-4">{{$employee->mobile??''}} / {{$employee->mobile2??''}}</p>
                        <div class="d-flex justify-content-center mb-2">
                            <button type="button" class="btn btn-warning" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#exampleModal">
                                Update
                            </button>
                        </div>
                    </div>
                </div> -->
                <!-- <div class="card mb-4 mb-lg-0">
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush rounded-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <i class="fas fa-globe fa-lg text-warning"></i>
                                <p class="mb-0">https://mdbootstrap.com</p>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <i class="fab fa-github fa-lg text-body"></i>
                                <p class="mb-0">mdbootstrap</p>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <i class="fab fa-twitter fa-lg" style="color: #55acee;"></i>
                                <p class="mb-0">@mdbootstrap</p>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <i class="fab fa-instagram fa-lg" style="color: #ac2bac;"></i>
                                <p class="mb-0">mdbootstrap</p>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                <i class="fab fa-facebook-f fa-lg" style="color: #3b5998;"></i>
                                <p class="mb-0">mdbootstrap</p>
                            </li>
                        </ul>
                    </div>
                </div> -->
            </div>
            <div class="col-lg-8">
                <!-- <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Full Name</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">Johnatan Smith</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Email</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">example@example.com</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Phone</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">(097) 234-5678</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Mobile</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">(098) 765-4321</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Address</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">Bay Area, San Francisco, CA</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Address</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">Bay Area, San Francisco, CA</p>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-3">
                                <p class="mb-0">Address</p>
                            </div>
                            <div class="col-sm-9">
                                <p class="text-muted mb-0">Bay Area, San Francisco, CA</p>
                            </div>
                        </div>
                    </div>
                </div> -->

                <div class="row">
                    <!-- Card 1 -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">First Name</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">{{$employee->firstname??'NA'}}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Last Name</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0"> {{$employee->lastname??'NA'}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Middle Name</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">{{$employee->middle_name??'NA'}}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Email</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">{{$employee->email??'NA'}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">National Id</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">{{$employee->national_id??'NA'}}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Salary</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">${{$employee->net_salary??'00'}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">mobile</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">{{$employee->mobile??'NA'}}</p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Mobile 2</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">{{$employee->mobile2??'NA'}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <!-- Card 3 -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Agence/Ministry</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">{{$employee->agences->agent_name??'NA'}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 4 -->

                </div>
                <!-- <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Address</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">Bay Area, San Francisco, CA</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Address</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0">Bay Area, San Francisco, CA</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-4 mb-md-0">
                            <div class="card-body">
                                <p class="mb-4"><span class="text-primary font-italic me-1">assigment</span> Project Status
                                </p>
                                <p class="mb-1" style="font-size: .77rem;">Web Design</p>
                                <div class="progress rounded" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="80"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mt-4 mb-1" style="font-size: .77rem;">Website Markup</p>
                                <div class="progress rounded" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 72%" aria-valuenow="72"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mt-4 mb-1" style="font-size: .77rem;">One Page</p>
                                <div class="progress rounded" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 89%" aria-valuenow="89"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mt-4 mb-1" style="font-size: .77rem;">Mobile Template</p>
                                <div class="progress rounded" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 55%" aria-valuenow="55"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mt-4 mb-1" style="font-size: .77rem;">Backend API</p>
                                <div class="progress rounded mb-2" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 66%" aria-valuenow="66"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card mb-4 mb-md-0">
                            <div class="card-body">
                                <p class="mb-4"><span class="text-primary font-italic me-1">assigment</span> Project Status
                                </p>
                                <p class="mb-1" style="font-size: .77rem;">Web Design</p>
                                <div class="progress rounded" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 80%" aria-valuenow="80"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mt-4 mb-1" style="font-size: .77rem;">Website Markup</p>
                                <div class="progress rounded" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 72%" aria-valuenow="72"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mt-4 mb-1" style="font-size: .77rem;">One Page</p>
                                <div class="progress rounded" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 89%" aria-valuenow="89"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mt-4 mb-1" style="font-size: .77rem;">Mobile Template</p>
                                <div class="progress rounded" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 55%" aria-valuenow="55"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <p class="mt-4 mb-1" style="font-size: .77rem;">Backend API</p>
                                <div class="progress rounded mb-2" style="height: 5px;">
                                    <div class="progress-bar" role="progressbar" style="width: 66%" aria-valuenow="66"
                                        aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>
        @endforeach
    </div>
    <script>
        // Récupération des éléments
        const resetPasswordCheckbox = document.getElementById('resetPassword');
        const resetUsernameCheckbox = document.getElementById('resetUsername');
        const resetAllCheckbox = document.getElementById('resetAll');
        const usernameField = document.getElementById('usernameField');
        const closeButton = document.getElementById('closeButton');

        // recuperation des boutton a afficher
        const resetPasswordButton = document.getElementById('resetPasswordButton');
        const resetUsernameButton = document.getElementById('resetUsernameButton');
        const resetAllButton = document.getElementById('resetAllButton');

        // Fonction pour désactiver les autres cases
        function toggleCheckboxes(activeCheckbox) {
            const checkboxes = [resetPasswordCheckbox, resetUsernameCheckbox, resetAllCheckbox];
            checkboxes.forEach((checkbox) => {
                if (checkbox !== activeCheckbox) {
                    checkbox.disabled = activeCheckbox.checked;
                }
            });
        }

        // Fonction pour afficher ou masquer le champ Username
        function toggleUsernameField() {
            if (resetUsernameCheckbox.checked || resetAllCheckbox.checked) {
                usernameField.style.display = 'block';
            } else {
                usernameField.style.display = 'none';
            }
        }

        // Ajout des écouteurs d'événements
        resetPasswordCheckbox.addEventListener('change', () => toggleCheckboxes(resetPasswordCheckbox));
        resetUsernameCheckbox.addEventListener('change', () => {
            toggleCheckboxes(resetUsernameCheckbox);
            toggleUsernameField();
        });
        resetAllCheckbox.addEventListener('change', () => {
            toggleCheckboxes(resetAllCheckbox);
            toggleUsernameField();
        });

        // renitialisation


        // Récupération des éléments



        // Fonction pour réinitialiser les champs et les cases cochées
        function resetForm() {
            // Réinitialiser les cases à cocher
            resetPasswordCheckbox.checked = false;
            resetUsernameCheckbox.checked = false;
            resetAllCheckbox.checked = false;

            // Réactiver toutes les cases à cocher
            resetPasswordCheckbox.disabled = false;
            resetUsernameCheckbox.disabled = false;
            resetAllCheckbox.disabled = false;

            // Masquer le champ Username
            usernameField.style.display = 'none';
            updateButtonsVisibility();
        }

        // Ajout d'un écouteur d'événement au bouton "Close"
        closeButton.addEventListener('click', resetForm);


        //affichage des boutton

        function updateButtonsVisibility() {
            // Masquer tous les boutons par défaut
            resetPasswordButton.style.display = 'none';
            resetUsernameButton.style.display = 'none';
            resetAllButton.style.display = 'none';

            // Afficher le bouton correspondant à la case cochée
            if (resetPasswordCheckbox.checked) {
                resetPasswordButton.style.display = 'block';
            }
            if (resetUsernameCheckbox.checked) {
                resetUsernameButton.style.display = 'block';
            }
            if (resetAllCheckbox.checked) {
                resetAllButton.style.display = 'block';
            }
        }

        // Ajouter des écouteurs d'événement pour chaque case à cocher
        resetPasswordCheckbox.addEventListener('change', updateButtonsVisibility);
        resetUsernameCheckbox.addEventListener('change', updateButtonsVisibility);
        resetAllCheckbox.addEventListener('change', updateButtonsVisibility);
    </script>

</section>
@endsection