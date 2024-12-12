@extends('components.appconfig') <!-- Extending the appconfig layout -->

@section('content')
<style>
    .input-group .form-control {
        width: calc(50% - 10px);
        /* Donne un peu d'espace pour l'effet collé */
    }

    .input-group-append {
        margin-left: -5px;
        /* Assure que le bouton est collé au champ de saisie */
    }
</style>
<div class="container-fluid pt-4">

    <div class="d-flex justify-content-center align-items-center mb-3">
        <!-- Affichage des messages d'erreur à gauche -->
        <div class="d-flex flex-column align-items-center animate__animated animate__zoomIn">
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
    </div>






    <!-- Section avec tableau -->
    <section class="mb-4">
        <div class="card animate__animated animate__zoomIn">
            <div class="card-header text-center py-3">

                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 text-center">
                        <strong>Vendor Orders Table</strong>
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
                                <th scope="col">Order Code</th>
                                <th scope="col">Username</th>
                                <th scope="col">Phone number</th>
                                <th scope="col">Email</th>
                                <th scope="col">Total</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th> <!-- Nouvelle colonne pour les actions -->
                            </tr>
                        </thead>
                        <tbody id="firsttbody">
                            @foreach ($vendororders as $key=> $order)
                            <tr>
                                <td>{{ $key+1}}</td>
                                <td>{{$order['ordercreated']->format('m/d/Y, h:i:s A')}}</td>
                                <td>{{$order['ordercode']}}</td>
                                <td>{{$order['employeeusername']}}</td>
                                <td>
                                    {{$order['employeemobile']}}
                                    @if(!empty($order['employeemobile2']))
                                    && {{$order['employeemobile2']}}
                                    @endif
                                </td>

                                <td>{{$order['employeeemail']}}</td>
                                <td class="text-start">${{ number_format($order['orderTotal'], 2, '.', ',') }}</td>
                                <td>
                                    @if ($order['orderStatus'] == 1)
                                    <span class="badge bg-warning">pending</span>
                                    @elseif ($order['orderStatus'] == 2)
                                    <span class="badge bg-primary">Processing</span>
                                    @elseif ($order['orderStatus'] == 3)
                                    <span class="badge bg-success">Validated</span>
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
                                    <!-- <button type="button" class="btn btn-danger btn-sm" data-mdb-modal-init data-mdb-target="#exampleModal1">
                                        <i class="fas fa-trash-alt"></i>
                                    </button> -->
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
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">order Details</h5>
                <button type="button" class="btn-close" data-mdb-ripple-init data-mdb-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th scope="col">Image</th> <!-- Image column -->
                                <th scope="col">Product Name</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Unit Price</th>
                                <th scope="col">Total Price</th>
                                <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Rows will be dynamically inserted here by JavaScript -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-end">Sub Total:</th>
                                <th>$0.00</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
            <form method="POST" action="{{ route('AdminVendor.ValidateOrder') }}">
                @csrf
                <input type="hidden" id="orderid" name="orderid">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        data-mdb-ripple-init data-mdb-dismiss="modal">Close</button>

                    <button type="submit" class="btn btn-outline-primary" id="editButton">
                        <span id="editButtonText">approuved order</span>
                        <div id="editSpinner" class="spinner-border text-primary" style="display: none; width: 1.5rem; height: 1.5rem;" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </button>

                </div>
            </form>
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
                <i class="fas fa-trash-alt mb-3 text-danger" style="font-size: 3rem;"></i>
                <p class="d-inline">Are you sure you want to delete this order?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-mdb-ripple-init data-mdb-dismiss="modal">Close</button>
                <button type="button" class="btn btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    // const eiditButton = document.getElementById('editButton'); // Bouton de suppression
    // const spinneredit = document.getElementById('editSpinner'); // Spinner pour le bouton
    // const buttonTextedit = document.getElementById('editButtonText'); // Texte du bouton

    // // Ajout de l'événement sur le bouton
    // eiditButton.addEventListener('click', function(event) {
    //     // Affiche le spinner et masque le texte
    //     buttonTextedit.style.display = 'none'; // Masque le texte du bouton
    //     spinneredit.style.display = 'inline-block'; // Affiche le spinner
    // });

    function updatePhoneNumberPlaceholder() {
        var countryCode = $('#country').val();

        var placeholderText = '';
        switch (countryCode) {
            case 'us':
            case 'ca':
                placeholderText = '+1';
                break;
            case 'fr':
                placeholderText = '+33';
                break;
            case 'de':
                placeholderText = '+49';
                break;
            case 'lr': // Liberia
                placeholderText = '+231';
                break;
            default:
                placeholderText = '+225';
        }

        $('#phone').val(placeholderText);
    }

    // Initialiser le placeholder avec la première option par défaut
    $(document).ready(function() {
        updatePhoneNumberPlaceholder();
    });





    function handleOrderDetailbutton(button) {
        const orderData = JSON.parse(button.getAttribute('data-items-products'));
        const imageBaseUrl = button.getAttribute('data-image-url');
        const modalBody = document.querySelector('#exampleModal .modal-body table tbody');
        modalBody.innerHTML = '';

        console.log('detail', orderData);

        document.querySelector("#orderid").value = orderData.orderId;

        function getStatusText(status) {
            switch (status) {
                case 1:
                    return 'Pending';
                case 2:
                    return 'Processing';
                case 3:
                    return 'Validated';
                default:
                    return 'Unknown';
            }
        }

        function getStatusBadgeClass(status) {
            switch (status) {
                case 1:
                    return 'bg-warning';
                case 2:
                    return 'bg-primary';
                case 3:
                    return 'bg-success';
                default:
                    return 'bg-secondary';
            }
        }

        const editButton = document.querySelector("#editButton");
        if (orderData.orderStatus === 3) {
            // Masquer le bouton si le statut est 3 (validé)
            editButton.style.display = 'none';
        } else {
            // Afficher le bouton si le statut n'est pas 3
            editButton.style.display = 'inline-block';
        }

        function formatToLocaleString(price) {
            return price.toLocaleString('en-US', {
                style: 'decimal',
                minimumFractionDigits: 2
            });
        }

        orderData.orderItems.forEach(item => {
            const totalPrice = (item.productprice || 0) * item.quantity;

            // Vérification des images, si null, on utilise une image par défaut
            const imageUrl = item.product_images1 ? `${imageBaseUrl}/${item.product_images1}` : 'path/to/default-image.jpg';

            // Utilisation du nom du produit ou "N/A" si le nom est null
            const productName = item.productname || 'N/A';

            // Gestion du prix du produit (si nul, afficher 0.00)
            const productPrice = item.productprice ? item.productprice : '0.00';

            const statusText = getStatusText(item.orderItemsStatus);
            const badgeClass = getStatusBadgeClass(item.orderItemsStatus);



            const row = `
            <tr>
                <td><img src="${imageUrl}" alt="${productName}" class="img-fluid" style="max-width: 50px;"></td>
                <td>${productName}</td>
                <td>${item.quantity}</td>
               <td class="text-start">$${formatToLocaleString(productPrice)}</td>
                <td class="text-start">$${formatToLocaleString(totalPrice)}</td>

                <td><span class="badge ${badgeClass}">${statusText}</span></td>
            </tr>
        `;
            modalBody.innerHTML += row;
        });

        // Calcul du sous-total, même si certains produits ont un prix nul
        const subtotal = orderData.orderItems.reduce((total, item) => total + (item.productprice || 0) * item.quantity, 0);

        const formattedSubtotal = subtotal.toLocaleString('en-US', {
            style: 'decimal',
            minimumFractionDigits: 2
        });

        document.querySelector('#exampleModal .modal-body table tfoot th').textContent = `Sub Total:`;
        document.querySelector('#exampleModal .modal-body table tfoot th + th').textContent = `$${formattedSubtotal}`;
    }
