<nav id="sidebarMenu" class="sidebar  position-fixed" style="height: 100vh; overflow-y: auto;">
    <div class="position-sticky">
        <div class="list-group list-group-flush mx-3 mt-4">
            <!-- Dashboard Section -->
            <a href="/" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init aria-current="true">
                <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>Dashboard</span>
            </a>

            <!-- pour le role admin -->
            @if(auth()->user()->role == 1)
            <!-- principal admin -->
            <a href="{{ route('content.page', ['page' => 'manage-vendors']) }}" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-users-cog fa-fw me-3"></i><span>Manage Vendors</span>
            </a>
            <a href="/manage-employees" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-users fa-fw me-3"></i><span>Manage Employees</span>
            </a>
            <!-- Vendor Orders and Products -->
            <a href="/vendor-product" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-cogs fa-fw me-3"></i><span>Vendor Product</span>
            </a>
            <!-- <a href="/vendor-order" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-truck fa-fw me-3"></i><span>Vendor Order</span>
            </a> -->
            <!-- <a href="/vendor-order" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-truck fa-fw me-3"></i>
                <span>Vendor Order</span>
                <span class="badge bg-primary rounded-pill ms-2">4</span>
            </a> -->

            <a href="/vendor-order" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-truck fa-fw me-3"></i>
                <span>Vendor Order</span>
                <span id="vendor-order-badge" class="badge bg-primary rounded-pill ms-2">0</span>
            </a>

            <a href="/manage-agencies" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-building fa-fw me-3"></i><span>Manage Agencies</span>
            </a>
            <a href="/manage-categories" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-th-large fa-fw me-3"></i><span>Manage Categories</span>
            </a>

            <a href="/manage-admin" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-th-large fa-fw me-3"></i><span>Manage admins</span>
            </a>

            <a href="/manage-admin-role" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-th-large fa-fw me-3"></i><span>Manage roles</span>
            </a>
            <a href="/manage-banner" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-th-large fa-fw me-3"></i><span>Manage Banner</span>
            </a>

            <!-- Payments Section -->
            <!-- <a href="/employee-paiement" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-credit-card fa-fw me-3"></i><span>Payments</span>
            </a> -->

            @endif

            <!-- pour le role vendor -->

            @if(auth()->user()->role == 3)
            <!-- vendors-->
            <!-- <a href="/add-products" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-plus fa-fw me-3"></i><span>Add Products</span>
            </a> -->
            <a href="/manage-vendor-product" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-box fa-fw me-3"></i><span>manage Products</span>
            </a>
            <!-- <a href="/manage-vendor-orders" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-box-open fa-fw me-3"></i><span>My Orders</span>
            </a> -->

            <!-- <a href="/manage-vendor-orders" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-box-open fa-fw me-3"></i>
                <span>My Orders</span>
                <span class="badge bg-success rounded-pill ms-2">8</span>
            </a> -->

            <a href="/manage-vendor-orders" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-box-open fa-fw me-3"></i>
                <span>My Orders</span>
                <span id="my-orders-badge" class="badge bg-success rounded-pill ms-2">0</span>
            </a>

            @endif

            <!-- pour le role CSA -->

            @if(auth()->user()->role == 5)
            <a href="{{ route('content.page', ['page' => 'manage-vendors']) }}" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-users-cog fa-fw me-3"></i><span>Manage Vendors</span>
            </a>
            <a href="/manage-employees" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-users fa-fw me-3"></i><span>Manage Employees</span>
            </a>
            <!-- <a href="/vendor-order" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-truck fa-fw me-3"></i><span>Vendor Order</span>
            </a> -->

            <!-- <a href="/vendor-order" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-truck fa-fw me-3"></i>
                <span>Vendor Order</span>
                <span class="badge bg-primary rounded-pill ms-2">4</span>
            </a> -->

            <a href="/vendor-order" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-truck fa-fw me-3"></i>
                <span>Vendor Order</span>
                <span id="vendor-order-badge" class="badge bg-primary rounded-pill ms-2">0</span>
            </a>



            <a href="/manage-banner" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-th-large fa-fw me-3"></i><span>Manage Banner</span>
            </a>
            @endif


        </div>
    </div>
