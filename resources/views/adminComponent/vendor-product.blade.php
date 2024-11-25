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
                                <th scope="col">#ID</th>
                                <th scope="col">Creation Date</th>
                                <th scope="col">Product Image</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Product Categorie</th>
                                <th scope="col">Product Price</th>
                                <th scope="col">Product Stock</th>
                                <th scope="col">Product Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($vendorproducts as $key=>$product)
                            <tr>
                                <th scope="row">{{$key+1}}</th>
                                <td>{{ $product['created_at']->format('m/d/Y, h:i:s A') }}</td>
                                <td> <img src="{{ asset('app/public/' . $product['product_images1']) }}" height="60" width="60" class="shadow  rounded-3" alt="" /></td>
                                <td>{{$product['product_name']}}</td>
                                <td>{{$product['category_name']}}</td>
                                <td>{{$product['productprice']}} $</td>
                                <td>{{$product['productstock']}}</td>
                                <td>
                                    @switch($product['productstatus'])
                                    @case(1)
                                    <span class="badge bg-success">Active</span>
                                    @break
                                    @case(0)
                                    <span class="badge bg-danger">Inactive</span>
                                    @break
                                    @default
                                    <span class="badge bg-secondary">Unknown</span>
                                    @endswitch
                                </td>

                                <td>
                                    <button type="button" class="btn btn-info btn-sm"
                                        data-mdb-ripple-init
                                        data-mdb-modal-init
                                        data-mdb-target="#exampleModal"
                                        data-produt='@json($product)'
                                        onclick="handleButtonClick(this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-mdb-modal-init data-mdb-target="#exampleModal1">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
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
            </div>
            <div class="modal-body">
                <!-- Carousel -->

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
                            <img src="" class="d-block " style="width: 100%; height: 250px; object-fit: cover;" alt="Motorbike Smoke" />
                            <div class="carousel-caption d-none d-md-block">
                                <h5 id="productDetailName"></h5>
                            </div>
                        </div>

                        <!-- Single item -->
                        <div class="carousel-item">
                            <img src="" class="d-block" style="width: 100%; height: 250px; object-fit: cover;" alt="Mountaintop" />
                            <div class="carousel-caption d-none d-md-block">
                                <h5 id="productDetailName"></h5>
                            </div>
                        </div>

                        <!-- Single item -->
                        <div class="carousel-item">
                            <img src="" class="d-block" style="width: 100%; height: 250px; object-fit: cover;" alt="Woman Reading a Book" />
                            <div class="carousel-caption d-none d-md-block">
                                <h5 id="productDetailName"></h5>

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

                <div id="carouselDarkVariant" class="carousel slide carousel-fade carousel-dark" data-mdb-ride="carousel">
                    <div class="carousel-indicators">
                        <button style="border-radius: 100%; width:10px; height:10px;background-color:aliceblue" data-mdb-target="#carouselDarkVariant" data-mdb-slide-to="0" class="active"></button>
                        <button style="border-radius: 100%; width:10px; height:10px;background-color:aliceblue" data-mdb-target="#carouselDarkVariant" data-mdb-slide-to="1"></button>
                        <button style="border-radius: 100%; width:10px; height:10px;background-color:aliceblue" data-mdb-target="#carouselDarkVariant" data-mdb-slide-to="2"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="" class="d-block w-100" style="height: 250px; object-fit: cover;" alt="Image 1" id="productImage1">
                        </div>
                        <div class="carousel-item">
                            <img src="" class="d-block w-100" style="height: 250px; object-fit: cover;" alt="Image 2" id="productImage2">
                        </div>
                        <div class="carousel-item">
                            <img src="" class="d-block w-100" style="height: 250px; object-fit: cover;" alt="Image 3" id="productImage3">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-mdb-target="#carouselDarkVariant" data-mdb-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-mdb-target="#carouselDarkVariant" data-mdb-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>

                <div class="accordion" id="accordionExample">

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button
                                data-mdb-collapse-init
                                class="accordion-button collapsed"
                                type="button"
                                data-mdb-target="#collapseTwo"
                                aria-expanded="false"
                                aria-controls="collapseTwo">
                                Product description
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-mdb-parent="#accordionExample">
                            <div class="accordion-body" id="productDetailDescription">

                            </div>
                        </div>
                    </div>

                </div>

                

                <div class="row d-flex mt-4">
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Product Information</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Product Name:</strong> <span id="productDetailName"></span></p>
                                <p><strong>Quantity:</strong> <span id="productDetailQuantity"></span></p>
                                <p><strong>Price:</strong> <span id="productDetailPrice"></span></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Additional Details</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Product Category:</strong> <span id="productDetailCategory"></span></p>
                                <p><strong>Status:</strong> <span id="productDetailStatus"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal">
        <div class="modal-content">
            <div class="modal-header bg-primary d-flex justify-content-center w-100">
                <h5 class="modal-title text-white text-center" id="productDetailModalLabel">Product Details</h5>
            </div>
            <div class="modal-body  ">

                <div id="carouselDarkVariant" class="carousel slide carousel-fade carousel-dark" data-mdb-ride="carousel" data-mdb-carousel-init>
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

                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="" class="d-block " style="width: 100%; height: 250px; object-fit: cover;" alt="Motorbike Smoke" />
                            <div class="carousel-caption d-none d-md-block">
                                <h5 id="productDetailName"></h5>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <img src="" class="d-block" style="width: 100%; height: 250px; object-fit: cover;" alt="Mountaintop" />
                            <div class="carousel-caption d-none d-md-block">
                                <h5 id="productDetailName"></h5>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <img src="" class="d-block" style="width: 100%; height: 250px; object-fit: cover;" alt="Woman Reading a Book" />
                            <div class="carousel-caption d-none d-md-block">
                                <h5 id="productDetailName"></h5>

                            </div>
                        </div>
                    </div>

                    <button class="carousel-control-prev" type="button" data-mdb-target="#carouselDarkVariant" data-mdb-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-mdb-target="#carouselDarkVariant" data-mdb-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>

                <div class="accordion" id="accordionExample">

                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button
                                data-mdb-collapse-init
                                class="accordion-button collapsed"
                                type="button"
                                data-mdb-target="#collapseTwo"
                                aria-expanded="false"
                                aria-controls="collapseTwo">
                                Product description
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-mdb-parent="#accordionExample">
                            <div class="accordion-body" id="productDetailDescription">

                            </div>
                        </div>
                    </div>

                </div>

                <div class="row d-flex mt-4">
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Product Information</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Product Name:</strong> <span id="productDetailNames"></span></p>
                                <p><strong>Quantity:</strong> <span id="productDetailQuantity"></span></p>
                                <p><strong>Price:</strong> <span id="productDetailPrice"></span></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Additional Details</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Product category:</strong> <span id="productDetailcategorie"></span></p>
                                <p><strong>product Status:</strong> <span id="productDetailStatus"></span> </p>

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
</div> -->


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
<script>
    function handleButtonClick(button) {
        const productData = JSON.parse(button.getAttribute('data-produt'));
        console.log('product', productData);

        document.querySelector("#productDetailModalLabel").textContent = "Product Details: " + productData.product_name;

        // Fonction pour vérifier l'existence de l'image
        function checkImage(imagePath) {
            const img = new Image();
            img.src = imagePath;
            return img.complete && img.naturalWidth !== 0;
        }

        // Affiche l'image du produit dans le carousel
        const carouselItems = document.querySelectorAll('.carousel-item img');
        const defaultImage = "{{ asset('app/public/images/default-product.jpg') }}"; // Image par défaut si l'image est manquante

        carouselItems.forEach((img, index) => {
            if (index === 0) {
                img.src = "{{ asset('app/public/') }}" + "/" + productData.product_images1; // Première image du carousel
            } else if (index === 1) {
                img.src = "{{ asset('app/public/') }}" + "/" + productData.product_images2; // Deuxième image
            } else if (index === 2) {
                img.src = "{{ asset('app/public/') }}" + "/" + productData.product_images3; // Troisième image
            }
        });
        
        // carouselItems.forEach((img, index) => {
        //     let imagePath = '';
        //     if (index === 0 && productData.product_images1) {
        //         imagePath = "{{ asset('app/public/') }}" + "/" + productData.product_images1;
        //     } else if (index === 1 && productData.product_images2) {
        //         imagePath = "{{ asset('app/public/') }}" + "/" + productData.product_images2;
        //     } else if (index === 2 && productData.product_images3) {
        //         imagePath = "{{ asset('app/public/') }}" + "/" + productData.product_images3;
        //     }

        //     // Si l'image existe, on l'affiche, sinon on met l'image par défaut
        //     img.src = checkImage(imagePath) ? imagePath : defaultImage;
        // });

        // Met à jour les informations du produit dans les cartes
        document.querySelector("#productDetailName").textContent = productData.product_name;
        document.querySelector("#productDetailQuantity").textContent = productData.productstock;
        document.querySelector("#productDetailPrice").textContent = "$" + (productData.productprice).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        document.querySelector("#productDetailDescription").textContent = productData.product_description;
        document.querySelector("#productDetailNames").textContent = productData.product_name;

        const status = productData.productstatus === 1 ? 'active' : 'inactive';
        document.querySelector("#productDetailStatus").textContent = status;

        document.querySelector("#productDetailcategorie").textContent = productData.category_name; // Exemple de revenu

        const statusElement = document.querySelector("#productDetailStatus");
        if (status === 'active') {
            statusElement.classList.add('badge', 'bg-success');
        } else {
            statusElement.classList.add('badge', 'bg-danger');
        }
    }