</script>


<script>
    $(document).ready(function() {
        $('#form1').on('keyup', function() {
            let searchQuery = $(this).val();
            let keyIncremented = 0;

            // Effectuer une requête AJAX
            $.ajax({
                url: "{{ route('search.order') }}",
                type: "GET",
                dataType: 'json', // Explicitly set expected data type
                data: {
                    search: searchQuery
                },
                success: function(response) {
                    // Vider le tableau
                    console.log('Full Response:', response);
                    $('#firsttbody').empty();

                    let orders = response.orders || []; // Utilise "orders" s'il est présent, sinon un tableau vide
                    if (orders.length > 0) {
                        orders.forEach((order, index) => {
                            keyIncremented++;

                            // Safe date formatting
                            let formattedDate = order.orderCreated ?
                                new Date(order.orderCreated).toLocaleString('en-US', {
                                    month: '2-digit',
                                    day: '2-digit',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    second: '2-digit',
                                    hour12: true
                                }) :
                                'N/A';

                            // Status handling
                            let statusClass, statusText;
                            switch (order.orderStatus) {
                                case "1":
                                    statusClass = 'bg-warning';
                                    statusText = 'Pending';
                                    break;
                                case "2":
                                    statusClass = 'bg-primary';
                                    statusText = 'Processing';
                                    break;
                                case "3":
                                    statusClass = 'bg-success';
                                    statusText = 'Validated';
                                    break;
                                default:
                                    statusClass = 'bg-warning';
                                    statusText = `Unknown status: ${order.orderStatus}`;
                                    break;
                            }

                            // Safely access order properties
                            const employee = order.employeefirstname || 'N/A';
                            const employeeEmail = order.employeeemail || 'N/A';
                            const orderTotal = order.orderTotal || 'N/A';

                            const formatbalance = orderTotal.toLocaleString('en-US', {
                                style: 'decimal',
                                minimumFractionDigits: 2
                            })

                            $('tbody').append(`
                            <tr>
                                <td>${keyIncremented}</td>
                                <td>${formattedDate}</td>
                                <td>${order.ordercode}</td>
                                <td>${employee}</td>
                                <td>
                                 ${order.employeemobile} ${order.employeemobile2 ? '&& ' + order.employeemobile2 : ''}
                                </td>
                                <td>${employeeEmail}</td>
                                <td class="text-start">$${formatbalance}</td>
                                <td>
                                    <span class="badge ${statusClass}">
                                       ${statusText}
                                    </span>
                                </td>
                                <td>
                                   <button type="button"
                                        class="btn btn-info btn-sm"
                                        data-mdb-ripple-init
                                        data-mdb-modal-init
                                        data-mdb-target="#exampleModal"
                                        data-items-products="${JSON.stringify(order).replace(/"/g, '&quot;')}"
                                         data-image-url="{{ asset('app/public/') }}"
                                        onclick="handleOrderDetailbutton(this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        `);
                        });
                    } else {
                        $('tbody').append(`
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                No orders found. Try a different search.
                            </td>
                        </tr>
                    `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', {
                        status: status,
                        error: error,
                        responseText: xhr.responseText
                    });

                    $('tbody').append(`
                    <tr>
                        <td colspan="7" class="text-center text-danger">
                            Error loading orders. 
                            ${xhr.status ? `(Error ${xhr.status})` : 'Please try again.'}
                        </td>
                    </tr>
                `);
                }
            });
        });
    });
