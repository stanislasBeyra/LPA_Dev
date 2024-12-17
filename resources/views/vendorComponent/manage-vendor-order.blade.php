@extends('components.appconfig') <!-- Extending the appconfig layout -->

@section('content')
<div class="container-fluid pt-4">

    <div class="d-flex justify-content-center align-items-center mb-3">
        <!-- Affichage des messages d'erreur à gauche -->
        <div class="d-flex flex-column align-items-center">
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
                                <th scope="col">Order Code</th>
                                <th scope="col">Name of Employee</th>
                                <th scope="col">Telephone Number</th>
                                <th scope="col">Email</th>
                                <th scope="col">Agency/Ministry</th>
                                <!-- <th scope="col">Total</th> -->
                                <!-- <th scope="col">Status</th> -->
                                <th scope="col">Actions</th> <!-- Nouvelle colonne pour les actions -->
                            </tr>
                        </thead> 
                        <tbody>
                            @foreach ($orders as $key=> $order)
                            <tr>
                                <td>{{ $key+1}}</td>
                                <td>{{$order['ordercreated']->format('m/d/Y, h:i:s A')}}</td>
                                <td>{{$order['ordercode']}}</td>
                                <td>{{$order['employeefirstname']}} {{$order['employeelastname']}}</td>
                                <td>
                                    {{$order['employeemobile']}}
                                    @if(!empty($order['employeemobile2']))
                                    && {{$order['employeemobile2']}}
                                    @endif
                                </td>
                                <td>{{$order['employeeemail']}}</td>
                                <td>{{$order['agence']}}</td>
                                <!-- <td>{{ number_format($order['orderTotal'], 2, '.', ',') }} FCFA</td> -->
                                <!-- <td>
                                    @if ($order['orderStatus'] == 1)
                                    <span class="badge bg-warning">pending</span>
                                    @elseif ($order['orderStatus'] == 2)
                                    <span class="badge bg-primary">Processing</span>
                                    @elseif ($order['orderStatus'] == 3)
                                    <span class="badge bg-success">Validated</span>
                                    @else
                                    <span class="badge bg-danger">Cancelled</span>
                                    @endif
                                </td> -->
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
                                <th>0.00 FCFA</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
            <form method="POST" action="{{ route('validated.order') }}">
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




<script>
    function handleOrderValidatedbutton(button) {

    }





    function handleOrderDetailbutton(button) {
        const orderData = JSON.parse(button.getAttribute('data-items-products'));
        const imageBaseUrl = button.getAttribute('data-image-url');
        const modalBody = document.querySelector('#exampleModal .modal-body table tbody');
        modalBody.innerHTML = '';

        console.log(orderData.orderId);

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



        orderData.orderItems.forEach(item => {
            const totalPrice = item.productprice * item.quantity;
            const imageUrl = `${imageBaseUrl}/${item.product_images1}`;

            const statusText = getStatusText(item.orderItemsStatus);
            const badgeClass = getStatusBadgeClass(item.orderItemsStatus);


            const row = `
                <tr>
                    <td><img src="${imageUrl}" alt="${item.productname}" class="img-fluid" style="max-width: 50px;"></td>
                    <td>${item.productname}</td>
                    <td>${item.quantity}</td>
                    <td>${item.productprice.toFixed(2)} $</td>
                    <td>${totalPrice.toFixed(2)} FCFA</td>
                    <td><span class="badge ${badgeClass}">${statusText}</span></td>

                </tr>
            `;
            modalBody.innerHTML += row;
        });

        const subtotal = orderData.orderItems.reduce((total, item) => total + (item.productprice * item.quantity), 0);

        const formattedSubtotal = subtotal.toLocaleString('fr-FR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });

        document.querySelector('#exampleModal .modal-body table tfoot th').textContent = `Sub Total:`;
        document.querySelector('#exampleModal .modal-body table tfoot th + th').textContent = `${formattedSubtotal} FCFA`;
    }
</script>




@endsection