</script>

<!-- 
<script>
    function handleButtonClick(button) {
        const productData = JSON.parse(button.getAttribute('data-produt'));
        console.log('product', productData);

        document.querySelector("#productDetailModalLabel").textContent = "Product Details: " + productData.product_name;

        // Affiche l'image du produit dans le carousel
        const carouselItems = document.querySelectorAll('.carousel-item img');
        carouselItems.forEach((img, index) => {
            if (index === 0) {
                img.src = "{{ asset('app/public/') }}" + "/" + productData.product_images1; // Première image du carousel
            } else if (index === 1) {
                img.src = "{{ asset('app/public/') }}" + "/" + productData.product_images2; // Deuxième image
            } else if (index === 2) {
                img.src = "{{ asset('app/public/') }}" + "/" + productData.product_images3; // Troisième image
            }
        });

        // Met à jour les informations du produit dans les cartes
        document.querySelector("#productDetailName").textContent = productData.product_name;
        document.querySelector("#productDetailQuantity").textContent = productData.stock;
        document.querySelector("#productDetailPrice").textContent = "$" + (productData.price).toLocaleString('en-US', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
        document.querySelector("#productDetailDescription").textContent = productData.product_description;
        document.querySelector("#productDetailNames").textContent = productData.product_name
        const status = productData.status === 1 ? 'active' : 'inactive';

        document.querySelector("#productDetailStatus").textContent = status

        document.querySelector("#productDetailcategorie").textContent = productData.category.categories_name; // Exemple de revenu

        const statusElement = document.querySelector("#productDetailStatus");
        if (status === 'active') {
            statusElement.classList.add('badge', 'bg-success');
        } else {
            statusElement.classList.add('badge', 'bg-danger');
        }


    }
</script> -->

@endsection