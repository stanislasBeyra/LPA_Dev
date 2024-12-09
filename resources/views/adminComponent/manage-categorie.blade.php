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
            ADD CATEGORIES
        </button>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryLabel">Add a New CATEGORY</h5>
                </div>
                <form method="POST" action="{{ route('categories.save') }}">
                    @csrf
                    <div class="modal-body">


                        <!-- Input pour le nom de la catégorie -->
                        <div class="form-outline mb-4">
                            <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required />
                            <label class="form-label" for="name">Category Name</label>
                            @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Input pour la description de la catégorie -->
                        <div class="form-outline mb-4">
                            <input type="text" id="categoryDescription" name="description" class="form-control @error('description') is-invalid @enderror" value="{{ old('description') }}" />
                            <label class="form-label" for="categoryDescription">Category Description</label>
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
                <div class="d-flex justify-content-between align-items-center">
                  
                    <h5 class="mb-0 text-center">
                        <strong>Categories</strong>
                    </h5>

                    <div class="input-group " style="width: 30%;">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="search" id="form1" class="form-control" placeholder="Search Roles" />
                            <label class="form-label" for="form1">Search</label>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">Creation Date</th>
                                <th scope="col">Category Name</th>
                                <th scope="col">Category Description</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->created_at->format('m/d/Y, h:i:s A') }}</td> <!-- Date de création formatée -->
                                <td>{{ $category->categories_name }}</td> <!-- Nom de la catégorie -->
                                <td>{{ $category->categories_description }}</td> <!-- Description de la catégorie -->
                                <td class="text-center">
                                    <button type="button" data-mdb-button-init data-mdb-ripple-init
                                        class="btn btn-outline-primary btn-sm"
                                        data-mdb-modal-init data-mdb-target="#staticBackdrop1"
                                        data-id="{{ $category->id }}"
                                        data-name="{{ $category->categories_name }}"
                                        data-description="{{ $category->categories_description }}"
                                        onclick="populateEditModal(this)">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        data-mdb-modal-init data-mdb-target="#exampleModal1"
                                        onclick="setCategoryId('{{ $category->id }}')">
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


<div class="modal top fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger d-flex justify-content-center w-100">
                <h5 class="modal-title text-white text-center" id="deleteConfirmationModalLabel">Delete Confirmation</h5>
            </div>

            <form id="deleteForm" action="{{ route('delete.categorie') }}" method="POST">
                @csrf
                <input type="hidden" id="categoryId" name="categoryId">
                <div class="modal-body text-center">
                    <i class="fas fa-trash-alt mb-3 text-danger" style="font-size: 3rem;"></i>
                    <p>Are you sure you want to delete this category? This action cannot be undone.</p>
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
                <h5 class="modal-title" id="exampleModalLabel1">Edit category</h5>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('update.Categorie') }}">
                    @csrf
                    <input type="hidden" id="editCategoryId" name="categoryid" />

                    <!-- Nom de la catégorie -->
                    <div class="form-outline mb-4">
                        <input type="text" id="editCategoryName" name="categories_name" class="form-control" required />
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

<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> -->


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

<script>
    $(document).ready(function() {
        $('#form1').on('keyup', function() {
            let searchQuery = $(this).val();

            console.log("value:::",searchQuery)

            // Effectuer une requête AJAX
            $.ajax({
                url: "{{ route('search.category') }}",
                type: "GET",
                data: {
                    search: searchQuery
                },
                success: function(response) {
                    // Vider le tableau
                    $('tbody').empty();

                    if (response.categories.length > 0) {
                        response.categories.forEach(categorie => {
                            

                            $('tbody').append(`
                            <tr>
                                <td>${new Date(categorie.created_at).toLocaleDateString()}</td>
                                <td>${categorie.categories_name}</td>
                                <td>${categorie.categories_description}</td>
                                <td class="text-center">
                                    <button type="button"
                                        data-mdb-button-init 
                                        data-mdb-ripple-init
                                        class="btn btn-outline-primary btn-sm edit-role"
                                        data-mdb-modal-init 
                                        data-mdb-target="#staticBackdrop1"
                                        data-id="${categorie.id}"
                                        data-name="${categorie.categories_name}"
                                        data-description="${categorie.categories_description}"
                                    >
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </td>
                            </tr>
                        `);
                        });

                        // Réattacher l'événement de clic après avoir ajouté les nouveaux boutons
                        attachEditModalEvent();
                    } else {
                        $('tbody').append('<tr><td colspan="4" class="text-center">No results found</td></tr>');
                    }
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        });

        // Fonction pour attacher l'événement de clic sur les boutons d'édition
    function attachEditModalEvent() {
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
    }

    // Appeler initialement pour attacher les événements sur les boutons existants
    attachEditModalEvent();

    });
</script>
@endsection