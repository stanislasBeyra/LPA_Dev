<?php
use App\Http\Controllers\Upload;

?>
@extends('components.appconfig') <!-- Extending the appconfig layout -->

@section('content')
    <div class="container-fluid pt-4">

        <!-- Section avec tableau -->
        <section class="mb-4">
            <div class="card animate__animated animate__zoomIn">
                <div class="card-header text-center py-3">


                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 text-center">
                            <strong>Vendor products Table</strong>
                        </h5>

                        <div class="input-group " style="width: 30%;">
                            <div class="form-outline" data-mdb-input-init>
                                <input type="search" id="form1" class="form-control" placeholder="Search Vendos" />
                                <label class="form-label" for="form1">Search</label>
                            </div>
                        </div>
                    </div>
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
                                @foreach ($vendorproducts as $key => $product)
                                    @php
                                        $imageUrl = Upload::getAbosoluteUrl('products', $product->product_images1);
                                    @endphp
                                    <tr>
                                        <th scope="row">{{ $key + 1 }}</th>
                                        <td>{{ $product['created_at']->format('m/d/Y, h:i:s A') }}</td>
                                        <td> <img src="{{ $imageUrl }}" height="60" width="60"
                                                class="shadow  rounded-3" alt="" />
                                        </td>
                                        <td>{{ $product['product_name'] }}</td>
                                        <td>{{ $product['category_name'] }}</td>

                                        <td>${{ number_format($product['productprice'], 2, '.', ',') }}</td>
                                        <td>{{ $product['productstock'] }}qty</td>
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
                                            <button type="button" class="btn btn-info btn-sm" data-mdb-ripple-init
                                                data-mdb-modal-init data-mdb-target="#exampleModal"
                                                data-produt='@json($product)'
                                                onclick="handleButtonClick(this)">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm" data-mdb-modal-init
                                                data-mdb-target="#exampleModal1"
                                                data-produt-delete='@json($product)'
                                                onclick="handledeleteButtonClick(this)">

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

                    <div id="carouselDarkVariant" class="carousel slide carousel-fade carousel-dark"
                        data-mdb-ride="carousel" data-mdb-carousel-init>
                        <!-- Indicators -->
                        <div class="carousel-indicators">
                            <button style="border-radius: 100%; width:10px; height:10px;background-color:aliceblue"
                                data-mdb-target="#carouselDarkVariant" data-mdb-slide-to="0" class="active"
                                aria-current="true" aria-label="Slide 1">
                            </button>
                            <button style="border-radius: 100%; width:10px; height:10px;background-color:aliceblue"
                                data-mdb-target="#carouselDarkVariant" data-mdb-slide-to="1" aria-label="Slide 1">
                            </button>
                            <button style="border-radius: 100%; width:10px; height:10px;background-color:aliceblue"
                                data-mdb-target="#carouselDarkVariant" data-mdb-slide-to="2" aria-label="Slide 1">
                            </button>
                        </div>

                        <!-- Inner -->
                        <div class="carousel-inner">
                            <!-- Single item -->
                            <div class="carousel-item active">
                                <img src="" class="d-block " style="width: 100%; height: 250px; object-fit: cover;"
                                    alt="Motorbike Smoke" />
                                <div class="carousel-caption d-none d-md-block">
                                    <h5 id="productDetailName"></h5>
                                </div>
                            </div>

                            <!-- Single item -->
                            <div class="carousel-item">
                                <img src="" class="d-block" style="width: 100%; height: 250px; object-fit: cover;"
                                    alt="Mountaintop" />
                                <div class="carousel-caption d-none d-md-block">
                                    <h5 id="productDetailName"></h5>
                                </div>
                            </div>

                            <!-- Single item -->
                            <div class="carousel-item">
                                <img src="" class="d-block" style="width: 100%; height: 250px; object-fit: cover;"
                                    alt="Woman Reading a Book" />
                                <div class="carousel-caption d-none d-md-block">
                                    <h5 id="productDetailName"></h5>

                                </div>
                            </div>
                        </div>
                        <!-- Inner -->

                        <!-- Controls -->
                        <button class="carousel-control-prev" type="button" data-mdb-target="#carouselDarkVariant"
                            data-mdb-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-mdb-target="#carouselDarkVariant"
                            data-mdb-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>



                    <div class="accordion" id="accordionExample">

                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingTwo">
                                <button data-mdb-collapse-init class="accordion-button collapsed" type="button"
                                    data-mdb-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    Product description
                                </button>
                            </h2>
                            <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo"
                                data-mdb-parent="#accordionExample">
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
                                    <p><strong>Product Category:</strong> <span id="productDetailCategory"></span></p>
                                    <p><strong>Status:</strong> <span id="productDetailStatus"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-mdb-ripple-init
                        data-mdb-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal top fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1"
        aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header bg-danger d-flex justify-content-center w-100">
                    <h5 class="modal-title text-white text-center" id="deleteConfirmationModalLabel">Delete Confirmation
                    </h5>
                </div>

                <div class="modal-body d-flex flex-column align-items-center text-center">
                    <!-- Icône de suppression au-dessus du texte -->
                    <i class="fas fa-trash-alt mb-3 text-danger" style="font-size: 3rem;"></i>
                    <!-- Icône de suppression -->
                    <p>Are you sure you want to delete this product? This action cannot be undone.</p>
                    <!-- Texte de confirmation -->
                </div>
                <form method="POST" action="{{ route('delete.VendorProduct') }}">
                    @csrf
                    <input type="hidden" id="Idproduct" name="productId">
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-mdb-ripple-init
                            data-mdb-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger" id="deleteButton">
                            <span id="deleteButtonText">Delete</span>
                            <div id="deleteSpinner" class="spinner-border text-light"
                                style="display: none; width: 1.5rem; height: 1.5rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        const deleteButton = document.getElementById('deleteButton'); // Bouton de suppression
        const spinnerdelete = document.getElementById('deleteSpinner'); // Spinner pour le bouton
        const buttonTextdelete = document.getElementById('deleteButtonText'); // Texte du bouton

        // Ajout de l'événement sur le bouton
        deleteButton.addEventListener('click', function(event) {
            // Affiche le spinner et masque le texte
            buttonTextdelete.style.display = 'none'; // Masque le texte du bouton
            spinnerdelete.style.display = 'inline-block'; // Affiche le spinner
        });

        function handledeleteButtonClick(button) {
            const productData = JSON.parse(button.getAttribute('data-produt-delete'));
            console.log('product', productData);
            document.querySelector("#Idproduct").value = productData.id;

        }

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
            // const defaultImage =
            //     "{{ asset('app/public/images/default-product.jpg') }}"; // Image par défaut si l'image est manquante

            // carouselItems.forEach((img, index) => {
            //     if (index === 0) {
            //         img.src = "{{ asset('app/public/') }}" + "/" + productData
            //             .product_images1; // Première image du carousel
            //     } else if (index === 1) {
            //         img.src = "{{ asset('app/public/') }}" + "/" + productData.product_images2; // Deuxième image
            //     } else if (index === 2) {
            //         img.src = "{{ asset('app/public/') }}" + "/" + productData.product_images3; // Troisième image
            //     }
            // });

            const baseUrl = "https://bucket-production-5e4b.up.railway.app:443/products/";

            // Génère les URLs complètes des images
            const imageUrl1 = baseUrl + productData.product_images1;
            const imageUrl2 = baseUrl + productData.product_images2;
            const imageUrl3 = baseUrl + productData.product_images3;

            carouselItems.forEach((img, index) => {
                if (index === 0) {
                    img.src = imageUrl1; // Première image du carousel
                } else if (index === 1) {
                    img.src = imageUrl2; // Deuxième image
                } else if (index === 2) {
                    img.src = imageUrl3; // Troisième image
                }
            });


            // Met à jour les informations du produit dans les cartes
            document.querySelector("#productDetailName").textContent = productData.product_name;
            document.querySelector("#productDetailNames").textContent = productData.product_name;
            document.querySelector("#productDetailQuantity").textContent = productData.productstock;
            document.querySelector("#productDetailPrice").textContent = "$" + (productData.productprice).toLocaleString(
                'en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
            document.querySelector("#productDetailDescription").textContent = productData.product_description;
            document.querySelector("#productDetailNames").textContent = productData.product_name;
            document.querySelector("#productDetailCategory").textContent = productData.category_name;

            document.querySelector("#productDetailcategorie").textContent = productData.category_name;

            const status = productData.productstatus === 1 ? 'active' : 'inactive';
            document.querySelector("#productDetailStatus").textContent = status;


            const statusElement = document.querySelector("#productDetailStatus");
            if (status === 'active') {
                statusElement.classList.add('badge', 'bg-success');
            } else {
                statusElement.classList.add('badge', 'bg-danger');
            }
        }
    </script>

    <script>
        $(document).ready(function() {
            $('#form1').on('keyup', function() {
                let searchQuery = $(this).val();
                let keyIncremented = 0;

                // Effectuer une requête AJAX
                $.ajax({
                    url: "{{ route('searvendor.product') }}",
                    type: "GET",
                    data: {
                        search: searchQuery
                    },
                    success: function(response) {
                        // Vider le tableau
                        $('tbody').empty();
                        console.log(response);
                        if (response.Products.length > 0) {
                            response.Products.forEach((Product, index) => {
                                keyIncremented++;

                                let formattedDate = new Date(Product.created_at)
                                    .toLocaleString('en-US', {
                                        month: '2-digit',
                                        day: '2-digit',
                                        year: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit',
                                        second: '2-digit',
                                        hour12: true
                                    });
                                let ProductJson = JSON.stringify(Product).replace(/"/g,
                                    '&quot;');
                                const productprice = Product.productprice;


                                const formatbalance = productprice.toLocaleString(
                                    'en-US', {
                                        style: 'decimal',
                                        minimumFractionDigits: 2
                                    })

                                let statusClass, statusText;
                                switch (Product.productstatus) {
                                    case 0:
                                        statusClass = 'bg-danger';
                                        statusText = 'Inactive';
                                        break;

                                    case 1:
                                        statusClass = 'bg-success';
                                        statusText = 'active';
                                        break;
                                    default:
                                        statusClass = 'bg-warning';
                                        statusText = `Unknown status`;
                                        break;
                                }


                                $('tbody').append(`
                            <tr>
                            <td>${keyIncremented}</td>
                                <td>${formattedDate}</td>
                                <td> <img src="{{ asset('app/public/${Product.product_images1}') }}"  height="60" width="60" class="shadow  rounded-3" alt="" /></td>
                                <td>${Product.product_name}</td>
                                <td>${Product.category_name??''}</td>
                                <td text-start>$${formatbalance}</td>
                                <td>${Product.productstock}</td>
                                <td>
                                <span class="badge ${statusClass}">
                                       ${statusText}
                                    </span>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm"
                                        data-mdb-ripple-init
                                        data-mdb-modal-init
                                        data-mdb-target="#exampleModal"
                                        data-produt='${JSON.stringify(Product).replace(/"/g, '&quot;')}'
                                        onclick="handleButtonClick(this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        data-mdb-modal-init
                                        data-mdb-target="#exampleModal1"
                                        data-produt-delete='${JSON.stringify(Product).replace(/"/g, '&quot;')}'
                                        onclick="handledeleteButtonClick(this)">

                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </td>


                            </tr>
                        `);
                            });

                        } else {
                            $('tbody').append(
                                '<tr><td colspan="4" class="text-center">No results found</td></tr>'
                            );
                        }
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            });

        });

        // $(document).ready(function() {
        //     $('#form1').on('keyup', function() {
        //         let searchQuery = $(this).val();
        //         let keyIncremented = 0;

        //         // Effectuer une requête AJAX
        //         $.ajax({
        //             url: "{{ route('searvendor.product') }}",
        //             type: "GET",
        //             dataType: 'json', // Explicitly set expected data type
        //             data: {
        //                 search: searchQuery
        //             },
        //             success: function(response) {
        //                 // Vider le tableau
        //                 console.log('Full Response:', response);
        //                 $('#tbody').empty();

        //                 let products = response.Products || [];
        //                 if (products.length > 0) {
        //                     products.forEach((product, index) => {
        //                         keyIncremented++;
        //                         // Safe date formatting
        //                         const creationDate = new Date(product.created_at).toLocaleDateString();
        //                         const statusLabel = product.productstatus === 1 ? 'Available' : 'Out of Stock';

        //                         $('tbody').append(`
    //                         <tr>
    //                                 <td>${keyIncremented}</td>
    //                                 <td>${creationDate}</td>

    //                                 <td>${product.product_name}</td>
    //                                 <td>${product.category_name}</td>
    //                                 <td>$${product.productprice}</td>
    //                                 <td>${product.productstock}</td>
    //                                 <td>${statusLabel}</td>
    //                                 <td>
    //                                     <button class="btn btn-primary btn-sm">Edit</button>
    //                                     <button class="btn btn-danger btn-sm">Delete</button>
    //                                 </td>
    //                             </tr>

    //                     `);
        //                     });
        //                 } else {
        //                     $('tbody').append(`
    //                     <tr>
    //                         <td colspan="7" class="text-center text-muted">
    //                             No orders found. Try a different search.
    //                         </td>
    //                     </tr>
    //                 `);
        //                 }
        //             },
        //             error: function(xhr, status, error) {
        //                 console.error('AJAX Error:', {
        //                     status: status,
        //                     error: error,
        //                     responseText: xhr.responseText
        //                 });

        //                 $('tbody').append(`
    //                 <tr>
    //                     <td colspan="7" class="text-center text-danger">
    //                         Error loading orders.
    //                         ${xhr.status ? `(Error ${xhr.status})` : 'Please try again.'}
    //                     </td>
    //                 </tr>
    //             `);
        //             }
        //         });
        //     });
        // });
    </script>
@endsection
