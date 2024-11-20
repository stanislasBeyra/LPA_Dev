@extends('components.appconfig') <!-- Extending the appconfig layout -->

@section('content')
<div class="container pt-4">

    <!-- Section contenant le bouton aligné à droite -->
    <div class="text-end mb-3">
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-primary fs-5" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#addAgencyModal">
            ADD CATEGORIES
        </button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="addAgencyModal" tabindex="-1" aria-labelledby="addAgencyModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryLabel">Add a New CATEGORY</h5>
                    
                </div>
                <div class="modal-body">
                    <form>
                        <!-- Name input -->
                        <div class="form-outline mb-4">
                            <input type="text" id="name" class="form-control" />
                            <label class="form-label" for="name">Category Name</label>
                        </div>

                        <!-- Categorie Description input -->
                        <div class="form-outline mb-4">
                            <input type="text" id="categoryDescription" class="form-control" />
                            <label class="form-label" for="categoryDescription">Category Description</label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Submit</button>
                </div>
            </div>
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
                            <!-- Données fictives -->
                            <tr>
                                <td>10/15/2024, 05:58:10 PM</td>
                                <td>Automotive</td>
                                <td>Parts, accessories, and tools for vehicles.</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>10/15/2024, 05:57:58 PM</td>
                                <td>Books</td>
                                <td>A wide selection of fiction, non-fiction, and educational books.</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>11/15/2024, 04:38:44 AM</td>
                                <td>Building Material</td>
                                <td>Assorted Construction wares.</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>10/15/2024, 05:56:48 PM</td>
                                <td>Children</td>
                                <td>Clothing, toys, and accessories for children.</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>10/15/2024, 05:53:17 PM</td>
                                <td>Clothing</td>
                                <td>Apparel and accessories.</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <td>10/15/2024, 05:51:26 PM</td>
                                <td>Electronics</td>
                                <td>All kinds of electronic items.</td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
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
