@extends('components.appconfig') <!-- Extending the appconfig layout -->

@section('content')
<div class="container-fluid pt-4">



    <!-- Section contenant le bouton aligné à droite -->
    <div class="d-flex justify-content-between mb-3">
        <!-- Affichage des messages d'erreur à gauche -->
        <div class="d-flex">
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

        <!-- Bouton à droite -->
        <button type="button" class="btn btn-primary fs-7 animate__animated animate__zoomIn" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addCategoryModal">
            Add Admins
        </button>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryLabel">Add a New Admin</h5>
                </div>
                <form method="POST" action="{{ route('add.admins') }}">
                    @csrf
                    <div class="modal-body">


                        <!-- Input pour le nom de la catégorie -->
                        <div class="form-outline mb-4">
                            <input type="text" id="firstname" name="firstname" class="form-control @error('firstname') is-invalid @enderror" value="{{ old('firstname') }}" required />
                            <label class="form-label" for="name">First Name</label>
                            @error('firstname')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input pour la description de la catégorie -->
                        <div class="form-outline mb-4">
                            <input type="text" id="lastname" name="lastname" class="form-control @error('lastname') is-invalid @enderror" value="{{ old('lastname') }}" />
                            <label class="form-label" for="categoryDescription">Last Name</label>
                            @error('lastname')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-outline mb-4">
                            <input type="text" id="username" name="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username') }}" />
                            <label class="form-label" for="categoryDescription">Username</label>
                            @error('username')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-outline mb-4">
                            <input type="text" id="mobile" name="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ old('mobile') }}" />
                            <label class="form-label" for="categoryDescription">Mobile Number</label>
                            @error('mobile')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-outline mb-4">
                            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" />
                            <label class="form-label" for="categoryDescription">Email Address</label>
                            @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-outline mb-4">
                            <select name="role" id="editUserRole" class="form-select @error('role') is-invalid @enderror" required>
                                <option value="">Select role</option>
                                @foreach($manageadminsroles as $role)
                                <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                                @endforeach
                            </select>
                            @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submitButton">
                            <span id="buttonText">Submit</span>
                            <div id="spinner" class="spinner-border text-light" style="display: none; width: 1.5rem; height: 1.5rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>




    <!-- Section avec tableau -->
    <section class="mb-4">
        <div class="card animate__animated animate__zoomIn">
            <div class="card-header text-center py-3">
                <h5 class="mb-0 text-center">
                    <strong>Admins List</strong>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">Creation Date</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Last Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Mobile</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $admin)
                            <tr>
                                <td>{{ $admin->created_at->format('m/d/Y, h:i:s A')??'AN' }}</td> <!-- Date de création formatée -->
                                <td>{{ $admin->firstname }}</td> <!-- Nom de la catégorie -->
                                <td> {{ $admin->lastname }}</td>
                                <td> {{ $admin->email}}</td>
                                <td> {{ $admin->mobile}}</td>
                                <!-- Description de la catégorie -->
                                <td class="text-center">

                                
                                    <button type="button"
                                        data-mdb-button-init data-mdb-ripple-init
                                        class="btn btn-outline-primary btn-sm"
                                        data-mdb-modal-init data-mdb-target="#staticBackdrop2"
                                        data-admin='@json($admin)'
                                        onclick="populateEditModal(this)">
                                        <i class="fas fa-edit"></i>
                                    </button>

                                    <button type="button" class="btn btn-danger btn-sm"
                                        data-mdb-modal-init data-mdb-target="#exampleModal1"
                                        onclick="setCategoryId('{{ $admin->id }}')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

</div>

<!-- modal de supression -->

<div class="modal top fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger d-flex justify-content-center w-100">
                <h5 class="modal-title text-white text-center" id="deleteConfirmationModalLabel">Delete Confirmation</h5>
            </div>

            <form id="deleteForm" action="{{ route('delete.admin') }}" method="POST">
                @csrf
                <input type="hidden" id="categoryId" name="AdminId">
                <div class="modal-body text-center">
                    <i class="fas fa-trash-alt mb-3 text-danger" style="font-size: 3rem;"></i>
                    <p>Are you sure you want to delete this Admin? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger" id="deleteButton">
                        <span id="deleteButtonText">Delete</span>
                        <div id="deleteSpinner" class="spinner-border text-light" style="display: none; width: 1.5rem; height: 1.5rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<!-- Button trigger modal -->


