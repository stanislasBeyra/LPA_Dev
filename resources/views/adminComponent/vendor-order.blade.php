@extends('components.appconfig') <!-- Extending the appconfig layout -->

@section('content')
<div class="container pt-4">

    <!-- Section avec tableau -->
    <section class="mb-4">
        <div class="card">
            <div class="card-header text-center py-3">
                <h5 class="mb-0 text-center">
                    <strong>Vendor Orders Table</strong>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Product Detail Views</th>
                                <th scope="col">Unique Purchases</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Product Revenue</th>
                                <th scope="col">Avg. Price</th>
                                <th scope="col">Actions</th> <!-- Nouvelle colonne pour les actions -->
                            </tr>
                        </thead>
                        <tbody>

                            <tr>
                                <th scope="row">Absolute change</th>
                                <td>
                                    <span class="text-danger">
                                        <i class="fas fa-caret-down me-1"></i><span>-17,654</span>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-success">
                                        <i class="fas fa-caret-up me-1"></i><span>28</span>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-success">
                                        <i class="fas fa-caret-up me-1"></i><span>111</span>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-success">
                                        <i class="fas fa-caret-up me-1"></i><span>$1,092.72</span>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-danger">
                                        <i class="fas fa-caret-down me-1"></i><span>$-1.78</span>
                                    </span>
                                </td>
                                <td>
                                    <!-- Boutons Action -->
                                    <button type="button" class="btn btn-info btn-sm" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#exampleModal">
                                        <i class="fas fa-eye"></i> View Details
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-mdb-modal-init data-mdb-target="#exampleModal1">
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

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal"> <!-- Adjusted width to 70% -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">order Details</h5>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Product Table -->
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Image</th> <!-- Image column -->
                                <th scope="col">Product</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Unit Price</th>
                                <th scope="col">Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><img src="https://i.pinimg.com/474x/b7/0b/0c/b70b0cf24259c3bb0853021163cdc31b.jpg" alt="Product 1" class="img-fluid" style="max-width:50px;"></td> <!-- Product 1 Image -->
                                <td>Product</td>
                                <td>Example Product 1</td>
                                <td>12</td>
                                <td>$12.00</td>
                                <td>$144.00</td>
                            </tr>
                            <tr>
                                <td><img src="https://i.pinimg.com/474x/60/66/8a/60668ab1f291ce0b9878af50ef3cbeae.jpg" alt="Product 2" class="img-fluid" style="max-width: 50px;"></td> <!-- Product 2 Image -->
                                <td>Product</td>
                                <td>Example Product 2</td>
                                <td>4</td>
                                <td>$10.00</td>
                                <td>$40.00</td>
                            </tr>
                            <tr>
                                <td><img src="https://i.pinimg.com/474x/a3/fb/d0/a3fbd06ccabc7b2dc25ccf9ea2b45997.jpg" alt="Product 3" class="img-fluid" style="max-width: 50px;"></td> <!-- Product 3 Image -->
                                <td>Product</td>
                                <td>Example Product 3</td>
                                <td>2</td>
                                <td>$8.00</td>
                                <td>$16.00</td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-end">Sub Total:</th>
                                <th>$200.00</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>





<!-- Modal de confirmation pour supprimer -->

<div class="modal top fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger d-flex justify-content-center w-100">
                <h5 class="modal-title text-white text-center" id="deleteConfirmationModalLabel">Delete Confirmation</h5>
            </div>

            <div class="modal-body d-flex flex-column align-items-center text-center">
                <!-- Icône de suppression au-dessus du texte -->
                <i class="fas fa-trash-alt mb-3 text-danger" style="font-size: 3rem;"></i> <!-- Icône de suppression -->
                <p class="d-inline">Are you sure you want to delete this order?</p> <!-- Texte de confirmation -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>





@endsection