</nav>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<script>
    $(document).ready(function() {
        // Fonction pour récupérer les données via AJAX
        function fetchOrderCounts() {
            $.ajax({
                url: "{{ route('count.adminorder') }}", // Route Laravel pour récupérer les comptes
                type: "GET",
                dataType: "json",
                success: function(response) {
                    // Mettre à jour les badges avec les données reçues
                    $('#vendor-order-badge').text(response.orders);
                    $('#vendor-order-badge-2').text(response.orders);
                },
                error: function(xhr, status, error) {
                    console.error("Erreur lors du chargement des données :", error);
                }
            });
        }


        function fetchVendorOrderCounts() {
            $.ajax({
                url: "{{ route('countvendor.order') }}", // Route Laravel pour récupérer les comptes
                type: "GET",
                dataType: "json",
                success: function(response) {
                    console.log('vendororderscount',response)
                    // Mettre à jour les badges avec les données reçues
                    $('#my-orders-badge').text(response.vendororders);
                    
                },
                error: function(xhr, status, error) {
                    console.error("Erreur lors du chargement des données :", error);
                }
            });
        }


        // Appel initial pour charger les badges dès l'ouverture de la page
        fetchOrderCounts();
        fetchVendorOrderCounts();

        // Initialisation de Pusher avec les clés d'environnement
        const pusher = new Pusher("{{ env('PUSHER_APP_KEY') }}", {
            cluster: "{{ env('PUSHER_APP_CLUSTER') }}", // Cluster spécifié dans l'environnement
            encrypted: true // Utilise un canal sécurisé
        });

        // Abonnement au canal "order-channel"
        const channel = pusher.subscribe('order-channel');

        // Sur écoute d'un événement "order-updated"
        channel.bind('order-updated', function(data) {
            console.log('Mise à jour reçue:', data);

            // Appelle la fonction AJAX pour rafraîchir les badges
            fetchOrderCounts();
            fetchVendorOrderCounts();
        });

        // Gestion des erreurs de connexion avec Pusher
        pusher.connection.bind('error', function(err) {
            console.error('Erreur de connexion avec Pusher :', err);
            alert('Erreur de connexion avec Pusher.');
        });
    });
</script>


<!-- <script>
    $(document).ready(function() {
        // Fonction pour récupérer les données via AJAX
        function fetchOrderCounts() {
            $.ajax({
                url: "{{ route('count.adminorder') }}", // Route Laravel
                type: "GET",
                dataType: "json",
                success: function(response) {
                    console.log('reponse de count',response)
                    // Mettre à jour chaque badge avec la valeur reçue
                    $('#my-orders-badge').text(response.orders);
                    $('#vendor-order-badge').text(response.orders);
                    $('#vendor-order-badge-2').text(response.orders);
                },

                
                error: function(xhr, status, error) {
                    console.error("Erreur lors du chargement des données :", error);
                }
            });
        }

        // Appel initial au chargement de la page
        fetchOrderCounts();

        // Rafraîchir automatiquement toutes les 10 secondes
        setInterval(fetchOrderCounts, 10000); // 10 000 ms = 10 secondes
    });
</script> -->



<!-- <nav id="sidebarMenu" class="sidebar bg-white">
    <div class="position-sticky">
        <div class="list-group list-group-flush mx-3 mt-4">
            <a href="/" class="list-group-item list-group-item-action py-2 active" data-mdb-ripple-init aria-current="true">
                <i class="fas fa-tachometer-alt fa-fw me-3"></i><span>Main dashboard</span>
            </a>
            <a href="#" class="list-group-item list-group-item-action py-2 " data-mdb-ripple-init>
                <i class="fas fa-chart-area fa-fw me-3"></i><span>Website traffic</span>
            </a>
            <a href="#" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init><i class="fas fa-lock fa-fw me-3"></i><span>Password</span></a>
            <a href="#" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init><i class="fas fa-chart-line fa-fw me-3"></i><span>Analytics</span></a>
            <a href="/historiques" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init>
                <i class="fas fa-chart-pie fa-fw me-3"></i><span>SEO</span>
            </a>
            <a href="#" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init><i class="fas fa-chart-bar fa-fw me-3"></i><span>Orders</span></a>
            <a href="#" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init><i class="fas fa-globe fa-fw me-3"></i><span>International</span></a>
            <a href="#" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init><i class="fas fa-building fa-fw me-3"></i><span>Partners</span></a>
            <a href="/historique" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init><i class="fas fa-calendar fa-fw me-3"></i><span>hsitorique</span></a>
            <a href="/historiquemobile" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init><i class="fas fa-calendar fa-fw me-3"></i><span>Mobile Monney</span></a>
            <a href="/home" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init><i class="fas fa-users fa-fw me-3"></i><span>Users</span></a>
            
            <a href="#" class="list-group-item list-group-item-action py-2" data-mdb-ripple-init><i class="fas fa-money-bill fa-fw me-3"></i><span>Sales</span></a>
        </div>
    </div>
</nav>

 -->