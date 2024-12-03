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
                    class="card-img-top" alt="Banner Image" />

                <div class="card-body d-flex align-items-center justify-content-between">
                    <div class="form-check form-switch me-3">
                        <input class="form-check-input banner-status-switch"
                            type="checkbox"
                            role="switch"
                            id="bannerSwitch{{ $banner->id }}"
                            data-banner-id="{{ $banner->id }}"
                            {{ $banner->is_active ? 'checked' : '' }} />
                    </div>
                    <button type="button" class="btn btn-danger btn-sm"
                        data-mdb-modal-init data-mdb-target="#exampleModal1"
                        onclick="setBanner('{{ $banner->id }}')">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>


    <!-- Note: class .show is used for demo purposes. Remove it when using it in the real project. -->


    <div class="toast-container position-fixed top-0 end-0 p-3">
        <div id="successToast" class="toast alert-success" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header alert-success">
                <strong class="me-auto">Success</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="successToastBody"></div>
        </div>

        <div id="errorToast" class="toast alert-danger" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto alert-danger">Erreur</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body" id="errorToastBody"></div>
        </div>
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
            <div class="modal-header bg-danger d-flex justify-content-center">
                <h5 class="modal-title text-white text-center">Confirmation de suppression</h5>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-trash-alt mb-3 text-danger" style="font-size: 3rem;"></i>
                <p>Êtes-vous sûr de vouloir supprimer cette bannière ? Cette action est irréversible.</p>
                <input type="hidden" id="bannerId" name="bannerId">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Annuler</button>
                <button type="button" onclick="DeleteBanner()" class="btn btn-danger" id="deleteButton">
                    <span id="deleteButtonText">Supprimer</span>
                    <div id="deleteSpinner" class="spinner-border text-light" style="display: none; width: 1.5rem; height: 1.5rem;" role="status">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->

<script>
    function setBanner(BannerId) {
        document.getElementById('bannerId').value = BannerId;
    };


    function DeleteBanner() {
    const deleteButton = document.getElementById('deleteButton');
    const deleteSpinner = document.getElementById('deleteSpinner');
    const deleteButtonText = document.getElementById('deleteButtonText');
    const bannerId = document.getElementById('bannerId').value;

    deleteButton.disabled = true;
    deleteSpinner.style.display = 'inline-block';
    deleteButtonText.style.display = 'none';

    fetch("{{ route('delete.banner') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            bannerId: bannerId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Afficher le toast de succès
            

            // Fermer le modal
            const deleteModal = mdb.Modal.getInstance(document.getElementById('exampleModal1'));
            if (deleteModal) {
                deleteModal.hide();
            }
            setTimeout(() => {
                location.reload();
            }, 1000);

            const successToast = document.getElementById('successToast');
            const successToastBody = document.getElementById('successToastBody');
            successToastBody.textContent = data.message || 'Bannière supprimée avec succès';
            
            // Initialiser et afficher le toast
            const toastInstance = new mdb.Toast(successToast);
            toastInstance.show();

            // Actualiser la page après un court délai pour permettre l'affichage du toast
            
        } else {
            // Afficher le toast d'erreur
            const errorToast = document.getElementById('errorToast');
            const errorToastBody = document.getElementById('errorToastBody');
            errorToastBody.textContent = data.message || 'Une erreur est survenue';
            
            // Initialiser et afficher le toast d'erreur
            const toastInstance = new mdb.Toast(errorToast);
            toastInstance.show();
        }
    })
    .catch(error => {
        // Afficher le toast d'erreur en cas d'erreur réseau
        const errorToast = document.getElementById('errorToast');
        const errorToastBody = document.getElementById('errorToastBody');
        errorToastBody.textContent = 'Erreur de connexion';
        
        const toastInstance = new mdb.Toast(errorToast);
        toastInstance.show();

        console.error('Erreur:', error);
    })
    .finally(() => {
        deleteButton.disabled = false;
        deleteSpinner.style.display = 'none';
        deleteButtonText.style.display = 'inline-block';
    });
}



    document.addEventListener('DOMContentLoaded', function() {
        const bannerSwitches = document.querySelectorAll('.banner-status-switch');

        bannerSwitches.forEach(switchElement => {
            switchElement.addEventListener('change', function() {
                const bannerId = this.getAttribute('data-banner-id');
                const isActive = this.checked ? 'on' : 'off';
                const originalState = !this.checked;

                fetch("{{ route('banner.status.update') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            bannerId: bannerId,
                            is_active: isActive
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Afficher le toast de succès
                            document.getElementById('successToastBody').textContent = data.message;
                            var successToast = new mdb.Toast(document.getElementById('successToast'));
                            successToast.show();
                        } else {
                            // Rétablir l'interrupteur si la mise à jour échoue
                            this.checked = originalState;

                            // Afficher le toast d'erreur
                            document.getElementById('errorToastBody').textContent = data.message;
                            var errorToast = new mdb.Toast(document.getElementById('errorToast'));
                            errorToast.show();
                        }
                    })
                    .catch(error => {
                        // Rétablir l'interrupteur en cas d'erreur
                        this.checked = originalState;

                        // Afficher le toast d'erreur
                        document.getElementById('errorToastBody').textContent = 'Une erreur est survenue lors de la mise à jour';
                        var errorToast = new mdb.Toast(document.getElementById('errorToast'));
                        errorToast.show();
                    });
            });
        });
    });
</script>

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