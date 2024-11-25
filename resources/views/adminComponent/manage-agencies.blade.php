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
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary fs-5" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addAgencyModal">
            ADD AGENCIES
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addAgencyModal" tabindex="-1" aria-labelledby="addAgencyModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAgencyModalLabel">Add a New Agency</h5>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('create.agencies') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Agency Name input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="text" name="agent_name" id="agencyName" class="form-control" required />
                            <label class="form-label" for="agencyName">Agency Name</label>
                        </div>

                        <!-- Agency Code input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="text" name="agency_code" id="agencyCode" class="form-control" required />
                            <label class="form-label" for="agencyCode">Agency Code</label>
                        </div>

                        <!-- Agency Description input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="text" name="description" id="agencyDescription" class="form-control" required />
                            <label class="form-label" for="agencyDescription">Agency Description</label>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <!-- Close button for modal -->
                        <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Close</button>
                        <!-- Submit button to submit form -->
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
                    <strong>Search Agencies</strong>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">#ID</th>
                                <th scope="col">Creation Date</th>
                                <th scope="col">Agence Name</th>
                                <th scope="col">Agence Description</th>
                                <th scope="col">Code</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($agences as $key=>$agence)
                            <tr>
                                <td>{{$key+1}}</td>
                                <td>{{ $agence->created_at->format('m/d/Y, h:i:s A') }}</td>
                                <td>{{$agence->agent_name}}</td>
                                <td>{{$agence->description}}</td>
                                <td>{{$agence->agency_code}}</td>
                                <td>
                                    <button type="button" data-mdb-button-init data-mdb-ripple-init
                                        class="btn btn-outline-primary btn-sm"
                                        data-mdb-modal-init data-mdb-target="#staticBackdrop1"
                                        data-id="{{ $agence->id }}"
                                        data-name="{{ $agence->agent_name }}"
                                        data-description="{{ $agence->description }}"
                                        data-code="{{ $agence->agency_code }}"
                                        onclick="populateEditModal(this)">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <!-- <button class="btn btn-sm btn-outline-primary"> <i class="fas fa-edit"></i> Edit</button> -->
                                    <button type="button" class="btn btn-danger btn-sm"
                                        data-mdb-modal-init
                                        data-mdb-target="#exampleModal1"
                                        onclick="setAgneciesID('{{ $agence->id }}')">
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

<!-- Modal -->
<div class="modal fade" id="staticBackdrop1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
    <div class="modal-dialog ">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Edit category</h5>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('agences.edit') }}">
                    @csrf
                    <input type="hidden" id="editAgenceId" name="agenceId" />

                    <!-- Nom de la catégorie -->
                    <div class="form-outline mb-4">
                        <input type="text" id="editAgenceName" name="agent_name" class="form-control" required />
                        <label class="form-label" for="editAgenceName">Agence Name</label>
                    </div>

                    <div data-mdb-input-init class="form-outline mb-4">
                        <input type="text" name="agency_code" id="AgencyCode" class="form-control" required />
                        <label class="form-label" for="agencyCode">Agency Code</label>
                    </div>

                    <!-- Description de la catégorie -->
                    <div class="form-outline mb-4">
                        <textarea id="editAgenceDescription" name="description" class="form-control" rows="4" required></textarea>
                        <label class="form-label" for="editAgenceDescription">Agence Description</label>
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

<div class="modal top fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger d-flex justify-content-center w-100">
                <h5 class="modal-title text-white text-center" id="deleteConfirmationModalLabel">Delete Confirmation</h5>
            </div>

            <form id="deleteForm" action="{{ route('agences.delete') }}" method="POST">
                @csrf
                <input type="hidden" id="agenceId" name="agenceId">
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
<script>
    function setAgneciesID(agenceId) {
        document.getElementById('agenceId').value = agenceId;
    };
</script>

<script>
    document.querySelectorAll('[data-mdb-target="#staticBackdrop1"]').forEach(button => {
        button.addEventListener('click', function() {
            // Récupérer les données du bouton
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const description = this.getAttribute('data-description');
            const code = this.getAttribute('data-code');
            console.log(code)

            // Remplir les champs du formulaire
            document.getElementById('editAgenceId').value = id;
            document.getElementById('editAgenceName').value = name;
            document.getElementById('AgencyCode').value = code;
            document.getElementById('editAgenceDescription').value = description;
        });
    });
</script>
@endsection