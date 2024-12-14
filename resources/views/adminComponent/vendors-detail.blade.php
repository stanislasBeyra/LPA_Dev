@extends('components.appconfig')

@section('content')
<div class="container-fluid pt-4">

    @foreach($users as $user)
    <section>
        <div class="container-fluid py-5">
            <div class="d-flex animate__animated animate__zoomIn">
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

            <div class="modal top fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
                <div class="modal-dialog modal-sm">
                    <div class="modal-content">
                        <div class="modal-header bg-warning d-flex justify-content-center w-100">
                            <h5 class="modal-title text-white text-center" id="deleteConfirmationModalLabel">Reset Confirmation</h5>
                        </div>

                        <div class="modal-body d-flex flex-column align-items-center text-center">

                            <i class="fas fa-lock-reset mb-3 text-warning" style="font-size: 3rem;"></i>
                            <p class="d-inline">Are you sure you want to reset the password for this vendor?</p>

                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Close</button>
                            <form action="{{ route('vendor.resetPassword') }}" method="POST">
                                @csrf
                                <input type="hidden" id="userid" name="userid" value="{{$user->id}}">

                                <button type="submit" class="btn btn-warning btn-block" id="editButton">
                                    <span id="editButtonText">Reset</span>
                                    <div id="editSpinner" class="spinner-border text-white" style="display: none; width: 1.5rem; height: 1.5rem;" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </button>

                            </form>

                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal  update usename password -->

            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-warning justify-content-center">
                            <h5 class="modal-title text-white" id="exampleModalLabel">Update Username or Password</h5>
                        </div>
                        <form action="{{ route('update.vendor.info') }}" method="post">
                            @csrf
                            <div class="modal-body">
                                <div class="row justify-content-center">
                                    <div class="form-outline" style=" display:none;">
                                        <input type="hidden" name="vendorsid" value="{{$user->id}}" class="form-control" />
                                        <label class="form-label" for="usernameInput"></label>
                                    </div>
                                    <div class="row mt-3 mb-4" id="usernameField" style="display: none;">
                                        <div class="form-outline" @if(auth()->user()->role == 5) style="display: none;" @endif>
                                            <input type="text" id="username" name="username" value="{{$user->username??''}}" class="form-control" />
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

            <!-- Modal -->

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

            <div class="row">
                <div class="col-lg-4">
                    <!-- Profile Card -->
                    <div class="card mb-4 animate__animated animate__zoomIn">
                        <div class="card-body text-center">
                            <div class="position-relative d-inline-block">
                                <img src="{{ $user->avatar ? asset('app/' . $user->avatar) : 'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp' }}"
                                    alt="avatar"
                                    class="rounded-circle shadow img-fluid clickable-avatar"
                                    style="width: 130px; height:130px; cursor: pointer;"
                                    onclick="document.getElementById('avatar-input').click()">

                                <form action="{{ route('admin.vendor.update-avatar') }}" method="POST" enctype="multipart/form-data" id="avatar-form">
                                    @csrf
                                    <input type="hidden" name="vendor_id" value="{{$user->id}}">
                                    <input type="file"
                                        name="avatar"
                                        id="avatar-input"
                                        style="display: none;"
                                        accept="image/*"
                                        onchange="previewAvatar(event)">

                                    <div id="save-avatar-section" style="display: none;" class="mt-2">
                                        <button type="submit" class="btn shadow btn-outline-success btn-sm" @if(auth()->user()->role == 5) style="display: none;" @endif>
                                            save
                                        </button>
                                        <button type="button" class="btn btn-outline-danger shadow btn-sm ml-2" onclick="cancelAvatarChange()">Cancel</i></button>
                                    </div>
                                </form>
                            </div>

                            <h5 class="my-3">{{$user->username}}</h5>
                            <p class="text-muted mb-1">Vendor</p>
                            <p class="text-muted mb-4">Full Name: Jane Doe</p>
                            <div class="d-flex justify-content-center mb-2">
                                <button type="button" class="btn btn-warning"
                                    data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#exampleModal"
                                    @if(auth()->user()->role == 5) style="display: none;" @endif>
                                    Update
                                </button>
                                <!-- <button type="button" class="btn btn-warning btn-sm" data-mdb-modal-init data-mdb-target="#exampleModal1">Reset Password</button> -->
                            </div>
                        </div>
                    </div>


                    <div class="card mb-6 mb-lg-0 animate__animated animate__zoomIn">
                        <div class="card-body">
                            <h5 class="card-title">Address Details</h5>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Street Address</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->vendor->businessaddress??'NA'}}</p>
                                </div>
                            </div>
                            <hr>
                            <!-- <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Country</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">Country Name</p>
                                </div>
                            </div> -->
                        </div>
                    </div>

                </div>

                <div class="col-lg-8">
                    <!-- Basic Information Card -->
                    <!-- Basic Information Card -->
                    <div class="card mb-4 animate__animated animate__zoomIn">
                        <div class="card-body">
                            <h5 class="card-title">Basic Information</h5>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Full Name</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->firstname}} {{$user->lastname}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Email</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->email}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Vendor Name</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->username}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Username</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->username}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Contact Person Name</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->vendor->contactpersonname??'NA'}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Business Email</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->vendor->businessemail??'NA'}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Phone Number</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->mobile}}</p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Business Details Card -->
                    <!-- Business Details Card -->
                    <div class="card mb-4 animate__animated animate__zoomIn">
                        <div class="card-body">
                            <h5 class="card-title">Business Details</h5>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Business Registration Number</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->vendor->businessregno??'NA'}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Tax ID Number</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->vendor->taxidnumber??'NA'}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Business Category</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->vendor->businesscategory??'NA'}}</p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Address Details Card -->


                    <!-- Banking Details Card -->
                    <!-- Banking Details Card -->
                    <div class="card mb-4 animate__animated animate__zoomIn">
                        <div class="card-body">
                            <h5 class="card-title">Banking Details</h5>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Bank Name 1</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->vendor->bankname1??'NA'}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Bank Account Number 1</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->vendor->bankaccount1??'NA'}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Bank Name 2</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->vendor->bankname2??'NA'}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Bank Account Number 2</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->vendor->bankaccount2??'NA'}}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Account Holder Name</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">{{$user->vendor->accountholdername??'NA'}}</p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Documents Info Card -->


                    <!-- Document Info Card -->



                </div>
            </div>
            <div class="card mb-4 animate__animated animate__zoomIn">
                <div class="card-body">
                    <h5 class="card-title">Document Info</h5>

                    <!-- Business Registration Certificate -->
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <p class="mb-0">Business Registration Certificate</p>
                        </div>
                        <div class="col-sm-9">
                            @php
                            // Récupérer et décoder les fichiers depuis le modèle Vendor
                            $businessCertificates = json_decode($user->vendor->businesscertificate ?? null, true);
                            @endphp

                            @if(!empty($businessCertificates))
                            <!-- Carousel wrapper -->
                            <div id="carouselBasicExample" class="carousel slide carousel-fade" data-mdb-ride="carousel">
                                <!-- Indicators -->
                                <div class="carousel-indicators">
                                    @foreach($businessCertificates as $index => $certificate)
                                    <button
                                        type="button"
                                        data-mdb-target="#carouselBasicExample"
                                        data-mdb-slide-to="{{ $index }}"
                                        class="{{ $index == 0 ? 'active' : '' }}"
                                        aria-current="{{ $index == 0 ? 'true' : 'false' }}"
                                        aria-label="Slide {{ $index + 1 }}"></button>
                                    @endforeach
                                </div>

                                <!-- Inner -->
                                <div class="carousel-inner">
                                    @foreach($businessCertificates as $index => $certificate)
                                    @php
                                    $fileExtension = pathinfo($certificate, PATHINFO_EXTENSION);
                                    $filePath = asset('app/' . $certificate);
                                    @endphp

                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                        @if($fileExtension == 'pdf')
                                        <!-- Affichage PDF -->
                                        <object data="{{ $filePath }}" type="application/pdf" class="d-block w-100" style="height: 500px; object-fit: contain;">
                                            <p>Votre navigateur ne supporte pas l'affichage des fichiers PDF.
                                                <a href="{{ $filePath }}" target="_blank">Téléchargez le fichier</a>.
                                            </p>
                                        </object>
                                        @elseif(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg']))
                                        <!-- Affichage image -->
                                        <img src="{{ $filePath }}" class="d-block w-100" style="height: 600px; width: auto; object-fit: contain;" alt="Business Registration Certificate">
                                        @else
                                        <!-- Si le type de fichier n'est pas supporté -->
                                        <p class="text-muted mb-0">No valid document found</p>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                <!-- Inner -->

                                <!-- Controls -->
                                <button class="carousel-control-prev text-danger " type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">previous</span>
                                </button>
                                <button class="carousel-control-next text-danger" type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">next</span>
                                </button>
                            </div>
                            <!-- Carousel wrapper -->
                            @else
                            <p class="text-muted mb-0">No valid document found</p>
                            @endif
                        </div>
                    </div>

                    <!-- Tax Clearance Certificate -->
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <p class="mb-0">Tax Clearance Certificate</p>
                        </div>
                        <div class="col-sm-9">
                            @php
                            // Décoder les certificats de taxes
                            $taxCertificates = json_decode($user->vendor->taxcertificate ?? null, true);
                            @endphp

                            @if(!empty($taxCertificates))
                            <!-- Carousel wrapper -->
                            <div id="carouselTaxCertificate" class="carousel slide carousel-fade" data-mdb-ride="carousel">
                                <!-- Indicators -->
                                <div class="carousel-indicators">
                                    @foreach($taxCertificates as $index => $certificate)
                                    <button
                                        type="button"
                                        data-mdb-target="#carouselTaxCertificate"
                                        data-mdb-slide-to="{{ $index }}"
                                        class="{{ $index == 0 ? 'active' : '' }}"
                                        aria-current="{{ $index == 0 ? 'true' : 'false' }}"
                                        aria-label="Slide {{ $index + 1 }}"></button>
                                    @endforeach
                                </div>

                                <!-- Inner -->
                                <div class="carousel-inner">
                                    @foreach($taxCertificates as $index => $certificate)
                                    @php
                                    $fileExtension = pathinfo($certificate, PATHINFO_EXTENSION);
                                    $filePath = asset('app/' . $certificate);
                                    @endphp

                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                        @if($fileExtension == 'pdf')
                                        <!-- Affichage PDF -->
                                        <object data="{{ $filePath }}" type="application/pdf" class="d-block w-100" style="height: 500px; object-fit: contain;">
                                            <p>Votre navigateur ne supporte pas l'affichage des fichiers PDF.
                                                <a href="{{ $filePath }}" target="_blank">Téléchargez le fichier</a>.
                                            </p>
                                        </object>
                                        @elseif(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg']))
                                        <!-- Affichage image -->
                                        <img src="{{ $filePath }}" class="d-block w-100" style="height: 600px; width: auto; object-fit: contain;" alt="Tax Clearance Certificate">
                                        @else
                                        <p class="text-muted mb-0">No valid document found</p>

                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                <!-- Inner -->

                                <!-- Controls -->
                                <button class="carousel-control-prev text-danger" type="button" data-mdb-target="#carouselTaxCertificate" data-mdb-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">previous</span>
                                </button>
                                <button class="carousel-control-next text-danger" type="button" data-mdb-target="#carouselTaxCertificate" data-mdb-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">next</span>
                                </button>
                            </div>
                            <!-- Carousel wrapper -->
                            @else
                            <p class="text-muted mb-0">No valid document found</p>

                            @endif
                        </div>
                    </div>

                    <!-- Passport or Government-Issued ID -->
                    <div class="row mb-3">
                        <div class="col-sm-3">
                            <p class="mb-0">Passport or Government-Issued ID</p>
                        </div>
                        <div class="col-sm-9">
                            @php
                            // Décoder les certificats de passeport ou carte d'identité
                            $passportOrID = json_decode($user->vendor->passportorID ?? null, true);
                            @endphp

                            @if(!empty($passportOrID))
                            <!-- Carousel wrapper -->
                            <div id="carouselPassportOrID" class="carousel slide carousel-fade" data-mdb-ride="carousel">
                                <!-- Indicators -->
                                <div class="carousel-indicators">
                                    @foreach($passportOrID as $index => $certificate)
                                    <button
                                        type="button"
                                        data-mdb-target="#carouselPassportOrID"
                                        data-mdb-slide-to="{{ $index }}"
                                        class="{{ $index == 0 ? 'active' : '' }}"
                                        aria-current="{{ $index == 0 ? 'true' : 'false' }}"
                                        aria-label="Slide {{ $index + 1 }}"></button>
                                    @endforeach
                                </div>

                                <!-- Inner -->
                                <div class="carousel-inner">
                                    @foreach($passportOrID as $index => $certificate)
                                    @php
                                    $fileExtension = pathinfo($certificate, PATHINFO_EXTENSION);
                                    $filePath = asset('app/' . $certificate);
                                    @endphp

                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                        @if($fileExtension == 'pdf')
                                        <!-- Affichage PDF -->
                                        <object data="{{ $filePath }}" type="application/pdf" class="d-block w-100" style="height: 500px; object-fit: contain;">
                                            <p>Votre navigateur ne supporte pas l'affichage des fichiers PDF.
                                                <a href="{{ $filePath }}" target="_blank">Téléchargez le fichier</a>.
                                            </p>
                                        </object>
                                        @elseif(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg']))
                                        <!-- Affichage image -->
                                        <img src="{{ $filePath }}" class="d-block w-100" style="height: 600px; width: auto; object-fit: contain;" alt="Passport or Government-Issued ID">
                                        @else
                                        <p class="text-muted mb-0">No valid document found</p>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                <!-- Inner -->

                                <!-- Controls -->
                                <button class="carousel-control-prev text-danger" type="button" data-mdb-target="#carouselPassportOrID" data-mdb-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">previous</span>
                                </button>
                                <button class="carousel-control-next text-danger" type="button" data-mdb-target="#carouselPassportOrID" data-mdb-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">next</span>
                                </button>
                            </div>
                            <!-- Carousel wrapper -->
                            @else
                            <p class="text-muted mb-0">No valid document found</p>

                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
</div>
</section>

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
<script>
    function previewAvatar(event) {
        const input = event.target;
        const image = document.querySelector('.clickable-avatar');
        const saveSection = document.getElementById('save-avatar-section');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                image.src = e.target.result;
                saveSection.style.display = 'block';
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    function cancelAvatarChange() {
        const input = document.getElementById('avatar-input');
        const image = document.querySelector('.clickable-avatar');
        const saveSection = document.getElementById('save-avatar-section');

        // Reset the file input
        input.value = '';

        // Restore original image
        image.src = "{{ $user->avatar ? asset('app/' . $user->avatar) : 'https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp' }}";

        // Hide save section
        saveSection.style.display = 'none';
    }
</script>

<Script>
    const eiditButton = document.getElementById('editButton'); // Bouton de suppression
    const spinneredit = document.getElementById('editSpinner'); // Spinner pour le bouton
    const buttonTextedit = document.getElementById('editButtonText'); // Texte du bouton

    // Ajout de l'événement sur le bouton
    eiditButton.addEventListener('click', function(event) {
        // Affiche le spinner et masque le texte
        buttonTextedit.style.display = 'none'; // Masque le texte du bouton
        spinneredit.style.display = 'inline-block'; // Affiche le spinner
    });
</Script>
@endsection