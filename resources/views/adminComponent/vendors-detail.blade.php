@extends('components.appconfig')

@section('content')
<div class="container-fluid pt-4">

    <section>
        <div class="container-fluid py-5">

            <!-- Modal -->
            <div class="modal top fade" id="staticBackdrop5" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
                <div class="modal-dialog modal-dialog-centered text-center d-flex justify-content-center">
                    <div class="modal-content w-75">
                        <div class="modal-body p-4">
                            <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp" alt="avatar" class="rounded-circle position-absolute top-0 start-50 translate-middle h-50" />
                            <form>
                                <div>
                                    <h5 class="pt-5 my-3">Jane Doe</h5>

                                    <!-- password input -->
                                    <div data-mdb-input-init class="form-outline mb-4">
                                        <input type="password" id="password1" class="form-control" />
                                        <label class="form-label" for="password1">Current Password</label>
                                    </div>

                                    <div data-mdb-input-init class="form-outline mb-4">
                                        <input type="password" id="password1" class="form-control" />
                                        <label class="form-label" for="password1">New Password</label>
                                    </div>
                                    <div data-mdb-input-init class="form-outline mb-4">
                                        <input type="password" id="password1" class="form-control" />
                                        <label class="form-label" for="password1">Confirm Password</label>
                                    </div>

                                    <!-- Submit button -->
                                    <button type="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary">Change Password</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal -->

            <div class="row">
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
                    <div class="card mb-4">
                        <div class="card-body text-center">
                            <img src="https://mdbcdn.b-cdn.net/img/Photos/new-templates/bootstrap-chat/ava3.webp" alt="avatar"
                                class="rounded-circle img-fluid" style="width: 150px;">
                            <h5 class="my-3">Jane Doe</h5>
                            <p class="text-muted mb-1">Vendor</p>
                            <p class="text-muted mb-4">Full Name: Jane Doe</p>
                            <div class="d-flex justify-content-center mb-2">
                                <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary" data-mdb-modal-init data-mdb-target="#staticBackdrop5">Reset Password</button>
                            </div>
                        </div>
                    </div>
                    <!-- Contact Info Card -->
                    <!-- <div class="card mb-4 mb-lg-0">
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush rounded-3">
                                <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                    <i class="fas fa-globe fa-lg text-warning"></i>
                                    <p class="mb-0">https://example.com</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                    <i class="fab fa-github fa-lg text-body"></i>
                                    <p class="mb-0">githubuser</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                    <i class="fab fa-twitter fa-lg" style="color: #55acee;"></i>
                                    <p class="mb-0">@janedoe</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                    <i class="fab fa-instagram fa-lg" style="color: #ac2bac;"></i>
                                    <p class="mb-0">janedoe_instagram</p>
                                </li>
                                <li class="list-group-item d-flex justify-content-between align-items-center p-3">
                                    <i class="fab fa-facebook-f fa-lg" style="color: #3b5998;"></i>
                                    <p class="mb-0">janedoe_fb</p>
                                </li>
                            </ul>
                        </div>
                    </div> -->

                    <div class="card mb-6 mb-lg-0">
                        <div class="card-body">
                            <h5 class="card-title">Address Details</h5>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Street Address</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">123 Street Name, City</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Country</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">Country Name</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-lg-8">
                    <!-- Basic Information Card -->
                    <!-- Basic Information Card -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Basic Information</h5>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Full Name</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">Jane Doe</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Email</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">janedoe@example.com</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Vendor Name</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">Jane Doe Enterprises</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Username</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">janedoe123</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Contact Person Name</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">John Doe</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Business Email</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">contact@janedoeenterprises.com</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Phone Number</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">+1234567890</p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Business Details Card -->
                    <!-- Business Details Card -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Business Details</h5>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Business Registration Number</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">BR123456789</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Tax ID Number</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">TIN987654321</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Business Category</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">E-commerce</p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Address Details Card -->


                    <!-- Banking Details Card -->
                    <!-- Banking Details Card -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Banking Details</h5>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Bank Name 1</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">Bank ABC</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Bank Account Number 1</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">123456789012</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Bank Name 2</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">Bank XYZ</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Bank Account Number 2</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">987654321098</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <p class="mb-0">Account Holder Name</p>
                                </div>
                                <div class="col-sm-9">
                                    <p class="text-muted mb-0">John Doe</p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Documents Info Card -->
                    <!-- Document Info Card -->
                    <div class="card mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Document Info</h5>

                            <!-- Business Registration Certificate -->
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <p class="mb-0">Business Registration Certificate</p>
                                </div>
                                <div class="col-sm-9">
                                    <!-- Check if it's a PDF or an image -->
                                    @php
                                    $filePath = asset('app/taxcertificate/1732245969_673ff9d1bed00.png');
                                    $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
                                    @endphp

                                    @if($fileExtension == 'pdf')
                                    <!-- Display PDF inline -->
                                    <object data="{{ $filePath }}" type="application/pdf" width="100%" height="500px">
                                        <p>Votre navigateur ne supporte pas l'affichage des fichiers PDF.
                                            <a href="{{ $filePath }}" target="_blank">Téléchargez le fichier</a>.
                                        </p>
                                    </object>
                                    @elseif(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg']))
                                    <!-- Display image inline -->
                                    <img src="{{ $filePath }}" alt="Business Registration Certificate" width="100%" height="auto">
                                    @else
                                    <!-- If it's neither a PDF nor an image -->
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
                                    <!-- Check if it's a PDF, if so display inline -->
                                    <object data="path/to/tax_clearance_certificate.pdf" type="application/pdf" width="100%" height="500px">
                                        <p>Votre navigateur ne supporte pas l'affichage des fichiers PDF. <a href="path/to/tax_clearance_certificate.pdf" target="_blank">Téléchargez le fichier</a>.</p>
                                    </object>
                                    <!-- If no PDF, show text 'Aucun' -->
                                    <p class="text-muted mb-0">Aucun</p>
                                </div>
                            </div>

                            <!-- Passport or Government-Issued ID -->
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <p class="mb-0">Passport or Government-Issued ID</p>
                                </div>
                                <div class="col-sm-9">
                                    <!-- Check if it's a PDF, if so display inline -->
                                    <object data="path/to/passport_or_id.pdf" type="application/pdf" width="100%" height="500px">
                                        <p>Votre navigateur ne supporte pas l'affichage des fichiers PDF. <a href="path/to/passport_or_id.pdf" target="_blank">Téléchargez le fichier</a>.</p>
                                    </object>
                                    <!-- If no PDF, show text 'Aucun' -->
                                    <p class="text-muted mb-0">Aucun</p>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div>
    </section>
</div>
@endsection