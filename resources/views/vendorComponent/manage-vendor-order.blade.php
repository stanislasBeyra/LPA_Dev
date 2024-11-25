@extends('components.appconfig') <!-- Extending the appconfig layout -->

@section('content')
<div class="container-fluid pt-4">

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
                                <th scope="col">#ID</th>
                                <th scope="col">Creation date</th>
                                <th scope="col">Username</th>
                                <th scope="col">Email</th>
                                <th scope="col">Total</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th> <!-- Nouvelle colonne pour les actions -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $key=> $order)
                            <tr>
                                <td>{{ $key+1}}</td>
                                <td>{{$order['ordercreated']->format('m/d/Y, h:i:s A')}}</td>
                                <td>{{$order['employeeusername']}}</td>
                                <td>{{$order['employeeusername']}}</td>
                                <td>{{ number_format($order['orderTotal'], 2, '.', ',') }} FCFA</td>
                                <td>
                                    @if ($order['orderStatus'] == 1)
                                    <span class="badge bg-warning">pending</span>
                                    @elseif ($order['orderStatus'] == 2)
                                    <span class="badge bg-primary">validated</span>
                                    @elseif ($order['orderStatus'] == 3)
                                    <span class="badge bg-success">Approuved</span>
                                    @else
                                    <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td>
                                <td>
                                    <!-- Boutons Action -->
                                    <button type="button"
                                        class="btn btn-info btn-sm"
                                        data-mdb-ripple-init
                                        data-mdb-modal-init
                                        data-mdb-target="#exampleModal"
                                        data-items-products='@json($order)'
                                        data-image-url="{{ asset('app/public/') }}"
                                        onclick="handleOrderDetailbutton(this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" data-mdb-modal-init data-mdb-target="#exampleModal1">
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

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Adjusted width to 70% -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">order Details</h5>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Product Table -->
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">Image</th> <!-- Image column -->
                                <th scope="col">Product Name</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Unit Price</th>
                                <th scope="col">Total Price</th>
                                <th scope="col">Validate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows will be dynamically inserted here by JavaScript -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-end">Sub Total:</th>
                                <th>0.00 FCFA</th>
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


<script>
    // function handleOrderDetailbutton(button) {
    //     // Récupérer les données JSON à partir de l'attribut 'data-items-products'
    //     const OrderItemsData = JSON.parse(button.getAttribute('data-items-products'));
    //     console.log('order items:', OrderItemsData);
    // }
</script>

<script>
    function handleOrderDetailbutton(button) {
        const orderData = JSON.parse(button.getAttribute('data-items-products'));
        const imageBaseUrl = button.getAttribute('data-image-url');
        const modalBody = document.querySelector('#exampleModal .modal-body table tbody');
        modalBody.innerHTML = ''; // Réinitialiser le corps du tableau pour éviter d'ajouter à des données précédentes

        // Parcourir les éléments de commande et ajouter des lignes dynamiquement
        orderData.orderItems.forEach(item => {
            const totalPrice = item.productprice * item.quantity; // Prix total pour le produit

            // Utiliser la fonction asset() pour obtenir l'URL de l'image en PHP, et l'intégrer dans le JavaScript
            const imageUrl = `${imageBaseUrl}/${item.product_images1}`;
            console.log('url',imageBaseUrl);

            const row = `
                <tr>
                    <td><img src="${imageUrl}" alt="${item.productname}" class="img-fluid" style="max-width: 50px;"></td>
                    <td>${item.productname}</td>
                    <td>${item.quantity}</td>
                    <td>${item.productprice.toFixed(2)} $</td>
                    <td>${totalPrice.toFixed(2)} FCFA</td>
                    <td><button class="btn btn-primary">Validate</button></td>
                </tr>
            `;
            modalBody.innerHTML += row; // Ajouter une nouvelle ligne au tableau
        });

        // Calcul du sous-total
        const subtotal = orderData.orderItems.reduce((total, item) => total + (item.productprice * item.quantity), 0);

        // Formater le sous-total avec séparateurs de milliers et deux décimales
        const formattedSubtotal = subtotal.toLocaleString('fr-FR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        // Mettre à jour l'élément de la ligne de sous-total dans le tableau
        document.querySelector('#exampleModal .modal-body table tfoot th').textContent = `Sub Total:`;
        document.querySelector('#exampleModal .modal-body table tfoot th + th').textContent = `${formattedSubtotal} FCFA`;
    }
</script>

<!-- 
<script>
    function handleOrderDetailbutton(button) {
        const orderData = JSON.parse(button.getAttribute('data-items-products'));
        const modalBody = document.querySelector('#exampleModal .modal-body table tbody');
        modalBody.innerHTML = ''; // Reset the table body to avoid appending to previous data

        // Loop through the orderItems array and add rows dynamically
        orderData.orderItems.forEach(item => {
            const totalPrice = item.productprice * item.quantity; // Total price for the product
            const row = `
                <tr>
                    <td><img src="${item.product_images1}" alt="${item.productname}" class="img-fluid" style="max-width: 50px;"></td>
                    <td>${item.productname}</td>
                    <td>${item.quantity}</td>
                    <td>${item.productprice.toFixed(2)} $</td>
                    <td>${totalPrice.toFixed(2)} FCFA</td>
                    <td><button class="btn btn-primary">Validate</button></td>
                </tr>
            `;
            modalBody.innerHTML += row; // Append new row
        });

        // Calcul du sous-total
        const subtotal = orderData.orderItems.reduce((total, item) => total + (item.productprice * item.quantity), 0);

        // Formater le sous-total avec séparateurs de milliers et deux décimales
        const formattedSubtotal = subtotal.toLocaleString('fr-FR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        // Mettre à jour l'élément de la ligne de sous-total dans le tableau
        document.querySelector('#exampleModal .modal-body table tfoot th').textContent = `Sub Total:`;
        document.querySelector('#exampleModal .modal-body table tfoot th + th').textContent = `${formattedSubtotal} FCFA`;

       
    }
</script> -->





@endsection