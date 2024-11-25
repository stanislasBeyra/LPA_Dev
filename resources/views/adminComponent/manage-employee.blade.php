@extends('components.appconfig') <!-- Extending the appconfig layout -->

@section('content')

<style>
    /* Style de base du toggle switch */
    .switch {
        position: relative;
        display: inline-block;
        width: 40px;
        height: 20px;
    }

    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: red;
        transition: 0.4s;
        border-radius: 34px;

    }

    .slider:before {
        position: absolute;
        content: "";
        height: 15px;
        width: 15px;
        border-radius: 50%;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: 0.4s;
    }

    /* Lorsque l'interrupteur est activé (checked) */
    input:checked+.slider {
        background-color: #4CAF50;
    }

    input:checked+.slider:before {
        transform: translateX(26px);
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

                            <!-- <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="password" id="password" name="password" class="form-control" />
                                    <label class="form-label" for="password">Password</label>
                                </div>
                            </div> -->



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


                        <div class="row mb-4">

                            <div class="col-md-6">
                                <p>Status :
                                    <label class="switch shadow">
                                        <input type="checkbox" class="toggle-status" name="status" checked>
                                        <span class="slider"></span>
                                    </label>
                                </p>
                            </div>
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

    <!-- Section avec tableau -->
    <section class="mb-4">
        <div class="card animate__animated animate__zoomIn">
            <div class="card-header text-center py-3">
                <h5 class="mb-0 text-center">
                    <strong>Sales Performance KPIs</strong>
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
                                <th scope="col">Email</th>
                                <th scope="col">Salary</th>
                                <th scope="col">Agence/Ministry</th>
                                <th scope="col">Status</th>
                                <!-- <th scope="col">Action</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($employees as $key=>$employee)
                            <tr>
                                <th scope="row">{{ $key+1 }}</th>
                                <td>{{ $employee->national_id }}</td>
                                <td>{{ $employee->firstname }}</td>
                                <td>{{ $employee->lastname }}</td>
                                <td>{{ $employee->email }}</td>
                                <td>{{ $employee->net_salary }}</td>
                                <td>{{ $employee->agence->agent_name ?? 'No agence selected' }}</td>
                                <td>
                                    <label class="switch">
                                        <input type="checkbox" {{ $employee->status == 1 ? 'checked' : '' }} data-id="{{
                                        $employee->id }}" class="toggle-status">
                                        <span class="slider"></span>
                                    </label>
                                    </p>
                                </td>
                                <!-- <td>Action</td> -->
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
</div>

<script>
    const submitButton = document.getElementById('submitButton');
    const spinner = document.getElementById('spinner');
    const buttonText = document.getElementById('buttonText');

    submitButton.addEventListener('click', function(event) {
        buttonText.style.display = 'none';
        spinner.style.display = 'inline-block';


    });
</script>
@endsection