<!-- Modal -->
<div class="modal fade" id="staticBackdrop2" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
    <div class="modal-dialog d-flex justify-content-center">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel2">Edit Admin</h5>
                <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('update.admins') }}">
                    @csrf
                    <input type="hidden" name="AdminId" id="AdminID">
                    <!-- Name input -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="text" name="firstname" id="FirstName" class="form-control" />
                        <label class="form-label" for="FirstName">First Name</label>
                    </div>

                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="text" name="lastname" id="LastName" class="form-control" />
                        <label class="form-label" for="LastName">Last Name</label>
                    </div>

                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="text" name="username" id="Username" class="form-control" />
                        <label class="form-label" for="Username">User Name</label>
                    </div>

                    <!-- adrees info -->
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="text" name="mobile" id="MobileNumber" class="form-control" />
                        <label class="form-label" for="MobileNumber">Mobile Number</label>
                    </div>
                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="email" name="email" id="AdressEmail" class="form-control" />
                        <label class="form-label" for="AdressEmail">Email address</label>
                    </div>

                    <div class="form-outline mb-4">
                        <select name="role" id="editUserRoles" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="">Select role</option>
                            @foreach($manageadminsroles as $role)
                            <option value="{{ $role->id }}">{{ $role->role_name }}</option>
                            @endforeach
                        </select>
                        @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit button -->
                    <button type="submit" class="btn btn-primary" id="editButton">
                        <span id="editButtonText">Update Admin</span>
                        <div id="editSpinner" class="spinner-border text-light" style="display: none; width: 1.5rem; height: 1.5rem;" role="status">
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
<div class="modal fade" id="staticBackdrop1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Edit category</h5>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('roles.update') }}">
                    @csrf
                    <input type="hidden" id="editCategoryId" name="roleId" />


                    <!-- Nom de la catégorie -->
                    <div class="form-outline mb-4">
                        <input type="text" id="editCategoryName" name="role_name" class="form-control" required />
                        <label class="form-label" for="editCategoryName">Category Name</label>
                    </div>

                    <!-- Description de la catégorie -->
                    <div class="form-outline mb-4">
                        <textarea id="editCategoryDescription" name="description" class="form-control" rows="4" required></textarea>
                        <label class="form-label" for="editCategoryDescription">Category Description</label>
                    </div>

                    <button type="submit" class="btn btn-outline-primary btn-block" id="editButton">
                        <span id="editButtonText">Submit And Edit</span>
                        <div id="editSpinner" class="spinner-border text-primary" style="display: none; width: 1.5rem; height: 1.5rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </button>
                </form>


            </div>
        </div>
    </div>
</div>
<!-- Modal -->

<script>
    function setCategoryId(categoryId) {
        document.getElementById('categoryId').value = categoryId;
    };


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
<!-- recupertaion des info a editer -->

<script>
    function populateEditModal(button) {
        // Récupérer les données de l'employé à partir de l'attribut 'data-user' du bouton
        const admin = JSON.parse(button.getAttribute('data-admin'));
        console.log(admin);

        // Remplir les champs du formulaire avec les informations de l'employé
        document.getElementById('AdminID').value = admin.id;
        document.getElementById('FirstName').value = admin.firstname;
        document.getElementById('LastName').value = admin.lastname;
        document.getElementById('MobileNumber').value = admin.mobile;
        document.getElementById('Username').value = admin.username;
        document.getElementById('AdressEmail').value = admin.email;
        document.getElementById('editUserRoles').value = admin.role;


        // Initialiser les éléments de formulaire après avoir rempli les valeurs
        document.querySelectorAll('.form-outline').forEach((formOutline) => {
            new mdb.Input(formOutline).init();
        });

        // Initialiser le select pour les agences
        new mdb.Select(document.getElementById('editUserRoles')).init();
    }
</script>



<script>
    // Fonction pour masquer les messages après 5 secondes
    window.onload = function() {
        setTimeout(function() {
            // Masquer les messages d'erreur et de succès
            document.getElementById("error-message")?.style.display = "none";
            document.getElementById("success-message")?.style.display = "none";
            document.getElementById("error-list")?.style.display = "none";
        }, 3000); // 5000 ms = 5 secondes
    };
</script>
@endsection