</script>

<!-- <script>
    $(document).ready(function() {
        $('#form1').on('keyup', function() {
            let searchQuery = $(this).val();
            let keyIncremented = 0;

            // Effectuer une requête AJAX
            $.ajax({
                url: "{{ route('search.order') }}",
                type: "GET",
                dataType: 'json', // Explicitly set expected data type
                data: {
                    search: searchQuery
                },
                success: function(response) {
                    // Vider le tableau
                    console.log('Full Response:', response);
                    $('#firsttbody').empty();

                    // Adjusted to handle different possible response structures
                    let orders = [];
                    if (Array.isArray(response)) {
                        orders = response;
                    } else if (response.data && Array.isArray(response.data)) {
                        orders = response.data;
                    } else if (response.orders && Array.isArray(response.orders)) {
                        orders = response.orders;
                    }

                    if (orders.length > 0) {
                        orders.forEach((order, index) => {
                            keyIncremented++;

                            // Safe date formatting
                            let formattedDate = order.created_at ?
                                new Date(order.created_at).toLocaleString('en-US', {
                                    month: '2-digit',
                                    day: '2-digit',
                                    year: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit',
                                    second: '2-digit',
                                    hour12: true
                                }) :
                                'N/A';

                            // Status handling

                            let statusClass, statusText;
                            if (order.status === "1") {
                                statusClass = 'bg-warning';
                                statusText = 'Pending';
                            } else if (order.status === "2") {
                                statusClass = 'bg-primary';
                                statusText = 'Processing';
                            } else if (order.status === "3") {
                                statusClass = 'bg-success';
                                statusText = 'Validated';
                            } else {
                                statusClass = 'bg-warning';
                                statusText = `Unknown status: ${order.status}`;
                            }


                            // Safer property access
                            const employee = order.employee || order.user || {};
                            const orderItem = (order.order_items && order.order_items[0]) || {};
                            const product = orderItem.product || {};
                            let orders = JSON.stringify(order).replace(/"/g, '&quot;');

                            // Debugging: log each order to understand its structure
                            console.log('Current Order:', order);

                            $('tbody').append(`
                            <tr>
                                <td>${keyIncremented}</td>
                                <td>${formattedDate}</td>
                                <td>${employee.username || 'N/A'}</td>
                               <td>${employee.email || 'N/A'}</td>
                                <td>$${order.total || 'N/A'}</td>
                                <td>
                                    <span class="badge ${statusClass}">
                                       ${statusText}
                                    </span>
                                </td>
                                <td>
                                   <button type="button"
                                        class="btn btn-primary btn-sm"
                                        data-mdb-ripple-init
                                        data-mdb-modal-init
                                        data-mdb-target="#exampleModal"
                                        data-items-products="${orders}"
                                        onclick="handleOrderDetailbutton(this)">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                               

                            </tr>
                        `);
                        });
                    } else {
                        $('tbody').append(`
                        <tr>
                            <td colspan="7" class="text-center text-muted">
                                No orders found. Try a different search.
                            </td>
                        </tr>
                    `);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', {
                        status: status,
                        error: error,
                        responseText: xhr.responseText
                    });

                    $('tbody').append(`
                    <tr>
                        <td colspan="7" class="text-center text-danger">
                            Error loading orders. 
                            ${xhr.status ? `(Error ${xhr.status})` : 'Please try again.'}
                        </td>
                    </tr>
                `);
                }
            });
        });
    });
</script> -->




@endsection