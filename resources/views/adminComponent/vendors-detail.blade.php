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
            <!-- Modal -->

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
                            <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp" alt="avatar"
                                class="rounded-circle img-fluid" style="width: 150px;">
                            <h5 class="my-3">{{$user->username}}</h5>
                            <p class="text-muted mb-1">Vendor</p>
                            <p class="text-muted mb-4">Full Name: Jane Doe</p>
                            <div class="d-flex justify-content-center mb-2">
                                <button type="button" class="btn btn-warning btn-sm" data-mdb-modal-init data-mdb-target="#exampleModal1">Reset Password</button>
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
                                        <p class="text-muted mb-0">Aucun document valide trouvé</p>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                <!-- Inner -->

                                <!-- Controls -->
                                <button class="carousel-control-prev text-danger " type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Précédent</span>
                                </button>
                                <button class="carousel-control-next text-danger" type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Suivant</span>
                                </button>
                            </div>
                            <!-- Carousel wrapper -->
                            @else
                            <p class="text-muted mb-0">Aucun document valide trouvé</p>
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
                                        <p class="text-muted mb-0">Aucun document valide trouvé</p>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                <!-- Inner -->

                                <!-- Controls -->
                                <button class="carousel-control-prev text-danger" type="button" data-mdb-target="#carouselTaxCertificate" data-mdb-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Précédent</span>
                                </button>
                                <button class="carousel-control-next text-danger" type="button" data-mdb-target="#carouselTaxCertificate" data-mdb-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Suivant</span>
                                </button>
                            </div>
                            <!-- Carousel wrapper -->
                            @else
                            <p class="text-muted mb-0">Aucun document valide trouvé</p>
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
                                        <p class="text-muted mb-0">Aucun document valide trouvé</p>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                <!-- Inner -->

                                <!-- Controls -->
                                <button class="carousel-control-prev text-danger" type="button" data-mdb-target="#carouselPassportOrID" data-mdb-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Précédent</span>
                                </button>
                                <button class="carousel-control-next text-danger" type="button" data-mdb-target="#carouselPassportOrID" data-mdb-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Suivant</span>
                                </button>
                            </div>
                            <!-- Carousel wrapper -->
                            @else
                            <p class="text-muted mb-0">Aucun document valide trouvé</p>
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