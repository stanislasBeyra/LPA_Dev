@extends('components.appconfig') <!-- Extending the appconfig layout -->

@section('content')
<div class="container pt-4">

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
        <button type="button" class="btn btn-primary fs-5" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addCategoryModal">
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
                        <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div id="spinner-container" class="d-none text-center">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

    <!-- Section avec tableau -->
    <section class="mb-4">
    <div class="card">
        <div class="card-header text-center py-3">
            <h5 class="mb-0 text-center">
                <strong>Categories</strong>
            </h5>
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
                            <td>{{ $category->created_at->format('m/d/Y, h:i:s A') }}</td>  <!-- Date de création formatée -->
                            <td>{{ $category->categories_name }}</td>  <!-- Nom de la catégorie -->
                            <td>{{ $category->categories_description }}</td>  <!-- Description de la catégorie -->
                            <td class="text-center">
                                <button type="button" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </button>
                                <button type="button" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash-alt"></i> Delete
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

<script>
    // Lorsque le formulaire est soumis
    document.getElementById('categoryForm').addEventListener('submit', function(event) {
        // Empêche le formulaire de se soumettre immédiatement
        event.preventDefault();

        // Affiche le spinner
        document.getElementById('spinner-container').classList.remove('d-none');

        // Simuler une action qui prend du temps (par exemple, une requête AJAX)
        setTimeout(function() {
            // Après la "soumission", cacher le spinner
            document.getElementById('spinner-container').classList.add('d-none');

            // Soumettre le formulaire après un délai simulé (par exemple, 2 secondes)
            document.getElementById('categoryForm').submit();
        }, 2000); // Remplacez ce délai par l'action réelle (par exemple, AJAX)
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