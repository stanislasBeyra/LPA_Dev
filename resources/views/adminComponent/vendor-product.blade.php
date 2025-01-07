@extends('components.appconfig')

@section('content')
<div class="container-fluid pt-4">
    <section class="mb-4">
        <div class="card animate__animated animate__zoomIn">
            <div class="card-header text-center py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-center">
                        <strong>Vendor products Table</strong>
                    </h5>
                    <div class="input-group" style="width: 30%;">
                        <div class="form-outline" data-mdb-input-init>
                            <input type="search" id="searchInput" class="form-control" placeholder="Search Vendors" />
                            <label class="form-label" for="searchInput">Search</label>
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
                                <th scope="col">Product Category</th>
                                <th scope="col">Product Price</th>
                                <th scope="col">Product Stock</th>
                                <th scope="col">Product Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="productsTableBody">
                            <!-- Les données seront chargées ici via Ajax -->
                        </tbody>
                    </table>
                    <div id="loadingSpinner" class="text-center d-none">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal de détails -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal">
        <div class="modal-content">
            <div class="modal-header bg-primary d-flex justify-content-center w-100">
                <h5 class="modal-title text-white text-center" id="productDetailModalLabel">Product Details</h5>
            </div>
            <div class="modal-body">
                <!-- Carousel -->
                <div id="carouselDarkVariant" class="carousel slide carousel-fade carousel-dark" data-mdb-ride="carousel" data-mdb-carousel-init>
                    <div class="carousel-indicators">
                        <button style="border-radius: 100%; width:10px; height:10px;background-color:aliceblue"
                            data-mdb-target="#carouselDarkVariant" data-mdb-slide-to="0"
                            class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button style="border-radius: 100%; width:10px; height:10px;background-color:aliceblue"
                            data-mdb-target="#carouselDarkVariant" data-mdb-slide-to="1"
                            aria-label="Slide 2"></button>
                        <button style="border-radius: 100%; width:10px; height:10px;background-color:aliceblue"
                            data-mdb-target="#carouselDarkVariant" data-mdb-slide-to="2"
                            aria-label="Slide 3"></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="" class="d-block w-100" style="height: 250px; object-fit: cover;" alt="Product Image 1" id="carouselImage1" />
                        </div>
                        <div class="carousel-item">
                            <img src="" class="d-block w-100" style="height: 250px; object-fit: cover;" alt="Product Image 2" id="carouselImage2" />
                        </div>
                        <div class="carousel-item">
                            <img src="" class="d-block w-100" style="height: 250px; object-fit: cover;" alt="Product Image 3" id="carouselImage3" />
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

                <div class="accordion mt-4" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button data-mdb-collapse-init class="accordion-button collapsed" type="button"
                                data-mdb-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Product description
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-mdb-parent="#accordionExample">
                            <div class="accordion-body" id="productDetailDescription"></div>
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
                                <p><strong>Stock:</strong> <span id="productDetailQuantity"></span></p>
                                <p><strong>Price:</strong> <span id="productDetailPrice"></span></p>
                                <p><strong>Vendor Name:</strong> <span id="productDetailVendor"></span></p>
                                <p><strong>Vendor Email:</strong> <span id="productDetailVendorEmail"></span></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">Additional Details</h5>
                            </div>
                            <div class="card-body">
                                <p><strong>Category:</strong> <span id="productDetailCategory"></span></p>
                                <p><strong>Category Description:</strong> <span id="productDetailCategoryDesc"></span></p>
                                <p><strong>Status:</strong> <span id="productDetailStatus"></span></p>
                                <p><strong>Vendor Mobile:</strong> <span id="productDetailVendorMobile"></span></p>
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

