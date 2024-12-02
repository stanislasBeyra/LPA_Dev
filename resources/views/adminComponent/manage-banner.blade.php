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
            Add banners
        </button>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryLabel">Add a New Banner</h5>
                </div>
                <form method="POST" action="{{ route('banners.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <!-- <div class="form-outline mb-4">
                            <input type="text" id="name" name="role_name" class="form-control @error('role_name') is-invalid @enderror" value="{{ old('role_name') }}" required />
                            <label class="form-label" for="name">Banner Title</label>
                            @error('role_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-outline mb-4">
                            <textarea id="description" name="description" class="form-control @error('description') is-invalid @enderror" rows="3" required>{{ old('description') }}</textarea>
                            <label class="form-label" for="description">Banner Description</label>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div> -->


                        <!-- Champ pour le téléchargement de l'image -->
                        <div class="form-outline mb-4">
                            <label for="banner_image" class="btn btn-primary">
                                Select Image
                            </label>
                            <input style="border: none;" type="file" id="banner_image"
                                name="banner_image"
                                class="form-control d-none @error('banner_image') is-invalid @enderror"
                                accept="image/*" required onchange="previewImage(event)" />
                            @error('banner_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Card contenant l'aperçu de l'image -->
                        <div class="card d-none" id="Img-card">
                            <img id="image_preview" src="#" alt="Image Preview" style="height: 500px;object-fit:cover;" class="card-img-top img-fluid" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="row">
        @foreach($banners as $banner)
        <div class="col-md-3 mb-4">
            <div class="card">
                <img src="{{ asset('app/public/'.$banner->image_url) }}"
                    class="card-img-top" alt="Fissure in Sandstone" />
                <div class="card-body">
                    <!-- <h5 class="card-title">Card title</h5>
                    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p> -->
                    <a href="#!" class="btn btn-primary" data-mdb-ripple-init>Button</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>




    <!-- Section avec tableau -->
    <!-- <section class="mb-4">
        <div class="card animate__animated animate__zoomIn">
            <div class="card-header text-center py-3">
                <h5 class="mb-0 text-center">
                    <strong>Banner</strong>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">Creation Date</th>
                                <th scope="col">Banner Title</th>
                                <th scope="col">Banner Description</th>
                                <th scope="col" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>jhghjk</td>
                                <td>jhghjk</td>
                                <td>jhghjk</td>
                                <td>jhghjk</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section> -->

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


<script>
    function previewImage(event) {
        const input = event.target;
        const preview = document.getElementById('image_preview');
        const imgCard = document.getElementById('Img-card');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.src = e.target.result;
                imgCard.classList.remove('d-none'); // Affiche la card
            };

            reader.readAsDataURL(input.files[0]);
        } else {
            preview.src = '#';
            imgCard.classList.add('d-none'); // Masque la card si aucune image n'est sélectionnée
        }
    }
</script>

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

@endsection