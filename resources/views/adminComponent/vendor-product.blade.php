@extends('components.appconfig') <!-- Extending the appconfig layout -->

@section('content')
<div class="container-fluid pt-4">

    <!-- Section avec tableau -->
    <section class="mb-4">
        <div class="card">
            <div class="card-header text-center py-3">
                <h5 class="mb-0 text-center">
                    <strong>Vendor products Table</strong>
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col"></th>
                                <th scope="col">Product Detail Views</th>
                                <th scope="col">Unique Purchases</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Product Revenue</th>
                                <th scope="col">Avg. Price</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th scope="row">Value</th>
                                <td>18,492</td>
                                <td>228</td>
                                <td>350</td>
                                <td>$4,787.64</td>
                                <td>$13.68</td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm" data-mdb-ripple-init data-mdb-modal-init data-mdb-target="#exampleModal">
                                        <i class="fas fa-eye"></i> View Details
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-mdb-modal-init data-mdb-target="#exampleModal1">
                                        <i class="fas fa-trash-alt"></i> Delete
                                    </button>
                                </td>
                            </tr>
                            <!-- Repeat for other rows -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Detail -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal"> <!-- Adjusted width to 70% -->
        <div class="modal-content">
            <div class="modal-header bg-primary d-flex justify-content-center w-100">
                <h5 class="modal-title text-white text-center" id="productDetailModalLabel">Product Details</h5>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
            </div>
            <div class="modal-body  ">
                <!-- Image carousel (optional, if multiple images are available) -->
                <!-- Carousel wrapper -->
                <div id="carouselDarkVariant" class="carousel slide carousel-fade carousel-dark" data-mdb-ride="carousel" data-mdb-carousel-init>
                    <!-- Indicators -->
                    <div class="carousel-indicators">
                        <button
                            style="border-radius: 100%; width:10px; height:10px;background-color:aliceblue"
                            data-mdb-target="#carouselDarkVariant"
                            data-mdb-slide-to="0"
                            class="active"
                            aria-current="true"
                            aria-label="Slide 1">
                        </button>
                        <button
                            style="border-radius: 100%; width:10px; height:10px;background-color:aliceblue"
                            data-mdb-target="#carouselDarkVariant"
                            data-mdb-slide-to="1"
                            aria-label="Slide 1">
                        </button>
                        <button
                            style="border-radius: 100%; width:10px; height:10px;background-color:aliceblue"
                            data-mdb-target="#carouselDarkVariant"
                            data-mdb-slide-to="2"
                            aria-label="Slide 1">
                        </button>
                    </div>

                    <!-- Inner -->
                    <div class="carousel-inner">
                        <!-- Single item -->
                        <div class="carousel-item active">
                            <img src="https://mdbcdn.b-cdn.net/img/Photos/Slides/img%20(19).webp" class="d-block w-100" alt="Motorbike Smoke" />
                            <div class="carousel-caption d-none d-md-block">
                                <h5>First slide label</h5>
                                <p>Nulla vitae elit libero, a pharetra augue mollis interdum.</p>
                            </div>
                        </div>

                        <!-- Single item -->
                        <div class="carousel-item">
                            <img src="https://mdbcdn.b-cdn.net/img/Photos/Slides/img%20(35).webp" class="d-block w-100" alt="Mountaintop" />
                            <div class="carousel-caption d-none d-md-block">
                                <h5>Second slide label</h5>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                            </div>
                        </div>

                        <!-- Single item -->
                        <div class="carousel-item">
                            <img src="https://mdbcdn.b-cdn.net/img/Photos/Slides/img%20(40).webp" class="d-block w-100" alt="Woman Reading a Book" />
                            <div class="carousel-caption d-none d-md-block">
                                <h5>Third slide label</h5>
                                <p>Praesent commodo cursus magna, vel scelerisque nisl consectetur.</p>
                            </div>
                        </div>
                    </div>
                    <!-- Inner -->

                    <!-- Controls -->
                    <button class="carousel-control-prev" type="button" data-mdb-target="#carouselDarkVariant" data-mdb-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-mdb-target="#carouselDarkVariant" data-mdb-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
                <!-- Carousel wrapper -->

                <div class="row d-flex mt-4">
                    <!-- Card gauche -->
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Product Information</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Product Name:</strong> Sample Product Name</p>
                                <p><strong>Quantity:</strong> 350</p>
                                <p><strong>Price:</strong> $13.68</p>
                                <p><strong>Product Revenue:</strong> $4,787.64</p>
                            </div>
                        </div>
                    </div>

                    <!-- Card droite -->
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Additional Details</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Unique Purchases:</strong> 228</p>
                                <p><strong>Product Views:</strong> 18,492</p>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Close</button>
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

            <div class="modal-body d-flex flex-column align-items-center text-center">
                <!-- Icône de suppression au-dessus du texte -->
                <i class="fas fa-trash-alt mb-3 text-danger" style="font-size: 3rem;"></i> <!-- Icône de suppression -->
                <p>Are you sure you want to delete this product? This action cannot be undone.</p><!-- Texte de confirmation -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>


@endsection