<!-- Modal de suppression -->
<div class="modal top fade" id="exampleModal1" tabindex="-1" aria-labelledby="exampleModalLabel1" aria-hidden="true" data-mdb-backdrop="true" data-mdb-keyboard="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger d-flex justify-content-center w-100">
                <h5 class="modal-title text-white text-center">Delete Confirmation</h5>
            </div>
            <div class="modal-body d-flex flex-column align-items-center text-center">
                <i class="fas fa-trash-alt mb-3 text-danger" style="font-size: 3rem;"></i>
                <p>Are you sure you want to delete this product? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDeleteButton">
                    <span id="deleteButtonText">Delete</span>
                    <div id="deleteSpinner" class="spinner-border text-light d-none" style="width: 1.5rem; height: 1.5rem;" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    // Fonction pour charger les données
    function loadProducts() {
        $('#loadingSpinner').removeClass('d-none');
        $.ajax({
            url: "{{ route('admin.vendorproduct') }}",
            type: "GET",
            dataType: 'json',
            success: function(response) {
                if (response.success && response.vendorProducts) {
                    updateProductsTable(response.vendorProducts);
                } else {
                    console.error('Erreur lors du chargement des données:', response.message);
                }
                $('#loadingSpinner').addClass('d-none');
            },
            error: function(xhr, status, error) {
                console.error('Erreur Ajax:', error);
                $('#loadingSpinner').addClass('d-none');
                $('#productsTableBody').html('<tr><td colspan="9" class="text-center text-danger">Erreur lors du chargement des données</td></tr>');
            }
        });
    }

    // Fonction pour mettre à jour le tableau
    function updateProductsTable(products) {
        const tbody = $('#productsTableBody');
        tbody.empty();

        products.forEach((product, index) => {
            const statusBadge = product.productstatus === 1 
                ? '<span class="badge bg-success">Active</span>' 
                : '<span class="badge bg-danger">Inactive</span>';

            const formattedPrice = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD'
            }).format(product.productprice);

            const formattedDate = new Date(product.created_at).toLocaleString();
            
            const row = `
                <tr>
                    <td>${index + 1}</td>
                    <td>${formattedDate}</td>
                    <td><img src="{{ asset('app/public/') }}/${product.product_images1}" height="60" width="60" class="shadow rounded-3" alt="${product.product_name}" /></td>
                    <td>${product.product_name}</td>
                    <td>${product.category_name}</td>
                    <td>${formattedPrice}</td>
                    <td>${product.productstock} qty</td>
                    <td>${statusBadge}</td>
                    <td>
                        <button type="button" class="btn btn-info btn-sm" 
                            data-mdb-ripple-init 
                            data-mdb-modal-init 
                            data-mdb-target="#exampleModal"
                            data-produt='${JSON.stringify(product).replace(/'/g, "&#39;")}'
                            onclick="handleButtonClick(this)">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button type="button" class="btn btn-danger btn-sm"
                            data-mdb-modal-init
                            data-mdb-target="#exampleModal1"
                            data-produt-delete='${JSON.stringify(product).replace(/'/g, "&#39;")}'
                            onclick="handledeleteButtonClick(this)">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });
    }

    // Chargement initial des données
    loadProducts();

    // Recherche en temps réel
    let searchTimeout;
    $('#searchInput').on('keyup', function() {
        clearTimeout(searchTimeout);
        const searchQuery = $(this).val();
        
        searchTimeout = setTimeout(() => {
            // Ici vous pouvez implémenter la logique de recherche
            // Soit en filtrant les données existantes, soit en faisant une nouvelle requête Ajax
            loadProducts(searchQuery);
        }, 300);
    });
});

// Fonction pour afficher les détails du produit
function handleButtonClick(button) {
    const productData = JSON.parse(button.getAttribute('data-produt'));
    
    // Mise à jour du titre
    document.querySelector("#productDetailModalLabel").textContent = "Product Details: " + productData.product_name;
    
    // Mise à jour des images du carousel
    const assetPath = "{{ asset('app/public/') }}";
    document.querySelector("#carouselImage1").src = `${assetPath}/${productData.product_images1}`;
    document.querySelector("#carouselImage2").src = `${assetPath}/${productData.product_images2}`;
    document.querySelector("#carouselImage3").src = `${assetPath}/${productData.product_images3}`;
    
    // Mise à jour des informations du produit
    document.querySelector("#productDetailNames").textContent = productData.product_name;
    document.querySelector("#productDetailQuantity").textContent = `${productData.productstock} qty`;
    document.querySelector("#productDetailPrice").textContent = new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(productData.productprice);
    document.querySelector("#productDetailDescription").textContent = productData.product_description;
    document.querySelector("#productDetailCategory").textContent = productData.category_name;
    document.querySelector("#productDetailCategoryDesc").textContent = productData.category_description;
    
    // Mise à jour des informations du vendeur
    document.querySelector("#productDetailVendor").textContent = productData.vendor_name;
    document.querySelector("#productDetailVendorEmail").textContent = productData.vendor_email;
    document.querySelector("#productDetailVendorMobile").textContent = productData.vendor_mobile;
    
    // Mise à jour du statut avec badge
    const statusElement = document.querySelector("#productDetailStatus");
    statusElement.className = ''; // Réinitialisation des classes
    statusElement.classList.add('badge', productData.productstatus === 1 ? 'bg-success' : 'bg-danger');
    statusElement.textContent = productData.productstatus === 1 ? 'Active' : 'Inactive';
}

// Variables pour la suppression
let productToDelete = null;

// Fonction pour gérer le clic sur le bouton de suppression
function handledeleteButtonClick(button) {
    const productData = JSON.parse(button.getAttribute('data-produt-delete'));
    productToDelete = productData.id;
}

// Gestionnaire de confirmation de suppression
document.getElementById('confirmDeleteButton').addEventListener('click', function() {
    if (!productToDelete) return;
    
    const buttonText = document.getElementById('deleteButtonText');
    const spinner = document.getElementById('deleteSpinner');
    
    // Afficher le spinner et désactiver le bouton
    buttonText.classList.add('d-none');
    spinner.classList.remove('d-none');
    this.disabled = true;
    
    // Envoyer la requête de suppression
    $.ajax({
        url: "{{ route('delete.VendorProduct') }}",
        type: "POST",
        data: {
            productId: productToDelete,
            _token: "{{ csrf_token() }}"
        },
        success: function(response) {
            // Fermer le modal
            $('#exampleModal1').modal('hide');
            
            // Recharger les données
            loadProducts();
            
            // Afficher une notification de succès (vous devez implémenter cette fonction)
            showNotification('Product successfully deleted', 'success');
        },
        error: function(xhr, status, error) {
            console.error('Error deleting product:', error);
            showNotification('Error deleting product', 'error');
        },
        complete: function() {
            // Réinitialiser le bouton
            buttonText.classList.remove('d-none');
            spinner.classList.add('d-none');
            document.getElementById('confirmDeleteButton').disabled = false;
            productToDelete = null;
        }
    });
});

// Fonction pour afficher les notifications
function showNotification(message, type = 'success') {
    // Vous pouvez implémenter votre propre système de notification ici
    // Par exemple, utiliser Toastr ou une autre bibliothèque
    alert(message);
}
</script>
@endsection