@extends('components.appconfig') <!-- Extending the appconfig layout -->

@section('content')

<style>
    /* Lorsque l'interrupteur est activé, le fond et le cercle deviennent verts */
    .form-check-input:checked {
        background-color: #28a745 !important;
        /* Couleur de fond verte */
        border-color: #28a745 !important;
        /* Change la couleur de la bordure */
    }

    /* Lorsque l'interrupteur est activé, change la couleur du cercle (thumb) en vert */
    .form-check-input:checked::before {
        background-color: #ffffff !important;
        /* Couleur blanche pour le cercle */
        border-color: #28a745 !important;
        /* Bordure verte pour le cercle */
    }

    /* Si vous souhaitez avoir un effet plus fluide, vous pouvez ajouter une transition */
    .form-check-input {
        transition: background-color 0.3s, border-color 0.3s;
    }

    .form-check-input::before {
        transition: background-color 0.3s, border-color 0.3s;
    }
</style>

<div class="container-fluid pt-4">

    <div class="d-flex justify-content-between mb-3">
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
        <button type="button" class="btn btn-primary fs-7 animate__animated animate__zoomIn" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#exampleModal">
            ADD EMPLOYEE
        </button>
    </div>
    <!-- Section contenant le bouton aligné à droite -->


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add a New Employee</h5>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('employee.register') }}" method="POST">
                        @csrf
                        <!-- Section 4: Banking Details -->
                        {{-- <h5 class="mb-4">Banking Details</h5> --}}


                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="number" id="national_id" name="national_id" class="form-control" />
                                    <label class="form-label" for="national_id">National ID Number</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="firstname" name="firstname" class="form-control" />
                                    <label class="form-label" for="firstname">First Name</label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="lastname" name="lastname" class="form-control" />
                                    <label class="form-label" for="lastname">Last Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="phone" id="mobile" name="mobile" class="form-control" />
                                    <label class="form-label" for="mobile">Phone Number</label>
                                </div>
                            </div>
                        </div>


                        <div class="row mb-4">

                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="username" name="username" class="form-control" />
                                    <label class="form-label" for="username">Username</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="email" id="email" name="email" class="form-control" />
                                    <label class="form-label" for="email">Email</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">



                            <div class="col-md-6">
                                <div class="">
                                    <select name="agence_id" id="agence_id" class="form-select" required>

                                        <option value="">Choose Name of Agency/Ministry</option>
                                        @foreach ($agences as $agence )
                                        <option value="{{ $agence->id }}">{{ $agence->agent_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>





                        <div class="form-check form-switch">
                            <label class="form-check-label" for="flexSwitchCheckDefault">Status</label>
                            <input class="form-check-input " type="checkbox" name="status" role="switch" id="flexSwitchCheckDefault" />

                        </div>



                        <!-- Submit button -->
                        <!-- <button type="submit" class="btn btn-primary btn-block">Submit</button> -->

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Close</button>
                            <!-- <button type="submit" class="btn btn-primary" data-mdb-ripple-init>Submit</button> -->
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
    </div>

    <div class="modal top fade" id="staticBackdrop5" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
        <div class="modal-dialog modal-dialog-centered text-center d-flex justify-content-center">
            <div class="modal-content w-75">
                <div class="modal-body p-4">
                    <form method="POST">
                        @csrf

                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" />
                            <label class="form-label" for="new_password_confirmation">motif</label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="submitButton">
                                <span id="buttonText">Change Status</span>
                                <div id="spinner" class="spinner-border text-light" style="display: none; width: 1.5rem; height: 1.5rem;" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Section avec tableau -->
    <section class="mb-4">
        <div class="card animate__animated animate__zoomIn">
            <div class="card-header text-center py-3">
                <h5 class="mb-0 text-center">
                    <strong>Employee Liste</strong>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">National Id </th>
                                <th scope="col">First Name </th>
                                <th scope="col">Last Name</th>
                                <th scope="col">Middle Name</th>
                                <th scope="col">Email</th>
                                <th scope="col">Salary</th>
                                <th scope="col">Agence/Ministry</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employees as $key=>$employee)
                            <tr>
                                <th scope="row">{{ $key+1 }}</th>
                                <td>{{ $employee->national_id }}</td>
                                <td>{{ $employee->firstname }}</td>
                                <td>{{ $employee->lastname }}</td>
                                <td>{{ $employee->middle_name??" " }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->net_salary }}</td>
                                <td>{{ $employee->agence->agent_name ?? 'No agence selected' }}</td>
                                <td>

                                    <p style="cursor: pointer;" data-mdb-toggle="modal"
                                        data-mdb-target="#staticBackdrop5"
                                        class="badge {{ $employee->status == 1 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $employee->status == 1 ? 'Active' : 'Inactive' }}
                                    </p>

                                </td>
                                <td>
                                    <button data-mdb-button-init data-mdb-ripple-init
                                        class="btn btn-outline-primary btn-sm"
                                        data-mdb-modal-init data-mdb-target="#staticBackdrop1"
                                        data-user='@json($employee)'
                                        onclick="handleButtonClick(this)">

                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        data-mdb-modal-init data-mdb-target="#exampleModal1"
                                        onclick="setVendors('{{ $employee->id }}')">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <td>No Data</td>
                            @endforelse
                        </tbody>
                    </table>
                    {{ $employees->links('pagination::bootstrap-5') }}

                </div>
            </div>
        </div>
    </section>


    <div class="modal fade" id="staticBackdrop1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content ">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Edit Employee</h5>
                </div>
                <div class="modal-body p-4">
                    <form action="{{ route('employee.update') }}" method="POST">
                        @csrf
                        <input type="hidden" id="EmployeeID"name="EmployeeId">
                        <!-- Première ligne -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="number" id="nationalid" name="national_id" class="form-control" />
                                    <label class="form-label" for="national_id">National ID Number</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="firstnames" name="firstname" class="form-control" />
                                    <label class="form-label" for="firstname">First Name</label>
                                </div>
                            </div>
                        </div>

                        <!-- Deuxième ligne -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="lastnames" name="lastname" class="form-control" />
                                    <label class="form-label" for="lastname">Last Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="phone" id="mobiles" name="mobile" class="form-control" />
                                    <label class="form-label" for="mobile">Phone Number</label>
                                </div>
                            </div>
                        </div>

                        <!-- Troisième ligne -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="MobileTwo" name="mobile_two" class="form-control" />
                                    <label class="form-label" for="mobile_two">Mobile Two</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="usernames" name="username" class="form-control" />
                                    <label class="form-label" for="username">Username</label>
                                </div>
                            </div>
                        </div>

                        <!-- Quatrième ligne -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" id="MiddleName" name="middle_name" class="form-control" />
                                    <label class="form-label" for="middle_name">Middle Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="email" id="EditEmail" name="email" class="form-control" />
                                    <label class="form-label" for="email">Email</label>
                                </div>
                            </div>
                        </div>

                        <!-- Ligne pour agence -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <select name="agence_id" id="agence_id" class="form-select" required>
                                        <option value="">Choose Name of Agency/Ministry</option>
                                        @foreach ($agences as $agence)
                                        <option value="{{ $agence->id }}" {{ $agence->id == $employee->agence->id ? 'selected' : '' }}>
                                            {{ $agence->agent_name }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>



                        <!-- Boutons de la modal -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-mdb-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" id="editButton">
                                <span id="editButtonText">Update Employee</span>
                                <div id="editSpinner" class="spinner-border text-light" style="display: none; width: 1.5rem; height: 1.5rem;" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </button>
                        </div>
                    </form>


                </div>
            </div>
        </div>
    </div>

</div>



<script>
    const submitButton = document.getElementById('submitButton');
    const spinner = document.getElementById('spinner');
    const buttonText = document.getElementById('buttonText');

    submitButton.addEventListener('click', function(event) {
        buttonText.style.display = 'none';
        spinner.style.display = 'inline-block';

    });


    function handleButtonClick(button) {
        // Récupérer les données de l'employé à partir de l'attribut 'data-user' du bouton
        const employee = JSON.parse(button.getAttribute('data-user'));
        console.log(employee);

        // Remplir les champs du formulaire avec les informations de l'employé
        document.getElementById('EmployeeID').value=employee.id;
        document.getElementById('nationalid').value = employee.national_id;
        document.getElementById('firstnames').value = employee.firstname;
        document.getElementById('lastnames').value = employee.lastname;
        document.getElementById('mobiles').value = employee.mobile;
        document.getElementById('usernames').value = employee.username;
        document.getElementById('EditEmail').value = employee.email;
        document.getElementById('MiddleName').value = employee.middle_name;
        document.getElementById('MobileTwo').value = employee.mobile2

        // Utilisez 'agencescode' pour l'ID de l'agence
        document.getElementById('agence_id').value = employee.agencescode;

        // Pré-sélectionner le statut (switch)
        document.getElementById('flexSwitchCheckDefault').checked = (employee.status == 1);

        // Ouvrir le modal (si nécessaire)
        // $(modalSelector).modal('show'); // Si vous utilisez Bootstrap Modal

        // Initialiser les éléments de formulaire après avoir rempli les valeurs
        document.querySelectorAll('.form-outline').forEach((formOutline) => {
            new mdb.Input(formOutline).init();
        });

        // Initialiser le select pour les agences
        new mdb.Select(document.getElementById('agence_id')).init();
    }
</script>
@endsection