@extends('components.appconfig') <!-- Extending the appconfig layout -->

@section('content')
<div class="container pt-4">

    <!-- Section contenant le bouton aligné à droite -->
    <div class="text-end mb-3">
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
                <div class="modal-body">

                    <form>
                        <!-- Agency Name input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="text" id="agencyName" class="form-control" />
                            <label class="form-label" for="agencyName">Agency Name</label>
                        </div>

                        <!-- Agency Code input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="text" id="agencyCode" class="form-control" />
                            <label class="form-label" for="agencyCode">Agency Code</label>
                        </div>

                        <!-- Agency Description input -->
                        <div data-mdb-input-init class="form-outline mb-4">
                            <input type="text" id="agencyDescription" class="form-control" />
                            <label class="form-label" for="agencyDescription">Agency Description</label>
                        </div>
                    </form>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" data-mdb-ripple-init>Submit</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Section avec tableau -->
    <section class="mb-4">
        <div class="card">
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
                                <th scope="col">Creation Date</th>
                                <th scope="col">Name</th>
                                <th scope="col">Description</th>
                                <th scope="col">Code</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>11/14/2024, 05:07:27 PM</td>
                                <td>CSA</td>
                                <td>Civil Service Agency</td>
                                <td>001</td>
                                <td><button class="btn btn-sm btn-outline-primary"> <i class="fas fa-edit"></i> Edit</button>
                                <button type="button" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                            </td>
                            </tr>
                            <tr>
                                <td>11/14/2024, 05:07:19 PM</td>
                                <td>Ministry of Gender</td>
                                <td>Ministry of Gender</td>
                                <td>002</td>
                                <td><button class="btn btn-sm btn-outline-primary"> <i class="fas fa-edit"></i> Edit</button>
                                <button type="button" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                            </td>
                            </tr>
                            <tr>
                                <td>11/14/2024, 05:06:44 PM</td>
                                <td>Ministry of Agriculture</td>
                                <td>Ministry of Agriculture</td>
                                <td>003</td>
                                <td><button class="btn btn-sm btn-outline-primary"> <i class="fas fa-edit"></i> Edit</button>
                                <button type="button" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                            </td>
                            </tr>
                            <tr>
                                <td>11/14/2024, 02:23:00 PM</td>
                                <td>Ministry Of Health</td>
                                <td>Ministry Of Health</td>
                                <td>004</td>
                                <td><button class="btn btn-sm btn-outline-primary"> <i class="fas fa-edit"></i> Edit</button>
                                <button type="button" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                            </td>
                            </tr>
                            <tr>
                                <td>11/14/2024, 01:32:15 PM</td>
                                <td>Ministry of Education</td>
                                <td>Ministry of Education</td>
                                <td>005</td>
                                <td><button class="btn btn-sm btn-outline-primary"> <i class="fas fa-edit"></i> Edit</button>
                                <button type="button" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                            </td>
                                
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
