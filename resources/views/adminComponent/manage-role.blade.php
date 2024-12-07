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
            Add Roles
        </button>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryLabel">Add a New Role</h5>
                </div>
                <form method="POST" action="{{ route('roles.add') }}">
                    @csrf
                    <div class="modal-body">


                        <!-- Input pour le nom de la catégorie -->
                        <div class="form-outline mb-4">
                            <input type="text" id="name" name="role_name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required />
                            <label class="form-label" for="name">Role Name</label>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input pour la description de la catégorie -->
                        <div class="form-outline mb-4">
                            <input type="text" id="categoryDescription" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}" />
                            <label class="form-label" for="categoryDescription">Role Description</label>
                            @error('description')
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
                    <strong>Roles</strong>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">Creation Date</th>
                                <th scope="col">Role Name</th>
                                <th scope="col">Role Description</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($roles as $role)
                            <tr>
                                <td>{{ $role->created_at->format('m/d/Y, h:i:s A')??'AN' }}</td> <!-- Date de création formatée -->
                                <td>{{ $role->role_name }}</td> <!-- Nom de la catégorie -->
                                <td>
                                    {{ substr($role->description, 0, 10) }}...{{ substr($role->description, -10) }}
                                </td>
                                <!-- Description de la catégorie -->
                                <td class="text-center">
                                    <button type="button" data-mdb-button-init data-mdb-ripple-init
                                        class="btn btn-outline-primary btn-sm"
                                        data-mdb-modal-init data-mdb-target="#staticBackdrop1"
                                        data-id="{{ $role->id }}"
                                        data-name="{{ $role->role_name }}"
                                        data-description="{{ $role->description }}"
                                        onclick="populateEditModal(this)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <!-- <button type="button" class="btn btn-danger btn-sm"
                                     data-mdb-modal-init data-mdb-target="#exampleModal1"
                                      onclick="setCategoryId('{{ $role->id }}')">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button> -->
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


<div class="modal top fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger d-flex justify-content-center w-100">
                <h5 class="modal-title text-white text-center" id="deleteConfirmationModalLabel">Delete Confirmation</h5>
            </div>

            <form id="deleteForm" action="{{ route('roles.delete') }}" method="POST">
                @csrf
                <input type="hidden" id="categoryId" name="roleId">
                <div class="modal-body text-center">
                    <i class="fas fa-trash-alt mb-3 text-danger" style="font-size: 3rem;"></i>
                    <p>Are you sure you want to delete this Role? This action cannot be undone.</p>
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


<!-- Modal -->
<div class="modal fade" id="staticBackdrop1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Edit Role</h5>
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
    document.querySelectorAll('[data-mdb-target="#staticBackdrop1"]').forEach(button => {
        button.addEventListener('click', function() {
            // Récupérer les données du bouton
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const description = this.getAttribute('data-description');

            // Remplir les champs du formulaire
            document.getElementById('editCategoryId').value = id;
            document.getElementById('editCategoryName').value = name;
            document.getElementById('editCategoryDescription').value = description;
        });
    });
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