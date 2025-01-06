@extends('components.appconfig') <!-- Extending the appconfig layout -->

@section('content')
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
        <button type="button" class="btn btn-primary fs-7" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary" data-mdb-modal-init data-mdb-target="#staticBackdrop1">
            add products
        </button>
    </div>
    <!-- Button trigger modal -->


    <!-- Modal -->
    <div class="modal fade" id="staticBackdrop1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add a New Product</h5>
                    <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('vendor.product.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <!-- Section 1: Product Information -->
                        <h5 class="mb-4">Product Information</h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="text" name="productname" id="productname" class="form-control" />
                                    <label class="form-label" for="productname">Product Name</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="number" name="productprice" id="productprice" class="form-control" />
                                    <label class="form-label" for="productprice">Product Price</label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <select name="categorie" class="form-select">
                                    <option value="">Categories</option>
                                    @foreach($categories as $categorie)
                                    <option value="{{ $categorie->id }}">{{ $categorie->categories_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <div class="form-outline">
                                    <input type="number" name="stock" id="stock" class="form-control" />
                                    <label class="form-label" for="stock">Stock</label>
                                </div>
                            </div>
                        </div>

                        <!-- Section 2: Product Details -->
                        <h5 class="mb-4">Product Details</h5>
                        <div class="row mb-4">
                            <div class="col-md-12">
                                <div class="form-outline">
                                    <textarea name="produdetail" id="produdetail" class="form-control" rows="3"></textarea>
                                    <label class="form-label" for="produdetail">Product Detail</label>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex flex-column mb-4">
                            <!-- Étiquette et champ de sélection des images -->
                            <div class="mb-3">
                                <label for="ProductImage" class="btn btn-primary">
                                    Select Images (max 3)
                                </label>
                                <input type="file" name="ProductImage[]" id="ProductImage"
                                    class="form-control d-none"
                                    multiple
                                    accept=".jpeg,.jpg,.png" />
                            </div>

                            <!-- Conteneur pour l'aperçu des images -->
                            <div id="previewContainer"
                                class="d-flex flex-wrap gap-3 justify-content-center align-items-center"
                                style="padding: 15px; min-height: 150px; border-radius: 8px;">
                                <!-- Les aperçus des images seront affichés ici -->
                            </div>

                        </div>

                        <!-- Error message -->
                        <div class="alert alert-danger mb-0 me-3" id="image-message" style="display: none;"></div>

                        <!-- Submit button -->
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Close</button>
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
    <!-- Modal -->

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
                        <thead >
                            <tr>
                                <th scope="col">#ID</th>
                                <th scope="col">Creation date</th>
                                <th scope="col">Product Image</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Product categorie</th>
                                <th scope="col">Product price</th>
                                <th scope="col">product stock</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($products as $key=>$product)
                            <tr>
                                <th scope="row">{{$key +1}}</th>
                                <td>{{ $product->created_at->format('m/d/Y, h:i:s A') }}</td>
                                <td> <img src="{{ asset('app/public/' . $product->product_images1) }}" height="60" width="60" class="shadow  rounded-3" alt="" /></td>
                                <td>{{$product->product_name}}</td>
                                <td>{{$product->category->categories_name??'NA'}}</td>
                                <td>${{ number_format($product->price, 2, '.', ',') }}</td>
                                <td>{{ number_format($product->stock, 0, '.', ',') }} qty</td>

                                <td>

                                    <button type="button" class="btn btn-info btn-sm" data-mdb-ripple-init
                                        data-mdb-modal-init data-mdb-target="#exampleModal"
                                        data-user='@json($product)'
                                        onclick="handleButtonClick(this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm"
                                        data-mdb-button-init
                                        data-mdb-ripple-init
                                        data-mdb-modal-init data-mdb-target="#staticBackdrop3"
                                        data-edit-product='@json($product)'
                                        onclick="handleEditButtonClick(this)">
                                        <i class="fas fa-edit"></i>

                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm"
                                        data-mdb-modal-init data-mdb-target="#exampleModal1"
                                        data-product='@json($product)'
                                        onclick="handledeleteButtonClick(this)">
                                        <i class="fas fa-trash-alt"></i>
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



<!--Edit Modal -->
<div class="modal fade" id="staticBackdrop3" tabindex="-1" aria-labelledby="exampleModalLabel3" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel3">Produc Edit</h5>
                <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn-close" data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form method="POST" action="{{ route('update.product') }}">
                    @csrf
                    <input type="hidden" id="productId" name="productId">
                    <!-- Name input -->
                    <h5 class="mb-4">Product Information</h5>
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="text" name="EditProductName" id="EditProductName" class="form-control" />
                                <label class="form-label" for="EditProductName">Product Name</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="number" name="EditProductPrice" id="EditProductPrice" class="form-control" />
                                <label class="form-label" for="EditProductPrice">Product Price</label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <select name="EditCategorie" id="EditCategorie" class="form-select">
                                <option value="">Categories</option>
                                @foreach($categories as $categorie)
                                <option value="{{ $categorie->id }}">{{ $categorie->categories_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <div class="form-outline">
                                <input type="number" name="Editstock" id="Editstock" class="form-control" />
                                <label class="form-label" for="Editstock">Stock</label>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Product Details -->
                    <h5 class="mb-4">Product Details</h5>
                    <div class="row mb-4">
                        <div class="col-md-12">
                            <div class="form-outline">
                                <textarea name="EditProduDetail" id="EditProduDetail" class="form-control" rows="3"></textarea>
                                <label class="form-label" for="EditProduDetail">Product Detail</label>
                            </div>
                        </div>
                    </div>




                    <!-- Submit button -->

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Close</button>

                        <button type="submit" class="btn btn-outline-primary" id="editButton">
                            <span id="editButtonText">Submit And Edit</span>
                            <div id="editSpinner" class="spinner-border text-primary" style="display: none; width: 1.5rem; height: 1.5rem;" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </button>

                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->

<!-- Modal Detail -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal"> <!-- Adjusted width to 70% -->
        <div class="modal-content">
            <div class="modal-header bg-primary d-flex justify-content-center w-100">
                <h5 class="modal-title text-white text-center" id="productDetailModalLabel">Product Details</h5>
                <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
            </div>
            <div class="modal-body">

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
                    <!-- Product Info Card -->
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

                    <!-- Additional Details Card -->
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
</div>

<!-- Modal delete -->
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
                <form method="POST" action="{{ route('delete.product') }}">
                    @csrf
                    <input type="hidden" id="Idproduct" name="productId">

                    <button type="submit" class="btn btn-danger" id="deleteButton">
                        <span id="deleteButtonText">Delete</span>
                        <div id="deleteSpinner" class="spinner-border text-light" style="display: none; width: 1.5rem; height: 1.5rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>





<script>
    function handledeleteButtonClick(button) {
        const productData = JSON.parse(button.getAttribute('data-product'));
        console.log('delete::', productData)

        document.querySelector("#Idproduct").value=productData.id
    }

    function handleEditButtonClick(button) {
        const productData = JSON.parse(button.getAttribute('data-edit-product'));
        console.log('edit::', productData)

        // ajout des element au formulaire
        document.querySelector("#productId").value=productData.id;
        document.querySelector("#EditProductName").value = productData.product_name;
        document.querySelector("#EditProductPrice").value = productData.price;
        document.querySelector("#EditCategorie").value = productData.categorie_id;
        document.querySelector("#Editstock").value = productData.stock;
        document.querySelector("#EditProduDetail").value = productData.product_description;
    }

    function handleButtonClick(button) {
        // Récupère les données JSON de l'attribut 'data-user'
        const productData = JSON.parse(button.getAttribute('data-user'));

        // Met à jour le titre du modal avec le nom du produit
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








    document.getElementById('ProductImage').addEventListener('change', function(event) {
        const previewContainer = document.getElementById('previewContainer');
        previewContainer.innerHTML = ''; // Efface les anciens aperçus

        const files = event.target.files;
        if (files.length > 3) {
            const alertMessage = `<div class="alert alert-danger">You can only upload a maximum of 3 images.</div>`;
            previewContainer.innerHTML = alertMessage;
            return;
        }

        Array.from(files).forEach(file => {
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '230px'; // Largeur des images
                img.style.height = '250px'; // Hauteur des images
                img.style.objectFit = 'cover'; // Ajuste l'image pour bien remplir les dimensions
                img.style.border = '1px solid #ccc'; // Ajout d'une bordure esthétique
                img.style.borderRadius = '3px'; // Coins arrondis
                previewContainer.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });
</script>

@endsection
